<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosNorma extends Seeder
{
    public function run()
    {
        // Verifica si ya existen datos para evitar duplicados
        if (DB::table('gaceta.estados_norma')->count() > 0) {
            return;
        }

        DB::table('gaceta.estados_norma')->insert([
            [
                'nombre_estado' => 'Aprobada',
                'descripcion' => 'Norma aprobada por el Pleno del Concejo Municipal y en plena aplicación',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_estado' => 'Derogada',
                'descripcion' => 'Norma que ya no está vigente, fue derogada',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_estado' => 'Modificada',
                'descripcion' => 'Norma que fue modificada parcialmente en su contenido',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_estado' => 'Adicionado',
                'descripcion' => 'Norma a la que se le añadieron nuevos artículos o disposiciones',
                'estado' => true,
                'fecha_registro' => now(),
            ],
        ]);
    }
}
