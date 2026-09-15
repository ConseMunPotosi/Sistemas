<?php

namespace App\Models\Institucional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Concejal extends Model
{
    use HasFactory;

    protected $table = 'institucional.concejales';

    protected $primaryKey = 'id_concejal';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'cargo',
        'comision',
        'distritos',
        'descripcion',
        'imagen',
        'estado',
        'fecha_registro'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_registro' => 'datetime',
        'id_concejal' => 'integer'
    ];
}
