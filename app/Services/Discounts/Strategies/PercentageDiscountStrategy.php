<?php

namespace App\Services\Discounts\Strategies;

use App\Services\Discounts\Contracts\DiscountStrategyInterface;
use App\Models\Descuento;

class PercentageDiscountStrategy implements DiscountStrategyInterface
{
    public function calculate(Descuento $descuento, float $subtotal, array $items): float
    {
        $descuentoCalculado = $subtotal * ($descuento->valor / 100);

        // Aplicar descuento máximo si está configurado
        if ($descuento->descuento_maximo && $descuentoCalculado > $descuento->descuento_maximo) {
            $descuentoCalculado = $descuento->descuento_maximo;
        }

        return round($descuentoCalculado, 2);
    }

    public function applies(Descuento $descuento, float $subtotal, array $items): bool
    {
        // Validar monto mínimo
        if (!$descuento->cumpleMontominimo($subtotal)) {
            return false;
        }

        // Validar cantidad mínima de productos
        $cantidadTotal = array_sum(array_column($items, 'cantidad'));
        if (!$descuento->cumpleCantidadMinima($cantidadTotal)) {
            return false;
        }

        return true;
    }
}
