<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pais extends Model
{
    use HasFactory;

    // Opcional: si tu tabla se llama "paises" y usas la convención, no es necesario:
     protected $table = 'paises';

    protected $fillable = [
        'nombre',
    ];

    /**
     * Un país tiene muchos departamentos.
     */
    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }
}
