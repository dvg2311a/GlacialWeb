<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeCartRequest;
use App\Models\TypeCart;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\Utils\Type;

class TypeCartController extends Controller
{

    public function index()
    {
        $type_cart = TypeCart::all();
        return Inertia::render('Carts/TypeCarts/Index', ['type_cart' => $type_cart]);
    }

    public function create()
    {
        $type_carts = new TypeCart();

        return Inertia::render('Carts/TypeCarts/Create', ['type_carts' => $type_carts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeCartRequest $request)
    {
        TypeCart::create($request -> validated());
        return redirect()->route('type_carts.index')->with('Tipo de carro creado correctamente');
    }


    public function edit(string $id)
    {
        $type_cart = TypeCart::findOrFail($id);
        return Inertia::render('Carts/TypeCarts/Edit', ['type_cart' => $type_cart]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeCartRequest $request, string $id)
    {
        $type_cart = TypeCart::findOrFail($id);
        $type_cart -> update($request -> validated());

        return redirect()->route('type_carts.index')->with('success', 'Tipo de carro actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type_cart = TypeCart::findOrFail($id);

        $type_cart -> delete();

                return redirect()->route('type_carts.index')->with('success', 'Tipo de carro eliminado');

    }
}
