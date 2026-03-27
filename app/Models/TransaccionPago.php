<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionPago extends Model
{
    use HasFactory;

    protected $table = 'transacciones_pago';

    protected $fillable = [
        'compra_id',
        'pasarela',
        'referencia_transaccion',
        'id_transaccion_pasarela',
        'monto',
        'moneda',
        'estado',
        'metodo_pago',
        'respuesta_pasarela',
        'codigo_autorizacion',
        'fecha_procesamiento',
        'mensaje_error'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'respuesta_pasarela' => 'array',
        'fecha_procesamiento' => 'datetime'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function logs()
    {
        return $this->hasMany(LogTransaccion::class, 'transaccion_pago_id');
    }

    public function esExitosa()
    {
        return $this->estado === 'aprobada';
    }

    public function estaPendiente()
    {
        return in_array($this->estado, ['pendiente', 'procesando']);
    }

    public function registrarEvento($evento, $datos = null, $ipOrigen = null)
    {
        return $this->logs()->create([
            'evento' => $evento,
            'datos_evento' => $datos,
            'ip_origen' => $ipOrigen ?? request()->ip()
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaccion) {
            if (empty($transaccion->referencia_transaccion)) {
                $transaccion->referencia_transaccion = 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(\Str::random(6));
            }
        });

        static::updated(function ($transaccion) {
            if ($transaccion->isDirty('estado')) {
                $transaccion->registrarEvento('cambio_estado', [
                    'estado_anterior' => $transaccion->getOriginal('estado'),
                    'estado_nuevo' => $transaccion->estado
                ]);

                // Si la transacción fue aprobada, actualizar el estado de la compra
                if ($transaccion->estado === 'aprobada') {
                    $transaccion->compra->update(['estado' => 'pagada']);
                    $transaccion->compra->generarComision();
                }
            }
        });
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
