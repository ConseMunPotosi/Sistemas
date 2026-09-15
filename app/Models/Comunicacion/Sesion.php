<?php

namespace App\Models\Comunicacion;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'comunicacion.sesiones';

    protected $primaryKey = 'id_sesion';

    protected $fillable = [
        'numero',
        'gestion',
        'tipo_sesion',
        'titulo',
        'fecha_sesion',
        'video',
        'estado',
    ];

    protected $casts = [
        'fecha_sesion' => 'date',
        'estado' => 'boolean',
    ];
}
