<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->string('search'));
        $status = $request->string('status')->toString();

        $inventories = Inventory::query()
            ->with('product:id,name')
            ->when($search !== '', function ($query) use ($search): void {
                $query->whereHas('product', fn ($productQuery) => $productQuery->where('name', 'like', "%{$search}%"));
            })
            ->when(in_array($status, ['available', 'low', 'out'], true), function ($query) use ($status): void {
                $query->where(function ($inventoryQuery) use ($status): void {
                    if ($status === 'out') {
                        $inventoryQuery->whereRaw('stock - committed_quantity <= 0');
                    } elseif ($status === 'low') {
                        $inventoryQuery->whereRaw('stock - committed_quantity > 0')
                            ->whereRaw('stock - committed_quantity <= low_stock_threshold');
                    } else {
                        $inventoryQuery->whereRaw('stock - committed_quantity > low_stock_threshold');
                    }
                });
            })
            ->orderBy('product_id')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'stock' => (int) Inventory::sum('stock'),
            'committed' => (int) Inventory::sum('committed_quantity'),
            'in_transit' => (int) Inventory::sum('in_transit_quantity'),
            'available' => (int) Inventory::selectRaw('COALESCE(SUM(stock - committed_quantity), 0) as total')->value('total'),
            'low' => (int) Inventory::whereRaw('stock - committed_quantity <= low_stock_threshold')->count(),
            'out' => (int) Inventory::whereRaw('stock - committed_quantity <= 0')->count(),
        ];

        return Inertia::render('Inventory/Index', [
            'inventories' => $inventories,
            'summary' => $summary,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function show(string $id): Response
    {
        $inventory = Inventory::query()
            ->with(['product:id,name', 'transactions' => fn ($query) => $query->latest()->with('user:id,name,surname')->limit(50)])
            ->findOrFail($id);

        return Inertia::render('Inventory/Show', [
            'inventory' => $inventory,
        ]);
    }
}
