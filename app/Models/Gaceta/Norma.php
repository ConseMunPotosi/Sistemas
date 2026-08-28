<?php

namespace App\Models\Gaceta;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Norma extends Model
{
    protected $table = 'gaceta.normas';
    protected $primaryKey = 'id_norma';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_norma', 'id_estado_norma', 'id_usuario_creacion',
        'id_norm_actualizacion', 'id_usuario_actualizacion',
        'tipo_norma', 'numero', 'gestion', 'titulo', 'desripcion',
        'fecha_publicacion', 'estado_norma', 'fecha_registro'
    ];

    public function tipo() : BelongsTo
    {
        return $this->belongsTo(TipoNorma::class, 'id_tipo_norma');
    }

    public function estado() : BelongsTo
    {
        return $this->belongsTo(EstadoNorma::class, 'id_estado_norma');
    }
    public function archivos()
    {
        return $this->hasMany(ArchivoNorma::class, 'id_norma');
    }
}
