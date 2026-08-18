<?php

namespace App\Http\Controllers\Api\comunicacion;

use App\Http\Controllers\Controller;
use App\Models\Comunicacion\CategoriaNoticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaNoticiaController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => CategoriaNoticia::all()
        ]);
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $categoria = CategoriaNoticia::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true, // Por defecto activo
        ]);

        return response()->json([
            'success' => true,
            'data' => $categoria
        ], 201);
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        $categoria = CategoriaNoticia::find($id);
        if (!$categoria) {
            return response()->json(['success' => false, 'message' => 'Categoría no encontrada'], 404);
        }
        return response()->json(['success' => true, 'data' => $categoria]);
    }

    // Actualizar una categoría
    public function update(Request $request, $id)
    {
        $categoria = CategoriaNoticia::find($id);
        if (!$categoria) {
            return response()->json(['success' => false, 'message' => 'Categoría no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string|max:100',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $categoria->update($request->all());
        return response()->json(['success' => true, 'data' => $categoria]);
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $categoria = CategoriaNoticia::find($id);
        if (!$categoria) {
            return response()->json(['success' => false, 'message' => 'Categoría no encontrada'], 404);
        }

        $categoria->delete();
        return response()->json(['success' => true, 'message' => 'Categoría eliminada']);
    }
}
