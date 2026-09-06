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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre');
            $table->string('correo_electronico')->unique();
            $table->string('password');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('codigo_verificacion', 10)->nullable();
            $table->dateTime('codigo_timestamp')->nullable();
            $table->unsignedInteger('intentos_fallidos')->default(0);
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
