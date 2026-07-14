<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class SeguridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // PASO 1: Configurar la hora (Bolivia)
        // ==========================================
        $now = now('America/La_Paz');
        $this->command->info('🕐 Hora actual: ' . $now);
        // ==========================================
        // PASO 2: Crear Roles
        // ==========================================
        $this->command->info('📝 Creando roles...');
        // Roles
        //DB::table('seguridad.roles')->insert([
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'estado' => true],
            ['nombre' => 'Concejal', 'descripcion' => 'Gestión y seguimiento de documentos de su comisión', 'estado' => true],
            ['nombre' => 'Asesor', 'descripcion' => 'Registro, recepción y atención de documentos', 'estado' => true],
            ['nombre' => 'Asistente', 'descripcion' => 'Solo visualización de información', 'estado' => true],
            ['nombre' => 'Responsable', 'descripcion' => 'Gestión y seguimiento de informacion administrativa', 'estado' => true],
            ['nombre' => 'Auxiliar', 'descripcion' => 'Solo visualización de información', 'estado' => true],
            ['nombre' => 'Secretaria', 'descripcion' => 'Inicia los procesos de gestión de documentos', 'estado' => true],
        ];
        foreach ($roles as $rol) {
            DB::table('seguridad.roles')->updateOrInsert(
                ['nombre' => $rol['nombre']], // Buscar por nombre
                [ // Datos a insertar o actualizar
                    'descripcion' => $rol['descripcion'],
                    'estado' => $rol['estado']
                ]
            );
        }

        // ==========================================
        // PASO 3: Crear Permisos
        // ==========================================
        $this->command->info('📝 Creando permisos...');
        // Permisos (Módulo: Acción)
        //DB::table('seguridad.permisos')->insert([
        $permisos = [
            ['modulo' => 'usuarios', 'accion' => 'ver', 'descripcion' => 'Ver usuarios'],
            ['modulo' => 'usuarios', 'accion' => 'crear', 'descripcion' => 'Crear usuarios'],
            ['modulo' => 'usuarios', 'accion' => 'editar', 'descripcion' => 'Editar usuarios'],
            ['modulo' => 'usuarios', 'accion' => 'eliminar', 'descripcion' => 'Eliminar usuarios'],
            ['modulo' => 'documentos', 'accion' => 'crear', 'descripcion' => 'Crear documentos'],
            ['modulo' => 'documentos', 'accion' => 'editar', 'descripcion' => 'Editar documentos'],
            ['modulo' => 'documentos', 'accion' => 'derivar', 'descripcion' => 'Derivar documentos'],
            ['modulo' => 'documentos', 'accion' => 'recibir', 'descripcion' => 'Recibir documentos'],
            ['modulo' => 'documentos', 'accion' => 'archivar', 'descripcion' => 'Archiva documentos'],
            ['modulo' => 'documentos', 'accion' => 'consultar', 'descripcion' => 'Consultar documentos'],
            ['modulo' => 'noticias', 'accion' => 'crear', 'descripcion' => 'Crear noticias institucionales'],
            ['modulo' => 'noticias', 'accion' => 'editar', 'descripcion' => 'Editar noticias institucionales'],
            ['modulo' => 'noticias', 'accion' => 'publicar_web', 'descripcion' => 'Publicar noticias en la pagina web'],
            ['modulo' => 'noticias', 'accion' => 'publicar_facebook', 'descripcion' => 'Registrar o enlazar publicación de facebook'],
            ['modulo' => 'noticias', 'accion' => 'archivar', 'descripcion' => 'Archivar noticias institucionales'],
            ['modulo' => 'noticias', 'accion' => 'consultar', 'descripcion' => 'Consultar noticias institucionales'],
        ];
        foreach ($permisos as $permiso) {
            DB::table('seguridad.permisos')->updateOrInsert(
                [
                    'modulo' => $permiso['modulo'], // Buscar por nombre
                    'accion' => $permiso['accion']
                ], 
                [ // Datos a insertar o actualizar
                    'descripcion' => $permiso['descripcion']
                ]
            );
        }
        // Usuarios (contraseña: 123456)
        //DB::table('seguridad.usuarios')->insert([
        $usuarios = [
            ['id_funcionario' => 1, 'usuario' => 'gvidaurre', 'password_hash' => Hash::make('123456'), 'correo' => 'german.vidaurre@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 2, 'usuario' => 'cclemente', 'password_hash' => Hash::make('123456'), 'correo' => 'claudio.clemente@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 3, 'usuario' => 'mmichel', 'password_hash' => Hash::make('123456'), 'correo' => 'maría.michel@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 4, 'usuario' => 'gcruz', 'password_hash' => Hash::make('123456'), 'correo' => 'guido.cruz@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 5, 'usuario' => 'mcalizaya', 'password_hash' => Hash::make('123456'), 'correo' => 'manuel.calizaya@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 6, 'usuario' => 'efernández', 'password_hash' => Hash::make('123456'), 'correo' => 'eddy.fernández@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 7, 'usuario' => 'cchacón', 'password_hash' => Hash::make('123456'), 'correo' => 'christie.chacón@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 8, 'usuario' => 'ajiménez', 'password_hash' => Hash::make('123456'), 'correo' => 'ariel.jiménez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 9, 'usuario' => 'jgutiérrez', 'password_hash' => Hash::make('123456'), 'correo' => 'jacqueline.gutiérrez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 10, 'usuario' => 'caroni', 'password_hash' => Hash::make('123456'), 'correo' => 'clementina.aroni@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 11, 'usuario' => 'mpinto', 'password_hash' => Hash::make('123456'), 'correo' => 'maría.pinto@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 12, 'usuario' => 'gjara', 'password_hash' => Hash::make('123456'), 'correo' => 'german.jara@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 13, 'usuario' => 'msangueza', 'password_hash' => Hash::make('123456'), 'correo' => 'mijael.sangueza@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 14, 'usuario' => 'rmachaga', 'password_hash' => Hash::make('123456'), 'correo' => 'rolando.machaga@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 15, 'usuario' => 'jvelásquez', 'password_hash' => Hash::make('123456'), 'correo' => 'jhanira.velásquez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 16, 'usuario' => 'echoque', 'password_hash' => Hash::make('123456'), 'correo' => 'erwin.choque@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 17, 'usuario' => 'fterrazas', 'password_hash' => Hash::make('123456'), 'correo' => 'faviana.terrazas@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 18, 'usuario' => 'wmamani', 'password_hash' => Hash::make('123456'), 'correo' => 'wilson.mamani@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 19, 'usuario' => 'kmiranda', 'password_hash' => Hash::make('123456'), 'correo' => 'karina.miranda@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 20, 'usuario' => 'elópez', 'password_hash' => Hash::make('123456'), 'correo' => 'elizangela.lópez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 21, 'usuario' => 'smartínez', 'password_hash' => Hash::make('123456'), 'correo' => 'samuel.martínez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 22, 'usuario' => 'cpinto', 'password_hash' => Hash::make('123456'), 'correo' => 'carlos.pinto@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 23, 'usuario' => 'jromay', 'password_hash' => Hash::make('123456'), 'correo' => 'jhonny.romay@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 24, 'usuario' => 'dzuleta', 'password_hash' => Hash::make('123456'), 'correo' => 'deymar.zuleta@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 25, 'usuario' => 'mprojas', 'password_hash' => Hash::make('123456'), 'correo' => 'maría.rojas@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 26, 'usuario' => 'obohorquez', 'password_hash' => Hash::make('123456'), 'correo' => 'omar.bohorquez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 27, 'usuario' => 'dcallapa', 'password_hash' => Hash::make('123456'), 'correo' => 'delia.callapa@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 28, 'usuario' => 'wlizondo', 'password_hash' => Hash::make('123456'), 'correo' => 'wilson.lizondo@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 29, 'usuario' => 'bconde', 'password_hash' => Hash::make('123456'), 'correo' => 'bonny.conde@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 30, 'usuario' => 'cescobar', 'password_hash' => Hash::make('123456'), 'correo' => 'cesar.escobar@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 31, 'usuario' => 'rcirilo', 'password_hash' => Hash::make('123456'), 'correo' => 'rafael.cirilo@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 32, 'usuario' => 'rsempertegui', 'password_hash' => Hash::make('123456'), 'correo' => 'rene.sempertegui@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 33, 'usuario' => 'aflores', 'password_hash' => Hash::make('123456'), 'correo' => 'anabel.flores@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 34, 'usuario' => 'wflores', 'password_hash' => Hash::make('123456'), 'correo' => 'wilber.flores@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 35, 'usuario' => 'equiroga', 'password_hash' => Hash::make('123456'), 'correo' => 'edgar.quiroga@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 36, 'usuario' => 'lramírez', 'password_hash' => Hash::make('123456'), 'correo' => 'luzmila.ramírez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 37, 'usuario' => 'ftorrez', 'password_hash' => Hash::make('123456'), 'correo' => 'fabiola.torrez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 38, 'usuario' => 'mlópez', 'password_hash' => Hash::make('123456'), 'correo' => 'maría.lópez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 39, 'usuario' => 'scastillo', 'password_hash' => Hash::make('123456'), 'correo' => 'shirley.castillo@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 40, 'usuario' => 'glópez', 'password_hash' => Hash::make('123456'), 'correo' => 'gunar.lópez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 41, 'usuario' => 'wcanaviri', 'password_hash' => Hash::make('123456'), 'correo' => 'willam.canaviri@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 42, 'usuario' => 'mfuentes', 'password_hash' => Hash::make('123456'), 'correo' => 'milton.fuentes@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 43, 'usuario' => 'destrada', 'password_hash' => Hash::make('123456'), 'correo' => 'diego.estrada@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 44, 'usuario' => 'ribarra', 'password_hash' => Hash::make('123456'), 'correo' => 'roger.ibarra@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 45, 'usuario' => 'jari', 'password_hash' => Hash::make('123456'), 'correo' => 'juan.ari@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 46, 'usuario' => 'mtotola', 'password_hash' => Hash::make('123456'), 'correo' => 'mario.totola@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 47, 'usuario' => 'emendoza', 'password_hash' => Hash::make('123456'), 'correo' => 'edgar.mendoza@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 48, 'usuario' => 'rtorrez', 'password_hash' => Hash::make('123456'), 'correo' => 'raúl.torrez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 49, 'usuario' => 'pfernández', 'password_hash' => Hash::make('123456'), 'correo' => 'policarpio.fernández@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 50, 'usuario' => 'srodríguez', 'password_hash' => Hash::make('123456'), 'correo' => 'sonia.rodríguez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 51, 'usuario' => 'jchoque', 'password_hash' => Hash::make('123456'), 'correo' => 'janeth.choque@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 52, 'usuario' => 'rquinteros', 'password_hash' => Hash::make('123456'), 'correo' => 'ramiro.quinteros@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 53, 'usuario' => 'mquispe', 'password_hash' => Hash::make('123456'), 'correo' => 'mariela.quispe@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 54, 'usuario' => 'mrojas', 'password_hash' => Hash::make('123456'), 'correo' => 'mirian.rojas@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 55, 'usuario' => 'xcondori', 'password_hash' => Hash::make('123456'), 'correo' => 'ximena.condori@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 56, 'usuario' => 'rmita', 'password_hash' => Hash::make('123456'), 'correo' => 'rosmery.mita@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 57, 'usuario' => 'fcori', 'password_hash' => Hash::make('123456'), 'correo' => 'fidel.cori@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 58, 'usuario' => 'rfernández', 'password_hash' => Hash::make('123456'), 'correo' => 'rubén.fernández@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 59, 'usuario' => 'ncardozo', 'password_hash' => Hash::make('123456'), 'correo' => 'ninette.cardozo@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 60, 'usuario' => 'flima', 'password_hash' => Hash::make('123456'), 'correo' => 'félix.lima@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 61, 'usuario' => 'pparrado', 'password_hash' => Hash::make('123456'), 'correo' => 'pamela.parrado@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 62, 'usuario' => 'mlisidro', 'password_hash' => Hash::make('123456'), 'correo' => 'maya.lisidro@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 63, 'usuario' => 'lcoro', 'password_hash' => Hash::make('123456'), 'correo' => 'limber.coro@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 64, 'usuario' => 'apadilla', 'password_hash' => Hash::make('123456'), 'correo' => 'alexander.padilla@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 65, 'usuario' => 'mcastro', 'password_hash' => Hash::make('123456'), 'correo' => 'mariela.castro@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 66, 'usuario' => 'mramírez', 'password_hash' => Hash::make('123456'), 'correo' => 'marlene.ramírez@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 67, 'usuario' => 'aozuna', 'password_hash' => Hash::make('123456'), 'correo' => 'adolfo.ozuna@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],
            ['id_funcionario' => 68, 'usuario' => 'dchoque', 'password_hash' => Hash::make('123456'), 'correo' => 'delia.choque@cmp.gob.bo', 'activo' => true, 'ultimo_acceso' => now(), 'fecha_creacion' => now()],

        ];
        foreach ($usuarios as $usuario) {
            DB::table('seguridad.usuarios')->updateOrInsert(
                [
                   'usuario' => $usuario['usuario'] // Buscar por usuario
                ], 
                [ // Datos a insertar o actualizar
                    'id_funcionario' => $usuario['id_funcionario'],
                    'password_hash' => $usuario['password_hash'],
                    'correo' => $usuario['correo'],
                    'activo' => $usuario['activo'],
                    'ultimo_acceso' => $usuario['ultimo_acceso'],
                    'fecha_creacion' => $usuario['fecha_creacion'],
                ]
            );
        }

        // Asignación de roles a usuarios
        //DB::table('seguridad.usuario_roles')->insert([
        $usuario_roles = [
            //Administrador
            ['id_usuario' => 44, 'id_rol' => 1],
            // Concejal
            ['id_usuario' => 2, 'id_rol' => 2], 
            ['id_usuario' => 3, 'id_rol' => 2], 
            ['id_usuario' => 4, 'id_rol' => 2], 
            ['id_usuario' => 5, 'id_rol' => 2], 
            ['id_usuario' => 6, 'id_rol' => 2],
            ['id_usuario' => 7, 'id_rol' => 2],
            ['id_usuario' => 8, 'id_rol' => 2],
            ['id_usuario' => 9, 'id_rol' => 2],
            ['id_usuario' => 10, 'id_rol' => 2],
            // Asesor
            ['id_usuario' => 52, 'id_rol' => 3], 
            ['id_usuario' => 54, 'id_rol' => 3], 
            ['id_usuario' => 55, 'id_rol' => 3], 
            ['id_usuario' => 57, 'id_rol' => 3], 
            ['id_usuario' => 59, 'id_rol' => 3],
            ['id_usuario' => 61, 'id_rol' => 3],
            ['id_usuario' => 63, 'id_rol' => 3],
            ['id_usuario' => 65, 'id_rol' => 3],
            ['id_usuario' => 67, 'id_rol' => 3],
            //Asistente
            ['id_usuario' => 11, 'id_rol' => 4], 
            ['id_usuario' => 12, 'id_rol' => 4], 
            ['id_usuario' => 13, 'id_rol' => 4], 
            ['id_usuario' => 17, 'id_rol' => 4],
            //Responsables
            ['id_usuario' => 24, 'id_rol' => 5], 
            ['id_usuario' => 30, 'id_rol' => 5], 
            ['id_usuario' => 34, 'id_rol' => 5], 
            ['id_usuario' => 40, 'id_rol' => 5], 
            ['id_usuario' => 42, 'id_rol' => 5],
            //Auxiliar
            ['id_usuario' => 15, 'id_rol' => 6], 
            ['id_usuario' => 27, 'id_rol' => 6], 
            ['id_usuario' => 29, 'id_rol' => 6], 
            ['id_usuario' => 31, 'id_rol' => 6], 
            ['id_usuario' => 33, 'id_rol' => 6],
            ['id_usuario' => 35, 'id_rol' => 6],
            ['id_usuario' => 37, 'id_rol' => 6],
            ['id_usuario' => 38, 'id_rol' => 6],
            ['id_usuario' => 39, 'id_rol' => 6],
            ['id_usuario' => 41, 'id_rol' => 6], 
            ['id_usuario' => 43, 'id_rol' => 6], 
            ['id_usuario' => 45, 'id_rol' => 6], 
            ['id_usuario' => 53, 'id_rol' => 6], 
            ['id_usuario' => 56, 'id_rol' => 6],
            ['id_usuario' => 58, 'id_rol' => 6],
            ['id_usuario' => 60, 'id_rol' => 6],
            ['id_usuario' => 62, 'id_rol' => 6],
            ['id_usuario' => 64, 'id_rol' => 6],
            ['id_usuario' => 66, 'id_rol' => 6],
            ['id_usuario' => 68, 'id_rol' => 6],
            //Secretaria
            ['id_usuario' => 19, 'id_rol' => 7],
            ['id_usuario' => 20, 'id_rol' => 7],

        ];
        foreach ($usuario_roles as $usuario_rol) {
            DB::table('seguridad.usuario_roles')->updateOrInsert(
                [
                   'id_usuario' => $usuario_rol['id_usuario'], // Buscar por id_usuario
                   'id_rol' => $usuario_rol['id_rol']
                ], 
                [ // Datos a insertar o actualizar
        
                ]
            );
        }
        // Asignación de permisos a roles
        // Administrador: todos los permisos
        $permisos = DB::table('seguridad.permisos')->pluck('id_permiso')->toArray();
        foreach ($permisos as $permiso) {
            DB::table('seguridad.rol_permisos')->insert([
                'id_rol' => 1,
                'id_permiso' => $permiso
            ]);
        }

        // Concejal: Gestión y seguimiento de documentos de su comisión
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 2, 'id_permiso' => 10],
        ]);

        // Asesor: Registro, recepción y atención de documentos
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 3, 'id_permiso' => 7],
            ['id_rol' => 3, 'id_permiso' => 8],
            ['id_rol' => 3, 'id_permiso' => 10],
        ]);

        // Asistente: Solo visualización de información
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 4, 'id_permiso' => 10],
        ]);

        // Responsable: Gestión y seguimiento de informacion administrativa
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 5, 'id_permiso' => 7],
            ['id_rol' => 5, 'id_permiso' => 8],
            ['id_rol' => 5, 'id_permiso' => 10],
        ]);

        // Auxliar: Solo visualización de información
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 6, 'id_permiso' => 10],
        ]);

        // Secretaria: Inicia los procesos de gestión de documentos
        DB::table('seguridad.rol_permisos')->insert([
            ['id_rol' => 7, 'id_permiso' => 5],
            ['id_rol' => 7, 'id_permiso' => 6],
            ['id_rol' => 7, 'id_permiso' => 7],
            ['id_rol' => 7, 'id_permiso' => 8],
            ['id_rol' => 7, 'id_permiso' => 9],
            ['id_rol' => 7, 'id_permiso' => 10],
        ]);

        
        // ==========================================
        // PASO 9: Mensaje de éxito
        // ==========================================
        $this->command->info('✅ SeguridadSeeder ejecutado correctamente');
        $this->command->info('========================================');
    }
}
