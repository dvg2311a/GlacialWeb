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
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plate_number', 20)->unique();
            $table->string('description', 50)->nullable();
            $table->enum('proprietary_type', ['Company', 'Individual']);
            $table->foreignId('type_cart_id')->constrained('type_carts')->onDelete('cascade');
            $table->foreignId('status_cart_id')->constrained('status_carts')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
