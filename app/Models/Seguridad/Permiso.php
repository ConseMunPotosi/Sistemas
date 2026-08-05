<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo de Permiso
 *
 * @property int $id_permiso
 * @property string $modulo
 * @property string $accion
 * @property string|null $descripcion
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Rol[] $roles
 */
class Permiso extends Model
{
    use HasFactory;

    protected $table = 'seguridad.permisos';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'modulo',
        'accion',
        'descripcion'
    ];

    protected $casts = [
        'id_permiso' => 'integer'
    ];

    /* Relación con los roles */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'seguridad.rol_permisos',
            'id_permiso',
            'id_rol'
        );
    }

    // === SCOPES ===

    /* Scope para filtrar por módulo */
    public function scopePorModulo(Builder $query, string $modulo): Builder
    {
        return $query->where('modulo', $modulo);
    }

    /* Scope para filtrar por acción */
    public function scopePorAccion(Builder $query, string $accion): Builder
    {
        return $query->where('accion', $accion);
    }

    /* Scope para buscar por módulo y acción */
    public function scopePorModuloYAccion(Builder $query, string $modulo, string $accion): Builder
    {
        return $query->where('modulo', $modulo)
                    ->where('accion', $accion);
    }

    // === MÉTODOS DE UTILIDAD ===

    /* Obtener el nombre completo del permiso (módulo:acción) */
    public function getNombreCompletoAttribute(): string
    {
        return $this->modulo . ':' . $this->accion;
    }

    /* Verificar si el permiso tiene un rol específico */
    public function hasRol(string $rolNombre): bool
    {
        return $this->roles()->where('nombre', $rolNombre)->exists();
    }
}
