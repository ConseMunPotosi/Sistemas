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
        Schema::create('comunicacion.noticias', function (Blueprint $table) {
            $table->id('id_noticia');
            $table->string('titulo', 200)->nullable();
            $table->text('resumen')->nullable();
            $table->text('contenido')->nullable();
            $table->text('imagen_portada')->nullable();
            //$table->integer('id_categoria')->nullable();
            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')
                  ->references('id_categoria')
                  ->on('comunicacion.categorias_noticia')
                  ->onDelete('set null');

            //$table->integer('id_usuario_creador')->nullable();
            $table->unsignedBigInteger('id_usuario_creador');
            $table->foreign('id_usuario_creador')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');

            $table->string('estado_publicacion', 30)->nullable();
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_publicacion')->nullable();
            $table->boolean('publicado_web')->default(false);
            $table->boolean('publicado_facebook')->default(false);
            $table->text('enlace_facebook')->nullable();
            $table->string('facebook_post_id', 150)->nullable();
            $table->boolean('estado')->default(true);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('comunicacion.noticias');
    }
};
