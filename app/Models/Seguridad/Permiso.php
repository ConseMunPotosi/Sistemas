<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'seguridad.permisos';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_permiso',
        'modulo',
        'accion',
        'descripcion',
        'id_permiso' => 'integer' //asegura que se trata como entero
    ];

    // Relaciones
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'seguridad.rol_permisos',
            'id_permiso',
            'id_rol'
        );
    }
}
