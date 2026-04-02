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
        Schema::create('plate_dimensions', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->decimal("height", 8, 2);
            $table->decimal("width", 9, 2);
            $table->enum("unit_measure", ['CM', 'INCH']);
            $table->string("shape", 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plate_dimensions');
    }
};
