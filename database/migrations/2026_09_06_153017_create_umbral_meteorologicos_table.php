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
        Schema::create('umbral_meteorologicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->decimal('humedad_min', 5, 2)->nullable();
            $table->decimal('humedad_max', 5, 2)->nullable();
            $table->decimal('temperatura_min', 5, 2)->nullable();
            $table->decimal('temperatura_max', 5, 2)->nullable();
            $table->decimal('presion_min', 5, 2)->nullable();
            $table->decimal('presion_max', 5, 2)->nullable();
            $table->timestamp('fecha')->useCurrent()->nullable();
            $table->index('id_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umbral_meteorologicos');
    }
};
