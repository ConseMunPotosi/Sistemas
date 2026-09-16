<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\comunicacion\CategoriaNoticiaController;
use App\Http\Controllers\Api\comunicacion\NoticiaController;

use App\Http\Controllers\Api\Gaceta\EstadoNormaController;
use App\Http\Controllers\Api\Gaceta\NormaController;
use App\Http\Controllers\Api\Gaceta\TipoNormaController;
use App\Http\Controllers\Api\Gaceta\ArchivoNormaController;

use App\Http\Controllers\Api\Comunicacion\SesionController;
use App\Http\Controllers\Api\Institucional\ConcejalController;
use App\Http\Controllers\Api\Institucional\ConfiguracionController;


// ==========================================================
// RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
// ==========================================================

Route::post('/login', [AuthController::class, 'login'])->name('login');


// ==========================================================
// NOTICIAS - PÚBLICO
// ==========================================================

Route::get('/noticias', [NoticiaController::class, 'index']);


// ==========================================================
// ARCHIVOS / GACETA - PÚBLICO
// ==========================================================

Route::get('/public/gaceta/normas', [NormaController::class, 'publicIndex']);


// ==========================================================
// SESIONES - PÚBLICO
// ==========================================================

Route::get('/public/sesiones', [SesionController::class, 'publicIndex']);


// ==========================================================
// CONCEJALES - PÚBLICO
// ==========================================================

Route::get('/public/concejales', [ConcejalController::class, 'publicIndex']);
Route::get('/public/configuracion', [ConfiguracionController::class, 'index']);
Route::get('/public/configuracion/{clave}', [ConfiguracionController::class, 'show']);


// ==========================================================
// RUTAS PROTEGIDAS
// ==========================================================

