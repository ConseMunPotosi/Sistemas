<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Auditoria{
/**
 * @property int $id_log
 * @property int $id_usuario
 * @property string|null $tabla_afectada
 * @property string $accion
 * @property string|null $descripcion
 * @property string|null $ip_usuario
 * @property \Illuminate\Support\Carbon|null $fecha_log
 * @property-read \App\Models\Seguridad\Usuario $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereFechaLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereIdLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereIpUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogSistema whereTablaAfectada($value)
 */
	class LogSistema extends \Eloquent {}
}

namespace App\Models\Comunicacion{
/**
 * @property int $id_archivo
 * @property int $id_noticia
 * @property string $nombre_archivo
 * @property string $ruta_archivo
 * @property string|null $tipo_mime
 * @property string|null $extension
 * @property int|null $peso_bytes
 * @property int $subido_por
 * @property \Illuminate\Support\Carbon|null $fecha_subida
 * @property bool $estado
 * @property-read \App\Models\Comunicacion\Noticia $noticia
 * @property-read \App\Models\Seguridad\Usuario $subidoPor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereFechaSubida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereIdArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereIdNoticia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereNombreArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia wherePesoBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereSubidoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoNoticia whereTipoMime($value)
 */
	class ArchivoNoticia extends \Eloquent {}
}

namespace App\Models\Comunicacion{
/**
 * @property int $id_categoria
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $estado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comunicacion\Noticia> $noticias
 * @property-read int|null $noticias_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriaNoticia whereNombre($value)
 */
	class CategoriaNoticia extends \Eloquent {}
}

namespace App\Models\Comunicacion{
/**
 * @property int $id_historial
 * @property int $id_noticia
 * @property int $id_usuario
 * @property string $accion
 * @property string|null $detalle
 * @property string|null $enlace_facebook
 * @property \Illuminate\Support\Carbon|null $fecha_accion
 * @property-read \App\Models\Comunicacion\Noticia $noticia
 * @property-read \App\Models\Seguridad\Usuario $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereEnlaceFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereFechaAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereIdHistorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereIdNoticia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialPublicacionNoticia whereIdUsuario($value)
 */
	class HistorialPublicacionNoticia extends \Eloquent {}
}

