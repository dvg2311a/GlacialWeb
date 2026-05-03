<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlavorProductRequest;
use App\Models\FlavorProduct;
use Inertia\Inertia;

class FlavorProductController extends Controller
{
    public function index()
    {
        $flavor_product = FlavorProduct::all();

        return Inertia::render('Product/FlavorProduct/Index', ['flavor_product' => $flavor_product]);
    }

    public function create()
    {
        $flavor_products = new FlavorProduct();
        return Inertia::render('Product/FlavorProduct/Create', ['flavor_products' => $flavor_products]);
    }

    public function store(FlavorProductRequest $request)
    {
        FlavorProduct::create($request->validated());
        return redirect()->route('flavor_products.index')->with('success', 'Sabor de producto creado correctamente');
    }


    public function show(string $id)
    {
        $flavor_product = FlavorProduct::findOrFail($id);

        return Inertia::render('Product/FlavorProduct/Show', ['flavor_product' => $flavor_product]);
    }

    public function edit(string $id)
    {
        $flavor_product = FlavorProduct::findOrFail($id);
        return Inertia::render('Product/FlavorProduct/Edit', ['flavor_product' => $flavor_product]);
    }

    public function update(FlavorProductRequest $request, string $id)
    {
        $flavor_product = FlavorProduct::findOrFail($id);
        $flavor_product->update($request->validated());

        return redirect()->route('flavor_products.index')->with('success', 'Sabor de producto actualizado');
    }

    public function destroy(string $id)
    {
        $flavor_product = FlavorProduct::findOrFail($id);
        $flavor_product->delete();

        return redirect()->route('flavor_products.index');
    }
}
