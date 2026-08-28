<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposNorma extends Seeder
{
    public function run()
    {
        // Verificación: Si ya existe al menos un registro, no insertar nada (evita duplicados)
        if (DB::table('gaceta.tipos_norma')->count() > 0) {
            return;
        }

        DB::table('gaceta.tipos_norma')->insert([
            [
                'nombre_tipo' => 'Leyes',
                'descripcion' => 'Normas de mayor jerarquía dentro del municipio que tienen un alcance general, es decir, aplican a todos los ciudadanos e instituciones dentro de su jurisdicción.',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_tipo' => 'Ordenanzas',
                'descripcion' => 'Normas municipales emitidas por el Concejo Municipal que regulan aspectos específicos de la vida comunitaria y administrativa del municipio.',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_tipo' => 'Resoluciones',
                'descripcion' => 'Actos administrativos emitidos por autoridades competentes que resuelven asuntos específicos de carácter particular o general.',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_tipo' => 'Decretos Municipales',
                'descripcion' => 'Normas emitidas por el Órgano Ejecutivo Municipal para reglamentar y ejecutar leyes y ordenanzas municipales.',
                'estado' => true,
                'fecha_registro' => now(),
            ],
            [
                'nombre_tipo' => 'Decretos ediles',
                'descripcion' => 'Normas de carácter administrativo emitidas por la Alcaldía para el funcionamiento interno y la gestión del municipio.',
                'estado' => true,
                'fecha_registro' => now(),
            ],
        ]);
    }
}
