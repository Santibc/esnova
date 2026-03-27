<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

    protected $table = 'comisiones';

    protected $fillable = [
        'empresa_id',
        'compra_id',
        'monto_venta',
        'porcentaje_comision',
        'monto_comision',
        'monto_empresa',
        'estado',
        'fecha_pago',
        'referencia_pago',
        'observaciones'
    ];

    protected $casts = [
        'monto_venta' => 'decimal:2',
        'porcentaje_comision' => 'decimal:2',
        'monto_comision' => 'decimal:2',
        'monto_empresa' => 'decimal:2',
        'fecha_pago' => 'date'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function marcarComoPagada($referenciaPago, $observaciones = null)
    {
        $this->update([
            'estado' => 'pagada',
            'fecha_pago' => now(),
            'referencia_pago' => $referenciaPago,
            'observaciones' => $observaciones
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePorPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }
}