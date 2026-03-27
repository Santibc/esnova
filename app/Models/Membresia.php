<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Membresia extends Model
{
    protected $fillable = [
        'empresa_id',
        'plan_membresia_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'precio_pagado',
        'transaccion_pago_id'
    ];
    
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'precio_pagado' => 'decimal:2'
    ];
    
    /**
     * Empresa de la membresía
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    
    /**
     * Plan de membresía
     */
    public function plan()
    {
        return $this->belongsTo(PlanMembresia::class, 'plan_membresia_id');
    }
    
    /**
     * Transacción de pago
     */
    public function transaccionPago()
    {
        return $this->belongsTo(TransaccionPago::class);
    }
    
    /**
     * Pagos de esta membresía
     */
    public function pagos()
    {
        return $this->hasMany(PagoMembresia::class);
    }
    
    /**
     * Activar membresía
     */
    public function activar()
    {
        \DB::beginTransaction();
        
        try {
            // Cancelar todas las membresías activas previas de la empresa
            $this->empresa->membresias()
                ->where('estado', 'activa')
                ->where('id', '!=', $this->id)
                ->update(['estado' => 'cancelada']);
            
            // Activar esta membresía
            $fechaFin = $this->plan->esGratuito() ? null : now()->addMonth();
            
            $this->update([
                'estado' => 'activa',
                'fecha_inicio' => now(),
                'fecha_fin' => $fechaFin
            ]);
            
            // Actualizar empresa con el ID del plan solamente
            $this->empresa->update(['plan_membresia_id' => $this->plan_membresia_id]);
            
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Cancelar membresía
     */
    public function cancelar()
    {
        $this->update(['estado' => 'cancelada']);
        
        // Regresar a plan gratuito
        $planGratuito = PlanMembresia::planGratuito();
        if ($planGratuito) {
            $this->empresa->update(['plan_membresia_id' => $planGratuito->id]);
        }
    }
    
    /**
     * Verificar si está activa
     */
    public function estaActiva()
    {
        return $this->estado === 'activa' && 
               ($this->fecha_fin === null || $this->fecha_fin > now()->toDateString());
    }
    
    /**
     * Días restantes
     */
    public function diasRestantes()
    {
        if (!$this->estaActiva()) {
            return 0;
        }
        
        // Si no tiene fecha de fin (plan gratuito/permanente)
        if ($this->fecha_fin === null) {
            return 999; // Indicador de "ilimitado"
        }
        
        return now()->diffInDays($this->fecha_fin);
    }
    
    /**
     * Scope para membresías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa')
                     ->where(function($q) {
                         $q->where('fecha_fin', '>', now()->toDateString())
                           ->orWhereNull('fecha_fin');
                     });
    }
    
    /**
     * Scope para membresías por vencer
     */
    public function scopePorVencer($query, $dias = 7)
    {
        return $query->where('estado', 'activa')
                     ->whereBetween('fecha_fin', [now(), now()->addDays($dias)]);
    }
}