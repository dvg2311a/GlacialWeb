<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\FlavorProduct;
use App\Models\Freezer;
use App\Models\Product;
use App\Models\StatusProduct;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class ProductController extends Controller
{

    public function index()
    {
        $products_view = Product::with(
            'freezer:id,number_freezer',
            'typeProduct:id,name',
            'statusProduct:id,status',
            'flavor:id,name'
        )->get();

        return Inertia::render('Product/Products/Index', ['product_view' => $products_view]);
    }

    public function create()
    {
        $products = new Product();
        $freezers = Freezer::all();
        $flavor_products = FlavorProduct::all();
        $status_products = StatusProduct::all();
        $type_products = TypeProduct::all();
        return Inertia::render('Product/Products/Create', ['products' => $products, 'freezers' => $freezers,
        'flavor_products' => $flavor_products, 'status_products' => $status_products, 'type_products' => $type_products]);
    }

    public function store(ProductRequest $request)
    {
        try {
            Product::create($request->validated());
            return redirect()->route('products.index')->with('success', 'Producto creado');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', '¡Ups! Ha ocurrido un error y el producto no fue creado. Vuelvelo a intentar.');
        }
    }

    public function show(string $id)
    {
        $product_show = Product::with('freezer', 'flavor', 'statusProduct', 'typeProduct')->findOrFail($id);
        // $freezer = Freezer::all();
        // $type_product = TypeProduct::all();
        // $flavor_product = FlavorProduct::all();
        // $status_product = StatusProduct::all();
        return Inertia::render('Product/Products/Show', ['product_show' => $product_show]);
    }

    public function edit(string $id)
    {
        $products = Product::findOrFail($id);
        $freezers = Freezer::all();
        $flavor_products = FlavorProduct::all();
        $status_products = StatusProduct::all();
        $type_products = TypeProduct::all();
        return Inertia::render('Product/Products/Edit', ['products' => $products, 'freezers' => $freezers,
        'flavor_products' => $flavor_products, 'status_products' => $status_products, 'type_products' => $type_products]);
    }


    public function update(ProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);

        $product -> update($request -> validated());

        return redirect() ->route('products.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product ->delete();

        return redirect() ->route('products.index')->with('success', 'Producto eliminado correctamente');

    }
}
