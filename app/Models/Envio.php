<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra_id',
        'transportadora',
        'numero_guia',
        'estado',
        'fecha_envio',
        'fecha_entrega_estimada',
        'fecha_entrega',
        'url_seguimiento',
        'observaciones'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_entrega_estimada' => 'datetime',
        'fecha_entrega' => 'datetime'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function actualizarEstado($estado, $observaciones = null)
    {
        $datos = ['estado' => $estado];
        
        if ($observaciones) {
            $datos['observaciones'] = $observaciones;
        }

        if ($estado === 'enviado' && !$this->fecha_envio) {
            $datos['fecha_envio'] = now();
        }

        if ($estado === 'entregado' && !$this->fecha_entrega) {
            $datos['fecha_entrega'] = now();
            
            // Actualizar estado de la compra
            $this->compra->update(['estado' => 'entregada']);
        }

        $this->update($datos);
    }

    public function getDiasTransitoAttribute()
    {
        if (!$this->fecha_envio) {
            return null;
        }

        $fechaFin = $this->fecha_entrega ?? now();
        return $this->fecha_envio->diffInDays($fechaFin);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeEnTransito($query)
    {
        return $query->whereIn('estado', ['enviado', 'en_transito']);
    }
}