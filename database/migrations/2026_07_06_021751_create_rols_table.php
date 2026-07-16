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
        Schema::create('seguridad.roles', function (Blueprint $table) {
            //$table->integer('id_rol')->primary();
            $table->id('id_rol');
            $table->string('nombre', 80);
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad.roles');
    }
};
