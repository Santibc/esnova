<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReaccionCalificacion extends Model
{
    protected $table = 'reacciones_calificaciones';

    protected $fillable = [
        'calificacion_id',
        'visitor_id',
        'emoji',
    ];

    /**
     * Emojis disponibles para reacciones
     */
    const EMOJIS_DISPONIBLES = [
        'hearts' => '😍',
        'wink' => '😉',
        'kiss' => '😘',
        'thumbsup' => '👍',
    ];

    /**
     * Relación con la calificación
     */
    public function calificacion()
    {
        return $this->belongsTo(CalificacionProducto::class, 'calificacion_id');
    }

    /**
     * Obtener el emoji unicode
     */
    public function getEmojiUnicodeAttribute()
    {
        return self::EMOJIS_DISPONIBLES[$this->emoji] ?? $this->emoji;
    }

    /**
     * Generar visitor_id único desde session e IP
     */
    public static function generarVisitorId()
    {
        $sessionId = session()->getId();
        return hash('sha256', $sessionId . request()->ip());
    }
}
