<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompra extends Model
{
    use HasFactory;

    protected $table = 'items_compra';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'variante_producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'precio_total',
        'referencia_producto',
        'nombre_producto',
        'info_variante'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'precio_total' => 'decimal:2'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function variante()
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_producto_id');
    }

    public function calcularPrecioTotal()
    {
        $subtotal = $this->cantidad * $this->precio_unitario;
        $this->precio_total = $subtotal - $this->descuento;
        return $this->precio_total;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if ($item->producto_id && !$item->referencia_producto) {
                $producto = Producto::find($item->producto_id);
                if ($producto) {
                    $item->referencia_producto = $producto->referencia;
                    $item->nombre_producto = $producto->nombre;
                    
                    if ($item->variante_producto_id) {
                        $variante = VarianteProducto::find($item->variante_producto_id);
                        if ($variante) {
                            $info = [];
                            if ($variante->talla) $info[] = "Talla: {$variante->talla}";
                            if ($variante->color) $info[] = "Color: {$variante->color}";
                            $item->info_variante = implode(', ', $info);
                        }
                    }
                }
            }
            
            $item->calcularPrecioTotal();
        });
    }
}