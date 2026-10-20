<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrivateSaleRequest;
use App\Models\InventoryTransaction;
use App\Models\PrivateSale;
use App\Models\PrivateSaleDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class PrivateSaleController extends Controller
{
    public function index()
    {
        $private_sale = PrivateSale::with('privateSaleDetail:id,quantity,unit_price,line_total,type_price,product_id,user_id,private_sale_id', 'seller:id,name,surname')->orderBy('date_sale', 'desc')->get();

        $private_sale_group = $private_sale->groupBy(function ($item) {
            return $item->date_sale->format('Y-m-d');
        });

        return Inertia::render('PrivateSale/Index', [
            'private_sale_group' => $private_sale_group,
            'can_edit' => Auth::user()?->hasRole('Administrador') ?? false,
        ]);
    }

    public function create()
    {
        $private_sale = new PrivateSale;
        $private_sale_details = new PrivateSaleDetail;
        $product = Product::all();
        $seller = Seller::all();

        return Inertia::render('PrivateSale/Create', [
            'private_sale' => $private_sale,
            'private_sale_details' => $private_sale_details,
            'product' => $product,
            'seller' => $seller,
        ]);
    }

    public function store(PrivateSaleRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $private_groups = $request->input('private_sales');

                if (empty($private_groups) && filled('seller_id') && ! empty($request->input('private_sales', []))) {
                    $private_groups = [[
                        'seller_id' => $request->seller_id,
                        'products' => $request->input('private_sales', []),
                    ]];
                }

                foreach ($private_groups ?? [] as $private_group) {

                    $private_sale = PrivateSale::create([
                        'date_sale' => $request->date_sale,
                        'seller_id' => $private_group['seller_id'],
                        'grand_total' => 0,
                    ]);

                    $grandTotal = 0;

                    foreach ($private_group['products'] ?? [] as $private_product) {
                        $quantity = (int) ($private_product['quantity'] ?? 0);
                        $unitPrice = (float) ($private_product['unit_price'] ?? 0);

                        $lineTotal = $quantity * $unitPrice;
                        $grandTotal += $lineTotal;

                        PrivateSaleDetail::create([
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'line_total' => $lineTotal,
                            'type_price' => $private_product['type_price'],
                            'product_id' => $private_product['id'],
                            'user_id' => Auth::id(),
                            'private_sale_id' => $private_sale->id,
                        ]);
                    }

                    $private_sale->update([
                        'grand_total' => $grandTotal,
                    ]);
                }
            });

            return redirect()->route('private_sales.index')->with('success', 'Registro creado exitosamente');
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Ocurrió un error al crear el registro');
        }
    }

    public function show(string $id)
    {
        $private_sale = PrivateSale::with('privateSaleDetail.product', 'seller')->findOrFail($id);
        $product = Product::all();

        return Inertia::render('PrivateSale/Show', [
            'private_sale' => $private_sale,
            'product' => $product,
        ]);
    }

    public function confirm(string $id, InventoryService $inventoryService)
    {
        try {
            DB::transaction(function () use ($id, $inventoryService): void {
                $privateSale = PrivateSale::query()
                    ->with('privateSaleDetail')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($privateSale->status === 'Confirmed') {
                    return;
                }

                if ($privateSale->status === 'Cancelled') {
                    throw ValidationException::withMessages([
                        'status' => 'Una venta cancelada no puede confirmarse.',
                    ]);
                }

                foreach ($privateSale->privateSaleDetail as $detail) {
                    $quantity = (int) $detail->quantity;

                    if ($quantity === 0) {
                        continue;
                    }

                    $inventoryService->issue(
                        productId: $detail->product_id,
                        quantity: $quantity,
                        referenceType: PrivateSaleDetail::class,
                        referenceId: $detail->id,
                        movementKey: "private-sale-detail:{$detail->id}:issue",
                        notes: "Confirmación de venta particular {$privateSale->id}.",
                    );
                }

                $privateSale->update(['status' => 'Confirmed']);
            });

            return redirect()
                ->route('private_sales.index')
                ->with('success', 'Venta confirmada y existencias actualizadas.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No fue posible confirmar la venta. Verifique la disponibilidad.');
        }
    }

    public function edit(string $id)
    {
        abort_unless(Auth::user()?->hasRole('Administrador'), 403);

        $private_sale = PrivateSale::with('privateSaleDetail.product', 'seller')->findOrFail($id);
        $product = Product::all();
        $seller = Seller::all();

        return Inertia::render('PrivateSale/Edit', [
            'private_sale' => $private_sale,
            'product' => $product,
            'seller' => $seller,
        ]);
    }

    public function update(PrivateSaleRequest $request, string $id, InventoryService $inventoryService)
    {
        try {
            abort_unless(Auth::user()?->hasRole('Administrador'), 403);

            DB::transaction(function () use ($request, $id, $inventoryService) {

                $private_sale = PrivateSale::with('privateSaleDetail')->findOrFail($id);

                if ($private_sale->status === 'Confirmed') {
                    $this->reverseConfirmedSaleMovements($private_sale, $inventoryService);
                }

                $private_sale->update([
                    'date_sale' => $request->date_sale,
                    'seller_id' => $request->seller_id,
                ]);

                $private_sale->privateSaleDetail()->delete();

                $grandTotal = 0;

                foreach ($request->input('private_sales', []) as $private_product) {
                    $quantity = (int) ($private_product['quantity'] ?? 0);
                    $unitPrice = (float) ($private_product['unit_price'] ?? 0);

                    $lineTotal = $quantity * $unitPrice;
                    $grandTotal += $lineTotal;

                    PrivateSaleDetail::create([
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                        'type_price' => $request->type_price,
                        'product_id' => $private_product['id'],
                        'user_id' => Auth::id(),
                        'private_sale_id' => $private_sale->id,
                    ]);
                }

                $private_sale->update([
                    'grand_total' => $grandTotal,
                    'status' => 'Draft',
                ]);
            });

            // ? End DB::transaction
            return redirect()->route('private_sales.index')->with('success', 'Registro actualizado exitosamente');
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el registro');
        }
    }

    public function destroy(string $id, InventoryService $inventoryService)
    {
        try {
            abort_unless(Auth::user()?->hasRole('Administrador'), 403);

            DB::transaction(function () use ($id, $inventoryService) {
                $private_delete = PrivateSale::findOrFail($id);

                if ($private_delete->status === 'Confirmed') {
                    $this->reverseConfirmedSaleMovements($private_delete, $inventoryService);
                }

                $private_delete->privateSaleDetail()->delete();
                $private_delete->delete();
            });

        } catch (Throwable) {
            return back()->with('error', 'Ocurrió un error al eliminar esta venta');
        }

        return redirect()->route('private_sales.index')->with('success', 'Venta privada eliminada exitosamente');
    }

    private function reverseConfirmedSaleMovements(
        PrivateSale $sale,
        InventoryService $inventoryService,
    ): void {
        foreach ($sale->privateSaleDetail as $detail) {
            $movement = InventoryTransaction::query()
                ->where('movement_key', "private-sale-detail:{$detail->id}:issue")
                ->first();

            if ($movement) {
                $inventoryService->reverse(
                    transaction: $movement,
                    movementKey: "private-sale-detail:{$detail->id}:admin-reversal",
                    notes: "Reversión administrativa de la venta particular {$sale->id}.",
                );
            }
        }
    }
}
