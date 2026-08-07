<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seguridad.usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_funcionario');
            $table->string('usuario', 50)->unique();
            $table->text('password_hash');
            $table->boolean('activo')->default(true);
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->string('remember_token', 100)->nullable();

            // Índices para mejorar rendimiento
            $table->index('usuario');
            $table->index('activo');

            // Llave foránea
            $table->foreign('id_funcionario')
                  ->references('id_funcionario')
                  ->on('institucional.funcionarios')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguridad.usuarios');
    }
};
