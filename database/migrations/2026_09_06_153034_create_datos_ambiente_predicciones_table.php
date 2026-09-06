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
        Schema::create('datos_ambiente_predicciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->float('temperatura_amb');
            $table->float('humedad_amb');
            $table->float('lux');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_ambiente_predicciones');
    }
};
