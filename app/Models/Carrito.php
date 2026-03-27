<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'empresa_id',
        'items',
        'subtotal',
        'descuento_total',
        'descuentos_aplicados',
        'codigo_descuento',
        'ultima_actividad'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'descuento_total' => 'decimal:2',
        'descuentos_aplicados' => 'array',
        'ultima_actividad' => 'datetime'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function agregarItem($productoId, $cantidad, $varianteId = null, $precio = null)
    {
        $items = $this->items ?? [];
        $key = $varianteId ? "{$productoId}-{$varianteId}" : $productoId;

        if (isset($items[$key])) {
            $items[$key]['cantidad'] += $cantidad;
            $items[$key]['precio_total'] = $items[$key]['cantidad'] * $items[$key]['precio'];
        } else {
            $producto = Producto::find($productoId);
            $precioUnitario = $precio ?? $producto->precio;

            $items[$key] = [
                'producto_id' => $productoId,
                'variante_id' => $varianteId,
                'cantidad' => $cantidad,
                'precio' => $precioUnitario,
                'precio_total' => $cantidad * $precioUnitario,
                'nombre' => $producto->nombre,
                'referencia' => $producto->referencia
            ];

            if ($varianteId) {
                $variante = VarianteProducto::find($varianteId);
                $items[$key]['info_variante'] = [
                    'talla' => $variante->talla,
                    'color' => $variante->color,
                    'sku' => $variante->sku
                ];
            }
        }

        $this->items = $items;
        $this->calcularSubtotal();
        $this->ultima_actividad = now();
        $this->save();

        // Aplicar descuentos automáticos después de agregar el item
        $this->aplicarDescuento();
    }

    public function quitarItem($key)
    {
        $items = $this->items ?? [];

        if (isset($items[$key])) {
            unset($items[$key]);
            $this->items = $items;
            $this->calcularSubtotal();
            $this->save();

            // Recalcular descuentos
            $this->aplicarDescuento();
        }
    }

    public function actualizarCantidad($key, $cantidad)
    {
        $items = $this->items ?? [];

        if (isset($items[$key]) && $cantidad > 0) {
            $items[$key]['cantidad'] = $cantidad;
            $items[$key]['precio_total'] = $cantidad * $items[$key]['precio'];
            $this->items = $items;
            $this->calcularSubtotal();
            $this->save();

            // Recalcular descuentos
            $this->aplicarDescuento();
        }
    }

    public function vaciar()
    {
        $this->items = [];
        $this->subtotal = 0;
        $this->save();
    }

    public function calcularSubtotal()
    {
        $subtotal = 0;

        foreach ($this->items ?? [] as $item) {
            $subtotal += $item['cantidad'] * $item['precio'];
        }

        $this->subtotal = $subtotal;
        return $subtotal;
    }

    public function aplicarDescuento(string $codigo = null)
    {
        $discountCalculator = app(\App\Services\Discounts\DiscountCalculator::class);

        $resultado = $discountCalculator->calculateDiscounts(
            $this->items ?? [],
            $this->subtotal,
            $this->empresa_id,
            $codigo ?? $this->codigo_descuento
        );

        $this->descuentos_aplicados = $resultado['descuentos'];
        $this->descuento_total = $resultado['total_descuento'];
        $this->codigo_descuento = $codigo;

        // Agregar información de descuentos por item a cada producto del carrito
        $items = $this->items ?? [];
        $descuentosPorItem = $resultado['descuentos_por_item'] ?? [];

        foreach ($items as $key => &$item) {
            if (isset($descuentosPorItem[$key])) {
                $item['descuentos'] = $descuentosPorItem[$key];
                $item['descuento_total_item'] = array_sum(array_column($descuentosPorItem[$key], 'monto'));
            } else {
                $item['descuentos'] = [];
                $item['descuento_total_item'] = 0;
            }
        }

        $this->items = $items;
        $this->save();

        return $resultado;
    }

    public function removerDescuento()
    {
        $this->descuentos_aplicados = [];
        $this->descuento_total = 0;
        $this->codigo_descuento = null;
        $this->save();
    }

    public function getTotalAttribute()
    {
        return max(0, $this->subtotal - $this->descuento_total);
    }

    public function getTotalItemsAttribute()
    {
        return collect($this->items ?? [])->sum('cantidad');
    }

    public static function obtenerOCrear($sessionId, $empresaId)
    {
        return static::firstOrCreate(
            [
                'session_id' => $sessionId,
                'empresa_id' => $empresaId
            ],
            [
                'items' => [],
                'subtotal' => 0,
                'ultima_actividad' => now()
            ]
        );
    }

    public function scopeAbandonados($query, $horas = 24)
    {
        return $query->where('ultima_actividad', '<', now()->subHours($horas))
                    ->whereJsonLength('items', '>', 0);
    }

    protected static function boot()
    {
        parent::boot();

        // Limpiar carritos abandonados después de 30 días
        static::created(function () {
            static::where('ultima_actividad', '<', now()->subDays(30))->delete();
        });
    }
}