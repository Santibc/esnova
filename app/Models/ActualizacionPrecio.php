<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualizacionPrecio extends Model
{
    use HasFactory;

    protected $table = 'actualizaciones_precios';

    protected $fillable = [
        'usuario_id',
        'estado',
        'nombre_archivo',
        'ruta_archivo',
        'total_filas',
        'actualizaciones_exitosas',
        'actualizaciones_fallidas',
        'errores',
        'detalles_procesados'
    ];

    protected $casts = [
        'errores' => 'array',
        'detalles_procesados' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getPorcentajeExitoAttribute()
    {
        if ($this->total_filas === 0) return 0;
        return round(($this->actualizaciones_exitosas / $this->total_filas) * 100, 2);
    }

    public function agregarError($fila, $referencia, $mensaje)
    {
        $errores = $this->errores ?? [];
        $errores[] = [
            'fila' => $fila,
            'referencia' => $referencia,
            'mensaje' => $mensaje,
            'timestamp' => now()->toISOString()
        ];
        $this->errores = $errores;
    }

    public function agregarProcesado($fila, $referencia, $listaPrecio, $precioAnterior, $precioNuevo)
    {
        $procesados = $this->detalles_procesados ?? [];
        $procesados[] = [
            'fila' => $fila,
            'referencia' => $referencia,
            'lista_precio' => $listaPrecio,
            'precio_anterior' => $precioAnterior,
            'precio_nuevo' => $precioNuevo,
            'timestamp' => now()->toISOString()
        ];
        $this->detalles_procesados = $procesados;
    }
}