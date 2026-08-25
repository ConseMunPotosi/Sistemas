<?php

namespace App\Http\Controllers\Api\comunicacion;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion\ArchivoNoticia;
use App\Models\Comunicacion\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    // Listar todas las noticias
    public function index()
    {
        $noticias = Noticia::with(['categoria', 'archivos'])
                            ->orderBy('fecha_creacion', 'desc')
                            ->get();

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
            'id_categoria' => 'required|exists:categorias_noticia,id_categoria',
            'contenido' => 'required|string',
            'estado_publicacion' => 'nullable|string|max:30',
            'fecha_publicacion' => 'nullable|date',
            'archivos.*' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 1. CREAR LA NOTICIA
        $noticia = Noticia::create([
            'titulo' => $request->titulo,
            'resumen' => $request->resumen,
            'contenido' => $request->contenido,
            'imagen_portada' => $request->imagen_portada,
            'id_categoria' => $request->id_categoria,
            'id_usuario_creador' => Auth::id(),
            'estado_publicacion' => $request->estado_publicacion,
            'fecha_creacion' => now(),
            'fecha_publicacion' => $request->fecha_publicacion ?: null,
            'publicado_web' => $request->publicado_web ?? true,
            'publicado_facebook' => $request->publicado_facebook ?? false,
            'enlace_facebook' => $request->enlace_facebook,
            'facebook_post_id' => $request->facebook_post_id,
            'estado' => $request->estado ?? true
        ]);

        // 🔥 PASO CRUCIAL: Recargamos la noticia desde la BD para obtener el ID real
        $noticia->refresh();

        // 2. PROCESAR Y GUARDAR TODOS LOS ARCHIVOS (CORREGIDO PARA EVITAR ERROR DE TEMP EN WINDOWS)
        if ($request->hasFile('archivos')) {

            $archivosSubidos = $request->file('archivos');
            if (!is_array($archivosSubidos)) {
                $archivosSubidos = [$archivosSubidos];
            }

            // Asegurarnos de que la carpeta exista en el proyecto
            $carpetaDestino = public_path('archivos_noticia');
            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            // Obtener la fecha actual formateada (ej: 2026-08-20-153045)
            $fechaActual = now()->format('Y-m-d-His');

            foreach ($archivosSubidos as $archivo) {
                if ($archivo && $archivo->isValid()) {

                    $nombreOriginal = $archivo->getClientOriginalName();
                    $extension = $archivo->getClientOriginalExtension();

                    // 🔥 GENERAR EL NOMBRE CON EL PATRÓN: id_noticia-fechaActual-nombreOriginal
                    $nombreBase = str_replace(' ', '_', pathinfo($nombreOriginal, PATHINFO_FILENAME));
                    $nombreLimpio = $noticia->id_noticia . '-' . $fechaActual . '-' . $nombreBase . '.' . $extension;

                    // 🔥 CAMBIO CRUCIAL: En lugar de mover, usamos file_get_contents y file_put_contents
                    // Esto evita el error de "archivo temporal no encontrado" en Windows.
                    try {
                        // Leemos el contenido del archivo temporal subido
                        $contenidoArchivo = file_get_contents($archivo->getRealPath());
                        // Lo escribimos directamente en la carpeta destino
                        file_put_contents($carpetaDestino . DIRECTORY_SEPARATOR . $nombreLimpio, $contenidoArchivo);
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al guardar el archivo físico: ' . $e->getMessage()
                        ], 500);
                    }

                    // Guardar la ruta relativa
                    $rutaArchivo = 'archivos_noticia/' . $nombreLimpio;

                    // GUARDAR EL REGISTRO EN LA BASE DE DATOS
                    ArchivoNoticia::create([
                        'id_noticia'    => $noticia->id_noticia,
                        'nombre_archivo'=> $nombreOriginal,
                        'ruta_archivo'  => $rutaArchivo,
                        'tipo_mime'     => $archivo->getMimeType(),
                        'extension'     => $extension,
                        'peso_bytes'    => $archivo->getSize(),
                        'subido_por'    => Auth::id(),
                        'fecha_subida'  => now(),
                    ]);
                }
            }
        }

        // 3. DEVOLVER RESPUESTA DE ÉXITO AL FRONTEND
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

    // Actualizar una noticia (¡CORREGIDO!)
    public function update(Request $request, $id)
    {
        $noticia = Noticia::find($id);
        if (!$noticia) {
            return response()->json(['success' => false, 'message' => 'Noticia no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|string|max:255',
            'id_categoria' => 'sometimes|exists:categorias_noticia,id_categoria',
            'estado_publicacion' => 'sometimes|string|max:30',
            'fecha_publicacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $datosParaActualizar = $request->except(['fecha_publicacion']);

        if ($request->has('fecha_publicacion')) {
            $datosParaActualizar['fecha_publicacion'] = $request->fecha_publicacion;
        } else {
            $datosParaActualizar['fecha_publicacion'] = null;
        }

        $noticia->update($datosParaActualizar);

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
