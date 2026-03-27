<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateTienda extends Model
{
    use HasFactory;

    protected $table = 'templates_tienda';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'vista_index',
        'vista_categoria',
        'vista_producto',
        'layout',
        'preview_image',
        'activo',
        'configuracion',
        'orden',
        'es_default',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'es_default' => 'boolean',
        'configuracion' => 'array',
    ];

    /**
     * Scope para obtener solo templates activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar templates
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /**
     * Relación con empresas que usan este template
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'template_tienda_id');
    }

    /**
     * Obtener array con las rutas de las vistas
     *
     * @return array
     */
    public function getVistas(): array
    {
        return [
            'index' => $this->vista_index,
            'categoria' => $this->vista_categoria,
            'producto' => $this->vista_producto,
            'layout' => $this->layout,
        ];
    }

    /**
     * Obtener el template por defecto
     *
     * @return TemplateTienda|null
     */
    public static function getDefault(): ?TemplateTienda
    {
        return static::where('es_default', true)->first()
            ?? static::activos()->ordenados()->first();
    }

    /**
     * Verificar si es el template por defecto
     *
     * @return bool
     */
    public function esDefault(): bool
    {
        return $this->es_default;
    }

    /**
     * Obtener URL de la imagen preview
     *
     * @return string
     */
    public function getPreviewUrlAttribute(): string
    {
        return $this->preview_image
            ? asset($this->preview_image)
            : asset('images/templates/default-preview.jpg');
    }
}
