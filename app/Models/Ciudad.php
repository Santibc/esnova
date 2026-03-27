<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ciudad extends Model
{
    use HasFactory;

     protected $table = 'ciudades';

    protected $fillable = [
        'departamento_id',
        'nombre',
    ];

    /**
     * Una ciudad pertenece a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

        public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
