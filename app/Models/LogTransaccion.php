<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaccion extends Model
{
    use HasFactory;

    protected $table = 'logs_transacciones';

    protected $fillable = [
        'transaccion_pago_id',
        'evento',
        'datos_evento',
        'ip_origen'
    ];

    protected $casts = [
        'datos_evento' => 'array'
    ];

    public function transaccion()
    {
        return $this->belongsTo(TransaccionPago::class, 'transaccion_pago_id');
    }

    public function scopePorEvento($query, $evento)
    {
        return $query->where('evento', $evento);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}