<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gaceta.normas', function (Blueprint $table) {
            $table->id('id_norma');

            // Relaciones con las nuevas tablas
            $table->unsignedBigInteger('id_tipo_norma')->nullable();
            $table->unsignedBigInteger('id_estado_norma')->nullable();

            // Relación con usuarios
            $table->unsignedBigInteger('id_usuario_creacion')->nullable();
            $table->unsignedBigInteger('id_norm_actualizacion')->nullable();
            $table->unsignedBigInteger('id_usuario_actualizacion')->nullable();

            $table->string('inicial_norma')->nullable();
            $table->integer('numero')->nullable();
            $table->integer('gestion')->nullable();
            $table->string('titulo', 500)->nullable();
            $table->text('desripcion')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('fecha_registro')->nullable();

            // Claves foráneas
            $table->foreign('id_tipo_norma')->references('id_tipo_norma')->on('gaceta.tipos_norma')->onDelete('set null');
            $table->foreign('id_estado_norma')->references('id_estado_norma')->on('gaceta.estados_norma')->onDelete('set null');
            $table->foreign('id_usuario_creacion')->references('id_usuario')->on('seguridad.usuarios')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gaceta.normas');
    }
};
