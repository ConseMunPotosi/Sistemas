<?php

namespace App\Models\Seguridad;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Institucional\Funcionario;
use App\Models\Documental\Documento;
use App\Models\Auditoria\LogSistema;
use App\Models\Documental\ArchivoDocumento;
use App\Models\Documental\Derivacion;
use App\Models\Documental\HistorialDocumento;
use App\Models\Comunicacion\Noticia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo de Usuario
 *
 * @property int $id_usuario
 * @property int $id_funcionario
 * @property string $usuario
 * @property string $password_hash
 * @property bool $activo
 * @property int $intentos_fallidos
 * @property \DateTime $ultimo_acceso
 * @property \DateTime $fecha_creacion
 * @property \DateTime|null $fecha_actualizacion
 * @property string|null $remember_token
 *
 * @property-read Funcionario $funcionario
 * @property-read \Illuminate\Database\Eloquent\Collection|Rol[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|Documento[] $documentosCreados
 * @property-read \Illuminate\Database\Eloquent\Collection|ArchivoDocumento[] $archivosSubidos
 * @property-read \Illuminate\Database\Eloquent\Collection|Derivacion[] $derivacionesEnviadas
 * @property-read \Illuminate\Database\Eloquent\Collection|Derivacion[] $derivacionesRecibidas
 * @property-read \Illuminate\Database\Eloquent\Collection|HistorialDocumento[] $historialDocumentos
 * @property-read \Illuminate\Database\Eloquent\Collection|LogSistema[] $logsSistema
 * @property-read \Illuminate\Database\Eloquent\Collection|Noticia[] $noticiasCreadas
 */

class Usuario extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /** Configuración de la tabla */
    protected $table = 'seguridad.usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    /* Atributos asignables masivamente */
    protected $fillable = [
        'id_funcionario',
        'usuario',
        'password_hash',
        'activo',
        'intentos_fallidos',
        'ultimo_acceso',
        'fecha_creacion',
        'fecha_actualizacion',
        'remember_token'
    ];

    /* Atributos ocultos para serialización */
    protected $hidden = [
        'password_hash',
        'remember_token'
    ];

    /* Casting de atributos */
    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'id_usuario' => 'integer',
        'intentos_fallidos' => 'integer'
    ];

    // === RELACIONES ===

    /* Relación con el funcionario */
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'id_funcionario', 'id_funcionario');
    }

    /* Relación con los roles */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'seguridad.usuario_roles',
            'id_usuario',
            'id_rol'
        );
    }

    /* Documentos creados por el usuario */
    public function documentosCreados(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_usuario_creador', 'id_usuario');
    }

    /* Archivos subidos por el usuario */
    public function archivosSubidos(): HasMany
    {
        return $this->hasMany(ArchivoDocumento::class, 'subido_por', 'id_usuario');
    }

    /* Derivaciones enviadas por el usuario */
    public function derivacionesEnviadas(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_usuario_envia', 'id_usuario');
    }

    /* Derivaciones recibidas por el usuario */
    public function derivacionesRecibidas(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_usuario_recibe', 'id_usuario');
    }

    /* Historial de documentos del usuario */
    public function historialDocumentos(): HasMany
    {
        return $this->hasMany(HistorialDocumento::class, 'id_usuario', 'id_usuario');
    }

    /* Logs del sistema del usuario */
    public function logsSistema(): HasMany
    {
        return $this->hasMany(LogSistema::class, 'id_usuario', 'id_usuario');
    }

    /* Noticias creadas por el usuario */
    public function noticiasCreadas(): HasMany
    {
        return $this->hasMany(Noticia::class, 'id_usuario_creador', 'id_usuario');
    }

    // === MÉTODOS DE AUTENTICACIÓN ===

    /* Obtener la contraseña para autenticación */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /* Obtener el nombre del campo identificador */
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    /* Obtener el valor del identificador */
    public function getAuthIdentifier(): int
    {
        return $this->getKey();
    }

    /* Obtener el nombre del campo de "recordarme" */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    // === MÉTODOS DE PERMISOS Y ROLES ===

    /* Verifica si el usuario tiene un rol específico */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('nombre', $roleName)->exists();
    }

    /* Verifica si el usuario tiene un permiso específico */
    public function hasPermission(string $modulo, string $accion): bool
    {
        return $this->roles()
            ->whereHas('permisos', function ($query) use ($modulo, $accion) {
                $query->where('modulo', $modulo)
                    ->where('accion', $accion);
            })
            ->exists();
    }

    /* Verifica si es administrador */
    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }

    /* Obtiene todos los permisos del usuario agrupados por módulo */
    public function getPermissions(): array
    {
        $permisos = [];

        foreach ($this->roles as $rol) {
            foreach ($rol->permisos as $permiso) {
                $permisos[$permiso->modulo][] = $permiso->accion;
            }
        }

        foreach ($permisos as $modulo => $acciones) {
            $permisos[$modulo] = array_unique($acciones);
        }

        return $permisos;
    }

    /* Verifica si el usuario tiene acceso a un módulo específico */
    public function canAccessModule(string $modulo): bool
    {
        $permissions = $this->getPermissions();
        return isset($permissions[$modulo]) && !empty($permissions[$modulo]);
    }

    /* Obtiene todos los módulos a los que tiene acceso */
    public function getAccessibleModules(): array
    {
        $permissions = $this->getPermissions();
        return array_keys($permissions);
    }

    /* Verifica si el usuario tiene permisos para crear */
    public function canCreate(string $modulo): bool
    {
        return $this->hasPermission($modulo, 'crear');
    }

    /* Verifica si el usuario tiene permisos para leer */
    public function canRead(string $modulo): bool
    {
        return $this->hasPermission($modulo, 'leer');
    }

    /* Verifica si el usuario tiene permisos para actualizar */
    public function canUpdate(string $modulo): bool
    {
        return $this->hasPermission($modulo, 'actualizar');
    }

    /* Verifica si el usuario tiene permisos para eliminar */
    public function canDelete(string $modulo): bool
    {
        return $this->hasPermission($modulo, 'eliminar');
    }

    // === MÉTODOS DE SEGURIDAD Y CONTROL ===

    /* Verifica si la cuenta está bloqueada por intentos fallidos */
    public function isLocked(): bool
    {
        return $this->intentos_fallidos >= 5;
    }

    /* Incrementa los intentos fallidos */
    public function incrementFailedAttempts(): void
    {
        $this->increment('intentos_fallidos');
        $this->fecha_actualizacion = now();
        $this->save();
    }

    /* Resetea los intentos fallidos */
    public function resetFailedAttempts(): void
    {
        $this->intentos_fallidos = 0;
        $this->fecha_actualizacion = now();
        $this->save();
    }

    /* Registra el último acceso */
    public function recordLastAccess(): void
    {
        $this->ultimo_acceso = now();
        $this->fecha_actualizacion = now();
        $this->resetFailedAttempts();
        $this->save();
    }

    // === SCOPES ===

    /* Scope para filtrar solo usuarios activos */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    /* Scope para buscar por nombre de usuario */
    public function scopePorUsuario(Builder $query, string $usuario): Builder
    {
        return $query->where('usuario', $usuario);
    }

    /* Scope para buscar por CI del funcionario */
    public function scopePorCiFuncionario(Builder $query, string $ci): Builder
    {
        return $query->whereHas('funcionario', function ($q) use ($ci) {
            $q->where('ci', $ci);
        });
    }

    /* Scope para filtrar usuarios con intentos fallidos */
    public function scopeConIntentosFallidos(Builder $query, int $min = 1): Builder
    {
        return $query->where('intentos_fallidos', '>=', $min);
    }

    /* Scope para filtrar usuarios bloqueados */
    public function scopeBloqueados(Builder $query): Builder
    {
        return $query->where('intentos_fallidos', '>=', 5);
    }

    /* Scope para buscar por rol */
    public function scopeConRol(Builder $query, string $rolNombre): Builder
    {
        return $query->whereHas('roles', function ($q) use ($rolNombre) {
            $q->where('nombre', $rolNombre);
        });
    }

    // === MUTATORS ===

    /* Setter para el usuario (siempre en minúsculas) */
    public function setUsuarioAttribute(string $value): void
    {
        $this->attributes['usuario'] = strtolower(trim($value));
    }

    // === ACCESORS ===

    /* Obtener el nombre completo del usuario */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->funcionario) {
            return $this->funcionario->nombres . ' ' . $this->funcionario->apellidos;
        }
        return $this->usuario;
    }

    /* Obtener el estado como texto */
    public function getEstadoTextoAttribute(): string
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }

    /* Obtener estado de bloqueo como texto */
    public function getEstadoBloqueoAttribute(): string
    {
        if ($this->intentos_fallidos >= 5) {
            return 'Bloqueado';
        }
        return 'Normal';
    }

    /* Obtener el nombre para mostrar en la interfaz */
    public function getDisplayNameAttribute(): string
    {
        if ($this->funcionario) {
            return $this->funcionario->nombres . ' ' . $this->funcionario->apellidos;
        }
        return $this->usuario;
    }

    /* Obtener los roles como array */
    public function getRolesArrayAttribute(): array
    {
        return $this->roles->pluck('nombre')->toArray();
    }

    /* Obtener los permisos como array */
    public function getPermissionsArrayAttribute(): array
    {
        $permissions = [];
        foreach ($this->roles as $rol) {
            foreach ($rol->permisos as $permiso) {
                $permissions[] = $permiso->modulo . ':' . $permiso->accion;
            }
        }
        return array_unique($permissions);
    }

    // === MÉTODOS DE UTILIDAD ADICIONALES ===
    /* Verificar si el usuario tiene algún rol de la lista */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('nombre', $roles)->exists();
    }

    /* Verificar si el usuario tiene todos los roles de la lista */
    public function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->roles->pluck('nombre')->toArray();
        return count(array_intersect($roles, $userRoles)) === count($roles);
    }

    /* Sincronizar roles del usuario */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /* Asignar un rol al usuario */
    public function assignRole(string $roleName): void
    {
        $rol = Rol::where('nombre', $roleName)->first();
        if ($rol) {
            $this->roles()->attach($rol->id_rol);
        }
    }

    /* Remover un rol del usuario */
    public function removeRole(string $roleName): void
    {
        $rol = Rol::where('nombre', $roleName)->first();
        if ($rol) {
            $this->roles()->detach($rol->id_rol);
        }
    }
}
