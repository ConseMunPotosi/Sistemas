<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::create('comunicacion.sesiones', function (Blueprint $table) {
            $table->id('id_sesion');

            $table->integer('numero');
            $table->integer('gestion');

            $table->string('tipo_sesion', 30);
            $table->string('titulo', 255);

            $table->date('fecha_sesion');

            $table->string('video', 500)->nullable();

            $table->boolean('estado')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunicacion.sesiones');
    }
};
