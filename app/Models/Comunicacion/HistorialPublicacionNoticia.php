<?php

namespace App\Models\Comunicacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialPublicacionNoticia extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'comunicacion.historial_publicacion_noticia';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_historial',
        'id_noticia',
        'id_usuario',
        'accion',
        'detalle',
        'enlace_facebook',
        'fecha_accion'
    ];

    protected $casts = [
        'fecha_accion' => 'datetime'
    ];

    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class, 'id_noticia', 'id_noticia');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
