<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\CartRequest;
use App\Models\Seller;
use App\Models\StatusCart;
use App\Models\TypeCart;
use Inertia\Inertia;


class CartController extends Controller
{

    public function index()
    {
        $carts = Cart::orderByDesc('id')->get();

        return Inertia::render('Carts/Cart/Index', ['carts' => $carts]);
    }

    public function create()
    {
        $carts = new Cart();
        $status_cart = StatusCart::all();
        $type_cart = TypeCart::all();
        $seller = Seller::all();

        return Inertia::render('Carts/Cart/Create', ['cart' => $carts, 'status_cart' => $status_cart, 'type_cart' => $type_cart, 'seller' => $seller]);
    }

    public function store(CartRequest $request)
    {
        Cart::create($request->validated());

        return redirect()->route('carts.index')->with('success', 'Carrito creado');
    }

    public function show(string $id)
    {
        $cart = Cart::findOrFail($id)->with('typeCart', 'statusCart', 'seller');
        return Inertia::render('Carts/Cart/Show', ['cart' => $cart]);
    }

    public function edit(string $id)
    {
        $carts = Cart::findOrFail($id);
        $status_cart = StatusCart::all();
        $type_cart = TypeCart::all();
        $seller = Seller::all();

        return Inertia::render('Carts/Cart/Edit');
    }

    public function update(CartRequest $request, string $id)
    {
        $cart = Cart::findOrFail($id)->with('typeCart', 'statusCart', 'seller');
        $cart->updated($request->validated());

        return redirect()->route('cart.index')->with('Carrito actualizado');
    }

    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);

        $cart->delete();

        return redirect()->route('cart.index')->with('Carrito eliminado');

    }
}
