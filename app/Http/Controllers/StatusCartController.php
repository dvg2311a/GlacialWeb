<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusCartRequest;
use App\Models\StatusCart;
use Inertia\Inertia;

class StatusCartController extends Controller
{
    public function index()
    {
        $status_carts = StatusCart::all();
        return Inertia::render('Carts/StatusCarts/Index', ['status_carts' => $status_carts]);
    }

    public function create()
    {
        $status_carts = new StatusCart();

        return Inertia::render('Carts/StatusCarts/Create', ['status_carts' => $status_carts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusCartRequest $request)
    {
        StatusCart::create($request->validated());
        return redirect()->route('status_carts.index')->with('Status de carrito creado correctamente');
    }


    public function edit(string $id)
    {
        $status_cart = StatusCart::findOrFail($id);
        return Inertia::render('Carts/StatusCarts/Edit', ['status_cart' => $status_cart]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusCartRequest $request, string $id)
    {
        $status_cart = StatusCart::findOrFail($id);
        $status_cart->update($request->validated());

        return redirect()->route('status_carts.index')->with('success', 'Status de carrito actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status_cart = StatusCart::findOrFail($id);

        $status_cart->delete();

        return redirect()->route('status_carts.index')->with('success', 'Status de carrito eliminado');
    }
}
