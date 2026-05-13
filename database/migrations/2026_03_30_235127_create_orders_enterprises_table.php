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
        Schema::create('orders_enterprises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('order_date');
            $table->date('expected_date');
            $table->enum('order_status', ['Pending', 'Received']);
            $table->decimal('total_tax', 12, 2)->default(0);
            $table->decimal('subtotal_base', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_enterprises');
    }
};
