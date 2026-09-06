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
        Schema::create('datos_suelo_predicciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->float('temperatura');
            $table->float('humedad');
            $table->float('ph');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_suelo_predicciones');
    }
};
