<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSlide extends Model
{
    protected $table = 'banner_slides';

    protected $fillable = [
        'empresa_id',
        'titulo',
        'subtitulo',
        'imagen',
        'btn1_texto',
        'btn1_link',
        'btn2_texto',
        'btn2_link',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset($this->imagen);
        }

        return $this->empresa?->imagen_portada_url;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }
}
