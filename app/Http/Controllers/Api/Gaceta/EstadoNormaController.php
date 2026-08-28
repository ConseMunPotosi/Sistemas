<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\EstadoNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadoNormaController extends Controller
{
    // Listar todos los estados de norma
    public function index()
    {
        $estados = EstadoNorma::orderBy('nombre_estado', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $estados
        ]);
    }

    // Crear un nuevo estado de norma
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_estado' => 'required|string|max:50|unique:gaceta.estados_norma,nombre_estado',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estado = EstadoNorma::create([
            'nombre_estado' => $request->nombre_estado,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? true,
            'fecha_registro' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $estado
        ], 201);
    }

    // Mostrar un estado específico
    public function show($id)
    {
        $estado = EstadoNorma::find($id);
        if (!$estado) {
            return response()->json(['success' => false, 'message' => 'Estado no encontrado'], 404);
        }
        return response()->json(['success' => true, 'data' => $estado]);
    }

    // Actualizar un estado de norma
    public function update(Request $request, $id)
    {
        $estado = EstadoNorma::find($id);
        if (!$estado) {
            return response()->json(['success' => false, 'message' => 'Estado no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_estado' => 'sometimes|string|max:50|unique:gaceta.estados_norma,nombre_estado,' . $id . ',id_estado_norma',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estado->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }

    // Eliminar un estado de norma
    public function destroy($id)
    {
        $estado = EstadoNorma::find($id);
        if (!$estado) {
            return response()->json(['success' => false, 'message' => 'Estado no encontrado'], 404);
        }

        $estado->delete();
        return response()->json(['success' => true, 'message' => 'Estado eliminado']);
    }
}
