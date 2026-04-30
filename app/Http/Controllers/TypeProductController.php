<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeProductRequest;
use App\Models\TypeProduct;
use Inertia\Inertia;

class TypeProductController extends Controller
{

    public function index()
    {
        $type_product = TypeProduct::all();

        return Inertia::render('Product/TypeProduct/Index', ['type_product' => $type_product]);
    }

    public function create()
    {
        $type_products = new TypeProduct();
        return Inertia::render('Product/TypeProduct/Create', ['type_products' => $type_products]);
    }

    public function store(TypeProductRequest $request)
    {
        TypeProduct::create($request->validated());
        return redirect()->route('type_products.index')->with('success', 'Tipo de prodcto creado correctamente');
    }


    public function show(string $id)
    {
        $type_product = TypeProduct::findOrFail($id);

        return Inertia::render('Product/TypeProduct/Show', ['type_product' => $type_product]);
    }

    public function edit(string $id)
    {
        $type_product = TypeProduct::findOrFail($id);
        return Inertia::render('Product/TypeProduct/Edit', ['type_product' => $type_product]);
    }

    public function update(TypeProductRequest $request, string $id)
    {
        $type_product = TypeProduct::findOrFail($id);
        $type_product->update($request->validated());

        return redirect()->route('type_products.index')->with('success', 'Tipo de producto actualizado');
    }

    public function destroy(string $id)
    {
        $type_product = TypeProduct::findOrFail($id);
        $type_product->delete();

        return redirect()->route('type_products.index');
    }
}
