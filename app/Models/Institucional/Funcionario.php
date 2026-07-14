<?php

namespace App\Models\Institucional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Seguridad\Usuario;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'institucional.funcionarios';
    protected $primaryKey = 'id_funcionario';
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        //'id_funcionario',
        'nombres',
        'apellidos',
        'ci',
        'celular',
        'correo',
        'estado',
        'fecha_registro',
        'id_unidad',
        'id_cargo',
        'gestion'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_registro' => 'datetime',
        'gestion' => 'date',
        'id_funcionario' => 'integer' //asegura que se trata como entero
    ];

    // Relaciones
    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class, 'id_unidad', 'id_unidad');
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id_funcionario', 'id_funcionario');
    }
}
