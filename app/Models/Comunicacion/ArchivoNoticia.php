<?php

namespace App\Models\Comunicacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoNoticia extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'comunicacion.archivos_noticia';
    protected $primaryKey = 'id_archivo';
    public $timestamps = false;

    protected $fillable = [
        'id_archivo',
        'id_noticia',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'extension',
        'peso_bytes',
        'subido_por',
        'fecha_subida',
    ];

    protected $casts = [
        'peso_bytes' => 'integer',
        'estado' => 'boolean',
        'fecha_subida' => 'datetime'
    ];

    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class, 'id_noticia', 'id_noticia');
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por', 'id_usuario');
    }
}
