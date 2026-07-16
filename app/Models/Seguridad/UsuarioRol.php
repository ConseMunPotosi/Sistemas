<?php

namespace App\Models\Seguridad;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioRol extends Pivot
{
    //protected $connection = 'pgsql';
    protected $table = 'seguridad.usuario_roles';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_rol'
    ];
}
