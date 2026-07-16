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
        Schema::create('comunicacion.historial_publicacion_noticia', function (Blueprint $table) {
            $table->id('id_historial');
            //$table->integer('id_noticia')->nullable();
            $table->unsignedBigInteger('id_noticia');
            $table->foreign('id_noticia')
                  ->references('id_noticia')
                  ->on('comunicacion.noticias')
                  ->onDelete('cascade');

            //$table->integer('id_usuario')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');

            $table->string('accion', 100);
            $table->text('detalle')->nullable();
            $table->text('enlace_facebook')->nullable();
            $table->timestamp('fecha_accion')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('comunicacion.historial_publicacion_noticia');
    }
};
