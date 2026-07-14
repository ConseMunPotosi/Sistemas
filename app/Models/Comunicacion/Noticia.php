<?php

namespace App\Models\Comunicacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seguridad\Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    use HasFactory;

    //protected $connection = 'pgsql';
    protected $table = 'comunicacion.noticias';
    protected $primaryKey = 'id_noticia';
    public $timestamps = false;

    protected $fillable = [
        'id_noticia',
        'titulo',
        'resumen',
        'contenido',
        'imagen_portada',
        'id_categoria',
        'id_usuario_creador',
        'estado_publicacion',
        'fecha_creacion',
        'fecha_publicacion',
        'publicado_web',
        'publicado_facebook',
        'enlace_facebook',
        'facebook_post_id',
        'estado'
    ];

    protected $casts = [
        'publicado_web' => 'boolean',
        'publicado_facebook' => 'boolean',
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_publicacion' => 'datetime'
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaNoticia::class, 'id_categoria', 'id_categoria');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_creador', 'id_usuario');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoNoticia::class, 'id_noticia', 'id_noticia');
    }

    public function historialPublicacion(): HasMany
    {
        return $this->hasMany(HistorialPublicacionNoticia::class, 'id_noticia', 'id_noticia');
    }
}
