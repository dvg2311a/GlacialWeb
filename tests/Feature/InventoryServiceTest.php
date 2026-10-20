<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InventoryException;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private InventoryService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(InventoryService::class);
    }

    public function test_receive_is_idempotent(): void
    {
        $product = $this->createProduct();

        $first = $this->service->receive($product->id, 10, 'test', 1, 'test:receive:1');
        $second = $this->service->receive($product->id, 10, 'test', 1, 'test:receive:1');

        $this->assertSame($first->id, $second->id);
        $this->assertSame(10, Inventory::where('product_id', $product->id)->value('stock'));
        $this->assertSame(1, InventoryTransaction::where('movement_key', 'test:receive:1')->count());
    }

    public function test_issue_rejects_insufficient_available_inventory(): void
    {
        $product = $this->createProduct();
        $this->service->receive($product->id, 5, 'test', 1, 'test:receive:2');

        $this->expectException(InsufficientInventoryException::class);

        $this->service->issue($product->id, 6, 'test', 2, 'test:issue:2');
    }

    public function test_commit_issue_and_release_preserve_accounting(): void
    {
        $product = $this->createProduct();
        $this->service->receive($product->id, 10, 'test', 1, 'test:receive:3');
        $this->service->commit($product->id, 7, 'test', 2, 'test:commit:3');

        $inventory = Inventory::where('product_id', $product->id)->firstOrFail();
        $this->assertSame(10, $inventory->stock);
        $this->assertSame(7, $inventory->committed_quantity);
        $this->assertSame(3, $inventory->available_quantity);

        $this->service->issueCommitted($product->id, 5, 'test', 3, 'test:issue-committed:3');
        $this->service->releaseCommitment($product->id, 2, 'test', 4, 'test:release:3');

        $inventory->refresh();
        $this->assertSame(5, $inventory->stock);
        $this->assertSame(0, $inventory->committed_quantity);
        $this->assertSame(5, $inventory->available_quantity);
    }

    public function test_reverse_creates_a_traceable_inverse_movement(): void
    {
        $product = $this->createProduct();
        $movement = $this->service->receive($product->id, 8, 'test', 1, 'test:receive:4');

        $reversal = $this->service->reverse($movement, 'test:reverse:4');

        $this->assertSame('reversal', $reversal->movement_type);
        $this->assertSame($movement->id, $reversal->reversal_of_id);
        $this->assertSame(0, Inventory::where('product_id', $product->id)->value('stock'));
    }

    public function test_reverse_cannot_be_applied_twice_to_the_same_movement(): void
    {
        $product = $this->createProduct();
        $movement = $this->service->receive($product->id, 4, 'test', 1, 'test:receive:6');
        $this->service->reverse($movement, 'test:reverse:6');

        $this->expectException(InventoryException::class);

        $this->service->reverse($movement, 'test:reverse:6-again');
    }

    public function test_failed_issue_does_not_create_a_movement(): void
    {
        $product = $this->createProduct();
        $this->service->receive($product->id, 2, 'test', 1, 'test:receive:5');

        try {
            $this->service->issue($product->id, 3, 'test', 2, 'test:issue:5');
        } catch (InsufficientInventoryException) {
            // Expected validation failure.
        }

        $this->assertDatabaseMissing('inventory_transactions', [
            'movement_key' => 'test:issue:5',
        ]);
        $this->assertSame(2, Inventory::where('product_id', $product->id)->value('stock'));
    }

    private function createProduct(): Product
    {
        $now = now();

        $typeFreezerId = DB::table('type_freezers')->insertGetId([
            'name' => 'TF'.random_int(100, 999),
            'description' => 'Tipo de prueba',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $freezerId = DB::table('freezers')->insertGetId([
            'number_freezer' => (string) random_int(10, 99),
            'status' => 'Activo',
            'type_freezer_id' => $typeFreezerId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $typeProductId = DB::table('type_products')->insertGetId([
            'name' => 'TP'.random_int(100, 999),
            'description' => 'Tipo de prueba',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $flavorId = DB::table('flavor_products')->insertGetId([
            'name' => 'Sabor'.random_int(100, 999),
            'description' => 'Sabor de prueba',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $statusId = DB::table('status_products')->insertGetId([
            'status' => 'Activo',
            'description' => 'Estado de prueba',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Product::create([
            'name' => 'Producto '.random_int(1000, 9999),
            'description' => 'Producto de prueba',
            'shape' => null,
            'wholesale_price' => 1,
            'retail_price' => 1,
            'purchase_price' => 1,
            'bad_price' => 1,
            'expiration_date' => null,
            'freezer_id' => $freezerId,
            'type_product_id' => $typeProductId,
            'flavor_id' => $flavorId,
            'status_product_id' => $statusId,
        ]);
    }
}
