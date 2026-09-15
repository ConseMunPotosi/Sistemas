<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('institucional.concejales', function (Blueprint $table) {
            $table->id('id_concejal');

            $table->string('nombre', 150);
            $table->string('cargo', 100)->nullable();
            $table->string('comision', 150)->nullable();
            $table->text('distritos')->nullable();
            $table->text('descripcion')->nullable();

            // Ruta de la fotografía del concejal
            $table->string('imagen', 255)->nullable();

            // Activo / inactivo para controlar su publicación
            $table->boolean('estado')->default(true);

            $table->timestamp('fecha_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('institucional.concejales');
    }
};
