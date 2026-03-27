<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaPrecio extends Model
{
    use HasFactory;

    protected $table = 'listas_precios';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function preciosProductos()
    {
        return $this->hasMany(PrecioProducto::class, 'lista_precio_id');
    }

    public function preciosVariantes()
    {
        return $this->hasMany(PrecioVariante::class, 'lista_precio_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'lista_precio_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}