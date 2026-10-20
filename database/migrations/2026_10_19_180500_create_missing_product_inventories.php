<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('products')
            ->leftJoin('inventories', 'inventories.product_id', '=', 'products.id')
            ->whereNull('inventories.id')
            ->select('products.id as product_id')
            ->orderBy('products.id')
            ->chunkById(500, function ($products) use ($now): void {
                $inventories = $products->map(fn ($product): array => [
                    'product_id' => $product->product_id,
                    'stock' => 0,
                    'committed_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'low_stock_threshold' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                if ($inventories !== []) {
                    DB::table('inventories')->insertOrIgnore($inventories);
                }
            }, 'products.id', 'product_id');
    }

    public function down(): void
    {
        // Inventory rows are preserved because they may contain real stock.
    }
};
