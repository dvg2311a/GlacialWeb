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
        Schema::create('private_sale_details', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("quantity");
            $table->decimal("unit_price");
            $table->decimal("line_total", 12, 2);
            $table->enum("type_price", ["Detalle", "Mayorista", "Malo"]);

            $table->foreignId("product_id")->constrained("products")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("private_sale_id")->constrained("private_sales")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_sale_details');
    }
};
