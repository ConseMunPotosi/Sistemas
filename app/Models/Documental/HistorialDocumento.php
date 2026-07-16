<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialDocumento extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'documental.historial_documento';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_historial',
        'id_documento',
        'id_usuario',
        'accion',
        'detalle',
        'fecha_accion'
    ];

    protected $casts = [
        'fecha_accion' => 'datetime'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
