<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\CartRequest;
use App\Models\StatusCart;
use App\Models\TypeCart;
use Inertia\Inertia;


class CartController extends Controller
{

    public function index()
    {
        $carts = Cart::orderByDesc('id')->with('typeCart')->get();

        return Inertia::render('Carts/Cart/Index', ['carts' => $carts]);
    }

    public function create()
    {
        $carts = new Cart();
        $status_cart = StatusCart::all();
        $type_cart = TypeCart::all();

        return Inertia::render('Carts/Cart/Create', ['cart' => $carts, 'status_cart' => $status_cart, 'type_cart' => $type_cart]);
    }

    public function store(CartRequest $request)
    {
        Cart::create($request->validated());

        return redirect()->route('carts.index')->with('success', 'Carrito creado');
    }

    public function show(string $id)
    {
        $cart = Cart::with('typeCart', 'statusCart')->findOrFail($id);
        return Inertia::render('Carts/Cart/Show', ['cart' => $cart]);
    }

    public function edit(string $id)
    {
        $carts = Cart::findOrFail($id);
        $status_cart = StatusCart::all();
        $type_cart = TypeCart::all();

        return Inertia::render('Carts/Cart/Edit', ['cart' => $carts, 'status_cart' => $status_cart, 'type_cart' => $type_cart]);
    }

    public function update(CartRequest $request, Cart $cart)
    {
        $cart->update($request->validated());

        return redirect()->route('carts.index')->with('success', 'Carrito actualizado');
    }

    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);

        $cart->delete();

        return redirect()->route('carts.index')->with('Carrito eliminado');

    }
}
