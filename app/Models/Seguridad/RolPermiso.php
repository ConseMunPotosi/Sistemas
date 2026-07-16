<?php

namespace App\Models\Seguridad;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolPermiso extends Pivot
{
    //protected $connection = 'pgsql';
    protected $table = 'seguridad.rol_permisos';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_permiso'
    ];
}
