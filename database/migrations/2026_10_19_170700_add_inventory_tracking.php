<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('inventories')
            ->select('product_id')
            ->groupBy('product_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('product_id');

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException(
                'No se puede establecer la relación 1:1 de inventario. '.
                'Existen productos con registros duplicados: '.$duplicates->implode(', ').'.'
            );
        }

        Schema::table('inventories', function (Blueprint $table) {
            $table->unique('product_id');
            $table->integer('committed_quantity')->default(0)->after('stock');
            $table->integer('in_transit_quantity')->default(0)->after('committed_quantity');
            $table->integer('low_stock_threshold')->default(0)->after('in_transit_quantity');
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('movement_type', 30);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->integer('committed_before')->default(0);
            $table->integer('committed_after')->default(0);
            $table->string('reference_type', 100);
            $table->unsignedBigInteger('reference_id');
            $table->string('movement_key', 191)->unique();
            $table->foreignId('reversal_of_id')->nullable()->constrained('inventory_transactions')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUnique(['product_id']);
            $table->dropColumn([
                'committed_quantity',
                'in_transit_quantity',
                'low_stock_threshold',
            ]);
        });
    }
};
