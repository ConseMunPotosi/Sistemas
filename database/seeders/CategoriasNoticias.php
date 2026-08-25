<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasNoticias extends Seeder
{
    public function run()
    {
        // Verifica si ya existen datos para evitar duplicados
        if (DB::table('comunicacion.categorias_noticia')->count() > 0) {
            return;
        }

        DB::table('comunicacion.categorias_noticia')->insert([
            [
                'nombre' => 'Noticias',
                'descripcion' => 'Noticias generales del Concejo Municipal de Potosí',
                'estado' => true,
            ],
            [
                'nombre' => 'Boletines de Prensa',
                'descripcion' => 'Boletines informativos emitidos por el Departamento de Relaciones Públicas',
                'estado' => true,
            ],
            [
                'nombre' => 'Comunicados',
                'descripcion' => 'Comunicados oficiales del Concejo Municipal de Potosí',
                'estado' => true,
            ],
            [
                'nombre' => 'Material AudioVisual',
                'descripcion' => 'Videos, Jingles, Spot y material multimedia del Concejo Municipal',
                'estado' => true,
            ],
            [
                'nombre' => 'Sesiones',
                'descripcion' => 'Información y resultados de las sesiones ordinarias y extraordinarias',
                'estado' => true,
            ],
        ]);
    }
}
