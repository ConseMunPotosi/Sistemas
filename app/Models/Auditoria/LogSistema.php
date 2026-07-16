<?php

namespace App\Models\Auditoria;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogSistema extends Model
{
    use HasFactory;

    protected $table = 'auditoria.logs_sistema';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_log',
        'id_usuario',
        'tabla_afectada',
        'accion',
        'descripcion',
        'ip_usuario',
        'fecha_log'
    ];

    protected $casts = [
        'fecha_log' => 'datetime'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
