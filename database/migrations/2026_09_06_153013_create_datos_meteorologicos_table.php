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
        Schema::create('datos_meteorologicos', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->decimal('temp', 20, 6)->nullable();
            $table->decimal('wind_speed', 20, 6)->nullable();
            $table->integer('pressure')->nullable();
            $table->integer('humidity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_meteorologicos');
    }
};
