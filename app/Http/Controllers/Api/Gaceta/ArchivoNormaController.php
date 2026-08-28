<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\ArchivoNorma;
use App\Models\Gaceta\Norma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchivoNormaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_norma' => 'required|exists:gaceta.normas,id_norma',
            'archivo' => 'required|file|mimes:pdf|max:10240', // Máximo 10MB
        ]);

        // 🔥 1. OBTENER EL ARCHIVO PRIMERO (Para evitar error de variable indefinida)
        $archivo = $request->file('archivo');

        // 🔥 2. OBTENER LA NORMA PARA EXTRAER DATOS
        $norma = Norma::find($request->id_norma);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }

        // 🔥 3. OBTENER EL TIPO DE NORMA (desde la relación)
        $tipoNorma = $norma->tipo ? $norma->tipo->nombre_tipo : 'SinTipo';

        // Generar un nombre seguro sin espacios
        $tipoNormaLimpio = str_replace(' ', '_', $tipoNorma);

        // 🔥 4. GENERAR EL NOMBRE: id_norma_numero_norma_tipo_norma.pdf
        $extension = $archivo->getClientOriginalExtension();
        $nombreLimpio = $norma->id_norma . '_' . $norma->numero . '_' . $tipoNormaLimpio . '.' . $extension;

        // 🔥 5. GUARDAR EN public/archivosNorma
        $carpetaDestino = public_path('archivosNorma');
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $archivo->move($carpetaDestino, $nombreLimpio);

        $rutaArchivo = 'archivosNorma/' . $nombreLimpio;

        // 🔥 6. GUARDAR EN LA BASE DE DATOS
        $nuevoArchivo = ArchivoNorma::create([
            'id_norma' => $norma->id_norma,
            'nombre_archivo' => $nombreLimpio, // Guardamos el nombre renombrado
            'ruta_archivo' => $rutaArchivo,
            'extension' => $extension,
            'peso' => $archivo->getSize(),
            'estado' => true,
            'tipo_mime' => $archivo->getMimeType(),
            'subidor_por' => Auth::id(),
            'fecha_subida' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $nuevoArchivo
        ], 201);
    }

    public function destroy(int $id)
    {
        $archivo = ArchivoNorma::find($id);
        if (!$archivo) return response()->json(['success' => false], 404);

        // Eliminar archivo físico
        if (file_exists(public_path($archivo->ruta_archivo))) {
            unlink(public_path($archivo->ruta_archivo));
        }

        $archivo->delete();
        return response()->json(['success' => true]);
    }
}
