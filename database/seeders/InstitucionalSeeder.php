<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class InstitucionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Unidades
        $unidades = [
            ['nombre' => 'Pleno', 'sigla' => 'PCM', 'descripcion' => 'Órgano deliberativo fiscalizador y legislativo', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Presidencia', 'sigla' => 'PRES', 'descripcion' => 'Presidencia del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Vicepresidencia', 'sigla' => 'VIPRES', 'descripcion' => 'Vicepresidencia del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Secretaría del Concejo', 'sigla' => 'SEC', 'descripcion' => 'Secretaría del Concejo Municipal de Potosi', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión Jurídica y Desarrollo Institucional', 'sigla' => 'CJyDI', 'descripcion' => 'Comisión Jurídica y Desarrollo Institucional', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión Económica y Financiera', 'sigla' => 'CEyF', 'descripcion' => 'Comisión Económica y Financiera', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Desarrollo Territorial y Límites', 'sigla' => 'CDTyL', 'descripcion' => 'Comisión de Desarrollo Territorial y Límites', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión Tecnica', 'sigla' => 'CT', 'descripcion' => 'Comisión Tecnica', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Desarrollo Humano', 'sigla' => 'CDH', 'descripcion' => 'Comisión de Desarrollo Humano', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Genero Generacional', 'sigla' => 'CGG', 'descripcion' => 'Comisión de Genero Generacional', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Desarrollo Económico, Productivo y Agropecuario', 'sigla' => 'CDEPyA', 'descripcion' => 'Comisión de Desarrollo Económico, Productivo y Agropecuario', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Turismo, Cultura y Preservación de Áreas Históricas', 'sigla' => 'CTCyPAH', 'descripcion' => 'Comisión de Turismo, Cultura y Preservación de Áreas Históricas', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Medio Ambiente, Minería y Forestación', 'sigla' => 'CMAMyF', 'descripcion' => 'Comisión de Medio Ambiente, Minería y Forestación', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Comisión de Servicios Públicos', 'sigla' => 'CSP', 'descripcion' => 'Comisión de Servicios Públicos', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Secretaría de Cámara', 'sigla' => 'SCA', 'descripcion' => 'Secretaría de Cámara', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Relaciones publicas', 'sigla' => 'RRPP', 'descripcion' => 'Relaciones publicas', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Jefatura de Gabinete', 'sigla' => 'JG', 'descripcion' => 'Jefatura de Gabinte del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Jefatura Administrativa y Financiera', 'sigla' => 'JAF', 'descripcion' => 'Jefatura Administrativa y Financiera', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Presupuestos y Tesorería', 'sigla' => 'UPT', 'descripcion' => 'Unidad de Presupuestos y Tesorería', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Contabilidad', 'sigla' => 'UC', 'descripcion' => 'Unidad de Contabilidad', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Recursos Humanos', 'sigla' => 'RRHH', 'descripcion' => 'Unidad de Recursos Humanos', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Activos Fijos y Almacenes', 'sigla' => 'UAFA', 'descripcion' => 'Unidad de Activos Fijos y Almacenes', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Bienes, Servicios y Contrataciones', 'sigla' => 'UBSC', 'descripcion' => 'Unidad de Bienes, Servicios y Contrataciones', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Archivo y Documentación', 'sigla' => 'UAD', 'descripcion' => 'Unidad de Archivo y Documentación del Concejo Municipal', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Unidad de Sistemas', 'sigla' => 'US', 'descripcion' => 'Unidad de Sistemas del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Asesoría Legal', 'sigla' => 'AL', 'descripcion' => 'Asesoría Legal del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Secretarías', 'sigla' => 'SEC', 'descripcion' => 'Secretaría General del Concejo Municipal de Potosí', 'estado' => true, 'fecha_creacion' => now()],
            ['nombre' => 'Servicios Generales', 'sigla' => 'SG', 'descripcion' => 'Otros Servicios Generales Relacionados al Concejo Municipal', 'estado' => true, 'fecha_creacion' => now()],
        ];
        foreach ($unidades as $unidad) {
            DB::table('institucional.unidades')->updateOrInsert(
                ['nombre' => $unidad['nombre']], // Buscar por nombre
                [ // Datos a insertar o actualizar
                    'descripcion' => $unidad['descripcion'],
                    'sigla' => $unidad['sigla'],
                    'estado' => $unidad['estado'],
                    'fecha_creacion' => now()
                ]
            );
        }

        // Cargos
        $cargos=[
            ['nombre' => 'Presidente del Concejo', 'descripcion' => 'Presidente del Concejo Municipal de Potosi', 'estado' => true],
            ['nombre' => 'Vicepresidente del Concejo', 'descripcion' => 'Vicepresidente del Concejo Municipal del Potosí', 'estado' => true],
            ['nombre' => 'Concejal Secretaria', 'descripcion' => 'Secretaría del Concejo Municipal de Potosí', 'estado' => true],
            ['nombre' => 'Concejal', 'descripcion' => 'Representante de la ciudadanía a través de tres funciones fundamentales', 'estado' => true],
            ['nombre' => 'Asesor(a)', 'descripcion' => 'Asesor de la Comisión Asignada', 'estado' => true],
            ['nombre' => 'Jefe de Gabinete', 'descripcion' => 'Asesor del Pleno del Concejo Municipal y a requerimiento de las diferentes Comisiones del Concejo Municipa', 'estado' => true],
            ['nombre' => 'Asesor(a) Legal', 'descripcion' => 'Asesor(a) Legal del Concejo Municipal de Potosí', 'estado' => true],
            ['nombre' => 'Asistente', 'descripcion' => 'Asistente de la comisión asignada', 'estado' => true],
            ['nombre' => 'Asesor(a) Financiero', 'descripcion' => 'Asesor(a) Financiero', 'estado' => true],
            ['nombre' => 'Auxiliar', 'descripcion' => 'Auxiliar de la Unidad Asignada', 'estado' => true],
            ['nombre' => 'Secretaria I', 'descripcion' => 'Secretaria I', 'estado' => true],
            ['nombre' => 'Secretaria II', 'descripcion' => 'Secretaria II', 'estado' => true],
            ['nombre' => 'Abogado Penalista', 'descripcion' => 'Abogado Penalista', 'estado' => true],
            ['nombre' => 'Secretario de Cámara', 'descripcion' => 'Secretario de Cámara', 'estado' => true],
            ['nombre' => 'Responsable de Relaciones Publicas', 'descripcion' => 'Responsable de Relaciones Publicas', 'estado' => true],
            ['nombre' => 'Camarógrafo', 'descripcion' => 'Camarógrafo', 'estado' => true],
            ['nombre' => 'Productor', 'descripcion' => 'Productor', 'estado' => true],
            ['nombre' => 'Protocolo', 'descripcion' => 'Auxiliar de Protocolo', 'estado' => true],
            ['nombre' => 'Contador', 'descripcion' => 'Contador', 'estado' => true],
            ['nombre' => 'Responsable de Recursos Humanos', 'descripcion' => 'Responsable de Recursos Humanos', 'estado' => true],
            ['nombre' => 'Jefe Administrativo y Finaciero', 'descripcion' => 'Jefe Administrativo y Finaciero', 'estado' => true],
            ['nombre' => 'Responsable de Activos Fijos y Almacenes', 'descripcion' => 'Responsable de Activos Fijos y Almacenes', 'estado' => true],
            ['nombre' => 'Encargado de Archivos', 'descripcion' => 'Encargado de Archivos', 'estado' => true],
            ['nombre' => 'Digitalizacion', 'descripcion' => 'Auxiliar de Digitalizacion', 'estado' => true],
            ['nombre' => 'Catalogador (a)', 'descripcion' => 'Auxiliar Catalogador', 'estado' => true],
            ['nombre' => 'Responsable de Presupuestos y Tesorería', 'descripcion' => 'Responsable de Presupuestos y Tesorería', 'estado' => true],
            ['nombre' => 'Responsable de Bienes, Servicios y Contrataciones', 'descripcion' => 'Responsable de Bienes, Servicios y Contrataciones', 'estado' => true],
            ['nombre' => 'Responsable de Sistemas', 'descripcion' => 'Responsable de Sistemas', 'estado' => true],
            ['nombre' => 'Encargado de Fotocopias', 'descripcion' => 'Encargado de Fotocopias', 'estado' => true],
            ['nombre' => 'Chofer I', 'descripcion' => 'Chofer I', 'estado' => true],
            ['nombre' => 'Chofer II', 'descripcion' => 'Chofer II', 'estado' => true],
            ['nombre' => 'Chofer III', 'descripcion' => 'Chofer III', 'estado' => true],
            ['nombre' => 'Personal de Limpieza y Desinfección I', 'descripcion' => 'Personal de Limpieza y Desinfección I', 'estado' => true],
            ['nombre' => 'Personal de Limpieza y Desinfección II', 'descripcion' => 'Personal de Limpieza y Desinfección II', 'estado' => true],
        ];
        foreach ($cargos as $cargo) {
            DB::table('institucional.cargos')->updateOrInsert(
                ['nombre' => $cargo['nombre']], // Buscar por nombre
                [ // Datos a insertar o actualizar
                    'descripcion' => $cargo['descripcion'],
                    'estado' => $cargo['estado']
                ]
            );
        }

        // Funcionarios
        //DB::table('institucional.funcionarios')->insert([
        $funcionarios=[
            ['nombres' => 'German Antonio', 'apellidos' => 'Vidaurre Villanueva', 'ci' => '1234567', 'celular' => '', 'correo' => 'german.vidaurre@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 2, 'id_cargo' => 1, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Claudio German', 'apellidos' => 'Clemente Vedia', 'ci' => '1234568', 'celular' => '', 'correo' => 'claudio.clemente@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 14, 'id_cargo' => 2, 'gestion' => date('Y-m-d')],
            ['nombres' => 'María Del Carmen', 'apellidos' => 'Michel Araujo', 'ci' => '1234569', 'celular' => '', 'correo' => 'maría.michel@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 7, 'id_cargo' => 3, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Guido Armando', 'apellidos' => 'Cruz Mora', 'ci' => '12345610', 'celular' => '', 'correo' => 'guido.cruz@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 5, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Manuel Alejandro', 'apellidos' => 'Calizaya Limachi', 'ci' => '12345611', 'celular' => '', 'correo' => 'manuel.calizaya@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 8, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Eddy', 'apellidos' => 'Fernández Fuertes', 'ci' => '12345612', 'celular' => '', 'correo' => 'eddy.fernández@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 9, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Christie Mónica', 'apellidos' => 'Chacón Duran', 'ci' => '12345613', 'celular' => '', 'correo' => 'christie.chacón@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 10, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Ariel', 'apellidos' => 'Jiménez Gómez', 'ci' => '12345614', 'celular' => '', 'correo' => 'ariel.jiménez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 11, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Jacqueline Lourdes', 'apellidos' => 'Gutiérrez Carrasco', 'ci' => '12345615', 'celular' => '', 'correo' => 'jacqueline.gutiérrez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 12, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Clementina', 'apellidos' => 'Aroni Mamani', 'ci' => '12345616', 'celular' => '', 'correo' => 'clementina.aroni@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 13, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'María Lilian', 'apellidos' => 'Pinto Gutiérrez', 'ci' => '6560822', 'celular' => '', 'correo' => 'maría.pinto@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 2, 'id_cargo' => 2, 'gestion' => date('Y-m-d')],
            ['nombres' => 'German Pablo', 'apellidos' => 'Jara', 'ci' => '8656680', 'celular' => '', 'correo' => 'german.jara@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 3, 'id_cargo' => 4, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Mijael Olguin', 'apellidos' => 'Sangueza', 'ci' => '13261819', 'celular' => '', 'correo' => 'mijael.sangueza@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 4, 'id_cargo' => 6, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Rolando Bejarano', 'apellidos' => 'Machaga', 'ci' => '10513002', 'celular' => '', 'correo' => 'rolando.machaga@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 17, 'id_cargo' => 9, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Jhanira Alejandra', 'apellidos' => 'Velásquez Benavidez', 'ci' => '12558361', 'celular' => '', 'correo' => 'jhanira.velásquez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 17, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Erwin Franco', 'apellidos' => 'Choque Coronel', 'ci' => '6612371', 'celular' => '', 'correo' => 'erwin.choque@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 26, 'id_cargo' => 10, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Faviana Arleth', 'apellidos' => 'Terrazas Buergo', 'ci' => '5118983', 'celular' => '', 'correo' => 'faviana.terrazas@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 26, 'id_cargo' => 11, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Wilson', 'apellidos' => 'Mamani Mendizábal', 'ci' => '4012533', 'celular' => '', 'correo' => 'wilson.mamani@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 18, 'id_cargo' => 12, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Karina Araceli', 'apellidos' => 'Miranda Flores', 'ci' => '3968889', 'celular' => '', 'correo' => 'karina.miranda@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 27, 'id_cargo' => 14, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Elizangela', 'apellidos' => 'López Sandoval', 'ci' => '6590556', 'celular' => '', 'correo' => 'elizangela.lópez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 27, 'id_cargo' => 15, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Samuel Fernando', 'apellidos' => 'Martínez Puma', 'ci' => '6602221', 'celular' => '', 'correo' => 'samuel.martínez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 26, 'id_cargo' => 16, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Carlos', 'apellidos' => 'Pinto Barberito', 'ci' => '8010944', 'celular' => '', 'correo' => 'carlos.pinto@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 15, 'id_cargo' => 17, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Jhonny', 'apellidos' => 'Romay Aillon', 'ci' => '4016220', 'celular' => '', 'correo' => 'jhonny.romay@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 15, 'id_cargo' => 18, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Deymar Eduardo', 'apellidos' => 'Zuleta Torrico', 'ci' => '4003839', 'celular' => '', 'correo' => 'deymar.zuleta@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 16, 'id_cargo' => 19, 'gestion' => date('Y-m-d')],
            ['nombres' => 'María Paola', 'apellidos' => 'Rojas Ckacka', 'ci' => '8577459', 'celular' => '', 'correo' => 'maría.rojas@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 16, 'id_cargo' => 20, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Omar Fernando', 'apellidos' => 'Bohorquez Velasco', 'ci' => '5071887', 'celular' => '', 'correo' => 'omar.bohorquez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 16, 'id_cargo' => 21, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Delia', 'apellidos' => 'Callapa Rojas', 'ci' => '5506701', 'celular' => '', 'correo' => 'delia.callapa@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 16, 'id_cargo' => 22, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Wilson', 'apellidos' => 'Lizondo Flores', 'ci' => '5115350', 'celular' => '', 'correo' => 'wilson.lizondo@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 20, 'id_cargo' => 23, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Bonny David', 'apellidos' => 'Conde Alachi', 'ci' => '4005630', 'celular' => '', 'correo' => 'bonny.conde@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 20, 'id_cargo' => 24, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Cesar Ruben', 'apellidos' => 'Escobar Infantes', 'ci' => '6600658', 'celular' => '', 'correo' => 'cesar.escobar@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 21, 'id_cargo' => 25, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Rafael', 'apellidos' => 'Cirilo Berazain', 'ci' => '6642177', 'celular' => '', 'correo' => 'rafael.cirilo@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 21, 'id_cargo' => 26, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Rene', 'apellidos' => 'Sempertegui Rodríguez', 'ci' => '3692868', 'celular' => '', 'correo' => 'rene.sempertegui@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 18, 'id_cargo' => 27, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Anabel Leonor', 'apellidos' => 'Flores Pacara', 'ci' => '8601172', 'celular' => '', 'correo' => 'anabel.flores@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 18, 'id_cargo' => 28, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Wilber Marcial', 'apellidos' => 'Flores Chambi', 'ci' => '8654432', 'celular' => '', 'correo' => 'wilber.flores@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 22, 'id_cargo' => 29, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Edgar Carlos', 'apellidos' => 'Quiroga Anagua', 'ci' => '8572806', 'celular' => '', 'correo' => 'edgar.quiroga@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 22, 'id_cargo' => 30, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Luzmila Llanos', 'apellidos' => 'Ramírez De Magne', 'ci' => '1349549', 'celular' => '', 'correo' => 'luzmila.ramírez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 24, 'id_cargo' => 31, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Fabiola Adriana', 'apellidos' => 'Torrez Condori', 'ci' => '9248483', 'celular' => '', 'correo' => 'fabiola.torrez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 24, 'id_cargo' => 32, 'gestion' => date('Y-m-d')],
            ['nombres' => 'María Elva', 'apellidos' => 'López Chagua', 'ci' => '4015936', 'celular' => '', 'correo' => 'maría.lópez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 24, 'id_cargo' => 33, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Shirley Pamela', 'apellidos' => 'Castillo Duarte', 'ci' => '8548702', 'celular' => '', 'correo' => 'shirley.castillo@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 24, 'id_cargo' => 34, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Gunar Gonzalo', 'apellidos' => 'López Espinoza', 'ci' => '6561143', 'celular' => '', 'correo' => 'gunar.lópez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 19, 'id_cargo' => 35, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Willam', 'apellidos' => 'Canaviri Gutiérrez', 'ci' => '4597265', 'celular' => '', 'correo' => 'willam.canaviri@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 19, 'id_cargo' => 36, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Milton', 'apellidos' => 'Fuentes Apaza', 'ci' => '5526298', 'celular' => '', 'correo' => 'milton.fuentes@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 23, 'id_cargo' => 37, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Diego Armando', 'apellidos' => 'Estrada Alfaro', 'ci' => '5799023', 'celular' => '', 'correo' => 'diego.estrada@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 23, 'id_cargo' => 38, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Roger Armando', 'apellidos' => 'Ibarra Chavarria', 'ci' => '5116791', 'celular' => '', 'correo' => 'roger.ibarra@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 25, 'id_cargo' => 39, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Juan Carlos', 'apellidos' => 'Ari Candi', 'ci' => '4001977', 'celular' => '', 'correo' => 'juan.ari@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 25, 'id_cargo' => 40, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Mario Israel', 'apellidos' => 'Totola Ramírez', 'ci' => '12406008', 'celular' => '', 'correo' => 'mario.totola@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 41, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Edgar', 'apellidos' => 'Mendoza Quispe', 'ci' => '5071393', 'celular' => '', 'correo' => 'edgar.mendoza@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 42, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Raúl Huáscar', 'apellidos' => 'Torrez Miranda', 'ci' => '4002906', 'celular' => '', 'correo' => 'raúl.torrez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 43, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Policarpio', 'apellidos' => 'Fernández Santos', 'ci' => '3966490', 'celular' => '', 'correo' => 'policarpio.fernández@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 44, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Sonia', 'apellidos' => 'Rodríguez Quiroz De Ponce', 'ci' => '5071576', 'celular' => '', 'correo' => 'sonia.rodríguez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 45, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Janeth Vaneza', 'apellidos' => 'Choque Coro', 'ci' => '8578849', 'celular' => '', 'correo' => 'janeth.choque@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 28, 'id_cargo' => 46, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Ramiro Nelson', 'apellidos' => 'Quinteros Castro', 'ci' => '995345', 'celular' => '', 'correo' => 'ramiro.quinteros@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 14, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Mariela', 'apellidos' => 'Quispe Pinto', 'ci' => '8548163', 'celular' => '', 'correo' => 'mariela.quispe@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 14, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Mirian Eliana', 'apellidos' => 'Rojas Arapa', 'ci' => '4017820', 'celular' => '', 'correo' => 'mirian.rojas@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 7, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Ximena Anabel', 'apellidos' => 'Condori Vilacahua', 'ci' => '6679125', 'celular' => '', 'correo' => 'ximena.condori@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 5, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Rosmery', 'apellidos' => 'Mita Salazar', 'ci' => '6698979', 'celular' => '', 'correo' => 'rosmery.mita@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 5, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Fidel Angel', 'apellidos' => 'Cori Reynolds', 'ci' => '3717890', 'celular' => '', 'correo' => 'fidel.cori@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 8, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Rubén', 'apellidos' => 'Fernández Mamani ', 'ci' => '8573330', 'celular' => '', 'correo' => 'rubén.fernández@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 8, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Ninette Lourdes', 'apellidos' => 'Cardozo Gonzales', 'ci' => '6581109', 'celular' => '', 'correo' => 'ninette.cardozo@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 9, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Félix Carlos', 'apellidos' => 'Lima Mamani', 'ci' => '7476140', 'celular' => '', 'correo' => 'félix.lima@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 9, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Pamela Hercilia', 'apellidos' => 'Parrado Flores', 'ci' => '3971680', 'celular' => '', 'correo' => 'pamela.parrado@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 10, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Maya Ninoska', 'apellidos' => 'Lisidro Quiroz', 'ci' => '10529152', 'celular' => '', 'correo' => 'maya.lisidro@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 10, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Limber', 'apellidos' => 'Coro Callapa', 'ci' => '6650946', 'celular' => '', 'correo' => 'limber.coro@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 11, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Alexander', 'apellidos' => 'Padilla Colque', 'ci' => '9473346', 'celular' => '', 'correo' => 'alexander.padilla@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 11, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Mariela Rocío', 'apellidos' => 'Castro Buitrago', 'ci' => '6700605', 'celular' => '', 'correo' => 'mariela.castro@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 12, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Marlene Cristina', 'apellidos' => 'Ramírez Peñaranda', 'ci' => '6560509', 'celular' => '', 'correo' => 'marlene.ramírez@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 12, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Adolfo Efraín', 'apellidos' => 'Ozuna Villca', 'ci' => '5119259', 'celular' => '', 'correo' => 'adolfo.ozuna@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 13, 'id_cargo' => 8, 'gestion' => date('Y-m-d')],
            ['nombres' => 'Delia', 'apellidos' => 'Choque Choque', 'ci' => '8659243', 'celular' => '', 'correo' => 'delia.choque@cmp.gob.bo', 'estado' => true, 'fecha_registro' => now(), 'id_unidad' => 13, 'id_cargo' => 13, 'gestion' => date('Y-m-d')],
        ];

        foreach ($funcionarios as $funcionario) {
            DB::table('institucional.funcionarios')->updateOrInsert(
                [
                    'ci' => $funcionario['ci'], // Buscar por ci
                    'nombres' => $funcionario['nombres'], // Buscar por nombres
                    'apellidos' => $funcionario['apellidos'] // Buscar por apellidos
                ],
                [ // Datos a insertar o actualizar
                    'celular'        => $funcionario['celular'],
                    'correo'         => $funcionario['correo'],
                    'estado'         => $funcionario['estado'],
                    'fecha_registro' => $funcionario['fecha_registro'],
                    'id_unidad'      => $funcionario['id_unidad'],
                    'id_cargo'       => $funcionario['id_cargo'],
                    'gestion'        => $funcionario['gestion']
                ]
            );
        }

    }
}
