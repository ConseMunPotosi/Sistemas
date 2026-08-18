<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\comunicacion\CategoriaNoticiaController;
use App\Http\Controllers\Api\comunicacion\NoticiaController;

// ==========================================
// RUTAS PÚBLICAS (sin autenticación)
// ==========================================

/**
 * Iniciar sesión
 * POST /api/login
 * Body: { "usuario": "admin", "password": "admin123" }
 * Response: { "success": true, "data": { "token": "...", "user": {...} } }
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');

// ==========================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {

    // RUTA DE CATEGORÍAS 
    Route::get('/noticias/categorias', [CategoriaNoticiaController::class, 'index']);
    Route::post('/noticias/categorias', [CategoriaNoticiaController::class, 'store']); // <--- VERIFICA ESTA LÍNEA
    Route::put('/noticias/categorias/{id}', [CategoriaNoticiaController::class, 'update']);
    Route::delete('/noticias/categorias/{id}', [CategoriaNoticiaController::class, 'destroy']);

    // RUTA DE NOTICIAS
    Route::get('/noticias', [NoticiaController::class, 'index']);
    Route::post('/noticias', [NoticiaController::class, 'store']);
    Route::put('/noticias/{id}', [NoticiaController::class, 'update']);
    Route::delete('/noticias/{id}', [NoticiaController::class, 'destroy']);

    Route::get('/cargos', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Institucional\Cargo::all() // Asegúrate de importar tu modelo
        ]);
    });

    Route::get('/unidades', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Institucional\Unidad::all()
        ]);
    });
    /**
     * Obtener perfil completo del usuario logueado (con funcionario, cargo y unidad)
     * GET /api/profile
     * Header: Authorization: Bearer {token}
     */
    Route::get('/profile', [AuthController::class, 'profile']);

    /**
     * Obtener usuario actual
     * GET /api/user
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "data": { "user": {...}, "permissions": {...} } }
     */
    Route::get('/user', [AuthController::class, 'user']);

    /**
     * Cerrar sesión
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "message": "Sesión cerrada exitosamente" }
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * Cambiar contraseña
     * POST /api/change-password
     * Header: Authorization: Bearer {token}
     * Body: { "current_password": "admin123", "new_password": "nueva123", "new_password_confirmation": "nueva123" }
     * Response: { "success": true, "message": "Contraseña actualizada exitosamente" }
     */
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    /**
     * Verificar token (para mantener sesión activa)
     * POST /api/verify-token
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "message": "Token válido" }
     */
    Route::post('/verify-token', function () {
        return response()->json([
            'success' => true,
            'message' => 'Token válido'
        ]);
    });

    // ==========================================
    // RUTAS DE ADMINISTRACIÓN (solo administradores)
    // ==========================================

    /**
     * Ruta de prueba para administradores
     * GET /api/admin-only
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "message": "Bienvenido administrador" }
     * Si el usuario no es administrador: { "success": false, "message": "No tienes permisos" }
     */
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

    // ==========================================
    // RUTAS DE GESTIÓN DE USUARIOS (solo administradores)
    // ==========================================

    /**
     * Listar todos los usuarios
     * GET /api/users
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "data": [ { "user": {...}, "roles": [...] } ] }
     */
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

    /**
     * Obtener un usuario específico
     * GET /api/users/{id}
     * Header: Authorization: Bearer {token}
     */
    Route::get('/users/{id}', function ($id) {
        $user = Auth::user();
        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $targetUser = \App\Models\Seguridad\Usuario::with(['funcionario', 'roles', 'funcionario.cargo', 'funcionario.unidad'])
            ->find($id);

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

    /**
     * Crear un nuevo usuario
     * POST /api/users
     * Header: Authorization: Bearer {token}
     * Body: { "id_funcionario": 1, "usuario": "jperez", "password": "123456", "correo": "jperez@test.com", "roles": [1, 2] }
     */
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

    /**
     * Actualizar un usuario
     * PUT /api/users/{id}
     * Header: Authorization: Bearer {token}
     */
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

        $targetUser->update($request->only(['usuario', 'correo', 'activo']));
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

    /**
     * Eliminar un usuario
     * DELETE /api/users/{id}
     * Header: Authorization: Bearer {token}
     */
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

    // ==========================================
    // RUTAS DE ROLES Y PERMISOS (solo administradores)
    // ==========================================

    /**
     * Listar todos los roles
     * GET /api/roles
     * Header: Authorization: Bearer {token}
     */
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

    /**
     * Listar todos los permisos
     * GET /api/permisos
     * Header: Authorization: Bearer {token}
     */
    Route::get('/permisos', function () {
        $user = Auth::user();
        if (!$user->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $permisos = \App\Models\Seguridad\Permiso::all()->groupBy('modulo');

        return response()->json([
            'success' => true,
            'data' => $permisos
        ]);
    });
});

// ==========================================
// RUTAS DE PRUEBA (solo en desarrollo)
// ==========================================

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
