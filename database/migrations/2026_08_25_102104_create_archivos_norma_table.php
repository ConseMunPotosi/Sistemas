<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gaceta.archivosNorma', function (Blueprint $table) {
            $table->id('id_archivo');

            // Relación principal con normas
            $table->unsignedBigInteger('id_norma');

            $table->string('nombre_archivo');
            $table->text('ruta_archivo');
            $table->string('extension')->nullable();
            $table->bigInteger('peso')->nullable();
            $table->boolean('estado')->default(true);
            $table->string('tipo_mime')->nullable();

            // Relación con usuarios
            $table->unsignedBigInteger('subidor_por')->nullable();
            $table->timestamp('fecha_subida')->default(now());
            $table->timestamps();

            // Claves foráneas
            $table->foreign('id_norma')->references('id_norma')->on('gaceta.normas')->onDelete('cascade');
            $table->foreign('subidor_por')->references('id_usuario')->on('seguridad.usuarios')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gaceta.archivosNorma');
    }
};
