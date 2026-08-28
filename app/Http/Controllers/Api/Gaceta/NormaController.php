<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\Norma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NormaController extends Controller
{
    // Listar todas las normas
    public function index()
    {
        $normas = Norma::with(['tipo', 'estado'])->orderBy('id_norma', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $normas
        ]);
    }

    // Crear una nueva norma
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipo_norma' => 'required|exists:gaceta.tipos_norma,id_tipo_norma',
            'id_estado_norma' => 'required|exists:gaceta.estados_norma,id_estado_norma',
            'numero' => 'required|integer',
            'gestion' => 'required|integer',
            'titulo' => 'required|string|max:500',
            'desripcion' => 'nullable|string',
            'fecha_publicacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $norma = Norma::create([
            'id_tipo_norma' => $request->id_tipo_norma,
            'id_estado_norma' => $request->id_estado_norma,
            'tipo_norma' => $request->tipo_norma,
            'numero' => $request->numero,
            'gestion' => $request->gestion,
            'titulo' => $request->titulo,
            'desripcion' => $request->desripcion,
            'fecha_publicacion' => $request->fecha_publicacion,
            'estado_norma' => $request->estado_norma,
            'fecha_registro' => now(),
            'id_usuario_creacion' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $norma->load(['tipo', 'estado'])
        ], 201);
    }

    // Mostrar una norma específica
    public function show($id)
    {
        $norma = Norma::with(['tipo', 'estado'])->find($id);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }
        return response()->json(['success' => true, 'data' => $norma]);
    }

    // Actualizar una norma
    public function update(Request $request, $id)
    {
        $norma = Norma::find($id);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_tipo_norma' => 'sometimes|exists:gaceta.tipos_norma,id_tipo_norma',
            'id_estado_norma' => 'sometimes|exists:gaceta.estados_norma,id_estado_norma',
            'numero' => 'sometimes|integer',
            'gestion' => 'sometimes|integer',
            'titulo' => 'sometimes|string|max:500',
            'desripcion' => 'nullable|string',
            'fecha_publicacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $norma->update($request->all());
        $norma->id_usuario_actualizacion = Auth::id();
        $norma->save();

        return response()->json([
            'success' => true,
            'data' => $norma->load(['tipo', 'estado'])
        ]);
    }

    // Eliminar una norma
    public function destroy($id)
    {
        $norma = Norma::find($id);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }

        $norma->delete();
        return response()->json(['success' => true, 'message' => 'Norma eliminada']);
    }
}
