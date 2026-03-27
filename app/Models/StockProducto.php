<?php

namespace App\Models;

use App\Events\StockBajoAlcanzado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockProducto extends Model
{
    use HasFactory;

    protected $table = 'stock_productos';

    protected $fillable = [
        'producto_id',
        'variante_producto_id',
        'cantidad_disponible',
        'cantidad_reservada',
        'stock_minimo',
        'stock_maximo',
        'ubicacion',
        'notas',
        'alerta_stock_bajo'
    ];

    protected $casts = [
        'alerta_stock_bajo' => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function variante()
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_producto_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoStock::class, 'producto_id', 'producto_id')
                    ->where(function($query) {
                        $query->whereNull('variante_producto_id')
                              ->orWhere('variante_producto_id', $this->variante_producto_id);
                    });
    }

    // Obtener stock real (disponible - reservado)
    public function getStockRealAttribute()
    {
        return $this->cantidad_disponible - $this->cantidad_reservada;
    }

    // Verificar si hay stock bajo
    public function getStockBajoAttribute()
    {
        return $this->alerta_stock_bajo && ($this->stock_real <= $this->stock_minimo);
    }

    // Verificar disponibilidad
    public function hayDisponibilidad($cantidad)
    {
        return $this->stock_real >= $cantidad;
    }

    // Reservar stock
    public function reservar($cantidad, $referencia = null)
    {
        if (!$this->hayDisponibilidad($cantidad)) {
            return false;
        }

        $this->cantidad_reservada += $cantidad;
        $this->save();

        // Registrar movimiento
        MovimientoStock::create([
            'producto_id' => $this->producto_id,
            'variante_producto_id' => $this->variante_producto_id,
            'tipo_movimiento' => 'reserva',
            'cantidad' => $cantidad,
            'stock_anterior' => $this->cantidad_disponible,
            'stock_nuevo' => $this->cantidad_disponible,
            'referencia_documento' => $referencia,
            'origen' => 'cotizacion',
            'usuario_id' => auth()->id() ?? 1
        ]);

        return true;
    }

    // Liberar reserva
    public function liberarReserva($cantidad, $referencia = null)
    {
        $this->cantidad_reservada = max(0, $this->cantidad_reservada - $cantidad);
        $this->save();

        // Registrar movimiento
        MovimientoStock::create([
            'producto_id' => $this->producto_id,
            'variante_producto_id' => $this->variante_producto_id,
            'tipo_movimiento' => 'liberacion',
            'cantidad' => $cantidad,
            'stock_anterior' => $this->cantidad_disponible,
            'stock_nuevo' => $this->cantidad_disponible,
            'referencia_documento' => $referencia,
            'origen' => 'cotizacion',
            'usuario_id' => auth()->id() ?? 1
        ]);

        return true;
    }

    // Entrada de stock
    public function entrada($cantidad, $origen = 'compra', $referencia = null, $motivo = null)
    {
        $stockAnterior = $this->cantidad_disponible;
        $this->cantidad_disponible += $cantidad;
        $this->save();

        // Registrar movimiento
        MovimientoStock::create([
            'producto_id' => $this->producto_id,
            'variante_producto_id' => $this->variante_producto_id,
            'tipo_movimiento' => 'entrada',
            'cantidad' => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $this->cantidad_disponible,
            'referencia_documento' => $referencia,
            'origen' => $origen,
            'motivo' => $motivo,
            'usuario_id' => auth()->id() ?? 1
        ]);

        return true;
    }

    // Salida de stock
    public function salida($cantidad, $origen = 'venta', $referencia = null, $motivo = null)
    {
        if ($this->cantidad_disponible < $cantidad) {
            return false;
        }

        $stockAnterior = $this->cantidad_disponible;

        // Calcular si estaba bajo ANTES de la operación
        $stockRealAnterior = $stockAnterior - $this->cantidad_reservada;
        $eraBajo = $this->alerta_stock_bajo && ($stockRealAnterior <= $this->stock_minimo);

        $this->cantidad_disponible -= $cantidad;

        // Si había reserva, la reducimos
        if ($this->cantidad_reservada > 0) {
            $this->cantidad_reservada = max(0, $this->cantidad_reservada - $cantidad);
        }

        $this->save();

        // Registrar movimiento
        MovimientoStock::create([
            'producto_id' => $this->producto_id,
            'variante_producto_id' => $this->variante_producto_id,
            'tipo_movimiento' => 'salida',
            'cantidad' => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $this->cantidad_disponible,
            'referencia_documento' => $referencia,
            'origen' => $origen,
            'motivo' => $motivo,
            'usuario_id' => auth()->id() ?? 1
        ]);

        // Verificar si el stock cruzo el umbral (no estaba bajo, ahora si)
        $esBajoAhora = $this->stock_bajo;

        if (!$eraBajo && $esBajoAhora) {
            event(new StockBajoAlcanzado($this, $stockRealAnterior, $this->stock_real));
        }

        return true;
    }

    // Ajuste de inventario
    public function ajustar($nuevaCantidad, $motivo = null)
    {
        $stockAnterior = $this->cantidad_disponible;
        $diferencia = $nuevaCantidad - $stockAnterior;

        // Calcular si estaba bajo ANTES de la operación
        $stockRealAnterior = $stockAnterior - $this->cantidad_reservada;
        $eraBajo = $this->alerta_stock_bajo && ($stockRealAnterior <= $this->stock_minimo);

        $this->cantidad_disponible = $nuevaCantidad;
        $this->save();

        // Registrar movimiento
        MovimientoStock::create([
            'producto_id' => $this->producto_id,
            'variante_producto_id' => $this->variante_producto_id,
            'tipo_movimiento' => 'ajuste',
            'cantidad' => $diferencia,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $this->cantidad_disponible,
            'origen' => 'ajuste_inventario',
            'motivo' => $motivo,
            'usuario_id' => auth()->id() ?? 1
        ]);

        // Verificar si el stock cruzo el umbral (no estaba bajo, ahora si)
        $esBajoAhora = $this->stock_bajo;

        if (!$eraBajo && $esBajoAhora) {
            event(new StockBajoAlcanzado($this, $stockRealAnterior, $this->stock_real));
        }

        return true;
    }

    // Scopes
    public function scopeConStockBajo($query)
    {
        return $query->whereRaw('(cantidad_disponible - cantidad_reservada) <= stock_minimo')
                     ->where('alerta_stock_bajo', true);
    }

    public function scopeSinStock($query)
    {
        return $query->whereRaw('(cantidad_disponible - cantidad_reservada) <= 0');
    }

    public function scopeConStock($query)
    {
        return $query->whereRaw('(cantidad_disponible - cantidad_reservada) > 0');
    }
}