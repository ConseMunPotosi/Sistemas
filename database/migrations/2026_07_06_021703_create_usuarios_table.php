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
        Schema::create('seguridad.usuarios', function (Blueprint $table) {
            //$table->integer('id_usuario')->primary();
            $table->id('id_usuario');
            //$table->integer('id_funcionario')->nullable();
            $table->unsignedBigInteger('id_funcionario');
            $table->foreign('id_funcionario')
                  ->references('id_funcionario')
                  ->on('institucional.funcionarios')
                  ->onDelete('set null');
            $table->string('usuario', 50)->unique();
            $table->text('password_hash');
            $table->string('correo', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamp('fecha_creacion')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad.usuarios');
    }
};
