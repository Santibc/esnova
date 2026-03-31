<?php

// 1. Empresa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Membresia;
use App\Models\PlanMembresia;
class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nombre',
        'slug',
        'descripcion',
        'logo',
        'imagen_portada',
        'email',
        'telefono',
        'direccion',
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'whatsapp',
        'horario_atencion',
        'activo',
        'plan_membresia_id',
        'template_tienda_id',
        'hero_video_url',
        'hero_video_message',
        'hero_video_button_text',
        'hero_video_button_link',
        'banner_titulo',
        'banner_subtitulo',
        'banner_imagen',
        'banner_btn1_texto',
        'banner_btn1_link',
        'banner_btn2_texto',
        'banner_btn2_link',
        'monto_minimo_compra'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'horario_atencion' => 'array'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function carruselImagenes()
    {
        return $this->hasMany(CarruselEmpresa::class);
    }

    public function carruselImagenesActivas()
    {
        return $this->hasMany(CarruselEmpresa::class)
            ->where('activo', true)
            ->where(function($q) {
                $q->whereNull('fecha_inicio')
                  ->orWhere('fecha_inicio', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', now());
            })
            ->orderBy('orden');
    }

    public function bannerSlides()
    {
        return $this->hasMany(BannerSlide::class);
    }

    public function bannerSlidesActivos()
    {
        return $this->hasMany(BannerSlide::class)
            ->where('activo', true)
            ->orderBy('orden');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function enlacesAcceso()
    {
        return $this->hasMany(EnlaceAcceso::class);
    }

    public function solicitudesCotizacion()
    {
        return $this->hasMany(SolicitudCotizacion::class);
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class);
    }

    public function pagos()
    {
        return $this->hasMany(PagoEmpresa::class);
    }

    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    public function getUrlAttribute()
    {
        return route('tienda.empresa', $this->slug);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset($this->logo) : asset('images/default-logo.png');
    }

    public function getImagenPortadaUrlAttribute()
    {
        return $this->imagen_portada ? asset($this->imagen_portada) : asset('images/default-cover.jpg');
    }

    public function getBannerImagenUrlAttribute()
    {
        return $this->banner_imagen ? asset($this->banner_imagen) : $this->imagen_portada_url;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($empresa) {
            if (empty($empresa->slug)) {
                $empresa->slug = Str::slug($empresa->nombre);
                
                // Asegurar que el slug sea único
                $count = static::where('slug', 'like', $empresa->slug . '%')->count();
                if ($count > 0) {
                    $empresa->slug = $empresa->slug . '-' . ($count + 1);
                }
            }
        });
    }
    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function categoriasActivas()
    {
        return $this->hasMany(Categoria::class)
                    ->where('activo', true)
                    ->orderBy('orden');
    }
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%");
        });
    }
    public function planMembresia()
    {
        return $this->belongsTo(PlanMembresia::class);
    }

    /**
     * Relación con el template de tienda
     */
    public function templateTienda()
    {
        return $this->belongsTo(TemplateTienda::class, 'template_tienda_id');
    }

    /**
     * Obtener el template de tienda (con fallback al default)
     *
     * @return TemplateTienda
     */
    public function getTemplate()
    {
        return $this->templateTienda ?? TemplateTienda::getDefault();
    }

    /**
     * Obtener el plan de la membresía activa (atributo computado)
     */
    public function getPlanMembresiaActivaAttribute()
    {
        $membresiaActiva = $this->membresiaActiva;
        if ($membresiaActiva && $membresiaActiva->plan) {
            return $membresiaActiva->plan;
        }
        
        // Fallback al plan asociado directamente
        return $this->planMembresia;
    }

    /**
     * Obtener límite de productos del plan activo
     */
    public function getLimiteProductosAttribute()
    {
        return $this->planMembresia?->limite_productos ?? 10;
    }

    /**
     * Obtener porcentaje de comisión del plan activo
     */
    public function getPorcentajeComisionAttribute()
    {
        return $this->planMembresia?->porcentaje_comision ?? 5.00;
    }

    /**
     * Obtener comisión fija del plan activo
     */
    public function getComisionFijaAttribute()
    {
        return $this->planMembresia?->comision_fija ?? 0.00;
    }

    /**
     * Obtener cargo fijo de comisión del plan activo (alias de comision_fija)
     */
    public function getCargoFijoComisionAttribute()
    {
        return $this->getComisionFijaAttribute();
    }

/**
 * Historial de membresías
 */
public function membresias()
{
    return $this->hasMany(Membresia::class);
}

/**
 * Membresía activa actual
 */
public function membresiaActiva()
{
    return $this->hasOne(Membresia::class)
                ->where('estado', 'activa')
                ->where(function($query) {
                    $query->where('fecha_fin', '>', now()->toDateString())
                          ->orWhereNull('fecha_fin');
                })
                ->latest('id'); // Usar la más reciente por ID
}

