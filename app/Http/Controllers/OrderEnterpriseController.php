<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderEnterpriseRequest;
use App\Models\OrderEnterprise;
use App\Models\Product;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class OrderEnterpriseController extends Controller
{

    public function index()
    {
        $order = OrderEnterprise::query()->with('purchaseOrderDetail:id,boxes_received,purchase_price_total_per_box,order_enterprise_id,product_id')->get();

        return Inertia::render('OrderEnterprise/Index', ['order' => $order]);
    }

    public function create(): mixed
    {
        $order = new OrderEnterprise();
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
                    'order_status' => $request->order_status
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
                        'boxes_ordered' => $product['boxes_ordered'],
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
                        'grand_total' => $grandTotalLine
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

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $order = OrderEnterprise::findOrFail($id);
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
