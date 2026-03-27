<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'numero_identificacion',
        'nombre_contacto',
        'email',
        'telefono',
  'ciudad_id',
        'vendedor_id',
        'lista_precio_id',
        'activo',
        'empresa_id ',
         'pais_id'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
    public function empresa()
{
    return $this->belongsTo(Empresa::class);
}

// Modificar scope para incluir empresa
public function scopePorEmpresa($query, $empresaId)
{
    return $query->where('empresa_id', $empresaId);
}
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function listaPrecio()
    {
        return $this->belongsTo(ListaPrecio::class, 'lista_precio_id');
    }

    public function enlacesAcceso()
    {
        return $this->hasMany(EnlaceAcceso::class, 'cliente_id');
    }

    public function enlacesAccesoActivos()
    {
        return $this->hasMany(EnlaceAcceso::class, 'cliente_id')
            ->where('activo', true)
            ->where('expira_en', '>', now());
    }

    public function solicitudesCotizacion()
    {
        return $this->hasMany(SolicitudCotizacion::class, 'cliente_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorVendedor($query, $vendedorId)
    {
        return $query->where('vendedor_id', $vendedorId);
    }
}