<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'blog_configuraciones';

    protected $fillable = [
        'empresa_id',
        'banner_titulo',
        'banner_subtitulo',
        'banner_imagen',
        'seo_title',
        'meta_description',
        'seo_keywords',
        'canonical_url',
        'og_image',
        'noindex',
        'nofollow',
    ];

    protected $casts = [
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getRobotsMetaAttribute()
    {
        $parts = [];
        $parts[] = $this->noindex ? 'noindex' : 'index';
        $parts[] = $this->nofollow ? 'nofollow' : 'follow';
        return implode(', ', $parts);
    }

    public function getBannerImagenUrlAttribute()
    {
        if ($this->banner_imagen) {
            return asset($this->banner_imagen);
        }
        return null;
    }

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return asset($this->og_image);
        }
        return null;
    }
}
