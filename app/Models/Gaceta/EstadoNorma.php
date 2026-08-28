<?php

namespace App\Models\Gaceta;

use Illuminate\Database\Eloquent\Model;

class EstadoNorma extends Model
{
    protected $table = 'gaceta.estados_norma';
    protected $primaryKey = 'id_estado_norma';
    public $timestamps = false;

    protected $fillable = ['nombre_estado', 'descripcion', 'estado'];
}
