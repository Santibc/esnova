<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $table = 'precios_productos';

    protected $fillable = [
        'producto_id',
        'lista_precio_id',
        'precio',
        'activo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function listaPrecio()
    {
        return $this->belongsTo(ListaPrecio::class, 'lista_precio_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}