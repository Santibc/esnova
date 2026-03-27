<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected $appends = ['imagen_url'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    public function productosActivos()
    {
        return $this->hasMany(Producto::class, 'categoria_id')->where('activo', true);
    }

    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset($this->imagen);
        }
        
        // Imagen por defecto si no hay imagen
        return asset('images/categoria-default.jpg');
    }

    /**
     * Eliminar la imagen anterior al actualizar
     */
    public function eliminarImagen()
    {
        if ($this->imagen && File::exists(public_path($this->imagen))) {
            File::delete(public_path($this->imagen));
        }
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $baseSlug = Str::slug($categoria->nombre);
                $slug = $baseSlug;
                
                // Asegurar que el slug sea único dentro de la empresa
                $count = static::where('empresa_id', $categoria->empresa_id)
                              ->where('slug', 'like', $baseSlug . '%')
                              ->count();
                              
                if ($count > 0) {
                    $slug = $baseSlug . '-' . ($count + 1);
                }
                
                $categoria->slug = $slug;
            }
        });

        // Eliminar imagen cuando se elimina la categoría
        static::deleting(function ($categoria) {
            $categoria->eliminarImagen();
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