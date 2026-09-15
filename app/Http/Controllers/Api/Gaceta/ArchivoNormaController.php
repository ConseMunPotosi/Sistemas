<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\ArchivoNorma;
use App\Models\Gaceta\Norma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArchivoNormaController extends Controller
{
    /**
     * Subir archivo PDF asociado a una norma
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_norma' => 'required|integer',
            'archivo' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Obtener el archivo subido
        $archivo = $request->file('archivo');

        // Obtener la norma
        $norma = Norma::find($request->id_norma);

        if (!$norma) {
            return response()->json([
                'success' => false,
                'message' => 'Norma no encontrada'
            ], 404);
        }

        // Verificar que realmente llegó el archivo
        if (!$archivo || !$archivo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo PDF no es válido o no se recibió correctamente.'
            ], 422);
        }

        // Obtener estos datos ANTES de mover el archivo
        $peso = $archivo->getSize();
        $tipoMime = $archivo->getMimeType();
        $extension = strtolower($archivo->getClientOriginalExtension());

        // Obtener tipo de norma
        $tipoNorma = $norma->tipo
            ? $norma->tipo->nombre_tipo
            : 'SinTipo';

        // Limpiar espacios del nombre
        $tipoNormaLimpio = str_replace(' ', '_', trim($tipoNorma));

        // Generar nombre del archivo
        $nombreLimpio =
            $norma->id_norma . '_' .
            $norma->numero . '_' .
            $tipoNormaLimpio . '.' .
            $extension;

        // Carpeta de destino
        $carpetaDestino = public_path('archivosNorma');

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        // Ruta física final
        $rutaFisica = $carpetaDestino . DIRECTORY_SEPARATOR . $nombreLimpio;

        // Si ya existe un archivo con el mismo nombre,
        // lo eliminamos para permitir reemplazar el PDF.
        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }

        // Mover archivo a la carpeta definitiva
        $archivo->move($carpetaDestino, $nombreLimpio);

        // Ruta pública que se guardará en PostgreSQL
        $rutaArchivo = 'archivosNorma/' . $nombreLimpio;

        // Registrar en la base de datos
        $nuevoArchivo = ArchivoNorma::create([
            'id_norma' => $norma->id_norma,
            'nombre_archivo' => $nombreLimpio,
            'ruta_archivo' => $rutaArchivo,
            'extension' => $extension,
            'peso' => $peso,
            'estado' => true,
            'tipo_mime' => $tipoMime,
            'subidor_por' => Auth::id(),
            'fecha_subida' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Archivo PDF subido correctamente.',
            'data' => $nuevoArchivo
        ], 201);
    }

    /**
     * Eliminar archivo asociado a una norma
     */
    public function destroy(int $id)
    {
        $archivo = ArchivoNorma::find($id);

        if (!$archivo) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo no encontrado.'
            ], 404);
        }

        // Eliminar archivo físico
        $rutaFisica = public_path($archivo->ruta_archivo);

        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }

        // Eliminar registro
        $archivo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Archivo eliminado correctamente.'
        ]);
    }
}
