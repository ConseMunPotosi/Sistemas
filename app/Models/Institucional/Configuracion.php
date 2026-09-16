<?php

namespace App\Models\Institucional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'institucional.configuracion';

    protected $primaryKey = 'id_configuracion';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'estado',
        'fecha_registro',
        'fecha_actualizacion'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_registro' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'id_configuracion' => 'integer'
    ];
}
