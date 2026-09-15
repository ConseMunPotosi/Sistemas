<?php

namespace App\Http\Controllers\Api\Comunicacion;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion\Sesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    /**
     * Listar todas las sesiones para administración.
     */
    public function index()
    {
        $sesiones = Sesion::orderByDesc('gestion')
            ->orderByDesc('numero')
            ->orderByDesc('fecha_sesion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sesiones,
        ]);
    }

    /**
     * Listado público de sesiones.
     * Solo muestra sesiones activas.
     */
    public function publicIndex()
    {
        $sesiones = Sesion::where('estado', true)
            ->orderByDesc('gestion')
            ->orderByDesc('numero')
            ->orderByDesc('fecha_sesion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sesiones,
        ]);
    }

    /**
     * Crear una nueva sesión y subir su video.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer',
            'gestion' => 'required|integer',
            'tipo_sesion' => 'required|string|max:30',
            'titulo' => 'required|string|max:255',
            'fecha_sesion' => 'required|date',
            'video' => 'required|file|mimes:mp4,mov,avi,webm,mkv|max:5120000',
            'estado' => 'sometimes|boolean',
        ]);

        $video = $request->file('video');

        if (!$video || !$video->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'El video no es válido o no se recibió correctamente.',
            ], 422);
        }

        // Carpeta física donde se almacenarán los videos
        $carpetaDestino = public_path('videos/sesiones');

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        // Generar nombre seguro
        $extension = strtolower($video->getClientOriginalExtension());

        $tipo = strtolower(trim($request->tipo_sesion));
        $tipo = preg_replace('/[^a-z0-9]+/i', '_', $tipo);
        $tipo = trim($tipo, '_');

        $nombreVideo =
            $request->gestion . '_' .
            str_pad($request->numero, 3, '0', STR_PAD_LEFT) . '_' .
            $tipo . '.' .
            $extension;

        // Guardar archivo
        $video->move($carpetaDestino, $nombreVideo);

        // Ruta que se almacenará en PostgreSQL
        $rutaVideo = 'videos/sesiones/' . $nombreVideo;

        $sesion = Sesion::create([
            'numero' => $request->numero,
            'gestion' => $request->gestion,
            'tipo_sesion' => $request->tipo_sesion,
            'titulo' => $request->titulo,
            'fecha_sesion' => $request->fecha_sesion,
            'video' => $rutaVideo,
            'estado' => $request->has('estado')
                ? $request->boolean('estado')
                : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesión creada y video subido correctamente.',
            'data' => $sesion,
        ], 201);
    }

    /**
     * Mostrar una sesión específica.
     */
    public function show($id)
    {
        $sesion = Sesion::find($id);

        if (!$sesion) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sesion,
        ]);
    }

    /**
     * Actualizar una sesión.
     * Si se envía un nuevo video, reemplaza el anterior.
     */
    public function update(Request $request, $id)
    {
        $sesion = Sesion::find($id);

        if (!$sesion) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada.',
            ], 404);
        }

        $request->validate([
            'numero' => 'sometimes|integer',
            'gestion' => 'sometimes|integer',
            'tipo_sesion' => 'sometimes|string|max:30',
            'titulo' => 'sometimes|string|max:255',
            'fecha_sesion' => 'sometimes|date',
            'video' => 'sometimes|nullable|file|mimes:mp4,mov,avi,webm,mkv|max:5120000',
            'estado' => 'sometimes|boolean',
        ]);

        $datos = [
            'numero' => $request->numero,
            'gestion' => $request->gestion,
            'tipo_sesion' => $request->tipo_sesion,
            'titulo' => $request->titulo,
            'fecha_sesion' => $request->fecha_sesion,
            'estado' => $request->has('estado')
                ? $request->boolean('estado')
                : $sesion->estado,
        ];

        // Eliminar valores null para no sobrescribir accidentalmente campos
        $datos = array_filter($datos, function ($valor) {
            return $valor !== null;
        });

        // Si se envió un nuevo video
        if ($request->hasFile('video')) {
            $video = $request->file('video');

            if (!$video || !$video->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El nuevo video no es válido.',
                ], 422);
            }

            $carpetaDestino = public_path('videos/sesiones');

            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $extension = strtolower($video->getClientOriginalExtension());

            $tipo = strtolower(
                trim($request->tipo_sesion ?? $sesion->tipo_sesion)
            );

            $tipo = preg_replace('/[^a-z0-9]+/i', '_', $tipo);
            $tipo = trim($tipo, '_');

            $gestion = $request->gestion ?? $sesion->gestion;
            $numero = $request->numero ?? $sesion->numero;

            $nombreVideo =
                $gestion . '_' .
                str_pad($numero, 3, '0', STR_PAD_LEFT) . '_' .
                $tipo . '.' .
                $extension;

            $rutaFisicaNueva =
                $carpetaDestino . DIRECTORY_SEPARATOR . $nombreVideo;

            // Eliminar archivo anterior
            if ($sesion->video) {
                $rutaFisicaAnterior = public_path($sesion->video);

                if (file_exists($rutaFisicaAnterior)) {
                    unlink($rutaFisicaAnterior);
                }
            }

            // Si ya existe el nuevo nombre, reemplazarlo
            if (file_exists($rutaFisicaNueva)) {
                unlink($rutaFisicaNueva);
            }

            $video->move($carpetaDestino, $nombreVideo);

            $datos['video'] = 'videos/sesiones/' . $nombreVideo;
        }

        $sesion->update($datos);

        return response()->json([
            'success' => true,
            'message' => 'Sesión actualizada correctamente.',
            'data' => $sesion,
        ]);
    }

    /**
     * Eliminar una sesión y su video físico.
     */
    public function destroy($id)
    {
        $sesion = Sesion::find($id);

        if (!$sesion) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada.',
            ], 404);
        }

        // Eliminar video físico
        if ($sesion->video) {
            $rutaFisica = public_path($sesion->video);

            if (file_exists($rutaFisica)) {
                unlink($rutaFisica);
            }
        }

        $sesion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión eliminada correctamente.',
        ]);
    }
}
