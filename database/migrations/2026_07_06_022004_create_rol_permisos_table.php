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
        Schema::create('seguridad.rol_permisos', function (Blueprint $table) {
            /*$table->integer('id_rol');
            $table->integer('id_permiso');*/
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_permiso');

            $table->primary(['id_rol', 'id_permiso']);
            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('seguridad.roles')
                  ->onDelete('cascade');
            $table->foreign('id_permiso')
                  ->references('id_permiso')
                  ->on('seguridad.permisos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad.rol_permisos');
    }
};
