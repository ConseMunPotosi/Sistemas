<?php

namespace App\Models\Institucional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'institucional.cargos';
    protected $primaryKey = 'id_cargo';
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        //'id_cargo',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_cargo' => 'integer'
    ];

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'id_cargo', 'id_cargo');
    }
}
