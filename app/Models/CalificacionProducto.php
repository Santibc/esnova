<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalificacionProducto extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_productos';

    protected $fillable = [
        'producto_id',
        'user_id',
        'compra_id',
        'item_compra_id',
        'parent_id',
        'nombre_visitante',
        'estrellas',
        'titulo',
        'comentario',
        'imagen',
        'verificada',
        'aprobada',
    ];

    protected $casts = [
        'estrellas' => 'integer',
        'verificada' => 'boolean',
        'aprobada' => 'boolean',
    ];

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con Usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Compra
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    /**
     * Relación con ItemCompra
     */
    public function itemCompra()
    {
        return $this->belongsTo(ItemCompra::class, 'item_compra_id');
    }

    /**
     * Scope para calificaciones aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('aprobada', true);
    }

    /**
     * Scope para calificaciones por producto
     */
    public function scopePorProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    /**
     * Scope para calificaciones por usuario
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para calificaciones verificadas
     */
    public function scopeVerificadas($query)
    {
        return $query->where('verificada', true);
    }

    /**
     * Obtener promedio de estrellas para un producto
     */
    public static function getPromedioEstrellas($productoId)
    {
        return self::porProducto($productoId)
            ->aprobadas()
            ->avg('estrellas') ?? 0;
    }

    /**
     * Obtener distribución de calificaciones para un producto
     */
    public static function getDistribucion($productoId)
    {
        $distribucion = [];

        for ($i = 5; $i >= 1; $i--) {
            $count = self::porProducto($productoId)
                ->aprobadas()
                ->where('estrellas', $i)
                ->count();
            $distribucion[$i] = $count;
        }

        return $distribucion;
    }

    /**
     * Obtener total de calificaciones para un producto
     */
    public static function getTotalCalificaciones($productoId)
    {
        return self::porProducto($productoId)->aprobadas()->count();
    }

    /**
     * Obtener el nombre del autor de la calificación
     * Retorna el nombre del usuario registrado o el nombre_visitante
     */
    public function getNombreAutorAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->nombre_visitante ?? 'Anónimo';
    }

    /**
     * Scope para calificaciones pendientes de aprobación
     */
    public function scopePendientes($query)
    {
        return $query->where('aprobada', false);
    }

    /**
     * Scope para calificaciones principales (no respuestas)
     */
    public function scopePrincipales($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Relación con la calificación padre (si es respuesta)
     */
    public function parent()
    {
        return $this->belongsTo(CalificacionProducto::class, 'parent_id');
    }

    /**
     * Relación con respuestas a esta calificación
     */
    public function respuestas()
    {
        return $this->hasMany(CalificacionProducto::class, 'parent_id');
    }

    /**
     * Respuestas aprobadas
     */
    public function respuestasAprobadas()
    {
        return $this->respuestas()->where('aprobada', true);
    }

    /**
     * Relación con reacciones
     */
    public function reacciones()
    {
        return $this->hasMany(ReaccionCalificacion::class, 'calificacion_id');
    }

    /**
     * Obtener conteo de reacciones por tipo de emoji
     */
    public function getConteoReaccionesAttribute()
    {
        return $this->reacciones()
            ->select('emoji', DB::raw('count(*) as total'))
            ->groupBy('emoji')
            ->pluck('total', 'emoji')
            ->toArray();
    }

    /**
     * Verificar si es una respuesta
     */
    public function esRespuesta()
    {
        return !is_null($this->parent_id);
    }

    /**
     * URL de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset($this->imagen);
        }
        return null;
    }
}
