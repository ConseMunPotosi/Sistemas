<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'seguridad.roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_rol',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_rol' => 'integer' //asegura que se trata como entero
    ];

    // Relaciones
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'seguridad.usuario_roles',
            'id_rol',
            'id_usuario'
        );
    }

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'seguridad.rol_permisos',
            'id_rol',
            'id_permiso'
        );
    }
}
