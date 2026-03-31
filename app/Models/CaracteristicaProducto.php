<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracteristicaProducto extends Model
{
    use HasFactory;

    protected $table = 'caracteristicas_productos';

    protected $fillable = [
        'producto_id',
        'icono',
        'imagen',
        'titulo',
        'descripcion',
        'orden'
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /**
     * Scope para ordenar por campo 'orden'
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden');
    }

    /**
     * URL completa de la imagen de la característica
     */
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset($this->imagen) : null;
    }

    /**
     * Verificar si tiene imagen asignada
     */
    public function getTieneImagenAttribute()
    {
        return !empty($this->imagen);
    }

    /**
     * Obtener la clase completa del icono Bootstrap
     */
    public function getIconoClaseAttribute()
    {
        if (str_starts_with($this->icono, 'bi-')) {
            return 'bi ' . $this->icono;
        }
        if (str_starts_with($this->icono, 'bi ')) {
            return $this->icono;
        }
        return 'bi bi-' . $this->icono;
    }
}
