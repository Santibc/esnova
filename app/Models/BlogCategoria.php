<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategoria extends Model
{
    use HasFactory;

    protected $table = 'blog_categorias';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'slug',
        'descripcion',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'blog_categoria_id');
    }

    public function postsActivos()
    {
        return $this->hasMany(BlogPost::class, 'blog_categoria_id')->where('activo', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $baseSlug = Str::slug($categoria->nombre);
                $slug = $baseSlug;

                $count = static::where('empresa_id', $categoria->empresa_id)
                              ->where('slug', 'like', $baseSlug . '%')
                              ->count();

                if ($count > 0) {
                    $slug = $baseSlug . '-' . ($count + 1);
                }

                $categoria->slug = $slug;
            }
        });
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}
