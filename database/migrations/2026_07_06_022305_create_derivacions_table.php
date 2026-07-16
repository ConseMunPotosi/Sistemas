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
        Schema::create('documental.derivaciones', function (Blueprint $table) {
            //$table->integer('id_derivacion')->primary();
            $table->id('id_derivaciones');
            //$table->integer('id_documento')->nullable();
            $table->unsignedBigInteger('id_documento');
            $table->foreign('id_documento')
                  ->references('id_documento')
                  ->on('documental.documentos')
                  ->onDelete('cascade');

            //$table->integer('id_unidad_origen')->nullable();
            $table->unsignedBigInteger('id_unidad_origen');
            $table->foreign('id_unidad_origen')
                  ->references('id_unidad')
                  ->on('institucional.unidades')
                  ->onDelete('set null');

            //$table->integer('id_unidad_destino')->nullable();
            $table->unsignedBigInteger('id_unidad_destino');
            $table->foreign('id_unidad_destino')
                  ->references('id_unidad')
                  ->on('institucional.unidades')
                  ->onDelete('set null');

            //$table->integer('id_usuario_envia')->nullable();
            $table->unsignedBigInteger('id_usuario_envia');
            $table->foreign('id_usuario_envia')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');

            $table->unsignedBigInteger('id_usuario_recibe');
            $table->foreign('id_usuario_recibe')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');
            
            $table->text('proveido')->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_recepcion')->nullable();
            $table->boolean('recibido')->default(false);
            $table->string('estado_derivacion', 50)->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documental.derivacions');
    }
};
