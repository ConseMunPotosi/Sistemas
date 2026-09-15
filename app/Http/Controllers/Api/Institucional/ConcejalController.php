<?php

namespace App\Http\Controllers\Api\Institucional;

use App\Http\Controllers\Controller;
use App\Models\Institucional\Concejal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConcejalController extends Controller
{
    public function index()
    {
        $concejales = Concejal::orderBy('id_concejal')->get();

        return response()->json([
            'success' => true,
            'data' => $concejales
        ]);
    }

    public function publicIndex()
    {
        $concejales = Concejal::where('estado', true)
            ->orderBy('id_concejal')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $concejales
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:150',
            'cargo' => 'nullable|string|max:100',
            'comision' => 'nullable|string|max:150',
            'distritos' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $concejal = Concejal::create([
            'nombre' => $request->nombre,
            'cargo' => $request->cargo,
            'comision' => $request->comision,
            'distritos' => $request->distritos,
            'descripcion' => $request->descripcion,
            'imagen' => null,
            'estado' => $request->has('estado')
                ? $request->boolean('estado')
                : true,
            'fecha_registro' => now(),
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');

            if ($imagen->isValid()) {
                $carpetaDestino = public_path('images/concejales');

                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                $extension = strtolower(
                    $imagen->getClientOriginalExtension()
                );

                $nombreImagen = 'concejal_' .
                    $concejal->id_concejal .
                    '_' .
                    time() .
                    '.' .
                    $extension;

                $imagen->move(
                    $carpetaDestino,
                    $nombreImagen
                );

                $concejal->update([
                    'imagen' => 'images/concejales/' . $nombreImagen
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $concejal->fresh()
        ], 201);
    }

    public function show($id)
    {
        $concejal = Concejal::find($id);

        if (!$concejal) {
            return response()->json([
                'success' => false,
                'message' => 'Concejal no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $concejal
        ]);
    }

    public function update(Request $request, $id)
    {
        $concejal = Concejal::find($id);

        if (!$concejal) {
            return response()->json([
                'success' => false,
                'message' => 'Concejal no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:150',
            'cargo' => 'nullable|string|max:100',
            'comision' => 'nullable|string|max:150',
            'distritos' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $datos = [
            'nombre' => $request->has('nombre')
                ? $request->nombre
                : $concejal->nombre,

            'cargo' => $request->has('cargo')
                ? $request->cargo
                : $concejal->cargo,

            'comision' => $request->has('comision')
                ? $request->comision
                : $concejal->comision,

            'distritos' => $request->has('distritos')
                ? $request->distritos
                : $concejal->distritos,

            'descripcion' => $request->has('descripcion')
                ? $request->descripcion
                : $concejal->descripcion,

            'estado' => $request->has('estado')
                ? $request->boolean('estado')
                : $concejal->estado,
        ];

        /*
         * Si se selecciona una nueva fotografía:
         * - NO eliminamos inmediatamente la anterior.
         * - Guardamos la nueva con un nombre único.
         *
         * Esto evita problemas de archivos bloqueados en Windows.
         */
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');

            if ($imagen->isValid()) {
                $carpetaDestino = public_path('images/concejales');

                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                $extension = strtolower(
                    $imagen->getClientOriginalExtension()
                );

                $nombreImagen = 'concejal_' .
                    $concejal->id_concejal .
                    '_' .
                    time() .
                    '.' .
                    $extension;

                $imagen->move(
                    $carpetaDestino,
                    $nombreImagen
                );

                $datos['imagen'] =
                    'images/concejales/' . $nombreImagen;
            }
        }

        $concejal->update($datos);

        return response()->json([
            'success' => true,
            'data' => $concejal->fresh()
        ]);
    }

    public function destroy($id)
    {
        $concejal = Concejal::find($id);

        if (!$concejal) {
            return response()->json([
                'success' => false,
                'message' => 'Concejal no encontrado'
            ], 404);
        }

        /*
         * Primero eliminamos el registro.
         *
         * No eliminamos físicamente la fotografía aquí porque
         * Windows puede tener el archivo bloqueado.
         */
        $concejal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Concejal eliminado'
        ]);
    }
}
