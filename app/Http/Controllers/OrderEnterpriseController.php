<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderEnterpriseRequest;
use App\Models\InventoryTransaction;
use App\Models\OrderEnterprise;
use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class OrderEnterpriseController extends Controller
{
    public function index()
    {
        $order = OrderEnterprise::query()->with('purchaseOrderDetail:id,boxes_received,purchase_price_total_per_box,order_enterprise_id,product_id')->latest()->get();
        $receiptKeys = InventoryTransaction::query()
            ->where('reference_type', PurchaseOrderDetail::class)
            ->whereIn('reference_id', $order->flatMap(fn (OrderEnterprise $enterpriseOrder) => $enterpriseOrder->purchaseOrderDetail->pluck('id')))
            ->pluck('movement_key')
            ->flip();

        $order->each(function (OrderEnterprise $enterpriseOrder) use ($receiptKeys): void {
            $enterpriseOrder->setAttribute('inventory_receipt_confirmed', $enterpriseOrder->purchaseOrderDetail
                ->filter(fn (PurchaseOrderDetail $detail): bool => (int) $detail->boxes_received > 0)
                ->every(fn (PurchaseOrderDetail $detail): bool => $receiptKeys->has("purchase-order-detail:{$detail->id}:receipt")));
        });

        return Inertia::render('OrderEnterprise/Index', ['order' => $order]);
    }

    public function create(): mixed
    {
        $order = new OrderEnterprise;
        $order_details = PurchaseOrderDetail::all();
        $products = Product::all();

        return Inertia::render('OrderEnterprise/Create', ['order' => $order, 'order_detials' => $order_details, 'products' => $products]);
    }

    public function store(OrderEnterpriseRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {

                $order = OrderEnterprise::create([
                    'order_date' => $request->order_date,
                    'expected_date' => $request->expected_date,
                    'order_status' => 'Pending',
                ]);

                $totalTaxGeneral = 0;
                $lineSubtotal = 0;
                $grandTotalLine = 0;

                foreach ($request->products as $product) {

                    $taxAmount = $product['purchase_price_per_box'] * ($product['tax_percentage'] / 100);

                    $totalPerBox = $product['purchase_price_per_box'] + $taxAmount;

                    $totalTax = $taxAmount * $product['boxes_received'];
                    $totalTaxGeneral += $totalTax;

                    $subtotalBase = $product['boxes_received'] * $product['purchase_price_per_box'];
                    $lineSubtotal += $subtotalBase;

                    $grandTotal = $product['boxes_received'] * $totalPerBox;
                    $grandTotalLine += $grandTotal;

                    PurchaseOrderDetail::create([
                        // 'boxes_ordered' => $product['boxes_ordered'],
                        'boxes_received' => $product['boxes_received'],
                        'purchase_price_per_box' => $product['purchase_price_per_box'],
                        'tax_percentage' => $product['tax_percentage'],
                        'tax_amount_per_box' => $taxAmount,
                        'purchase_price_total_per_box' => $totalPerBox,
                        'order_enterprise_id' => $order->id,
                        'product_id' => $product['id'],
                    ]);

                    $order->update([
                        'total_tax' => $totalTaxGeneral,
                        'subtotal_base' => $lineSubtotal,
                        'grand_total' => $grandTotalLine,
                    ]);
                }
            });

            return redirect()->route('orders_enterprises.index')->with('success', 'Pedido creado exitosamente');
        } catch (Throwable $e) {
            dd($e->getMessage());

            return back()->withInput()->with('error', 'Ocurrió un error al registrar la orden');
        }
    }

    public function show(string $id)
    {
        $order = OrderEnterprise::with('purchaseOrderDetail')->findOrFail($id);

        $purchase = PurchaseOrderDetail::with('product')
            ->where('order_enterprise_id', $order->id)
            ->get();
        $product = Product::all();

        return Inertia::render('OrderEnterprise/Show', [
            'order' => $order,
            'product' => $product,
            'purchase' => $purchase,
        ]);
    }

    public function confirmReceipt(string $id, InventoryService $inventoryService)
    {
        try {
            DB::transaction(function () use ($id, $inventoryService): void {
                $order = OrderEnterprise::query()
                    ->with('purchaseOrderDetail')
                    ->lockForUpdate()
                    ->findOrFail($id);

                foreach ($order->purchaseOrderDetail as $detail) {
                    $quantity = (int) $detail->boxes_received;

                    if ($quantity === 0) {
                        continue;
                    }

                    $inventoryService->receive(
                        productId: $detail->product_id,
                        quantity: $quantity,
                        referenceType: PurchaseOrderDetail::class,
                        referenceId: $detail->id,
                        movementKey: "purchase-order-detail:{$detail->id}:receipt",
                        notes: "Recepción del pedido empresarial {$order->id}.",
                    );
                }

                $order->update(['order_status' => 'Received']);
            });

            return redirect()
                ->route('orders_enterprises.index')
                ->with('success', 'Recepción confirmada y existencias actualizadas.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No fue posible confirmar la recepción del pedido.');
        }
    }

    public function edit(string $id)
    {
        try {
            $order = OrderEnterprise::with('purchaseOrderDetail')->findOrFail($id);

            $purchase = PurchaseOrderDetail::where('order_enterprise_id', $order->id)->with('product')->get();
            $products = Product::all();

            return Inertia::render('OrderEnterprise/Edit', [
                'order' => $order,
                'purchase' => $purchase,
                'products' => $products,
            ]);
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al ingresar a la vista el pedido');
        }
    }

    public function update(OrderEnterpriseRequest $request, string $id)
    {
        try {

            DB::transaction(function () use ($request, $id) {

                $order = OrderEnterprise::findOrFail($id);

                if ($order->order_status === 'Received') {
                    throw ValidationException::withMessages([
                        'order_status' => 'Un pedido recibido no puede editarse sin revertir primero su recepción.',
                    ]);
                }

                $order->update([
                    'order_date' => $request->order_date,
                    'expected_date' => $request->expected_date,
                    'order_status' => 'Pending',
                ]);

                $order->purchaseOrderDetail()->delete();

                $totalTaxGeneral = 0;
                $subtotalBaseGeneral = 0;
                $grandTotalGeneral = 0;

                foreach ($request->products as $product) {

                    $taxAmount = $product['purchase_price_per_box'] * ($product['tax_percentage'] / 100);

                    $totalPerBox = $product['purchase_price_per_box'] + $taxAmount;

                    $totalTax = $taxAmount * $product['boxes_received'];
                    $totalTaxGeneral += $totalTax;

                    $subtotalBase = $product['boxes_received'] * $product['purchase_price_per_box'];
                    $subtotalBaseGeneral += $subtotalBase;

                    $grandTotal = $product['boxes_received'] * $totalPerBox;
                    $grandTotalGeneral += $grandTotal;

                    // recrear detalle
                    PurchaseOrderDetail::create([
                        // 'boxes_ordered' => $product['boxes_ordered'],
                        'boxes_received' => $product['boxes_received'],
                        'purchase_price_per_box' => $product['purchase_price_per_box'],
                        'tax_percentage' => $product['tax_percentage'],
                        'tax_amount_per_box' => $taxAmount,
                        'purchase_price_total_per_box' => $totalPerBox,
                        'order_enterprise_id' => $order->id,
                        'product_id' => $product['id'],
                    ]);
                }

                // actualizar totales
                $order->update([
                    'total_tax' => $totalTaxGeneral,
                    'subtotal_base' => $subtotalBaseGeneral,
                    'grand_total' => $grandTotalGeneral,
                ]);
            });

            return redirect()
                ->route('orders_enterprises.index')
                ->with('success', 'Pedido actualizado exitosamente');
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el pedido');
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $order = OrderEnterprise::findOrFail($id);

                if ($order->order_status === 'Received') {
                    throw ValidationException::withMessages([
                        'order_status' => 'Un pedido recibido no puede eliminarse sin revertir primero su recepción.',
                    ]);
                }

                // eliminar detalles relacionados
                $order->purchaseOrderDetail()->delete();
                $order->delete();
            });

            return redirect()->route('orders_enterprises.index')->with('success', 'Pedido eliminado correctamente');
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al eliminar el pedido');
        }
    }
}
