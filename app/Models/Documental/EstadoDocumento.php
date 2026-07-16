<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoDocumento extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'documental.estados_documento';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_estado',
        'nombre',
        'descripcion'
    ];

    protected $casts = [
        'id_estado' => 'integer' //asegura que se trata como entero
    ];

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_estado', 'id_estado');
    }
}
