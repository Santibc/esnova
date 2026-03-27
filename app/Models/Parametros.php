<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
    // Nombre de la tabla
    protected $table = 'parametros';

    // Nombre de la clave primaria
    protected $primaryKey = 'id_parametro';

    // No utiliza timestamps automáticos (created_at, updated_at)
    public $timestamps = false;

    // Casts para campos booleanos y fechas
    protected $casts = [
        'estado' => 'boolean',
        'reservado' => 'boolean',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre_parametro',
        'valor_parametro',
        'estado',
        'ayuda',
        'reservado',
        'created',
        'updated',
    ];
}
