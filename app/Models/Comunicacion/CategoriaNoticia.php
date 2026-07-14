<?php

namespace App\Models\Comunicacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaNoticia extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'comunicacion.categorias_noticia';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class, 'id_categoria', 'id_categoria');
    }
}
