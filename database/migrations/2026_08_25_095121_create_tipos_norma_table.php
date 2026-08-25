<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {   DB::statement('CREATE SCHEMA IF NOT EXISTS gaceta');

        Schema::create('gaceta.tipos_norma', function (Blueprint $table) {
            $table->id('id_tipo_norma');
            $table->string('nombre_tipo', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamp('fecha_registro')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gaceta.tipos_norma');
    }
};
