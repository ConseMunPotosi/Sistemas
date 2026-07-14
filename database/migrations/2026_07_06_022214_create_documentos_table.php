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
        Schema::create('documental.documentos', function (Blueprint $table) {
            //$table->integer('id_documento')->primary();
            $table->id('id_documento');
            //$table->string('codigo', 100)->nullable();
            $table->string('cite', 100)->nullable();
            $table->text('referencia')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_documento')->nullable();
            $table->string('prioridad', 30)->nullable();
            $table->boolean('reservado')->default(false);
            //$table->boolean('estado')->default(true);
            //$table->integer('id_tipo_documento')->nullable();
            $table->unsignedBigInteger('id_tipo_documento');
            $table->foreign('id_tipo_documento')
                  ->references('id_tipo_documento')
                  ->on('documental.tipos_documento')
                  ->onDelete('set null');

            //$table->integer('id_estado')->nullable();
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')
                  ->references('id_estado')
                  ->on('documental.estados_documento')
                  ->onDelete('set null');
            
            //$table->integer('id_unidad_origen')->nullable();
            $table->unsignedBigInteger('id_unidad_origen');
            $table->foreign('id_unidad_origen')
                  ->references('id_unidad')
                  ->on('institucional.unidades')
                  ->onDelete('set null');

            //$table->integer('id_usuario_creador')->nullable();
            $table->unsignedBigInteger('id_usuario_creador');
            $table->foreign('id_usuario_creador')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');

            $table->timestamp('fecha_registro')->nullable();

            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documental.documentos');
    }
};
