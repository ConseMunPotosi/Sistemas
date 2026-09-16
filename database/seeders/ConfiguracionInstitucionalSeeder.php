<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institucional\Configuracion;

class ConfiguracionInstitucionalSeeder extends Seeder
{
    public function run()
    {
        $configuraciones = [
            [
                'clave' => 'nombre_institucion',
                'valor' => 'Consejo Municipal de Potosí',
                'descripcion' => 'Nombre oficial de la institución',
            ],
            [
                'clave' => 'periodo_constitucional',
                'valor' => '2026-2027',
                'descripcion' => 'Periodo constitucional vigente',
            ],
            [
                'clave' => 'gestion_actual',
                'valor' => '2026',
                'descripcion' => 'Gestión institucional actual',
            ],
            [
                'clave' => 'direccion',
                'valor' => '',
                'descripcion' => 'Dirección institucional',
            ],
            [
                'clave' => 'telefono',
                'valor' => '',
                'descripcion' => 'Teléfono institucional',
            ],
            [
                'clave' => 'correo',
                'valor' => '',
                'descripcion' => 'Correo electrónico institucional',
            ],
        ];

        foreach ($configuraciones as $configuracion) {
            Configuracion::updateOrCreate(
                ['clave' => $configuracion['clave']],
                [
                    'valor' => $configuracion['valor'],
                    'descripcion' => $configuracion['descripcion'],
                    'estado' => true,
                    'fecha_registro' => now(),
                    'fecha_actualizacion' => now(),
                ]
            );
        }
    }
}
