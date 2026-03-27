<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StockProducto;
class VarianteProducto extends Model
{
    use HasFactory;

    protected $table = 'variantes_productos';

    protected $fillable = [
        'producto_id',
        'talla',
        'color',
        'sku',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function precios()
    {
        return $this->hasMany(PrecioVariante::class, 'variante_producto_id');
    }

    public function itemsSolicitudCotizacion()
    {
        return $this->hasMany(ItemSolicitudCotizacion::class, 'variante_producto_id');
    }
public function stock()
{
    return $this->hasOne(StockProducto::class, 'variante_producto_id');
}
    // Calcular precio final con ajuste
    public function getPrecioFinal($listaPrecioId)
    {
        $precioBase = $this->producto->getPrecioPorLista($listaPrecioId);
        if (!$precioBase) return null;

        $ajuste = $this->precios()->where('lista_precio_id', $listaPrecioId)->where('activo', true)->first();
        $ajustePrecio = $ajuste ? $ajuste->ajuste_precio : 0;

        return $precioBase + $ajustePrecio;
    }

    public function getNombreVarianteAttribute()
    {
        $partes = array_filter([$this->talla, $this->color]);
        return implode(' - ', $partes);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}