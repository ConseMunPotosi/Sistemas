<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tipos de Documento
        //DB::table('documental.tipos_documento')->insert([
        $tipos_documento = [
            //Documentos Externos
            ['nombre' => 'Solicitud de Audiencia', 'descripcion' => 'Solicitud de Audiencia', 'estado' => true],
            ['nombre' => 'Solicitud de reunión', 'descripcion' => 'Solicitud de reunión', 'estado' => true],
            ['nombre' => 'Solicitud de informe', 'descripcion' => 'Solicitud de informe', 'estado' => true],
            ['nombre' => 'Solicitud de inspección', 'descripcion' => 'Solicitud de inspeccion', 'estado' => true],
            ['nombre' => 'Solicitud de mediación', 'descripcion' => 'Solicitud de mediación', 'estado' => true],
            ['nombre' => 'Solicitud formal', 'descripcion' => 'Solicitud formal', 'estado' => true],
            //Documentacion Tecnica
            ['nombre' => 'Informe Técnico', 'descripcion' => 'Informe Técnico', 'estado' => true],
            ['nombre' => 'Informe Legal', 'descripcion' => 'Informe Legal', 'estado' => true],
            ['nombre' => 'Informe Financiero', 'descripcion' => 'Informe Financiero', 'estado' => true],
            ['nombre' => 'Informe Económico', 'descripcion' => 'Informe Económico', 'estado' => true],
            ['nombre' => 'Informe de Supervisión', 'descripcion' => 'Informe de Supervisión', 'estado' => true],
            ['nombre' => 'Informe de Inspección', 'descripcion' => 'Informe de Inspección', 'estado' => true],
            ['nombre' => 'Informe de Comisión', 'descripcion' => 'Informe de Comisión', 'estado' => true],
            ['nombre' => 'Informe de Actividades', 'descripcion' => 'Informe de Actividades', 'estado' => true],
            ['nombre' => 'Informe', 'descripcion' => 'Informe', 'estado' => true],
            //Documentos Internos
            ['nombre' => 'Nota', 'descripcion' => 'Nota', 'estado' => true],
            ['nombre' => 'Memorándum', 'descripcion' => 'Memorándum', 'estado' => true],
            ['nombre' => 'Circular', 'descripcion' => 'Circular', 'estado' => true],
            ['nombre' => 'Instructivo', 'descripcion' => 'Instructivo', 'estado' => true],
            ['nombre' => 'Comunicación Interna', 'descripcion' => 'Comunicación Interna', 'estado' => true],
            //Documentos Legislativos
            ['nombre' => 'Proyecto de ley Municipal', 'descripcion' => 'Proyecto de ley Municipal', 'estado' => true],
            ['nombre' => 'Ley Municipal', 'descripcion' => 'Ley Municipal', 'estado' => true],
            ['nombre' => 'Ordenanza Municipal', 'descripcion' => 'Ordenanza Municipal', 'estado' => true],
            ['nombre' => 'Resolución Aministrativa', 'descripcion' => 'Resolución Aministrativa', 'estado' => true],
        ];
        foreach ($tipos_documento as $tipo_documento) {
            DB::table('documental.tipos_documento')->updateOrInsert(
                ['nombre' => $tipo_documento['nombre']], // Buscar por nombre
                [ // Datos a insertar o actualizar
                    'descripcion' => $tipo_documento['descripcion'],
                    'estado' => $tipo_documento['estado']
                ]
            );
        }
        // Estados de Documento
        //DB::table('documental.estados_documento')->insert([
        $estados_documento = [
            ['nombre' => 'Borrador', 'descripcion' => 'Documento en edición'],
            ['nombre' => 'Pendiente', 'descripcion' => 'Esperando aprobación'],
            ['nombre' => 'Aprobado', 'descripcion' => 'Documento aprobado'],
            ['nombre' => 'Rechazado', 'descripcion' => 'Documento rechazado'],
            ['nombre' => 'Archivado', 'descripcion' => 'Documento finalizado'],
        ];
        foreach ($estados_documento as $estado_documento) {
            DB::table('documental.estados_documento')->updateOrInsert(
                ['nombre' => $estado_documento['nombre']], // Buscar por nombre
                [ // Datos a insertar o actualizar
                    'descripcion' => $estado_documento['descripcion']
                ]
            );
        }

        // Documentos
        //DB::table('documental.documentos')->insert([
        $documentos = [
            [
                'cite' => 'CITE-001/2026',
                'referencia' => 'Solicitud de Audiencia',
                'descripcion' => 'Coordinacion Interinstitucional',
                'fecha_documento' => '2026-06-15',
                'prioridad' => 'Alta',
                'reservado' => false,
                'id_tipo_documento' => 1,
                'id_estado' => 3,
                'id_unidad_origen' => 1,
                'id_usuario_creador' => 1,
                'fecha_registro' => now()
            ],
            [
                'cite' => 'CITE-002/2026',
                'referencia' => 'Informe Tecnico',
                'descripcion' => 'Informe de avance del proyecto de implementación',
                'fecha_documento' => '2026-06-20',
                'prioridad' => 'Media',
                'reservado' => false,
                'id_tipo_documento' => 7,
                'id_estado' => 2,
                'id_unidad_origen' => 2,
                'id_usuario_creador' => 2,
                'fecha_registro' => now()
            ],
        ];
        foreach ($documentos as $documento) {
            DB::table('documental.documentos')->updateOrInsert(
                ['cite' => $documento['cite']], // Buscar por cite
                [ // Datos a insertar o actualizar
                    'referencia' => $documento['referencia'],
                    'descripcion' => $documento['descripcion'],
                    'fecha_documento' => $documento['fecha_documento'],
                    'prioridad' => $documento['prioridad'],
                    'reservado' => $documento['reservado'],
                    'id_tipo_documento' => $documento['id_tipo_documento'],
                    'id_estado' => $documento['id_estado'],
                    'id_unidad_origen' => $documento['id_unidad_origen'],
                    'id_usuario_creador' => $documento['id_usuario_creador'],
                    'fecha_registro' => $documento['fecha_registro']
                ]
            );
        }
        // Derivaciones
        //DB::table('documental.derivaciones')->insert([
        $derivaciones = [
            [
                'id_documento' => 1,
                'id_unidad_origen' => 1,
                'id_unidad_destino' => 2,
                'id_usuario_envia' => 1,
                'id_usuario_recibe' => 2,
                'proveido' => 'Revisar y dar respuesta',
                'fecha_envio' => now(),
                'fecha_recepcion' => null,
                'recibido' => false,
                'estado_derivacion' => 'Pendiente'
            ],
        ];

        foreach ($derivaciones as $derivacion) {
            DB::table('documental.derivaciones')->updateOrInsert(
                [
                    'id_unidad_origen' => $derivacion['id_unidad_origen'],
                    'id_unidad_destino' => $derivacion['id_unidad_destino'],
                    'id_usuario_envia' => $derivacion['id_usuario_envia'],
                    'id_usuario_recibe' => $derivacion['id_usuario_recibe'],
                ], // Buscar por derivacion
                
                [ // Datos a insertar o actualizar
                    'id_documento' => $derivacion['id_documento'],
                    'id_unidad_origen' => $derivacion['id_unidad_origen'],
                    'id_unidad_destino' => $derivacion['id_unidad_destino'],
                    'id_usuario_envia' => $derivacion['id_usuario_envia'],
                    'id_usuario_recibe' => $derivacion['id_usuario_recibe'],
                    'fecha_envio' => $derivacion['fecha_envio'],
                    'fecha_recepcion' => $derivacion['fecha_recepcion'],
                    'recibido' => $derivacion['recibido'],
                    'estado_derivacion' => $derivacion['estado_derivacion']
                ]
            );
        }

        // Historial de documentos
        /*DB::table('documental.historial_documento')->insert([
            [
                'id_historial' => 1,
                'id_documento' => 1,
                'id_usuario' => 1,
                'accion' => 'Creación',
                'detalle' => 'Documento creado por Juan Pérez',
                'fecha_accion' => now()
            ],
            [
                'id_historial' => 2,
                'id_documento' => 1,
                'id_usuario' => 2,
                'accion' => 'Derivación',
                'detalle' => 'Documento derivado a María García',
                'fecha_accion' => now()
            ],
        ]);*/

    }
}
