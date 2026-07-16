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
        Schema::create('seguridad.usuario_roles', function (Blueprint $table) {
            //$table->integer('id_usuario');
            //$table->integer('id_rol');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');

            $table->primary(['id_usuario', 'id_rol']);
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('cascade');
            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('seguridad.roles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad.usuario_roles');
    }
};
