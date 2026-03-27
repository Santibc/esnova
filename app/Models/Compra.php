<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_compra',
        'empresa_id',
        'user_id',
        'nombre_cliente',
        'email_cliente',
        'telefono_cliente',
        'direccion_envio',
        'ciudad_id',
        'subtotal',
        'descuento_total',
        'descuentos_aplicados',
        'impuestos',
        'costo_envio',
        'total',
        'estado',
        'metodo_pago',
        'mensaje_pago',
        'archivo_pago',
        'motivo_rechazo',
        'fecha_revision',
        'revisado_por',
        'notas'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento_total' => 'decimal:2',
        'descuentos_aplicados' => 'array',
        'impuestos' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_revision' => 'datetime'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación con el usuario (cliente)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las calificaciones de los productos de esta compra
     */
    public function calificaciones()
    {
        return $this->hasMany(CalificacionProducto::class);
    }

    /**
     * Verificar si la compra puede ser calificada (estados válidos)
     */
    public function puedeSerCalificada()
    {
        return in_array($this->estado, ['pagada', 'enviada', 'entregada']);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function items()
    {
        return $this->hasMany(ItemCompra::class);
    }

    public function transaccionesPago()
    {
        return $this->hasMany(TransaccionPago::class);
    }

    public function transaccionAprobada()
    {
        return $this->hasOne(TransaccionPago::class)->where('estado', 'aprobada');
    }

    public function comision()
    {
        return $this->hasOne(Comision::class);
    }

    public function envio()
    {
        return $this->hasOne(Envio::class);
    }

    /**
     * Relación con el usuario que revisó el pago
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Verifica si el método de pago es "otro"
     */
    public function esMetodoOtro(): bool
    {
        return $this->metodo_pago === 'otro';
    }

    /**
     * Verifica si tiene archivo de pago adjunto
     */
    public function tieneArchivoPago(): bool
    {
        return !empty($this->archivo_pago);
    }

    /**
     * Obtiene la URL del archivo de pago (guardado en public/)
     */
    public function urlArchivoPago(): ?string
    {
        if (!$this->archivo_pago) {
            return null;
        }
        return asset($this->archivo_pago);
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStock::class, 'referencia_documento', 'numero_compra');
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('cantidad');
    }

    public function calcularTotales()
    {
        $this->subtotal = $this->items->sum('precio_total');
        $this->total = ($this->subtotal - $this->descuento_total) + $this->impuestos + $this->costo_envio;
        $this->save();
        return $this->total;
    }

    public function descuentosAplicadosRegistro()
    {
        return $this->hasMany(DescuentoAplicado::class);
    }

    public function registrarDescuentos()
    {
        if (!$this->descuentos_aplicados || empty($this->descuentos_aplicados)) {
            return;
        }

        foreach ($this->descuentos_aplicados as $descuentoData) {
            if (!isset($descuentoData['descuento'])) {
                continue;
            }

            $descuento = $descuentoData['descuento'];

            // Registrar en la tabla de descuentos aplicados
            DescuentoAplicado::create([
                'descuento_id' => is_object($descuento) ? $descuento->id : $descuento['id'],
                'compra_id' => $this->id,
                'email_cliente' => $this->email_cliente,
                'monto_descuento' => $descuentoData['monto'] ?? 0,
            ]);

            // Incrementar contador de usos del descuento
            if (is_object($descuento)) {
                $descuento->incrementarUsos();
            } else {
                $descuentoModel = \App\Models\Descuento::find($descuento['id']);
                $descuentoModel?->incrementarUsos();
            }
        }
    }
public function generarComision() {
    if ($this->estado !== 'pagada') {
        return null;
    }

    return DB::transaction(function () {
        $this->refresh();

        $porcentaje = $this->empresa->planMembresia?->porcentaje_comision ?? 6.09;
        $cargoFijo = (float)($this->empresa->planMembresia?->comision_fija ?? 900);

        $montoVariable = $this->total * ($porcentaje / 100);
        $montoComision = round($montoVariable + $cargoFijo, 2);
        $montoEmpresa  = round($this->total - $montoComision, 2);

        return \App\Models\Comision::updateOrCreate(
            ['compra_id' => $this->id],
            [
                'empresa_id'          => $this->empresa_id,
                'monto_venta'         => $this->total,
                'porcentaje_comision' => $porcentaje,
                'monto_comision'      => $montoComision,
                'monto_empresa'       => $montoEmpresa,
                'estado'              => 'pendiente',
                'observaciones'       => "Comisión: {$porcentaje}% + $" . number_format($cargoFijo, 0),
            ]
        );
    });
}

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($compra) {
            if (empty($compra->numero_compra)) {
                $compra->numero_compra = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(\Str::random(4));
            }
        });
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePorMetodoPago($query, $metodo)
    {
        return $query->where('metodo_pago', $metodo);
    }

    public function scopePendientesRevision($query)
    {
        return $query->where('metodo_pago', 'otro')
                     ->where('estado', 'pendiente');
    }
}