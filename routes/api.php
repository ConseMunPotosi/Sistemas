<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|Aquí puedes registrar las rutas de la API para tu aplicación.
|Estas rutas son cargadas por el RouteServiceProvider y todas ellas
|se asignarán al grupo de middleware "api".
|
*/

// ==========================================
// RUTAS PÚBLICAS (sin autenticación)
// ==========================================

/**
 * Iniciar sesión
 * POST /api/login
 * Body: { "usuario": "admin", "password": "admin123" }
 * Response: { "success": true, "data": { "token": "...", "usuario": {...} } }
 */

Route::post('/login', [AuthController::class, 'login'])->name('login');

// ==========================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Cerrar sesión
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "message": "Sesión cerrada exitosamente" }
     */
    Route::post('/logout', [AuthController::class, 'logout']);
    
    /**
     * Obtener usuario actual
     * GET /api/user
     * Header: Authorization: Bearer {token}
     * Response: { "success": true, "data": { "usuario": {...}, "permisos": {...} } }
     */
    Route::get('/user', [AuthController::class, 'user']);
    
    /**
     * Cambiar contraseña
     * POST /api/change-password
     * Header: Authorization: Bearer {token}
     * Body: { "current_password": "admin123", "new_password": "nueva123", "new_password_confirmation": "nueva123" }
     * Response: { "success": true, "message": "Contraseña actualizada exitosamente" }
     */
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

// ==========================================
// RUTAS DE PRUEBA
// ==========================================

/**
 * Ruta de prueba para administradores
 * GET /api/admin-only
 * Header: Authorization: Bearer {token}
 * Response: { "success": true, "message": "Bienvenido administrador" }
 * Si el usuario no es administrador: { "success": false, "message": "No tienes permisos" }
 */
Route::middleware(['auth:sanctum'])->get('/admin-only', function () {
    $user = auth()->user();
    if (!$user->hasRole('Administrador')) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos de administrador'
        ], 403);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Bienvenido administrador'
    ]);
});

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});*/
