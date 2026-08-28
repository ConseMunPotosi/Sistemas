<?php

namespace App\Models\Gaceta;

use Illuminate\Database\Eloquent\Model;

class TipoNorma extends Model
{
    protected $table = 'gaceta.tipos_norma';
    protected $primaryKey = 'id_tipo_norma';
    public $timestamps = false;

    protected $fillable = ['nombre_tipo', 'descripcion', 'estado'];
}
