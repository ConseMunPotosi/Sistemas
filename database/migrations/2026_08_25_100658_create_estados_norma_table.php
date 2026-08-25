<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gaceta.estados_norma', function (Blueprint $table) {
            $table->id('id_estado_norma');
            $table->string('nombre_estado', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamp('fecha_registro')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gaceta.estados_norma');
    }
};
