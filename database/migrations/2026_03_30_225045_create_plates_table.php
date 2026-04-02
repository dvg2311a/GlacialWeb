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
        Schema::create('plates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_plate')->unique();
            $table->foreignId('plate_dimension_id')->constrained('plate_dimensions')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("freezer_id")->constrained('freezers')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plates');
    }
};
