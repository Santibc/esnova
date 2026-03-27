<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarruselEmpresa extends Model
{
    use HasFactory;

    protected $table = 'carrusel_empresas';

    protected $fillable = [
        'empresa_id',
        'imagen',
        'titulo',
        'descripcion',
        'link',
        'orden',
        'activo',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getImagenUrlAttribute()
    {
        // Si la imagen existe, genera la URL con asset(), de lo contrario puedes retornar una URL por defecto
        return $this->imagen ? asset($this->imagen) : null;
    }

    public function estaActivo()
    {
        if (!$this->activo) {
            return false;
        }

        $ahora = now();

        if ($this->fecha_inicio && $ahora < $this->fecha_inicio) {
            return false;
        }

        if ($this->fecha_fin && $ahora > $this->fecha_fin) {
            return false;
        }

        return true;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)
            ->where(function($q) {
                $q->whereNull('fecha_inicio')
                  ->orWhere('fecha_inicio', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', now());
            });
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }
}
