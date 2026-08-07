<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo de Rol
 *
 * @property int $id_rol
 * @property string $nombre
 * @property string|null $descripcion
 * @property bool $estado
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Usuario[] $usuarios
 * @property-read \Illuminate\Database\Eloquent\Collection|Permiso[] $permisos
 */
class Rol extends Model
{
    use HasFactory;

    /* Configuración de la tabla */
    protected $table = 'seguridad.roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    /* Atributos asignables masivamente */
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    /* Casting de atributos */
    protected $casts = [
        'estado' => 'boolean',
        'id_rol' => 'integer'
    ];

    // === RELACIONES ===
    /* Relación con los usuarios */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'seguridad.usuario_roles',
            'id_rol',
            'id_usuario'
        );
    }

    /* Relación con los permisos */
    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'seguridad.rol_permisos',
            'id_rol',
            'id_permiso'
        )->withTimestamps();
    }

    // === SCOPES ===

    /* Scope para filtrar roles activos */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

    /* Scope para filtrar roles inactivos */
    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where('estado', false);
    }

    /* Scope para buscar por nombre */
    public function scopePorNombre(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', 'LIKE', "%{$nombre}%");
    }

    /* Scope para buscar por nombre exacto */
    public function scopePorNombreExacto(Builder $query, string $nombre): Builder
    {
        return $query->where('nombre', $nombre);
    }

    // === MÉTODOS DE PERMISOS ===

    /* Verifica si el rol tiene un permiso específico */
    public function hasPermission(string $modulo, string $accion): bool
    {
        return $this->permisos()
            ->where('modulo', $modulo)
            ->where('accion', $accion)
            ->exists();
    }

    /* Verifica si el rol tiene todos los permisos de una lista */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $modulo => $acciones) {
            foreach ($acciones as $accion) {
                if (!$this->hasPermission($modulo, $accion)) {
                    return false;
                }
            }
        }
        return true;
    }

    /* Verifica si el rol tiene alguno de los permisos de una lista */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $modulo => $acciones) {
            foreach ($acciones as $accion) {
                if ($this->hasPermission($modulo, $accion)) {
                    return true;
                }
            }
        }
        return false;
    }

    /* Obtiene todos los permisos del rol agrupados por módulo */
    public function getPermissions(): array
    {
        $permisos = [];
        foreach ($this->permisos as $permiso) {
            $permisos[$permiso->modulo][] = $permiso->accion;
        }

        // Eliminar duplicados
        foreach ($permisos as $modulo => $acciones) {
            $permisos[$modulo] = array_unique($acciones);
        }

        return $permisos;
    }

    /* Obtiene todos los permisos como array plano */
    public function getPermissionsFlat(): array
    {
        return $this->permisos->pluck('nombre')->toArray();
    }

    /* Obtiene los módulos a los que tiene acceso */
    public function getAccessibleModules(): array
    {
        return array_keys($this->getPermissions());
    }

    // === MÉTODOS DE ASIGNACIÓN ===

    /* Asigna permisos al rol */
    public function assignPermissions(array $permisoIds): void
    {
        $this->permisos()->sync($permisoIds);
    }

    /* Asigna un permiso específico */
    public function assignPermission(int $permisoId): void
    {
        if (!$this->permisos()->where('id_permiso', $permisoId)->exists()) {
            $this->permisos()->attach($permisoId);
        }
    }

    /* Remueve un permiso del rol */
    public function removePermission(int $permisoId): void
    {
        $this->permisos()->detach($permisoId);
    }

    /* Sincroniza permisos (reemplaza los existentes) */
    public function syncPermissions(array $permisoIds): void
    {
        $this->permisos()->sync($permisoIds);
    }

    // === MÉTODOS DE UTILIDAD ===

    /* Verifica si el rol es administrador */
    public function isAdmin(): bool
    {
        return $this->nombre === 'Administrador';
    }

    /* Activa el rol */
    public function activate(): void
    {
        $this->estado = true;
        $this->save();
    }

    /* Desactiva el rol */
    public function deactivate(): void
    {
        $this->estado = false;
        $this->save();
    }

    /* Cambia el estado del rol */
    public function toggleStatus(): void
    {
        $this->estado = !$this->estado;
        $this->save();
    }

    /* Obtiene el estado como texto */
    public function getEstadoTextoAttribute(): string
    {
        return $this->estado ? 'Activo' : 'Inactivo';
    }

    /* Obtiene el número de usuarios con este rol */
    public function getUsuariosCountAttribute(): int
    {
        return $this->usuarios()->count();
    }

    /* Obtiene el número de permisos del rol */
    public function getPermisosCountAttribute(): int
    {
        return $this->permisos()->count();
    }

    // === VALIDACIÓN ===

    /* Verifica si el rol puede ser eliminado (no tiene usuarios asignados)      */
    public function canBeDeleted(): bool
    {
        return $this->usuarios()->count() === 0;
    }

    /* Verifica si el rol es sistema (no se puede eliminar) */
    public function isSystemRole(): bool
    {
        return in_array($this->nombre, ['Administrador', 'Sistema']);
    }
}
