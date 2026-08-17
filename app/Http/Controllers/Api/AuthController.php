<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seguridad\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Controlador de autenticación
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /**
     * INICIAR SESIÓN
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // PASO 1: Validar los datos recibidos
            $validator = Validator::make($request->all(), [
                'usuario' => 'required|string|max:50',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // PASO 2: Buscar el usuario por usuario
            $usuario = Usuario::where('usuario', $request->usuario)
                ->with(['roles', 'funcionario', 'funcionario.cargo', 'funcionario.unidad'])
                ->first();

            // PASO 3: Verificar si el usuario existe
            if (!$usuario) {
                Log::warning('Intento de login con usuario no encontrado', [
                    'usuario' => $request->usuario,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                    'errors' => [
                        'usuario' => ['El usuario o contraseña son incorrectos']
                    ]
                ], 401);
            }

            // PASO 4: Verificar contraseña
            if (!Hash::check($request->password, $usuario->password_hash)) {
                // Incrementar intentos fallidos
                $usuario->incrementFailedAttempts();

                Log::warning('Intento de login con contraseña incorrecta', [
                    'usuario' => $request->usuario,
                    'user_id' => $usuario->id_usuario,
                    'intentos' => $usuario->intentos_fallidos,
                    'ip' => $request->ip()
                ]);

                // Verificar si la cuenta está bloqueada
                if ($usuario->isLocked()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cuenta bloqueada por múltiples intentos fallidos',
                        'errors' => [
                            'usuario' => ['Tu cuenta ha sido bloqueada. Contacta al administrador.']
                        ]
                    ], 403);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                    'errors' => [
                        'usuario' => ['El usuario o contraseña son incorrectos']
                    ]
                ], 401);
            }

            // PASO 5: Verificar si el usuario está activo
            if (!$usuario->activo) {
                Log::warning('Intento de login con usuario inactivo', [
                    'usuario' => $request->usuario,
                    'user_id' => $usuario->id_usuario,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Usuario desactivado',
                    'errors' => [
                        'usuario' => ['Tu cuenta está desactivada. Contacta al administrador.']
                    ]
                ], 403);
            }

            // PASO 6: Verificar si la cuenta está bloqueada
            if ($usuario->isLocked()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuenta bloqueada',
                    'errors' => [
                        'usuario' => ['Tu cuenta ha sido bloqueada por múltiples intentos fallidos.']
                    ]
                ], 403);
            }

            // PASO 7: Resetear intentos fallidos y registrar acceso
            $usuario->recordLastAccess();

            // PASO 8: Generar token de acceso
            $token = $usuario->createToken('auth-token', ['*'], now()->addHours(24))->plainTextToken;

            // PASO 9: Preparar datos del usuario para la respuesta
            $userData = $this->formatUserData($usuario);

            // PASO 10: Obtener permisos
            $permisos = $this->getUserPermissions($usuario);

            // Registrar actividad exitosa
            Log::info('Login exitoso', [
                'user_id' => $usuario->id_usuario,
                'usuario' => $usuario->usuario,
                'ip' => $request->ip()
            ]);

            // PASO 11: Respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => $userData,
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'permissions' => $permisos,
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en login', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario' => $request->usuario ?? 'unknown',
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * CERRAR SESIÓN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                // Eliminar el token actual
                $user->currentAccessToken()->delete();

                Log::info('Logout exitoso', [
                    'user_id' => $user->id_usuario,
                    'usuario' => $user->usuario,
                    'ip' => $request->ip()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en logout', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()->id_usuario ?? 'unknown',
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar logout'
            ], 500);
        }
    }

    /**
     * OBTENER USUARIO AUTENTICADO
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Cargar relaciones
            $user->load(['funcionario', 'funcionario.cargo', 'funcionario.unidad', 'roles']);

            // Actualizar último acceso
            $user->ultimo_acceso = now();
            $user->save();

            // Formatear datos del usuario
            $userData = $this->formatUserData($user);

            // Obtener permisos
            $permisos = $this->getUserPermissions($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $userData,
                    'permissions' => $permisos,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener usuario', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()->id_usuario ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del usuario'
            ], 500);
        }
    }

    /**
     * CAMBIAR CONTRASEÑA
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta',
                    'errors' => [
                        'current_password' => ['La contraseña actual es incorrecta']
                    ]
                ], 403);
            }

            // Actualizar contraseña
            $user->password_hash = Hash::make($request->new_password);
            $user->fecha_actualizacion = now();
            $user->save();

            Log::info('Contraseña actualizada', [
                'user_id' => $user->id_usuario,
                'usuario' => $user->usuario
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()->id_usuario ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar la contraseña'
            ], 500);
        }
    }

    /**
     * REFRESCAR TOKEN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            $user = $request->user();

            // Eliminar token actual
            $user->currentAccessToken()->delete();

            // Crear nuevo token
            $token = $user->createToken('auth-token', ['*'], now()->addHours(24))->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token refrescado exitosamente',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al refrescar token', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()->id_usuario ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al refrescar el token'
            ], 500);
        }
    }

    /**
     * FORMATO DE DATOS DEL USUARIO
     *
     * @param Usuario $usuario
     * @return array
     */
    private function formatUserData(Usuario $usuario): array
    {
        return [
            'id' => $usuario->id_usuario,
            'usuario' => $usuario->usuario,
            'nombre_completo' => $usuario->nombre_completo,
            'estado' => $usuario->estado,
            'ultimo_acceso' => $usuario->ultimo_acceso,
            'funcionario' => $usuario->funcionario ? [
                'id' => $usuario->funcionario->id_funcionario,
                'nombres' => $usuario->funcionario->nombres,
                'apellidos' => $usuario->funcionario->apellidos,
                'nombre_completo' => $usuario->funcionario->nombres . ' ' . $usuario->funcionario->apellidos,
                'ci' => $usuario->funcionario->ci ?? null,
                'cargo' => $usuario->funcionario->cargo ? [
                    'id' => $usuario->funcionario->cargo->id_cargo,
                    'nombre' => $usuario->funcionario->cargo->nombre
                ] : null,
                'unidad' => $usuario->funcionario->unidad ? [
                    'id' => $usuario->funcionario->unidad->id_unidad,
                    'nombre' => $usuario->funcionario->unidad->nombre
                ] : null,
            ] : null,
            'roles' => $usuario->roles->map(function ($rol) {
                return [
                    'id' => $rol->id_rol,
                    'nombre' => $rol->nombre,
                    'descripcion' => $rol->descripcion,
                    'permisos' => $rol->permisos->map(function ($permiso) {
                        return [
                            'modulo' => $permiso->modulo,
                            'accion' => $permiso->accion
                        ];
                    })
                ];
            })
        ];
    }

    /**
     * OBTENER PERMISOS DEL USUARIO
     *
     * @param Usuario $usuario
     * @return array
     */
    private function getUserPermissions(Usuario $usuario): array
    {
        $permisos = [];

        foreach ($usuario->roles as $rol) {
            foreach ($rol->permisos as $permiso) {
                if (!isset($permisos[$permiso->modulo])) {
                    $permisos[$permiso->modulo] = [];
                }
                $permisos[$permiso->modulo][] = $permiso->accion;
            }
        }

        // Eliminar duplicados y ordenar
        foreach ($permisos as $modulo => $acciones) {
            $permisos[$modulo] = array_values(array_unique($acciones));
            sort($permisos[$modulo]);
        }

        // Ordenar por módulo
        ksort($permisos);

        return $permisos;
    }

    public function profile(Request $request)
    {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Cargar las relaciones necesarias (funcionario, y dentro de funcionario: cargo y unidad)
        $user->load(['funcionario.cargo', 'funcionario.unidad', 'roles']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
