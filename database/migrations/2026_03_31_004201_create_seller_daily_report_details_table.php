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
        Schema::create('seller_daily_report_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity_out')->default(0);
            $table->integer('quantity_return')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('wholesale_price', 12, 2)->default(0);
            $table->foreignId('seller_daily_report_id')->constrained('seller_daily_reports')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_daily_report_details');
    }
};
