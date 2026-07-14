<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institucional\Unidad;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'documental.documentos';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_documento',
        //'codigo',
        'cite',  //code
        'referencia',
        'descripcion', //detail
        'fecha_documento', 
        'prioridad',
        'reservado',
        //'estado',
        'id_tipo_documento', // procedure_type_id
        'id_estado', // archived o pending
        'id_unidad_origen', // area_id
        'id_usuario_creador', //user_id
        'fecha_registro' // create_at
    ];

    protected $casts = [
        'reservado' => 'boolean',
        //'estado' => 'boolean',
        'fecha_documento' => 'date',
        'fecha_registro' => 'datetime',
        'id_documento' => 'id_documento'
    ];

    // Relaciones
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento', 'id_tipo_documento');
    }

    public function estadoDocumento(): BelongsTo
    {
        return $this->belongsTo(EstadoDocumento::class, 'id_estado', 'id_estado');
    }

    public function unidadOrigen(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidad_origen', 'id_unidad');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_creador', 'id_usuario');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoDocumento::class, 'id_documento', 'id_documento');
    }

    public function derivaciones(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_documento', 'id_documento');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialDocumento::class, 'id_documento', 'id_documento');
    }
}
