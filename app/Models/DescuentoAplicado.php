<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescuentoAplicado extends Model
{
    use HasFactory;

    protected $table = 'descuentos_aplicados';

    protected $fillable = [
        'descuento_id',
        'compra_id',
        'email_cliente',
        'monto_descuento',
    ];

    protected $casts = [
        'monto_descuento' => 'decimal:2',
    ];

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}
