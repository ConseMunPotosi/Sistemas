<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institucional\Concejal;

class ConcejalSeeder extends Seeder
{
    public function run(): void
    {
        $concejales = [

            [
                'nombre' => 'Abog. German Antonio Vidaurre Villanueva',
                'cargo' => 'Concejal - Presidente',
                'comision' => null,
                'distritos' => 'No tiene asignado',
                'descripcion' => 'Presidente del Concejo Municipal de Potosi.',
                'imagen' => '/images/concejales/Antonio_Vidaurre.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Claudio German Clemente Vedia',
                'cargo' => 'Concejal - Vicepresidente',
                'comision' => null,
                'distritos' => 'No tiene asignado',
                'descripcion' => 'Vicepresidente del Concejo Municipal de Potosi.',
                'imagen' => '/images/concejales/Claudio_Clemente.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Maria del Carmen Michel Araujo',
                'cargo' => 'Concejal - Secretaria',
                'comision' => null,
                'distritos' => 'No tiene asignado',
                'descripcion' => 'Secretaria del Concejo Municipal de Potosi.',
                'imagen' => '/images/concejales/Carmen_Michel.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Ing. Manuel Alejandro Calizaya Limachi',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '9 - 12',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Manuel_Calizaya.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Ariel Jimenez Gomez',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '1 - 17 - 19',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Ariel_Jimenez.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Ing. Clementina Aroni Mamani',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '5 - 16',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Clementina_Aroni.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Ing. Guido Armando Cruz Mora',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '8 - 20 - 21',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Guido_Cruz.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Jacqueline Lourdes Gutierrez Carrasco',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '6 - 7 - 11',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Jacqueline_Gutierrez.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Christie Monica Chacon Duran',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '3 - 10',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Monica_Chacon.png',
                'estado' => true,
            ],

            [
                'nombre' => 'Eddy Fernandez Fuertes',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => '13 - 14 - 18',
                'descripcion' => 'Concejal del Gobierno Autonomo Municipal de Potosi.',
                'imagen' => '/images/concejales/Eddy_Fernandez.png',
                'estado' => true,
            ],

            [
                'nombre' => 'A designar',
                'cargo' => 'Concejal',
                'comision' => null,
                'distritos' => 'No asignado',
                'descripcion' => 'Espacio pendiente de designacion.',
                'imagen' => '/images/concejales/A_designar.png',
                'estado' => true,
            ],

        ];

        foreach ($concejales as $concejal) {
            Concejal::create([
                ...$concejal,
                'fecha_registro' => now(),
            ]);
        }
    }
}
