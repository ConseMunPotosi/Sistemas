<?php

namespace App\Http\Controllers\Api\Institucional;

use App\Http\Controllers\Controller;
use App\Models\Institucional\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfiguracionController extends Controller
{
    /**
     * Obtener todas las configuraciones institucionales activas.
     */
    public function index()
    {
        $configuraciones = Configuracion::where('estado', true)
            ->orderBy('id_configuracion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $configuraciones
        ]);
    }

    /**
     * Obtener una configuración específica por su clave.
     */
    public function show($clave)
    {
        $configuracion = Configuracion::where('clave', $clave)
            ->where('estado', true)
            ->first();

        if (!$configuracion) {
            return response()->json([
                'success' => false,
                'message' => 'Configuración no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $configuracion
        ]);
    }

    /**
     * Actualizar el valor de una configuración.
     */
    public function update(Request $request, $clave)
    {
        $configuracion = Configuracion::where('clave', $clave)->first();

        if (!$configuracion) {
            return response()->json([
                'success' => false,
                'message' => 'Configuración no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'valor' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $configuracion->update([
            'valor' => $request->input('valor'),
            'fecha_actualizacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $configuracion->fresh()
        ]);
    }
}
