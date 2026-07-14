<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use App\Models\Institucional\Unidad;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Derivacion extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'documental.derivaciones';
    protected $primaryKey = 'id_derivacion';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_derivacion',
        'id_documento', //procedure_id
        'id_unidad_origen', //from_area
        'id_unidad_destino', // to_area
        'id_usuario_envia', // user_id_send
        'id_usuario_recibe', // user_id_receive
        'proveido', // Es la instruccion del documento derivado
        'fecha_envio', // date_send
        'fecha_recepcion', // date_received
        'recibido', // Falso o Verdadero
        'estado_derivacion' // Pendiente o 
    ];

    protected $casts = [
        'recibido' => 'boolean',
        'fecha_envio' => 'datetime',
        'fecha_recepcion' => 'datetime',
        'id_derivacion' => 'id_derivacion'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    public function unidadOrigen(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidad_origen', 'id_unidad');
    }

    public function unidadDestino(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidad_destino', 'id_unidad');
    }

    public function usuarioEnvia(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_envia', 'id_usuario');
    }

    public function usuarioRecibe(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_recibe', 'id_usuario');
    }
}
