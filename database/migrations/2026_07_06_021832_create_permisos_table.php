<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /* Run the migrations. */
    public function up(): void
    {
        // 1. Tabla de roles
        Schema::create('seguridad.roles', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->nullable()->useCurrentOnUpdate();
        });

        // 2. Tabla de permisos
        Schema::create('seguridad.permisos', function (Blueprint $table) {
            $table->id('id_permiso');
            $table->string('modulo', 50);
            $table->string('accion', 50);
            $table->string('descripcion', 255)->nullable();
            $table->unique(['modulo', 'accion']); // Combinación única
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->nullable()->useCurrentOnUpdate();
        });

        // 3. Tabla de usuarios
        Schema::create('seguridad.usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedBigInteger('id_funcionario');
            $table->string('usuario', 50)->unique();
            $table->text('password_hash');
            $table->string('correo', 150)->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('intentos_fallidos')->default(0);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->string('remember_token', 100)->nullable();

            // Índices
            $table->index('usuario');
            $table->index('correo');
            $table->index('activo');

            // Llave foránea
            $table->foreign('id_funcionario')
                  ->references('id_funcionario')
                  ->on('institucional.funcionarios')
                  ->onDelete('cascade');
        });

        // 4. Tabla pivote: usuario_roles
        Schema::create('seguridad.usuario_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->timestamp('fecha_asignacion')->useCurrent();

            // Llaves foráneas
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('seguridad.usuarios')
                  ->onDelete('cascade');

            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('seguridad.roles')
                  ->onDelete('cascade');

            // Combinación única
            $table->unique(['id_usuario', 'id_rol']);

            // Índices
            $table->index('id_usuario');
            $table->index('id_rol');
        });

        // 5. Tabla pivote: rol_permisos
        Schema::create('seguridad.rol_permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_permiso');
            $table->timestamp('fecha_asignacion')->useCurrent();

            // Llaves foráneas
            $table->foreign('id_rol')
                  ->references('id_rol')
                  ->on('seguridad.roles')
                  ->onDelete('cascade');

            $table->foreign('id_permiso')
                  ->references('id_permiso')
                  ->on('seguridad.permisos')
                  ->onDelete('cascade');

            // Combinación única
            $table->unique(['id_rol', 'id_permiso']);

            // Índices
            $table->index('id_rol');
            $table->index('id_permiso');
        });
    }

    /* Reverse the migrations. */
    public function down(): void
    {
        // Eliminar en orden inverso (por las llaves foráneas)
        Schema::dropIfExists('seguridad.rol_permisos');
        Schema::dropIfExists('seguridad.usuario_roles');
        Schema::dropIfExists('seguridad.usuarios');
        Schema::dropIfExists('seguridad.permisos');
        Schema::dropIfExists('seguridad.roles');
    }
};
