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
        Schema::create('documental.historial_documento', function (Blueprint $table) {
            //$table->integer('id_historial')->primary();
            $table->id('id_historial');
            //$table->integer('id_documento')->nullable();
            $table->unsignedBigInteger('id_documento');
            $table->foreign('id_documento')
                  ->references('id_documento')
                  ->on('documental.documentos')
                  ->onDelete('cascade');

            //$table->integer('id_usuario')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');

            $table->string('accion', 100);
            $table->text('detalle')->nullable();
            $table->timestamp('fecha_accion')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documental.historial_documentos');
    }
};
