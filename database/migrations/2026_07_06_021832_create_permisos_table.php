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
        Schema::create('seguridad.permisos', function (Blueprint $table) {
            //$table->integer('id_permiso')->primary();
            $table->id('id_permiso');
            $table->string('modulo', 100);
            $table->string('accion', 100);
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad.permisos');
    }
};
