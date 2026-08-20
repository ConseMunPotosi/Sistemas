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
        Schema::table('comunicacion.noticias', function (Blueprint $table) {
            // Eliminamos la columna si existe (para evitar duplicados)
            if (Schema::hasColumn('comunicacion.noticias', 'fecha_publicacion')) {
                $table->dropColumn('fecha_publicacion');
            }
        });

        Schema::table('comunicacion.noticias', function (Blueprint $table) {
            // La recreamos con tipo timestamp y permitiendo null
            $table->timestamp('fecha_publicacion')->nullable()->after('fecha_creacion');
        });
    }

    public function down()
    {
        Schema::table('comunicacion.noticias', function (Blueprint $table) {
            $table->dropColumn('fecha_publicacion');
        });
    }
};
