<?php

namespace App\Services\Discounts\Strategies;

use App\Services\Discounts\Contracts\DiscountStrategyInterface;
use App\Models\Descuento;

class FixedAmountDiscountStrategy implements DiscountStrategyInterface
{
    public function calculate(Descuento $descuento, float $subtotal, array $items): float
    {
        // El descuento no puede ser mayor al subtotal
        return min($descuento->valor, $subtotal);
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
