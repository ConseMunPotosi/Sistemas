<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gaceta.normas', function (Blueprint $table) {
            // Solo agregar si no existe
            if (!Schema::hasColumn('gaceta.normas', 'id_tipo_norma')) {
                $table->unsignedBigInteger('id_tipo_norma')->nullable();
                $table->foreign('id_tipo_norma')->references('id_tipo_norma')->on('gaceta.tipos_norma')->onDelete('set null');
            }

            if (!Schema::hasColumn('gaceta.normas', 'id_estado_norma')) {
                $table->unsignedBigInteger('id_estado_norma')->nullable();
                $table->foreign('id_estado_norma')->references('id_estado_norma')->on('gaceta.estados_norma')->onDelete('set null');
            }

            if (!Schema::hasColumn('gaceta.normas', 'id_usuario_creacion')) {
                $table->unsignedBigInteger('id_usuario_creacion')->nullable();
                $table->foreign('id_usuario_creacion')->references('id_usuario')->on('seguridad.usuarios')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('gaceta.normas', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_norma']);
            $table->dropForeign(['id_estado_norma']);
            $table->dropForeign(['id_usuario_creacion']);
            $table->dropColumn(['id_tipo_norma', 'id_estado_norma', 'id_usuario_creacion']);
        });
    }
};
