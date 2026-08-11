<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SuperAdministradorSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // 1. Crear Unidad
            $id_unidad = DB::table('institucional.unidades')->insertGetId(
                [
                    'nombre' => 'Administración General',
                    'sigla' => 'ADMGS',
                    'descripcion' => 'Unidad de Administración General del Sistema',
                    'estado' => true,
                    'fecha_creacion' => Carbon::now(),
                ],
                'id_unidad'
            );

            // 2. Crear Cargo
            $id_cargo = DB::table('institucional.cargos')->insertGetId(
                [
                    'nombre' => 'Administrador del Sistema',
                    'descripcion' => 'Super Administrador con acceso total al sistema',
                    'estado' => true,
                ],
                'id_cargo'
            );

            // 3. Crear Funcionario
            $id_funcionario = DB::table('institucional.funcionarios')->insertGetId(
                [
                    'nombres' => 'Super',
                    'apellidos' => 'Administrador',
                    'ci' => '12345678',
                    'celular' => '',
                    'correo' => 'super.admin@concejopotosi.gob.bo',
                    'estado' => true,
                    'fecha_registro' => Carbon::now(),
                    'id_unidad' => $id_unidad,
                    'id_cargo' => $id_cargo,
                    'gestion' => '2026-05-03',
                ],
                'id_funcionario'
            );

            // 4. Crear Usuario
            $id_usuario = DB::table('seguridad.usuarios')->insertGetId(
                [
                    'id_funcionario' => $id_funcionario,
                    'usuario' => 'admin',
                    'password_hash' => Hash::make('CMP@2026'),
                    'activo' => true,
                    'intentos_fallidos' => 0,
                    'ultimo_acceso' => null,
                    'fecha_creacion' => Carbon::now(),
                    'fecha_actualizacion' => Carbon::now(),
                    'remember_token' => null,
                ],
                'id_usuario'
            );

            // 5. Crear Rol
            $id_rol = DB::table('seguridad.roles')->insertGetId(
                [
                    'nombre' => 'SUPER_ADMIN',
                    'descripcion' => 'Super Administrador con acceso total al sistema',
                    'estado' => true,
                    'fecha_creacion' => Carbon::now(),
                    'fecha_actualizacion' => null,
                ],
                'id_rol'
            );

            // 6. Asignar Rol
            DB::table('seguridad.usuario_roles')->insert([
                'id_usuario' => $id_usuario,
                'id_rol' => $id_rol,
            ]);

            $this->command->info('✅ Super Administrador creado exitosamente!');
            $this->command->info('📋 Usuario: superadmin');
            $this->command->info('🔑 Contraseña: Cmp@2026');

        });
    }
}
