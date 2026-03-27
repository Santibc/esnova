<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ConfiguracionPasarela extends Model
{
    use HasFactory;

    protected $table = 'configuracion_pasarela';

    protected $fillable = [
        'pasarela',
        'public_key',
        'private_key',
        'event_key',
        'webhook_url',
        'modo_prueba',
        'configuracion_adicional',
        'activo'
    ];

    protected $casts = [
        'modo_prueba' => 'boolean',
        'activo' => 'boolean',
        'configuracion_adicional' => 'array'
    ];

    // Encriptar keys sensibles
    public function setPrivateKeyAttribute($value)
    {
        $this->attributes['private_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getPrivateKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setEventKeyAttribute($value)
    {
        $this->attributes['event_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getEventKeyAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            // Intentar desencriptar
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // Si falla la desencriptación, assumir que es texto plano
            // Esto puede suceder si se cambió la APP_KEY o se guardó sin encriptar
            \Log::warning('Event key no pudo ser desencriptado, usando valor plano', [
                'error' => $e->getMessage(),
                'value_length' => strlen($value)
            ]);
            return $value;
        }
    }

    public static function obtenerConfiguracionActiva($pasarela = 'wompi')
    {
        return static::where('activo', true)
                    ->first();
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}