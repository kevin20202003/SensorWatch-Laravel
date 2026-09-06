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
        Schema::create('datos_meteorologicos_predicciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->float('temp');
            $table->float('humidity');
            $table->float('pressure');
            $table->float('wind_speed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_meteorologicos_predicciones');
    }
};
