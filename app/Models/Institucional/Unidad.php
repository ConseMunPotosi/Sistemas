<?php

namespace App\Models\Institucional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Documental\Documento;
use App\Models\Documental\Derivacion;

class Unidad extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'institucional.unidades';
    protected $primaryKey = 'id_unidad';
    public $timestamps = false;

    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_unidad',
        'nombre',
        'sigla',
        'descripcion',
        'estado',
        'fecha_creacion'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime',
        'id_unidad' => 'integer'
    ];

    // Relaciones
    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'id_unidad', 'id_unidad');
    }

    public function documentosOrigen(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_unidad_origen', 'id_unidad');
    }

    public function derivacionesOrigen(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_unidad_origen', 'id_unidad');
    }

    public function derivacionesDestino(): HasMany
    {
        return $this->hasMany(Derivacion::class, 'id_unidad_destino', 'id_unidad');
    }
}