namespace App\Models\Comunicacion{
/**
 * @property int $id_noticia
 * @property string|null $titulo
 * @property string|null $resumen
 * @property string|null $contenido
 * @property string|null $imagen_portada
 * @property int $id_categoria
 * @property int $id_usuario_creador
 * @property string|null $estado_publicacion
 * @property \Illuminate\Support\Carbon|null $fecha_creacion
 * @property \Illuminate\Support\Carbon|null $fecha_publicacion
 * @property bool $publicado_web
 * @property bool $publicado_facebook
 * @property string|null $enlace_facebook
 * @property string|null $facebook_post_id
 * @property bool $estado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comunicacion\ArchivoNoticia> $archivos
 * @property-read int|null $archivos_count
 * @property-read \App\Models\Comunicacion\CategoriaNoticia $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comunicacion\HistorialPublicacionNoticia> $historialPublicacion
 * @property-read int|null $historial_publicacion_count
 * @property-read \App\Models\Seguridad\Usuario $usuarioCreador
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereContenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereEnlaceFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereEstadoPublicacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereFacebookPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereFechaCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereFechaPublicacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereIdCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereIdNoticia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereIdUsuarioCreador($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereImagenPortada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia wherePublicadoFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia wherePublicadoWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereResumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Noticia whereTitulo($value)
 */
	class Noticia extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property int $id_archivo
 * @property int $id_documento
 * @property string $nombre_archivo
 * @property string $ruta_archivo
 * @property string|null $tipo_mime
 * @property string|null $extension
 * @property int|null $peso_bytes
 * @property int $version
 * @property int $subido_por
 * @property \Illuminate\Support\Carbon|null $fecha_subida
 * @property bool $estado
 * @property-read \App\Models\Documental\Documento $documento
 * @property-read \App\Models\Seguridad\Usuario $subidoPor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereFechaSubida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereIdArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereIdDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereNombreArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento wherePesoBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereRutaArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereSubidoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereTipoMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoDocumento whereVersion($value)
 */
	class ArchivoDocumento extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property int $id_derivaciones
 * @property int $id_documento
 * @property int $id_unidad_origen
 * @property int $id_unidad_destino
 * @property int $id_usuario_envia
 * @property int $id_usuario_recibe
 * @property string|null $proveido
 * @property \Illuminate\Support\Carbon|null $fecha_envio
 * @property \Illuminate\Support\Carbon|null $fecha_recepcion
 * @property bool $recibido
 * @property string|null $estado_derivacion
 * @property mixed $id_derivacion
 * @property-read \App\Models\Documental\Documento $documento
 * @property-read \App\Models\Institucional\Unidad $unidadDestino
 * @property-read \App\Models\Institucional\Unidad $unidadOrigen
 * @property-read \App\Models\Seguridad\Usuario $usuarioEnvia
 * @property-read \App\Models\Seguridad\Usuario $usuarioRecibe
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereEstadoDerivacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereFechaEnvio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereFechaRecepcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdDerivaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdUnidadDestino($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdUnidadOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdUsuarioEnvia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereIdUsuarioRecibe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereProveido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Derivacion whereRecibido($value)
 */
	class Derivacion extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property mixed $id_documento
 * @property string|null $cite
 * @property string|null $referencia
 * @property string|null $descripcion
 * @property \Illuminate\Support\Carbon|null $fecha_documento
 * @property string|null $prioridad
 * @property bool $reservado
 * @property int $id_tipo_documento
 * @property int $id_estado
 * @property int $id_unidad_origen
 * @property int $id_usuario_creador
 * @property \Illuminate\Support\Carbon|null $fecha_registro
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\ArchivoDocumento> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Derivacion> $derivaciones
 * @property-read int|null $derivaciones_count
 * @property-read \App\Models\Documental\EstadoDocumento $estadoDocumento
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\HistorialDocumento> $historial
 * @property-read int|null $historial_count
 * @property-read \App\Models\Documental\TipoDocumento $tipoDocumento
 * @property-read \App\Models\Institucional\Unidad $unidadOrigen
 * @property-read \App\Models\Seguridad\Usuario $usuarioCreador
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereCite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereFechaDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereFechaRegistro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereIdDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereIdTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereIdUnidadOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereIdUsuarioCreador($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento wherePrioridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documento whereReservado($value)
 */
	class Documento extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property int $id_estado
 * @property string $nombre
 * @property string|null $descripcion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Documento> $documentos
 * @property-read int|null $documentos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento whereIdEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EstadoDocumento whereNombre($value)
 */
	class EstadoDocumento extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property int $id_historial
 * @property int $id_documento
 * @property int $id_usuario
 * @property string $accion
 * @property string|null $detalle
 * @property \Illuminate\Support\Carbon|null $fecha_accion
 * @property-read \App\Models\Documental\Documento $documento
 * @property-read \App\Models\Seguridad\Usuario $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereFechaAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereIdDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereIdHistorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HistorialDocumento whereIdUsuario($value)
 */
	class HistorialDocumento extends \Eloquent {}
}

namespace App\Models\Documental{
/**
 * @property int $id_tipo_documento
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $estado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Documento> $documentos
 * @property-read int|null $documentos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento whereIdTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TipoDocumento whereNombre($value)
 */
	class TipoDocumento extends \Eloquent {}
}

namespace App\Models\Institucional{
/**
 * @property int $id_cargo
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $estado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Institucional\Funcionario> $funcionarios
 * @property-read int|null $funcionarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereIdCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cargo whereNombre($value)
 */
	class Cargo extends \Eloquent {}
}

namespace App\Models\Institucional{
/**
 * @property int $id_funcionario
 * @property string $nombres
 * @property string $apellidos
 * @property string $ci
 * @property string $sexo
 * @property string|null $celular
 * @property string|null $correo
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $fecha_registro
 * @property int $id_unidad
 * @property int $id_cargo
 * @property \Illuminate\Support\Carbon|null $gestion
 * @property-read \App\Models\Institucional\Cargo $cargo
 * @property-read \App\Models\Institucional\Unidad $unidad
 * @property-read \App\Models\Seguridad\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereFechaRegistro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereGestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereIdCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereIdFuncionario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereIdUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Funcionario whereSexo($value)
 */
	class Funcionario extends \Eloquent {}
}

namespace App\Models\Institucional{
/**
 * @property int $id_unidad
 * @property string $nombre
 * @property string|null $sigla
 * @property string|null $descripcion
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $fecha_creacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Derivacion> $derivacionesDestino
 * @property-read int|null $derivaciones_destino_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Derivacion> $derivacionesOrigen
 * @property-read int|null $derivaciones_origen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Documental\Documento> $documentosOrigen
 * @property-read int|null $documentos_origen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Institucional\Funcionario> $funcionarios
 * @property-read int|null $funcionarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereFechaCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereIdUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unidad whereSigla($value)
 */
	class Unidad extends \Eloquent {}
}

namespace App\Models\Seguridad{
/**
 * Modelo de Permiso
 *
 * @property int $id_permiso
 * @property string $modulo
 * @property string $accion
 * @property string|null $descripcion
 * @property-read \Illuminate\Database\Eloquent\Collection|Rol[] $roles
 * @property string $fecha_creacion
 * @property string|null $fecha_actualizacion
 * @property-read string $nombre_completo
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso porAccion(string $accion)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso porModulo(string $modulo)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso porModuloYAccion(string $modulo, string $accion)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereFechaActualizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereFechaCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereIdPermiso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permiso whereModulo($value)
 */
	class Permiso extends \Eloquent {}
}

namespace App\Models\Seguridad{
/**
 * Modelo de Rol
 *
 * @property int $id_rol
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $estado
 * @property-read \Illuminate\Database\Eloquent\Collection|Usuario[] $usuarios
 * @property-read \Illuminate\Database\Eloquent\Collection|Permiso[] $permisos
 * @property string $fecha_creacion
 * @property string|null $fecha_actualizacion
 * @property-read string $estado_texto
 * @property-read int|null $permisos_count
 * @property-read int|null $usuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol activos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol inactivos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol porNombre(string $nombre)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol porNombreExacto(string $nombre)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereFechaActualizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereFechaCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereNombre($value)
 */
	class Rol extends \Eloquent {}
}

namespace App\Models\Seguridad{
/**
 * @property int $id_rol
 * @property int $id_permiso
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso whereIdPermiso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolPermiso whereUpdatedAt($value)
 */
	class RolPermiso extends \Eloquent {}
}

namespace App\Models\Seguridad{
/**
 * Modelo de Usuario
 *
 * @property int $id_usuario
 * @property int $id_funcionario
 * @property string $usuario
 * @property string $password_hash
 * @property bool $activo
 * @property int $intentos_fallidos
 * @property \DateTime $ultimo_acceso
 * @property \DateTime $fecha_creacion
 * @property \DateTime|null $fecha_actualizacion
 * @property string|null $remember_token
 * @property-read Funcionario $funcionario
 * @property-read \Illuminate\Database\Eloquent\Collection|Rol[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|Documento[] $documentosCreados
 * @property-read \Illuminate\Database\Eloquent\Collection|ArchivoDocumento[] $archivosSubidos
 * @property-read \Illuminate\Database\Eloquent\Collection|Derivacion[] $derivacionesEnviadas
 * @property-read \Illuminate\Database\Eloquent\Collection|Derivacion[] $derivacionesRecibidas
 * @property-read \Illuminate\Database\Eloquent\Collection|HistorialDocumento[] $historialDocumentos
 * @property-read \Illuminate\Database\Eloquent\Collection|LogSistema[] $logsSistema
 * @property-read \Illuminate\Database\Eloquent\Collection|Noticia[] $noticiasCreadas
 * @property-read int|null $archivos_subidos_count
 * @property-read int|null $derivaciones_enviadas_count
 * @property-read int|null $derivaciones_recibidas_count
 * @property-read int|null $documentos_creados_count
 * @property-read string $display_name
 * @property-read string $estado_bloqueo
 * @property-read string $estado_texto
 * @property-read string $nombre_completo
 * @property-read array $permissions_array
 * @property-read array $roles_array
 * @property-read int|null $historial_documentos_count
 * @property-read int|null $logs_sistema_count
 * @property-read int|null $noticias_creadas_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario activos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario bloqueados()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario conIntentosFallidos(int $min = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario conRol(string $rolNombre)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario porCiFuncionario(string $ci)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario porUsuario(string $usuario)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereFechaActualizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereFechaCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdFuncionario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIntentosFallidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario wherePasswordHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereUltimoAcceso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereUsuario($value)
 */
	class Usuario extends \Eloquent {}
}

namespace App\Models\Seguridad{
/**
 * @property int $id_usuario
 * @property int $id_rol
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdUsuario($value)
 */
	class UsuarioRol extends \Eloquent {}
}

