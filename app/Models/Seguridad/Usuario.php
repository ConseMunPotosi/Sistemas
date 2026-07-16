<?php

namespace App\Models\Seguridad;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // ← CAMBIAR ESTO

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

use Laravel\Sanctum\HasApiTokens; // ← NUEVO: Para tokens de API
use Illuminate\Database\Eloquent\Builder; // ← NUEVO: Para tipar $query

/**
 * Modelo de Usuario
 * 
 * @property int $id_usuario
 * @property int $id_funcionario
 * @property string $usuario
 * @property string $password_hash
 * @property string $correo
 * @property bool $activo
 * @property \DateTime $ultimo_acceso
 * @property \DateTime $fecha_creacion
 * 
 * @property-read Funcionario $funcionario
 * @property-read \Illuminate\Database\Eloquent\Collection|Rol[] $roles
 */

class Usuario extends Authenticatable 
{
    use HasFactory, HasApiTokens;

    protected $table = 'seguridad.usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_usuario',
        'id_funcionario',
        'usuario',
        'password_hash',
        'correo',
        'activo',
        'ultimo_acceso',
        'fecha_creacion'
    ];

    protected $hidden = [
        'password_hash'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime',
        'fecha_creacion' => 'datetime',
        'id_usuario' => 'integer' //asegura que se trata como entero
    ];

    // Relaciones
    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'id_funcionario', 'id_funcionario');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'seguridad.usuario_roles',
            'id_usuario',
            'id_rol'
        );
    }

    public function documentosCreados(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_usuario_creador', 'id_usuario');
    }

    public function archivosSubidos(): HasMany
    {
        return $this->hasMany(ArchivoDocumento::class, 'subido_por', 'id_usuario');
    }

    public function derivacionesEnviadas(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_usuario_envia', 'id_usuario');
    }

    public function derivacionesRecibidas(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_usuario_recibe', 'id_usuario');
    }

    public function historialDocumentos(): HasMany
    {
        return $this->hasMany(HistorialDocumento::class, 'id_usuario', 'id_usuario');
    }

    public function logsSistema(): HasMany
    {
        return $this->hasMany(LogSistema::class, 'id_usuario', 'id_usuario');
    }

    public function noticiasCreadas(): HasMany
    {
        return $this->hasMany(Noticia::class, 'id_usuario_creador', 'id_usuario');
    }

    // METODOS DE AUTENTICACION ======================
    // Agregar este método en el modelo
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
    // Método para obtener el identificador (para auth)
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    // Método para obtener el valor del identificador
    public function getAuthIdentifier(): int
    {
        return $this->getKey();
    }

    // ==========================================
    // MÉTODOS DE PERMISOS (NUEVOS)
    // ==========================================

    /**
    * Verifica si el usuario tiene un rol específico
    */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('nombre', $roleName)->exists();
    }

    /**
    * Verifica si el usuario tiene un permiso específico
    */
    public function hasPermission(string $modulo, string $accion): bool
    {
        return $this->roles()
            ->whereHas('permisos', function ($query) use ($modulo, $accion) {
                $query->where('modulo', $modulo)
                      ->where('accion', $accion);
            })
            ->exists();
    }

    /**
    * Verifica si es administrador
    */
    public function isAdmin(): bool
    {
        return $this->hasRole('Administrador');
    }

    /**
    * Obtiene todos los permisos del usuario agrupados por módulo
    */
    public function getPermissions(): array
    {
        $permisos = [];

        foreach ($this->roles as $rol) {
            foreach ($rol->permisos as $permiso) {
                $permisos[$permiso->modulo][] = $permiso->accion;
            }
        }

        // Eliminar duplicados
        foreach ($permisos as $modulo => $acciones) {
            $permisos[$modulo] = array_unique($acciones);
        }

        return $permisos;
    }

    // ==========================================
    // SCOPES (NUEVOS - Útiles para consultas)
    // ==========================================

    /**
    * Scope para filtrar solo usuarios activos
    */
    public function scopeActivos(Builder $query): Builder // ← AGREGAR TIPO $query
    {
        return $query->where('activo', true);
    }

    /**
    * Scope para buscar por nombre de usuario
    */
    public function scopePorUsuario(Builder $query, string $usuario): Builder // ← AGREGAR TIPOS
    {
        return $query->where('usuario', $usuario);
    }

    /**
    * Scope para buscar por correo
    */
    public function scopePorCorreo(Builder $query, string $correo): Builder
    {
        return $query->where('correo', $correo);
    }
    // Scope para buscar por CI del funcionario
    public function scopePorCiFuncionario(Builder $query, string $ci): Builder // ← NUEVO
    {
        return $query->whereHas('funcionario', function ($q) use ($ci) {
            $q->where('ci', $ci);
        });
    }
    // ==========================================
    // MUTATORS (NUEVOS - Para consistencia)
    // ==========================================

    /**
    * Setter para el usuario (siempre en minúsculas)
    */
    public function setUsuarioAttribute(string $value): void
    {
        $this->attributes['usuario'] = strtolower($value);
    }

    /**
    * Setter para el correo (siempre en minúsculas)
    */
    public function setCorreoAttribute(string $value):void
    {
        $this->attributes['correo'] = strtolower($value);
    }

    // ==========================================
    // ACCESORS (NUEVOS - Para conveniencia)
    // ==========================================

    /**
    * Obtener el nombre completo del usuario
    */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->funcionario) {
            return $this->funcionario->nombres . ' ' . $this->funcionario->apellidos;
        }
        return $this->usuario;
    }

    /**
    * Obtener el estado como texto
    */
    public function getEstadoTextoAttribute(): string
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }

}
