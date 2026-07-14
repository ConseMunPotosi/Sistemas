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
        Schema::create('documental.estados_documento', function (Blueprint $table) {
            //$table->integer('id_estado')->primary();
            $table->id('id_estado');
            $table->string('nombre', 80);
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documental.estados_documento');
    }
};
