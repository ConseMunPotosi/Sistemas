<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seguridad\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/**
 * Controlador de autenticación
 * 
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller
{
    /*public function login(Request $request)
    {
        // PASO 1: Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ]);
        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }
    }*/


    /**
     * INICIAR SESION
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
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

        // PASO 2: Buscar el usuario
        $usuario = Usuario::where('usuario', $request->usuario)
                          ->orWhere('correo', $request->usuario)
                          ->first();

        // PASO 3: Verificar credenciales
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
                'errors' => [
                    'usuario' => ['El usuario o contraseña son incorrectos']
                ]
            ], 401);
        }

        // PASO 4: Verificar si el usuario está activo
        if (!$usuario->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario desactivado',
                'errors' => [
                    'usuario' => ['Tu cuenta está desactivada. Contacta al administrador.']
                ]
            ], 403);
        }

        // PASO 5: Actualizar último acceso
        $usuario->ultimo_acceso = now('America/La_Paz');
        $usuario->save();

        // PASO 6: Generar token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // PASO 7: Cargar relaciones
        $usuario->load('funcionario', 'roles');

        // PASO 8: Obtener permisos
        $permisos = $this->getUserPermissions($usuario);

        // PASO 9: Respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'data' => [
                'usuario' => $usuario,
                'token' => $token,
                'token_type' => 'Bearer',
                'permisos' => $permisos,
            ]
        ], 200);
    }

    /**
     * CERRAR SESION
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }

    /**
     * OBTENER USUARIO AUTENTICADO
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();
        $user->load('funcionario', 'roles');

        return response()->json([
            'success' => true,
            'data' => [
                'usuario' => $user,
                'permisos' => $this->getUserPermissions($user),
            ]
        ]);
    }

    /**
     * CAMBIAR CONTRASEÑA
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
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

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ], 403);
        }

        $user->password_hash = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada exitosamente'
        ], 200);
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
                $permisos[$permiso->modulo][] = $permiso->accion;
            }
        }

        foreach ($permisos as $modulo => $acciones) {
            $permisos[$modulo] = array_unique($acciones);
        }

        return $permisos;
    }
}
