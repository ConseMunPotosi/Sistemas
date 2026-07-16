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
        Schema::create('documental.archivos_documento', function (Blueprint $table) {
            //$table->integer('id_archivo')->primary();
            $table->id('id_archivo');
            //$table->integer('id_documento')->nullable();
            $table->unsignedBigInteger('id_documento');
            $table->foreign('id_documento')
                  ->references('id_documento')
                  ->on('documental.documentos')
                  ->onDelete('cascade');
            $table->string('nombre_archivo', 255);
            $table->text('ruta_archivo');
            $table->string('tipo_mime', 100)->nullable();
            $table->string('extension', 20)->nullable();
            $table->bigInteger('peso_bytes')->nullable();
            $table->integer('version')->default(1);
            //$table->integer('subido_por')->nullable();
            $table->unsignedBigInteger('subido_por');
            $table->foreign('subido_por')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');
                  
            $table->timestamp('fecha_subida')->nullable();
            $table->boolean('estado')->default(true);

           
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documental.archivos_documento');
    }
};
