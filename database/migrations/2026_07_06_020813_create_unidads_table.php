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
        Schema::create('institucional.unidades', function (Blueprint $table) {
            //$table->integer('id_unidad')->primary();
            $table->id('id_unidad');
            $table->string('nombre', 150);
            $table->string('sigla', 30)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamp('fecha_creacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('institucional.unidades');
    }
};
