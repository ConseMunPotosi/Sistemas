<?php

namespace App\Http\Controllers\Api\Comunicacion;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion\ArchivoNoticia;
use App\Models\Comunicacion\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoticiaController extends Controller
{
    // ==========================================================
    // LISTAR NOTICIAS
    // ==========================================================
    public function index()
    {
        $noticias = Noticia::with([
            'categoria',
            'archivos'
        ])
        ->orderBy('fecha_creacion', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $noticias
        ]);
    }


    // ==========================================================
    // CREAR NOTICIA
    // ==========================================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',

            'id_categoria' =>
                'required|exists:categorias_noticia,id_categoria',

            'contenido' => 'required|string',

            'resumen' =>
                'nullable|string',

            'estado_publicacion' =>
                'nullable|string|max:30',

            'fecha_publicacion' =>
                'nullable|date',

            'archivos.*' =>
                'nullable|file|max:10240',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }


        // ======================================================
        // 1. CREAR NOTICIA
        // ======================================================

        $noticia = Noticia::create([

            'titulo' =>
                $request->titulo,

            'resumen' =>
                $request->resumen,

            'contenido' =>
                $request->contenido,

            'imagen_portada' =>
                $request->imagen_portada,

            'id_categoria' =>
                $request->id_categoria,

            'id_usuario_creador' =>
                Auth::id(),

            'estado_publicacion' =>
                $request->estado_publicacion,

            'fecha_creacion' =>
                now(),

            'fecha_publicacion' =>
                $request->fecha_publicacion ?: null,

            'publicado_web' =>
                $request->has('publicado_web')
                    ? filter_var(
                        $request->publicado_web,
                        FILTER_VALIDATE_BOOLEAN
                    )
                    : true,

            'publicado_facebook' =>
                $request->has('publicado_facebook')
                    ? filter_var(
                        $request->publicado_facebook,
                        FILTER_VALIDATE_BOOLEAN
                    )
                    : false,

            'enlace_facebook' =>
                $request->enlace_facebook,

            'facebook_post_id' =>
                $request->facebook_post_id,

            'estado' =>
                $request->has('estado')
                    ? filter_var(
                        $request->estado,
                        FILTER_VALIDATE_BOOLEAN
                    )
                    : true,
        ]);


        // Obtener ID real
        $noticia->refresh();


        // ======================================================
        // 2. GUARDAR ARCHIVOS
        // ======================================================

        if ($request->hasFile('archivos')) {

            $archivosSubidos =
                $request->file('archivos');


            if (!is_array($archivosSubidos)) {

                $archivosSubidos =
                    [$archivosSubidos];

            }


            $carpetaDestino =
                public_path('archivos_noticia');


            if (!file_exists($carpetaDestino)) {

                mkdir(
                    $carpetaDestino,
                    0777,
                    true
                );

            }


            $fechaActual =
                now()->format('Y-m-d-His');


            foreach ($archivosSubidos as $archivo) {

                if (!$archivo || !$archivo->isValid()) {
                    continue;
                }


                // ==================================================
                // OBTENER DATOS ANTES DE COPIAR EL TEMPORAL
                // ==================================================

                $nombreOriginal =
                    $archivo->getClientOriginalName();

                $extension =
                    strtolower(
                        $archivo->getClientOriginalExtension()
                    );

                $tipoMime =
                    $archivo->getMimeType();

                $pesoBytes =
                    $archivo->getSize();


                // ==================================================
                // GENERAR NOMBRE DEL ARCHIVO
                // ==================================================

                $nombreBase =
                    str_replace(
                        ' ',
                        '_',
                        pathinfo(
                            $nombreOriginal,
                            PATHINFO_FILENAME
                        )
                    );


                $nombreLimpio =
                    $noticia->id_noticia .
                    '-' .
                    $fechaActual .
                    '-' .
                    $nombreBase .
                    '.' .
                    $extension;


                $rutaFisica =
                    $carpetaDestino .
                    DIRECTORY_SEPARATOR .
                    $nombreLimpio;


                // ==================================================
                // COPIAR ARCHIVO
                // Compatible con Windows
                // ==================================================

                try {

                    $contenidoArchivo =
                        file_get_contents(
                            $archivo->getRealPath()
                        );


                    if ($contenidoArchivo === false) {

                        return response()->json([
                            'success' => false,
                            'message' =>
                                'No fue posible leer el archivo temporal.'
                        ], 500);

                    }


                    $guardado =
                        file_put_contents(
                            $rutaFisica,
                            $contenidoArchivo
                        );


                    if ($guardado === false) {

                        return response()->json([
                            'success' => false,
                            'message' =>
                                'No fue posible guardar el archivo físico.'
                        ], 500);

                    }

                } catch (\Throwable $e) {

                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Error al guardar el archivo físico: ' .
                            $e->getMessage()
                    ], 500);

                }


                // ==================================================
                // RUTA RELATIVA
                // ==================================================

                $rutaArchivo =
                    'archivos_noticia/' .
                    $nombreLimpio;


                // ==================================================
                // REGISTRO EN BD
                // ==================================================

                ArchivoNoticia::create([

                    'id_noticia' =>
                        $noticia->id_noticia,

                    'nombre_archivo' =>
                        $nombreOriginal,

                    'ruta_archivo' =>
                        $rutaArchivo,

                    'tipo_mime' =>
                        $tipoMime,

                    'extension' =>
                        $extension,

                    'peso_bytes' =>
                        $pesoBytes,

                    'subido_por' =>
                        Auth::id(),

                    'fecha_subida' =>
                        now(),
                ]);

            }

        }


        // ======================================================
        // 3. RESPUESTA
        // ======================================================

        return response()->json([

            'success' => true,

            'data' =>
                $noticia->load([
                    'categoria',
                    'archivos'
                ])

        ], 201);
    }


    // ==========================================================
    // MOSTRAR UNA NOTICIA
    // ==========================================================
    public function show($id)
    {
        $noticia =
            Noticia::with([
                'categoria',
                'archivos'
            ])->find($id);


        if (!$noticia) {

            return response()->json([
                'success' => false,
                'message' => 'Noticia no encontrada'
            ], 404);

        }


        return response()->json([
            'success' => true,
            'data' => $noticia
        ]);
    }


    // ==========================================================
    // ACTUALIZAR NOTICIA
    // ==========================================================
    public function update(
        Request $request,
        $id
    ) {

        $noticia =
            Noticia::find($id);


        if (!$noticia) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Noticia no encontrada'
            ], 404);

        }


        $validator =
            Validator::make(
                $request->all(),
                [
                    'titulo' =>
                        'sometimes|string|max:255',

                    'id_categoria' =>
                        'sometimes|exists:categorias_noticia,id_categoria',

                    'contenido' =>
                        'sometimes|string',

                    'resumen' =>
                        'nullable|string',

                    'estado_publicacion' =>
                        'sometimes|string|max:30',

                    'fecha_publicacion' =>
                        'nullable|date',
                ]
            );


        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }


        // ======================================================
        // PREPARAR DATOS
        // ======================================================

        $datosParaActualizar =
            $request->except([
                'fecha_publicacion',
                '_method'
            ]);


        if ($request->has('fecha_publicacion')) {

            $datosParaActualizar[
                'fecha_publicacion'
            ] =
                $request->fecha_publicacion;

        }


        // ======================================================
        // ACTUALIZAR
        // ======================================================

        $noticia->update(
            $datosParaActualizar
        );


        // ======================================================
        // AGREGAR NUEVOS ARCHIVOS
        // ======================================================

        if ($request->hasFile('archivos')) {

            $archivosSubidos =
                $request->file('archivos');


            if (!is_array($archivosSubidos)) {

                $archivosSubidos =
                    [$archivosSubidos];

            }


            $carpetaDestino =
                public_path('archivos_noticia');


            if (!file_exists($carpetaDestino)) {

                mkdir(
                    $carpetaDestino,
                    0777,
                    true
                );

            }


            $fechaActual =
                now()->format('Y-m-d-His');


            foreach ($archivosSubidos as $archivo) {

                if (!$archivo || !$archivo->isValid()) {
                    continue;
                }


                // Datos antes de copiar
                $nombreOriginal =
                    $archivo->getClientOriginalName();

                $extension =
                    strtolower(
                        $archivo->getClientOriginalExtension()
                    );

                $tipoMime =
                    $archivo->getMimeType();

                $pesoBytes =
                    $archivo->getSize();


                $nombreBase =
                    str_replace(
                        ' ',
                        '_',
                        pathinfo(
                            $nombreOriginal,
                            PATHINFO_FILENAME
                        )
                    );


                $nombreLimpio =
                    $noticia->id_noticia .
                    '-' .
                    $fechaActual .
                    '-' .
                    $nombreBase .
                    '.' .
                    $extension;


                $rutaFisica =
                    $carpetaDestino .
                    DIRECTORY_SEPARATOR .
                    $nombreLimpio;


                try {

                    $contenidoArchivo =
                        file_get_contents(
                            $archivo->getRealPath()
                        );


                    if ($contenidoArchivo === false) {

                        continue;

                    }


                    file_put_contents(
                        $rutaFisica,
                        $contenidoArchivo
                    );

                } catch (\Throwable $e) {

                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Error al guardar el archivo: ' .
                            $e->getMessage()
                    ], 500);

                }


                ArchivoNoticia::create([

                    'id_noticia' =>
                        $noticia->id_noticia,

                    'nombre_archivo' =>
                        $nombreOriginal,

                    'ruta_archivo' =>
                        'archivos_noticia/' .
                        $nombreLimpio,

                    'tipo_mime' =>
                        $tipoMime,

                    'extension' =>
                        $extension,

                    'peso_bytes' =>
                        $pesoBytes,

                    'subido_por' =>
                        Auth::id(),

                    'fecha_subida' =>
                        now(),

                ]);

            }

        }


        return response()->json([

            'success' => true,

            'data' =>
                $noticia->load([
                    'categoria',
                    'archivos'
                ])

        ]);
    }


    // ==========================================================
    // ELIMINAR NOTICIA
    // ==========================================================
    public function destroy($id)
    {
        $noticia =
            Noticia::find($id);


        if (!$noticia) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Noticia no encontrada'
            ], 404);

        }


        // Eliminar archivos físicos
        $archivos =
            ArchivoNoticia::where(
                'id_noticia',
                $noticia->id_noticia
            )->get();


        foreach ($archivos as $archivo) {

            $ruta =
                public_path(
                    $archivo->ruta_archivo
                );


            if (
                file_exists($ruta)
            ) {

                @unlink($ruta);

            }

        }


        // Eliminar registros de archivos
        ArchivoNoticia::where(
            'id_noticia',
            $noticia->id_noticia
        )->delete();


        // Eliminar noticia
        $noticia->delete();


        return response()->json([
            'success' => true,
            'message' =>
                'Noticia eliminada correctamente'
        ]);
    }
}
