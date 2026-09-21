<?php

namespace App\Http\Controllers;

use App\Models\PrivateSale;
use App\Models\PrivateSaleDetail;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\http\Requests\PrivateSaleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Throwable;

class PrivateSaleController extends Controller
{

    public function index()
    {
        $private_sale = PrivateSale::with('privateSaleDetail:id,quantity,unit_price,line_total,type_price,product_id,user_id,private_sale_id', 'seller:id,name,surname')->orderBy('grand_total', 'asc')->get();

        $private_sale_group = $private_sale->groupBy(function ($item) {
            return $item->date_sale->format('Y-m-d');
        });

        return Inertia::render('PrivateSale/Index', [
            'private_sale_group' => $private_sale_group
        ]);
    }

    public function create()
    {
        $private_sale = new PrivateSale();
        $private_sale_details = new PrivateSaleDetail();
        $product = Product::all();
        $seller = Seller::all();

        return Inertia::render('PrivateSale/Create', [
            'private_sale' => $private_sale,
            'private_sale_details' => $private_sale_details,
            'product' => $product,
            'seller' => $seller
        ]);
    }

    public function store(PrivateSaleRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $private_groups = $request->input('private_sales');

                if (empty($private_groups) && filled('seller_id') && !empty($request->input('private_sales', []))) {
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
                        'grand_total' => $grandTotal
                    ]);
                }
            });

            return redirect()->route('private_sales.index')->with('success', 'Registro creado exitosamente');
        } catch (Throwable $e) {
            dd($e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear el registro');
        }
    }

    public function show(string $id)
    {
        $private_sale = PrivateSale::with('privateSaleDetail.product', 'seller')->findOrFail($id);
        $product = Product::all();


        return Inertia::render('PrivateSale/Show', [
            'private_sale' => $private_sale,
            'product' => $product
        ]);
    }

    public function edit(string $id)
    {
        $private_sale = PrivateSale::with('privateSaleDetail.product', 'seller')->findOrFail($id);
        $product = Product::all();
        $seller = Seller::all();

        return Inertia::render('PrivateSale/Edit', [
            'private_sale' => $private_sale,
            'product' => $product,
            'seller' => $seller
        ]);
    }

    public function update(PrivateSaleRequest $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {

                $private_sale = PrivateSale::findOrFail($id);

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
                    'grand_total' => $grandTotal
                ]);
            });
            //? End DB::transaction
            return redirect()->route('private_sales.index')->with('success', 'Registro actualizado exitosamente');
        } catch (Throwable $e) {
            dd($e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear el registro');
        }
    }

    public function destroy(string $id)
    {
        try{
            DB::transaction(function() use ($id){
                $private_delete = PrivateSale::findOrFail($id);
                $private_delete->privateSaleDetail()->delete();
                $private_delete->delete();
            });

        } catch(Throwable){
            return back()->with('error', 'Ocurrió un error al eliminar esta venta');
        }

        return redirect()->route('private_sales.index')->with('success', 'Venta privada eliminada exitosamente');
    }
}
