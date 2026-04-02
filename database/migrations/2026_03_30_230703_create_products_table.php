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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->unique()->index();
            $table->string('description', 100);
            $table->string('shape', 50)->nullable();
            $table->decimal('wholesale_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('bad_price', 10, 2);
            $table->date('expiration_date')->nullable();
            $table->foreignId('freezer_id')->constrained('freezers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('type_product_id')->constrained('type_products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('flavor_id')->constrained('flavor_products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('status_product_id')->constrained('status_products')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
