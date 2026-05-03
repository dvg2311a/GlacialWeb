<?php

namespace App\Http\Controllers;

use App\Models\StatusProduct;
use Inertia\Inertia;
use App\Http\Requests\StatusProductRequest;

class StatusProductController extends Controller
{
    public function index()
    {
        $status_products = StatusProduct::all();

        return Inertia::render('Product/StatusProduct/Index', ['status_products' => $status_products]);
    }

    public function create()
    {
        $status_products = new StatusProduct();
        return Inertia::render('Product/StatusProduct/Create', ['status_products' => $status_products]);
    }

    public function store(StatusProductRequest $request)
    {
        StatusProduct::create($request->validated());
        return redirect()->route('status_products.index')->with('success', 'Tipo de producto creado correctamente');
    }


    public function show(string $id)
    {
        $status_product = StatusProduct::findOrFail($id);

        return Inertia::render('Product/StatusProduct/Show', ['status_product' => $status_product]);
    }

    public function edit(string $id)
    {
        $status_product = StatusProduct::findOrFail($id);
        return Inertia::render('Product/StatusProduct/Edit', ['status_product' => $status_product]);
    }

    public function update(StatusProductRequest $request, string $id)
    {
        $status_product = StatusProduct::findOrFail($id);
        $status_product->update($request->validated());

        return redirect()->route('status_products.index')->with('success', 'Tipo de producto actualizado');
    }

    public function destroy(string $id)
    {
        $status_product = StatusProduct::findOrFail($id);
        $status_product->delete();

        return redirect()->route('status_products.index');
    }
}