Route::middleware('auth:sanctum')->group(function () {

    // ======================================================
    // CATEGORÍAS DE NOTICIAS
    // ======================================================

    Route::get('/noticias/categorias', [CategoriaNoticiaController::class, 'index']);
    Route::post('/noticias/categorias', [CategoriaNoticiaController::class, 'store']);
    Route::put('/noticias/categorias/{id}', [CategoriaNoticiaController::class, 'update']);
    Route::delete('/noticias/categorias/{id}', [CategoriaNoticiaController::class, 'destroy']);

    Route::put('/configuracion/{clave}', [ConfiguracionController::class, 'update']);


    // ======================================================
    // NOTICIAS
    // ======================================================

    Route::post('/noticias', [NoticiaController::class, 'store']);
    Route::put('/noticias/{id}', [NoticiaController::class, 'update']);
    Route::delete('/noticias/{id}', [NoticiaController::class, 'destroy']);


    // ======================================================
    // GACETA
    // ======================================================

    // Tipos de norma
    Route::get('/gaceta/tipos-norma', [TipoNormaController::class, 'index']);
    Route::post('/gaceta/tipos-norma', [TipoNormaController::class, 'store']);
    Route::put('/gaceta/tipos-norma/{id}', [TipoNormaController::class, 'update']);
    Route::delete('/gaceta/tipos-norma/{id}', [TipoNormaController::class, 'destroy']);

    // Estados de norma
    Route::get('/gaceta/estados-norma', [EstadoNormaController::class, 'index']);
    Route::post('/gaceta/estados-norma', [EstadoNormaController::class, 'store']);
    Route::put('/gaceta/estados-norma/{id}', [EstadoNormaController::class, 'update']);
    Route::delete('/gaceta/estados-norma/{id}', [EstadoNormaController::class, 'destroy']);

    // Normas
    Route::get('/gaceta/normas', [NormaController::class, 'index']);
    Route::post('/gaceta/normas', [NormaController::class, 'store']);
    Route::put('/gaceta/normas/{id}', [NormaController::class, 'update']);
    Route::delete('/gaceta/normas/{id}', [NormaController::class, 'destroy']);

    // Archivos de normas
    Route::post('/gaceta/archivos-norma', [ArchivoNormaController::class, 'store']);
    Route::delete('/gaceta/archivos-norma/{id}', [ArchivoNormaController::class, 'destroy']);


    // ======================================================
    // SESIONES DEL CONCEJO
    // ======================================================

    // Listar sesiones para administración
    Route::get('/sesiones', [SesionController::class, 'index']);

    // Crear sesión y subir video
    Route::post('/sesiones', [SesionController::class, 'store']);

    // Mostrar una sesión
    Route::get('/sesiones/{id}', [SesionController::class, 'show']);

    // Actualizar sesión
    Route::put('/sesiones/{id}', [SesionController::class, 'update']);

    // Eliminar sesión y su video
    Route::delete('/sesiones/{id}', [SesionController::class, 'destroy']);


    // ======================================================
    // INSTITUCIONAL
    // ======================================================

    // ======================================================
    // CONCEJALES
    // ======================================================

    // Listar todos los concejales para el Dashboard
    Route::get('/concejales', [ConcejalController::class, 'index']);

    // Crear concejal
    Route::post('/concejales', [ConcejalController::class, 'store']);

    // Mostrar un concejal
    Route::get('/concejales/{id}', [ConcejalController::class, 'show']);

    // Actualizar concejal
    Route::put('/concejales/{id}', [ConcejalController::class, 'update']);

    // Eliminar concejal
    Route::delete('/concejales/{id}', [ConcejalController::class, 'destroy']);


    // ======================================================
    // CARGOS
    // ======================================================

    Route::get('/cargos', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Institucional\Cargo::all()
        ]);
    });


    // ======================================================
    // UNIDADES
    // ======================================================

    Route::get('/unidades', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Institucional\Unidad::all()
        ]);
    });


    // ======================================================
    // AUTENTICACIÓN
    // ======================================================

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::post('/verify-token', function () {
        return response()->json([
            'success' => true,
            'message' => 'Token válido'
        ]);
    });


    // ======================================================
    // ADMINISTRACIÓN
    // ======================================================

    Route::get('/admin-only', function () {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos de administrador'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bienvenido administrador',
            'user' => $user->load(['funcionario', 'roles'])
        ]);
    })->middleware('can:admin');


    // ======================================================
    // GESTIÓN DE USUARIOS
    // ======================================================

    Route::get('/users', function () {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver usuarios'
            ], 403);
        }

        $users = \App\Models\Seguridad\Usuario::with(['funcionario', 'roles'])
            ->where('id_usuario', '!=', $user->id_usuario)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    });


    Route::get('/users/{id}', function ($id) {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $targetUser = \App\Models\Seguridad\Usuario::with([
            'funcionario',
            'roles',
            'funcionario.cargo',
            'funcionario.unidad'
        ])->find($id);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $targetUser
        ]);
    });


    Route::post('/users', function (Request $request) {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para crear usuarios'
            ], 403);
        }

        $request->validate([
            'id_funcionario' => 'required|exists:institucional.funcionarios,id_funcionario',
            'usuario' => 'required|string|max:50|unique:seguridad.usuarios,usuario',
            'password' => 'required|string|min:6',
            'correo' => 'nullable|email|max:150',
            'roles' => 'array',
            'roles.*' => 'exists:seguridad.roles,id_rol'
        ]);

        $newUser = \App\Models\Seguridad\Usuario::create([
            'id_funcionario' => $request->id_funcionario,
            'usuario' => $request->usuario,
            'password_hash' => bcrypt($request->password),
            'correo' => $request->correo,
            'activo' => true,
            'fecha_creacion' => now(),
            'fecha_actualizacion' => now(),
            'intentos_fallidos' => 0
        ]);

        if ($request->has('roles')) {
            $newUser->roles()->attach($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $newUser->load(['funcionario', 'roles'])
        ], 201);
    });


    Route::put('/users/{id}', function (Request $request, $id) {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para actualizar usuarios'
            ], 403);
        }

        $targetUser = \App\Models\Seguridad\Usuario::find($id);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $request->validate([
            'usuario' => 'sometimes|string|max:50|unique:seguridad.usuarios,usuario,' . $id . ',id_usuario',
            'correo' => 'nullable|email|max:150',
            'activo' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:seguridad.roles,id_rol'
        ]);

        $targetUser->update(
            $request->only(['usuario', 'correo', 'activo'])
        );

        $targetUser->fecha_actualizacion = now();
        $targetUser->save();

        if ($request->has('roles')) {
            $targetUser->roles()->sync($request->roles);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente',
            'data' => $targetUser->load(['funcionario', 'roles'])
        ]);
    });


    Route::delete('/users/{id}', function ($id) {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar usuarios'
            ], 403);
        }

        $targetUser = \App\Models\Seguridad\Usuario::find($id);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($targetUser->id_usuario === $user->id_usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario'
            ], 400);
        }

        $targetUser->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    });


    // ======================================================
    // ROLES Y PERMISOS
    // ======================================================

    Route::get('/roles', function () {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $roles = \App\Models\Seguridad\Rol::with('permisos')->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    });


    Route::get('/permisos', function () {
        $user = Auth::user();

        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $permisos = \App\Models\Seguridad\Permiso::all()
            ->groupBy('modulo');

        return response()->json([
            'success' => true,
            'data' => $permisos
        ]);
    });
});


// ==========================================================
// RUTAS DE PRUEBA - SOLO DESARROLLO
// ==========================================================

if (app()->environment('local', 'development')) {

    Route::get('/test-db', function () {
        try {

            \DB::connection()->getPdo();

            return response()->json([
                'success' => true,
                'message' => 'Conexión a base de datos exitosa',
                'database' => \DB::connection()->getDatabaseName()
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error de conexión a base de datos',
                'error' => $e->getMessage()
            ], 500);
        }
    });


    Route::get('/test-auth', function () {
        return response()->json([
            'success' => true,
            'message' => 'Ruta de prueba de autenticación',
            'user' => Auth::user() ?? 'No autenticado'
        ]);
    });
}
