<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSolicitudCotizacion extends Model
{
    use HasFactory;

    protected $table = 'items_solicitud_cotizacion';

    protected $fillable = [
        'solicitud_cotizacion_id',
        'producto_id',
        'variante_producto_id',
        'cantidad',
        'precio_unitario',
        'precio_total',
        'referencia_producto',
        'nombre_producto',
        'info_variante'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'precio_total' => 'decimal:2',
    ];

    public function solicitudCotizacion()
    {
        return $this->belongsTo(SolicitudCotizacion::class, 'solicitud_cotizacion_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function varianteProducto()
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_producto_id');
    }

    // Calcular precio total automáticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->precio_total = $item->cantidad * $item->precio_unitario;
        });

        static::saved(function ($item) {
            // Recalcular monto total de la solicitud
            $item->solicitudCotizacion->calcularMontoTotal();
        });
    }
}