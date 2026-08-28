<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\TipoNorma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoNormaController extends Controller
{
    // Listar todos los tipos de norma
    public function index()
    {
        $tipos = TipoNorma::orderBy('nombre_tipo', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $tipos
        ]);
    }

    // Crear un nuevo tipo de norma
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_tipo' => 'required|string|max:100|unique:gaceta.tipos_norma,nombre_tipo',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tipo = TipoNorma::create([
            'nombre_tipo' => $request->nombre_tipo,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? true,
            'fecha_registro' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $tipo
        ], 201);
    }

    // Mostrar un tipo específico
    public function show($id)
    {
        $tipo = TipoNorma::find($id);
        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo no encontrado'], 404);
        }
        return response()->json(['success' => true, 'data' => $tipo]);
    }

    // Actualizar un tipo de norma
    public function update(Request $request, $id)
    {
        $tipo = TipoNorma::find($id);
        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_tipo' => 'sometimes|string|max:100|unique:gaceta.tipos_norma,nombre_tipo,' . $id . ',id_tipo_norma',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tipo->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $tipo
        ]);
    }

    // Eliminar un tipo de norma
    public function destroy($id)
    {
        $tipo = TipoNorma::find($id);
        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo no encontrado'], 404);
        }

        $tipo->delete();
        return response()->json(['success' => true, 'message' => 'Tipo eliminado']);
    }
}
