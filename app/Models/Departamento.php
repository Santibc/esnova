<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'pais_id',
        'nombre',
    ];

    /**
     * Un departamento pertenece a un país.
     */
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    /**
     * Un departamento tiene muchas ciudades.
     */
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class);
    }
}
