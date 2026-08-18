<?php

namespace App\Http\Controllers\Api\comunicacion;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticiaController extends Controller
{
    // Listar todas las noticias
    public function index()
    {
        // 🟢 Cargamos también el nombre de la categoría para mostrarlo en el Frontend
        $noticias = Noticia::with('categoria')->orderBy('fecha_creacion', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $noticias
        ]);
    }

    // Crear una nueva noticia
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias_noticias,id_categoria',
            'contenido' => 'required|string',
            'estado_publicacion' => 'required|in:borrador,programado,publicado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 🟢 Asignamos valores por defecto si no vienen
        $noticia = Noticia::create([
            'titulo' => $request->titulo,
            'resumen' => $request->resumen,
            'contenido' => $request->contenido,
            'imagen_portada' => $request->imagen_portada,
            'id_categoria' => $request->id_categoria,
            'id_usuario_creador' => auth()->id,
            'estado_publicacion' => $request->estado_publicacion,
            'fecha_creacion' => now(),
            'fecha_publicacion' => $request->fecha_publicacion,
            'publicado_web' => $request->publicado_web ?? true,
            'publicado_facebook' => $request->publicado_facebook ?? false,
            'enlace_facebook' => $request->enlace_facebook,
            'facebook_post_id' => $request->facebook_post_id,
            'estado' => $request->estado ?? true
        ]);

        return response()->json([
            'success' => true,
            'data' => $noticia->load('categoria')
        ], 201);
    }

    // Mostrar una noticia específica
    public function show($id)
    {
        $noticia = Noticia::with('categoria')->find($id);
        if (!$noticia) {
            return response()->json(['success' => false, 'message' => 'Noticia no encontrada'], 404);
        }
        return response()->json(['success' => true, 'data' => $noticia]);
    }

    // Actualizar una noticia
    public function update(Request $request, $id)
    {
        $noticia = Noticia::find($id);
        if (!$noticia) {
            return response()->json(['success' => false, 'message' => 'Noticia no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|string|max:255',
            'id_categoria' => 'sometimes|exists:categorias_noticias,id_categoria',
            'estado_publicacion' => 'sometimes|in:borrador,programado,publicado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $noticia->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $noticia->load('categoria')
        ]);
    }

    // Eliminar una noticia
    public function destroy($id)
    {
        $noticia = Noticia::find($id);
        if (!$noticia) {
            return response()->json(['success' => false, 'message' => 'Noticia no encontrada'], 404);
        }

        $noticia->delete();
        return response()->json(['success' => true, 'message' => 'Noticia eliminada']);
    }
}
