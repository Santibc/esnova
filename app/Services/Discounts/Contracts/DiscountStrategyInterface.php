<?php

namespace App\Services\Discounts\Contracts;

use App\Models\Descuento;

interface DiscountStrategyInterface
{
    /**
     * Calcular el monto de descuento
     *
     * @param Descuento $descuento
     * @param float $subtotal
     * @param array $items
     * @return float
     */
    public function calculate(Descuento $descuento, float $subtotal, array $items): float;

    /**
     * Validar si el descuento aplica
     *
     * @param Descuento $descuento
     * @param float $subtotal
     * @param array $items
     * @return bool
     */
    public function applies(Descuento $descuento, float $subtotal, array $items): bool;
}
