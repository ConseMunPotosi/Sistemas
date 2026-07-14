<?php

namespace App\Models\Documental;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'documental.tipos_documento';
    protected $primaryKey = 'id_tipo_documento';
    public $timestamps = false;
    // IMPORTANTE: Indicar que la clave primaria es autoincremental
    public $incrementing = true;
    // Como es BIGINT (por usar $table->id()), el tipo debe ser 'int'
    protected $keyType = 'int';

    protected $fillable = [
        //'id_tipo_documento',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'id_tipo_documento' => 'integer' //asegura que se trata como entero
    ];

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_tipo_documento', 'id_tipo_documento');
    }
}
