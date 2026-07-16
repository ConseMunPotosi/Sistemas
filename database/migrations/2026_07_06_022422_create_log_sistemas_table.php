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
        Schema::create('auditoria.logs_sistema', function (Blueprint $table) {
            //$table->integer('id_log')->primary();
            $table->id('id_log');
            //$table->integer('id_usuario')->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('set null');
                  
            $table->string('tabla_afectada', 100)->nullable();
            $table->string('accion', 50);
            $table->text('descripcion')->nullable();
            $table->string('ip_usuario', 50)->nullable();
            $table->timestamp('fecha_log')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('auditoria.log_sistemas');
    }
};
