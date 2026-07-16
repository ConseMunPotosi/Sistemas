<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoDocumento extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'documental.archivos_documento';
    protected $primaryKey = 'id_archivo';
    public $timestamps = false;

    protected $fillable = [
        'id_archivo',
        'id_documento',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'extension',
        'peso_bytes',
        'version',
        'subido_por',
        'fecha_subida',
        'estado'
    ];

    protected $casts = [
        'peso_bytes' => 'integer',
        'version' => 'integer',
        'estado' => 'boolean',
        'fecha_subida' => 'datetime'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por', 'id_usuario');
    }
}
