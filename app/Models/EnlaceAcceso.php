<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Empresa;
class EnlaceAcceso extends Model
{
    use HasFactory;

    protected $table = 'enlaces_acceso';

    protected $fillable = [
        'cliente_id',
        'creado_por',
        'token',
        'dias_validos',
        'mostrar_precios',
        'mostrar_stock', // Nuevo campo
        'expira_en',
        'activo',
        'visitas',
        'ultimo_acceso',
        'empresa_id',
        'notas'
    ];

    protected $casts = [
        'mostrar_precios' => 'boolean',
        'mostrar_stock' => 'boolean', // Nuevo cast
        'activo' => 'boolean',
        'expira_en' => 'datetime',
        'ultimo_acceso' => 'datetime',
    ];
public function empresa()
{
    return $this->belongsTo(Empresa::class);
}

// Agregar scope
public function scopePorEmpresa($query, $empresaId)
{
    return $query->where('empresa_id', $empresaId);
}
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function solicitudesCotizacion()
    {
        return $this->hasMany(SolicitudCotizacion::class, 'enlace_acceso_id');
    }

    public function esValido()
    {
        return $this->activo && $this->expira_en > now();
    }

    public function getUrlAttribute()
    {
        return route('catalogo.token', $this->token);
    }

    // Registrar acceso al enlace
    public function registrarAcceso()
    {
        $this->increment('visitas');
        $this->update(['ultimo_acceso' => now()]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enlace) {
            if (empty($enlace->token)) {
                $enlace->token = Str::random(32);
            }
            if (empty($enlace->expira_en)) {
                $enlace->expira_en = now()->addDays($enlace->dias_validos);
            }
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeValidos($query)
    {
        return $query->where('activo', true)->where('expira_en', '>', now());
    }

    public function scopePorCreador($query, $usuarioId)
    {
        return $query->where('creado_por', $usuarioId);
    }
}