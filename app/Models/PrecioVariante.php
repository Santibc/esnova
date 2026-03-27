<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioVariante extends Model
{
    use HasFactory;

    protected $table = 'precios_variantes';

    protected $fillable = [
        'variante_producto_id',
        'lista_precio_id',
        'ajuste_precio',
        'activo'
    ];

    protected $casts = [
        'ajuste_precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function varianteProducto()
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_producto_id');
    }

    public function listaPrecio()
    {
        return $this->belongsTo(ListaPrecio::class, 'lista_precio_id');
    }
}

    