<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Services\SitemapService;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'empresa_id',
        'user_id',
        'blog_categoria_id',
        'titulo',
        'slug',
        'seo_title',
        'meta_description',
        'seo_keywords',
        'og_image',
        'canonical_url',
        'noindex',
        'nofollow',
        'imagen_portada',
        'youtube_url',
        'introduccion',
        'contenido',
        'bloque_confianza',
        'producto_enlace_id',
        'faqs',
        'activo',
        'publicado_en',
        'orden'
    ];

    protected $casts = [
        'bloque_confianza' => 'array',
        'faqs' => 'array',
        'activo' => 'boolean',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
        'publicado_en' => 'datetime',
    ];

    // Relaciones

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoria()
    {
        return $this->belongsTo(BlogCategoria::class, 'blog_categoria_id');
    }

    public function productoEnlace()
    {
        return $this->belongsTo(Producto::class, 'producto_enlace_id');
    }

    public function relacionados()
    {
        return $this->belongsToMany(
            BlogPost::class,
            'blog_post_relacionados',
            'blog_post_id',
            'relacionado_id'
        );
    }

    // Accessors

    public function getImagenPortadaUrlAttribute()
    {
        if ($this->imagen_portada) {
            return asset($this->imagen_portada);
        }
        // Placeholder SVG con gradiente para posts sin imagen
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%23667eea'/%3E%3Cstop offset='100%25' style='stop-color:%23764ba2'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='800' height='450' fill='url(%23g)'/%3E%3Ctext x='400' y='210' text-anchor='middle' fill='rgba(255,255,255,0.3)' font-size='80' font-family='sans-serif'%3E%E2%9C%8E%3C/text%3E%3Ctext x='400' y='270' text-anchor='middle' fill='rgba(255,255,255,0.5)' font-size='20' font-family='sans-serif'%3EBlog%3C/text%3E%3C/svg%3E";
    }

    public function getSeoTitleTextoAttribute()
    {
        return $this->seo_title ?: $this->titulo;
    }

    public function getRobotsMetaAttribute()
    {
        $parts = [];
        $parts[] = $this->noindex ? 'noindex' : 'index';
        $parts[] = $this->nofollow ? 'nofollow' : 'follow';
        return implode(', ', $parts);
    }

    public function getExtractoAttribute()
    {
        return Str::limit(strip_tags($this->introduccion), 150);
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->youtube_url) return null;

        $videoId = null;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $this->youtube_url, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }

    // Imagen

    public function eliminarImagen()
    {
        if ($this->imagen_portada && File::exists(public_path($this->imagen_portada))) {
            File::delete(public_path($this->imagen_portada));
        }
    }

    // Boot

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $baseSlug = Str::slug($post->titulo);
                $slug = $baseSlug;

                $count = static::where('empresa_id', $post->empresa_id)
                              ->where('slug', 'like', $baseSlug . '%')
                              ->count();

                if ($count > 0) {
                    $slug = $baseSlug . '-' . ($count + 1);
                }

                $post->slug = $slug;
            }
        });

        static::deleting(function ($post) {
            $post->eliminarImagen();
            $post->relacionados()->detach();
        });

        // Regenerar sitemap automáticamente
        static::created(function ($post) {
            SitemapService::generar();
        });

        static::updated(function ($post) {
            SitemapService::generar();
        });

        static::deleted(function ($post) {
            SitemapService::generar();
        });
    }

    // Scopes

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePublicados($query)
    {
        return $query->where('activo', true)
                     ->whereNotNull('publicado_en')
                     ->where('publicado_en', '<=', now());
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('titulo', 'like', "%{$termino}%")
              ->orWhere('introduccion', 'like', "%{$termino}%")
              ->orWhere('contenido', 'like', "%{$termino}%");
        });
    }
}
