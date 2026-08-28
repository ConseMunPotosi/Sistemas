<?php

namespace App\Models\Gaceta;

use Illuminate\Database\Eloquent\Model;

class ArchivoNorma extends Model
{
    protected $table = 'gaceta.archivosNorma';
    protected $primaryKey = 'id_archivo';
    public $timestamps = true; // Usa created_at y updated_at

    protected $fillable = [
        'id_norma',
        'nombre_archivo',
        'ruta_archivo',
        'extension',
        'peso',
        'estado',
        'tipo_mime',
        'subidor_por',
        'fecha_subida'
    ];

    public function norma()
    {
        return $this->belongsTo(Norma::class, 'id_norma');
    }
}