/**
 * Verificar si puede crear más productos
 */
public function puedeCrearProductos()
{
    $totalProductos = $this->productos()->where('activo', true)->count();
    return $totalProductos < $this->limite_productos;
}

/**
 * Productos restantes que puede crear
 */
public function productosRestantes()
{
    $totalProductos = $this->productos()->where('activo', true)->count();
    return max(0, $this->limite_productos - $totalProductos);
}

/**
 * Tiene plan premium (no gratuito)
 */
public function tienePlanPremium()
{
    return $this->planMembresia && $this->planMembresia->precio > 0;
}

/**
 * Calcular comisión para un monto
 */
public function calcularComision($monto)
{
    $porcentajeComision = $this->planMembresia?->porcentaje_comision ?? 5.00;
    $comisionFija = $this->planMembresia?->comision_fija ?? 0.00;
    
    $comisionPorcentaje = ($monto * $porcentajeComision) / 100;
    return $comisionPorcentaje + $comisionFija;
}

/**
 * Verificar y actualizar estado de membresía
 */
public function verificarYActualizarMembresia()
{
    try {
        // Obtener la membresía que tiene estado "activa" sin importar si está vencida
        $membresiaConEstadoActiva = $this->membresias()
                                         ->where('estado', 'activa')
                                         ->latest('id')
                                         ->first();
        
        // Caso 1: Membresía vencida (tiene estado activa pero fecha pasada)
        if ($membresiaConEstadoActiva && $membresiaConEstadoActiva->fecha_fin && $membresiaConEstadoActiva->fecha_fin < now()->toDateString()) {
            \DB::beginTransaction();
            
            // Membresía vencida, marcar como vencida
            $membresiaConEstadoActiva->update(['estado' => 'vencida']);
            
            // Obtener plan gratuito
            $planGratuito = PlanMembresia::where('precio', 0)->first();
            
            if ($planGratuito) {
                // Crear nueva membresía gratuita
                Membresia::create([
                    'empresa_id' => $this->id,
                    'plan_membresia_id' => $planGratuito->id,
                    'estado' => 'activa',
                    'fecha_inicio' => now(),
                    'fecha_fin' => null, // Plan gratuito no expira
                    'precio_pagado' => 0
                ]);
                
                // Actualizar empresa con ID del plan gratuito
                $this->update(['plan_membresia_id' => $planGratuito->id]);
                
                // Desactivar productos excedentes (más recientes primero)
                $this->desactivarProductosExcedentes($planGratuito->limite_productos);
            }
            
            \DB::commit();
            return true;
        }
        
        // Obtener membresía realmente activa (no vencida)
        $membresiaRealmenteActiva = $this->membresiaActiva;
        
        // Caso 2: Sincronizar plan_membresia_id con membresía activa
        if ($membresiaRealmenteActiva && $this->plan_membresia_id !== $membresiaRealmenteActiva->plan_membresia_id) {
            \Log::info("Sincronizando empresa {$this->id}: plan {$this->plan_membresia_id} -> {$membresiaRealmenteActiva->plan_membresia_id}");
            
            // Solo actualizar el plan_membresia_id
            $resultado = $this->update(['plan_membresia_id' => $membresiaRealmenteActiva->plan_membresia_id]);
            
            \Log::info("Resultado del update: " . ($resultado ? 'exitoso' : 'fallido'));
            
            return true;
        }
        
        // Caso 3: No hay membresía activa pero debería tener plan gratuito
        if (!$membresiaRealmenteActiva) {
            \DB::beginTransaction();
            
            $planGratuito = PlanMembresia::where('precio', 0)->first();
            
            if ($planGratuito) {
                // Crear membresía gratuita
                Membresia::create([
                    'empresa_id' => $this->id,
                    'plan_membresia_id' => $planGratuito->id,
                    'estado' => 'activa',
                    'fecha_inicio' => now(),
                    'fecha_fin' => null,
                    'precio_pagado' => 0
                ]);
                
                // Actualizar empresa
                $this->update(['plan_membresia_id' => $planGratuito->id]);
                
                \DB::commit();
                return true;
            }
            
            \DB::rollBack();
        }
        
        return false;
        
    } catch (\Exception $e) {
        if (\DB::transactionLevel() > 0) {
            \DB::rollBack();
        }
        \Log::error('Error al verificar y actualizar membresía: ' . $e->getMessage());
        return false;
    }
}

/**
 * Desactivar productos que exceden el límite del plan
 */
private function desactivarProductosExcedentes($limiteProductos)
{
    $productosActivos = $this->productos()
                            ->where('activo', true)
                            ->orderBy('created_at', 'desc')
                            ->get();
    
    if ($productosActivos->count() > $limiteProductos) {
        $productosADesactivar = $productosActivos->skip($limiteProductos);
        
        foreach ($productosADesactivar as $producto) {
            $producto->update(['activo' => false]);
        }
    }
}
}