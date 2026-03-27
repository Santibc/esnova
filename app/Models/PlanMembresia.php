<?php

// app/Models/PlanMembresia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanMembresia extends Model
{
    protected $table = 'planes_membresia';
    
    protected $fillable = [
        'nombre',
        'slug',
        'precio',
        'limite_productos',
        'limite_transacciones',
        'porcentaje_comision',
        'comision_fija',
        'descripcion',
        'caracteristicas',
        'activo',
        'orden',
        'marca_de_agua'
    ];
    
    protected $casts = [
        'caracteristicas' => 'array',
        'precio' => 'decimal:2',
        'porcentaje_comision' => 'decimal:2',
        'comision_fija' => 'decimal:2',
        'activo' => 'boolean',
        'marca_de_agua' => 'boolean'
    ];
    
    /**
     * Scope para planes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
    
    /**
     * Obtener plan gratuito (fundador)
     */
    public static function planGratuito()
    {
        return static::where('precio', 0)->first();
    }
    
    /**
     * Empresas con este plan
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
    
    /**
     * Membresías de este plan
     */
    public function membresias()
    {
        return $this->hasMany(Membresia::class);
    }
    
    /**
     * Es plan gratuito
     */
    public function esGratuito()
    {
        return $this->precio == 0;
    }
}