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
        Schema::create('institucional.funcionarios', function (Blueprint $table) {
            //$table->integer('id_funcionario')->primary();
            $table->id('id_funcionario');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('ci', 30)->unique();
            $table->string('celular', 30)->nullable();
            $table->string('correo', 150)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamp('fecha_registro')->nullable();
            //$table->integer('id_unidad')->nullable();
            // 1. Crear la columna para la Unidad
            $table->unsignedBigInteger('id_unidad');
            // Definir la llave foránea física hacia el esquema institucional
            $table->foreign('id_unidad')
                ->references('id_unidad')
                ->on('institucional.unidades')
                ->onDelete('restrict');
            //$table->integer('id_cargo')->nullable();
            // 2. Crear la columna para el Cargo
            $table->unsignedBigInteger('id_cargo');
            // Definir la llave foránea física hacia el esquema institucional
            $table->foreign('id_cargo')
                ->references('id_cargo')
                ->on('institucional.cargos')
                ->onDelete('restrict');
            $table->date('gestion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('institucional.funcionarios');
    }
};
