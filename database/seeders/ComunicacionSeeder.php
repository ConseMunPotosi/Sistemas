<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComunicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorías de Noticias
        DB::table('comunicacion.categorias_noticia')->insert([
            ['id_categoria' => 1, 'nombre' => 'Institucionales', 'descripcion' => 'Noticias de la institución', 'estado' => true],
            ['id_categoria' => 2, 'nombre' => 'Eventos', 'descripcion' => 'Eventos y actividades', 'estado' => true],
            ['id_categoria' => 3, 'nombre' => 'Capacitaciones', 'descripcion' => 'Cursos y talleres', 'estado' => true],
            ['id_categoria' => 4, 'nombre' => 'Comunicados', 'descripcion' => 'Comunicados oficiales', 'estado' => true],
        ]);

        // Noticias
        DB::table('comunicacion.noticias')->insert([
            [
                'id_noticia' => 1,
                'titulo' => 'Nuevo sistema de gestión documental',
                'resumen' => 'Implementación del nuevo sistema LegiSys',
                'contenido' => 'Se ha implementado el sistema de gestión documental LegiSys...',
                'imagen_portada' => 'noticias/legisys.jpg',
                'id_categoria' => 1,
                'id_usuario_creador' => 1,
                'estado_publicacion' => 'Publicado',
                'fecha_creacion' => now(),
                'fecha_publicacion' => now(),
                'publicado_web' => true,
                'publicado_facebook' => true,
                'enlace_facebook' => 'https://facebook.com/post/123',
                'facebook_post_id' => '123456789',
                'estado' => true
            ],
            [
                'id_noticia' => 2,
                'titulo' => 'Curso de capacitación en Laravel',
                'resumen' => 'Curso intensivo para desarrolladores',
                'contenido' => 'Se realizará un curso de capacitación en Laravel...',
                'imagen_portada' => 'noticias/laravel.jpg',
                'id_categoria' => 3,
                'id_usuario_creador' => 2,
                'estado_publicacion' => 'Borrador',
                'fecha_creacion' => now(),
                'fecha_publicacion' => null,
                'publicado_web' => false,
                'publicado_facebook' => false,
                'enlace_facebook' => null,
                'facebook_post_id' => null,
                'estado' => true
            ],
        ]);

    }
}
