<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('institucional.configuracion', function (Blueprint $table) {
            $table->id('id_configuracion');

            $table->string('clave', 100)->unique();
            $table->text('valor')->nullable();
            $table->string('descripcion', 255)->nullable();

            $table->boolean('estado')->default(true);
            $table->timestamp('fecha_registro')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institucional.configuracion');
    }
};
