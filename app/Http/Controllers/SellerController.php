<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRequest;
use App\Models\Cart;
use App\Models\Seller;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SellerController extends Controller
{

    public function index()
    {
        $seller = Seller::with('cart')->get();
        return Inertia::render('SellersModule/Seller/Index', ['seller' => $seller]);
    }


    public function create()
    {
        $seller = new Seller();
        $carts = Cart::all();
        return Inertia::render('SellersModule/Seller/Create', ['seller' => $seller, 'carts' => $carts]);
    }

    public function store(SellerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('store/sellers', 'public');
        }

        Seller::create($data);
        return redirect()->route('sellers.index')->with('success', 'Vendedor guardado correctamente');
    }


    public function show(string $id)
    {
        $seller = Seller::with('cart')->findOrFail($id);

        return Inertia::render('SellersModule/Seller/Show', [
            'seller' => $seller,
        ]);
    }

    public function picture(Seller $seller)
    {
        abort_unless($seller->getRawOriginal('picture'), 404);

        return response()->file(Storage::disk('public')->path($seller->getRawOriginal('picture')));
    }

    public function edit(string $id)
    {
        $seller = Seller::findOrFail($id);
        $carts = Cart::all();

        return Inertia::render('SellersModule/Seller/Edit', [
            'seller' => $seller,
            'carts' => $carts,
        ]);
    }

    public function update(SellerRequest $request, string $id)
    {
        $seller = Seller::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('picture')) {
            if ($seller->getRawOriginal('picture')) {
                Storage::disk('public')->delete($seller->getRawOriginal('picture'));
            }

            $data['picture'] = $request->file('picture')->store('store/sellers', 'public');
        }

        $seller->update($data);

        return redirect()->route('sellers.index')->with('success', 'Vendedor actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $seller = Seller::findOrFail($id);

        if ($seller->getRawOriginal('picture')) {
            Storage::disk('public')->delete($seller->getRawOriginal('picture'));
        }

        $seller->delete();

        return redirect()->route('sellers.index')->with('success', 'Vendedor eliminado correctamente');
    }
}
