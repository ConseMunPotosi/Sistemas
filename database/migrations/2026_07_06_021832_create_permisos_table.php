<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /* Run the migrations. */
    public function up(): void
    {
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
