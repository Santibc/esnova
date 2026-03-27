<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMembresia extends Model
{
    protected $table = 'pagos_membresia';
    
    protected $fillable = [
        'empresa_id',
        'membresia_id',
        'monto',
        'referencia_pago',
        'estado',
        'metodo_pago',
        'respuesta_pasarela',
        'fecha_pago'
    ];
    
    protected $casts = [
        'monto' => 'decimal:2',
        'respuesta_pasarela' => 'array',
        'fecha_pago' => 'datetime'
    ];
    
    /**
     * Empresa del pago
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    
    /**
     * Membresía asociada
     */
    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }
    
    /**
     * Aprobar pago
     */
    public function aprobar($datosTransaccion = [])
    {
        $this->update([
            'estado' => 'aprobado',
            'fecha_pago' => now(),
            'respuesta_pasarela' => $datosTransaccion
        ]);
        
        // Activar membresía
        if ($this->membresia) {
            $this->membresia->activar();
        }
    }
    
    /**
     * Rechazar pago
     */
    public function rechazar($motivo = '')
    {
        $this->update([
            'estado' => 'rechazado',
            'respuesta_pasarela' => ['error' => $motivo]
        ]);
    }
}