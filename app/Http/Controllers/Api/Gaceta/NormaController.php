<?php

namespace App\Http\Controllers\Api\Gaceta;

use App\Http\Controllers\Controller;
use App\Models\Gaceta\Norma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NormaController extends Controller
{
    public function index()
    {
        $normas = Norma::with(['tipo', 'estado', 'archivos'])
          ->orderBy('id_norma', 'desc')
          ->get();

        return response()->json(['success' => true, 'data' => $normas]);
    }

    public function publicIndex(Request $request)
    {
        $query = Norma::query()
            ->with([
                'tipo:id_tipo_norma,nombre_tipo,descripcion,estado',
                'estado:id_estado_norma,nombre_estado,descripcion,estado',
                'archivos' => function ($q) {
                    $q->where('estado', true)->orderBy('id_archivo');
                },
            ])
            ->whereHas('tipo', function ($q) use ($request) {
                $q->where('estado', true);
                if ($request->filled('tipo')) {
                    $q->whereRaw('LOWER(nombre_tipo) = LOWER(?)', [$request->string('tipo')->toString()]);
                }
            })
            ->orderByDesc('gestion')
            ->orderByDesc('numero')
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id_norma');

        if ($request->filled('buscar')) {
            $buscar = trim($request->string('buscar')->toString());
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'ILIKE', "%{$buscar}%")
                    ->orWhere('desripcion', 'ILIKE', "%{$buscar}%")
                    ->orWhereRaw('CAST(numero AS TEXT) ILIKE ?', ["%{$buscar}%"])
                    ->orWhereRaw('CAST(gestion AS TEXT) ILIKE ?', ["%{$buscar}%"]);
            });
        }

        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipo_norma' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    if (!DB::table('gaceta.tipos_norma')->where('id_tipo_norma', $value)->exists()) {
                        $fail('El tipo de norma seleccionado no existe.');
                    }
                },
            ],
            'id_estado_norma' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    if (!DB::table('gaceta.estados_norma')->where('id_estado_norma', $value)->exists()) {
                        $fail('El estado de norma seleccionado no existe.');
                    }
                },
            ],
            'numero' => 'required|integer',
            'gestion' => 'required|integer',
            'titulo' => 'required|string|max:500',
            'desripcion' => 'nullable|string',
            'fecha_publicacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $norma = Norma::create([
            'id_tipo_norma' => $request->id_tipo_norma,
            'id_estado_norma' => $request->id_estado_norma,
            'numero' => $request->numero,
            'gestion' => $request->gestion,
            'titulo' => $request->titulo,
            'desripcion' => $request->desripcion,
            'fecha_publicacion' => $request->fecha_publicacion,
            'fecha_registro' => now(),
            'id_usuario_creacion' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $norma->load(['tipo', 'estado'])
        ], 201);
    }

    public function show($id)
    {
        $norma = Norma::with(['tipo', 'estado'])->find($id);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }
        return response()->json(['success' => true, 'data' => $norma]);
    }

    public function update(Request $request, $id)
    {
        $norma = Norma::find($id);
        if (!$norma) {
            return response()->json(['success' => false, 'message' => 'Norma no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_tipo_norma' => [
                'sometimes', 'integer',
                function ($attribute, $value, $fail) {
                    if (!DB::table('gaceta.tipos_norma')->where('id_tipo_norma', $value)->exists()) {
                        $fail('El tipo de norma seleccionado no existe.');
                    }
                },
            ],
            'id_estado_norma' => [
                'sometimes', 'integer',
                function ($attribute, $value, $fail) {
                    if (!DB::table('gaceta.estados_norma')->where('id_estado_norma', $value)->exists()) {
                        $fail('El estado de norma seleccionado no existe.');
                    }
                },
            ],
            'numero' => 'sometimes|integer',
            'gestion' => 'sometimes|integer',
            'titulo' => 'sometimes|string|max:500',
            'desripcion' => 'nullable|string',
            'fecha_publicacion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $norma->update([
            'id_tipo_norma' => $request->id_tipo_norma,
            'id_estado_norma' => $request->id_estado_norma,
            'numero' => $request->numero,
            'gestion' => $request->gestion,
            'titulo' => $request->titulo,
            'desripcion' => $request->desripcion,
            'fecha_publicacion' => $request->fecha_publicacion,
        ]);

        $norma->id_usuario_actualizacion = Auth::id();
        $norma->save();

        return response()->json([
            'success' => true,
            'data' => $norma->load(['tipo', 'estado'])
        ]);
    }

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
