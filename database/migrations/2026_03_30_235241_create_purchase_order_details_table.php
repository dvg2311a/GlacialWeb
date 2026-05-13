<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('boxes_ordered');
            $table->integer('boxes_received')->default(0);
            $table->decimal('purchase_price_per_box', 12, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0.15);
            $table->decimal('tax_amount_per_box', 12, 2);
            $table->decimal('purchase_price_total_per_box', 12, 2);
            $table->foreignId('order_enterprise_id')->constrained('orders_enterprises')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->constrained('products')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
