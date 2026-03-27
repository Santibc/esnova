<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PagoEmpresa extends Model
{
    use HasFactory;

    protected $table = 'pagos_empresas';

    protected $fillable = [
        'empresa_id',
        'periodo',
        'total_ventas',
        'total_comisiones',
        'total_a_pagar',
        'estado',
        'fecha_pago',
        'metodo_pago',
        'referencia_pago',
        'comprobante_pago',
        'detalle_comisiones',
        'observaciones'
    ];

    protected $casts = [
        'total_ventas' => 'decimal:2',
        'total_comisiones' => 'decimal:2',
        'total_a_pagar' => 'decimal:2',
        'fecha_pago' => 'date',
        'detalle_comisiones' => 'array'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getComprobanteUrlAttribute()
    {
        return $this->comprobante_pago ? Storage::url($this->comprobante_pago) : null;
    }

    public function marcarComoPagado($metodoPago, $referenciaPago, $comprobante = null, $observaciones = null)
    {
        $this->update([
            'estado' => 'pagado',
            'fecha_pago' => now(),
            'metodo_pago' => $metodoPago,
            'referencia_pago' => $referenciaPago,
            'comprobante_pago' => $comprobante,
            'observaciones' => $observaciones
        ]);

        // Marcar las comisiones incluidas como pagadas
        if ($this->detalle_comisiones) {
            Comision::whereIn('id', $this->detalle_comisiones)
                    ->update(['estado' => 'pagada']);
        }
    }

    public static function generarPagoPeriodo($empresaId, $periodo, $fechaInicio, $fechaFin)
    {
        $comisiones = Comision::where('empresa_id', $empresaId)
                             ->where('estado', 'pendiente')
                             ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                             ->get();

        if ($comisiones->isEmpty()) {
            return null;
        }

        return static::create([
            'empresa_id' => $empresaId,
            'periodo' => $periodo,
            'total_ventas' => $comisiones->sum('monto_venta'),
            'total_comisiones' => $comisiones->sum('monto_comision'),
            'total_a_pagar' => $comisiones->sum('monto_empresa'),
            'estado' => 'pendiente',
            'detalle_comisiones' => $comisiones->pluck('id')->toArray()
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}