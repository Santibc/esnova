<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Descuento extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'descripcion',
        'tipo',
        'valor',
        'descuento_maximo',
        'aplica_a',
        'productos_aplicables',
        'categorias_aplicables',
        'monto_minimo_compra',
        'cantidad_minima_productos',
        'solo_primera_compra',
        'limite_usos_total',
        'usos_actuales',
        'limite_usos_por_cliente',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'es_acumulable',
        'prioridad',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'descuento_maximo' => 'decimal:2',
        'monto_minimo_compra' => 'decimal:2',
        'cantidad_minima_productos' => 'integer',
        'solo_primera_compra' => 'boolean',
        'limite_usos_total' => 'integer',
        'usos_actuales' => 'integer',
        'limite_usos_por_cliente' => 'integer',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'activo' => 'boolean',
        'es_acumulable' => 'boolean',
        'prioridad' => 'integer',
    ];

    // Mutators para convertir IDs a enteros
    public function setProductosAplicablesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['productos_aplicables'] = json_encode(array_map('intval', array_filter($value)));
        } else {
            $this->attributes['productos_aplicables'] = $value;
        }
    }

    public function setCategoriasAplicablesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['categorias_aplicables'] = json_encode(array_map('intval', array_filter($value)));
        } else {
            $this->attributes['categorias_aplicables'] = $value;
        }
    }

    // Accessors para asegurar que siempre sean enteros
    public function getProductosAplicablesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }

        return [];
    }

    public function getCategoriasAplicablesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }

        return [];
    }

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'descuento_producto');
    }

    public function aplicaciones()
    {
        return $this->hasMany(DescuentoAplicado::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeVigentes($query)
    {
        $now = Carbon::now();
        return $query->where(function($q) use ($now) {
            $q->where(function($query) use ($now) {
                $query->where('fecha_inicio', '<=', $now)
                      ->orWhereNull('fecha_inicio');
            })->where(function($query) use ($now) {
                $query->where('fecha_fin', '>=', $now)
                      ->orWhereNull('fecha_fin');
            });
        });
    }

    public function scopeDisponibles($query)
    {
        return $query->where(function($q) {
            $q->whereNull('limite_usos_total')
              ->orWhereRaw('usos_actuales < limite_usos_total');
        });
    }

    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo', $codigo);
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePorPrioridad($query)
    {
        return $query->orderBy('prioridad', 'desc');
    }

    // Métodos de validación
    public function estaVigente(): bool
    {
        $now = Carbon::now();

        if ($this->fecha_inicio && $now->lt($this->fecha_inicio)) {
            return false;
        }

        if ($this->fecha_fin && $now->gt($this->fecha_fin)) {
            return false;
        }

        return true;
    }

    public function tieneUsosDisponibles(): bool
    {
        if (!$this->limite_usos_total) {
            return true;
        }

        return $this->usos_actuales < $this->limite_usos_total;
    }

    public function puedeUsarCliente(string $email = null): bool
    {
        if (!$this->limite_usos_por_cliente || !$email) {
            return true;
        }

        $usosCliente = $this->aplicaciones()
            ->where('email_cliente', $email)
            ->count();

        return $usosCliente < $this->limite_usos_por_cliente;
    }

    public function cumpleMontominimo(float $monto): bool
    {
        if (!$this->monto_minimo_compra) {
            return true;
        }

        return $monto >= $this->monto_minimo_compra;
    }

    public function cumpleCantidadMinima(int $cantidad): bool
    {
        if (!$this->cantidad_minima_productos) {
            return true;
        }

        return $cantidad >= $this->cantidad_minima_productos;
    }

    public function aplicaAProducto(int $productoId): bool
    {
        if ($this->aplica_a !== 'producto') {
            return false;
        }

        if (!$this->productos_aplicables) {
            return false;
        }

        return in_array($productoId, $this->productos_aplicables);
    }

    public function aplicaACategoria(int $categoriaId): bool
    {
        if ($this->aplica_a !== 'categoria') {
            return false;
        }

        if (!$this->categorias_aplicables) {
            return false;
        }

        return in_array($categoriaId, $this->categorias_aplicables);
    }

    public function incrementarUsos(): void
    {
        $this->increment('usos_actuales');
    }

    public function getDescripcionCompletaAttribute(): string
    {
        $descripcion = $this->nombre;

        switch ($this->tipo) {
            case 'porcentaje':
                $descripcion .= " - {$this->valor}% de descuento";
                if ($this->descuento_maximo) {
                    $descripcion .= " (máximo $" . number_format($this->descuento_maximo, 0, ',', '.');
                }
                break;
            case 'monto_fijo':
                $descripcion .= " - $" . number_format($this->valor, 0, ',', '.') . " de descuento";
                break;
            case 'envio_gratis':
                $descripcion .= " - Envío gratis";
                break;
            case '2x1':
                $descripcion .= " - 2x1";
                break;
            case '3x2':
                $descripcion .= " - 3x2";
                break;
        }

        if ($this->monto_minimo_compra) {
            $descripcion .= " en compras desde $" . number_format($this->monto_minimo_compra, 0, ',', '.');
        }

        return $descripcion;
    }
}
