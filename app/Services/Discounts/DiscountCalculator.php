<?php

namespace App\Services\Discounts;

use App\Models\Descuento;
use App\Services\Discounts\Contracts\DiscountStrategyInterface;
use App\Services\Discounts\Strategies\PercentageDiscountStrategy;
use App\Services\Discounts\Strategies\FixedAmountDiscountStrategy;

class DiscountCalculator
{
    private array $strategies = [];

    public function __construct()
    {
        $this->strategies = [
            'porcentaje' => new PercentageDiscountStrategy(),
            'monto_fijo' => new FixedAmountDiscountStrategy(),
        ];
    }

    /**
     * Calcular descuentos aplicables al carrito
     *
     * @param array $items
     * @param float $subtotal
     * @param int $empresaId
     * @param string|null $codigoDescuento
     * @param string|null $emailCliente
     * @return array
     */
    public function calculateDiscounts(
        array $items,
        float $subtotal,
        int $empresaId,
        ?string $codigoDescuento = null,
        ?string $emailCliente = null
    ): array {
        $descuentosAplicados = [];
        $totalDescuento = 0;

        // Si se proporcionó un código de descuento, intentar aplicarlo
        if ($codigoDescuento) {
            $descuento = Descuento::porCodigo($codigoDescuento)
                ->porEmpresa($empresaId)
                ->activos()
                ->vigentes()
                ->disponibles()
                ->first();

            if ($descuento && $this->canApplyDiscount($descuento, $subtotal, $items, $emailCliente)) {
                $montoDescuento = $this->calculateDiscountAmount($descuento, $subtotal, $items);

                if ($montoDescuento > 0) {
                    $descuentosAplicados[] = [
                        'descuento' => $descuento,
                        'monto' => $montoDescuento,
                        'codigo' => $descuento->codigo,
                        'descripcion' => $descuento->getDescripcionCompletaAttribute(),
                    ];

                    $totalDescuento += $montoDescuento;
                }
            }
        }

        // Buscar descuentos automáticos aplicables (sin código)
        $descuentosAutomaticos = Descuento::porEmpresa($empresaId)
            ->activos()
            ->vigentes()
            ->disponibles()
            ->whereNull('codigo') // Descuentos sin código son automáticos
            ->porPrioridad()
            ->get();

        foreach ($descuentosAutomaticos as $descuento) {
            // Si el descuento no es acumulable y ya hay descuentos aplicados, saltar
            if (!$descuento->es_acumulable && count($descuentosAplicados) > 0) {
                continue;
            }

            if ($this->canApplyDiscount($descuento, $subtotal, $items, $emailCliente)) {
                $montoDescuento = $this->calculateDiscountAmount($descuento, $subtotal, $items);

                if ($montoDescuento > 0) {
                    $descuentosAplicados[] = [
                        'descuento' => $descuento,
                        'monto' => $montoDescuento,
                        'codigo' => null,
                        'descripcion' => $descuento->getDescripcionCompletaAttribute(),
                    ];

                    $totalDescuento += $montoDescuento;

                    // Si no es acumulable, romper el bucle
                    if (!$descuento->es_acumulable) {
                        break;
                    }
                }
            }
        }

        // Calcular descuentos por item individual
        $descuentosPorItem = $this->calculateItemDiscounts($items, $descuentosAplicados);

        return [
            'descuentos' => $descuentosAplicados,
            'total_descuento' => round($totalDescuento, 2),
            'descuentos_por_item' => $descuentosPorItem,
        ];
    }

    /**
     * Verificar si se puede aplicar un descuento
     */
    private function canApplyDiscount(
        Descuento $descuento,
        float $subtotal,
        array $items,
        ?string $emailCliente
    ): bool {
        // Verificar vigencia
        if (!$descuento->estaVigente()) {
            return false;
        }

        // Verificar usos disponibles
        if (!$descuento->tieneUsosDisponibles()) {
            return false;
        }

        // Verificar usos por cliente
        if ($emailCliente && !$descuento->puedeUsarCliente($emailCliente)) {
            return false;
        }

        // Verificar si aplica según el ámbito
        if (!$this->appliesToItems($descuento, $items)) {
            return false;
        }

        // Obtener estrategia y validar
        $strategy = $this->getStrategy($descuento->tipo);
        if (!$strategy) {
            return false;
        }

        return $strategy->applies($descuento, $subtotal, $items);
    }

    /**
     * Verificar si el descuento aplica a los items del carrito
     */
    private function appliesToItems(Descuento $descuento, array $items): bool
    {
        // Si aplica a toda la orden, siempre es válido
        if ($descuento->aplica_a === 'orden' || $descuento->aplica_a === 'carrito') {
            return true;
        }

        // Si aplica a productos específicos
        if ($descuento->aplica_a === 'producto') {
            $productosAplicables = $descuento->productos_aplicables ?? [];

            if (empty($productosAplicables)) {
                return false;
            }

            // Verificar si hay al menos un item en el carrito que esté en la lista de productos aplicables
            foreach ($items as $item) {
                $productoId = is_object($item) ? $item->producto_id : ($item['producto_id'] ?? null);

                if (in_array($productoId, $productosAplicables)) {
                    return true;
                }
            }

            return false;
        }

        // Si aplica a categorías específicas
        if ($descuento->aplica_a === 'categoria') {
            $categoriasAplicables = $descuento->categorias_aplicables ?? [];

            if (empty($categoriasAplicables)) {
                return false;
            }

            // Verificar si hay al menos un item cuyo producto pertenezca a alguna de las categorías
            foreach ($items as $item) {
                $producto = is_object($item) ? $item->producto : null;

                // Si no tenemos acceso al producto, intentar cargarlo
                if (!$producto && isset($item['producto_id'])) {
                    $producto = \App\Models\Producto::find($item['producto_id']);
                }

                if ($producto && in_array($producto->categoria_id, $categoriasAplicables)) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    /**
     * Calcular el monto del descuento
     */
    private function calculateDiscountAmount(
        Descuento $descuento,
        float $subtotal,
        array $items
    ): float {
        // Si el descuento aplica a productos o categorías específicas,
        // calcular solo sobre esos items
        if ($descuento->aplica_a === 'producto' || $descuento->aplica_a === 'categoria') {
            return $this->calculateItemSpecificDiscount($descuento, $items);
        }

        // Si aplica a toda la orden, calcular sobre el subtotal completo
        $strategy = $this->getStrategy($descuento->tipo);

        if (!$strategy) {
            return 0;
        }

        return $strategy->calculate($descuento, $subtotal, $items);
    }

    /**
     * Calcular descuento para productos/categorías específicas
     */
    private function calculateItemSpecificDiscount(Descuento $descuento, array $items): float
    {
        $subtotalAplicable = 0;
        $itemsAplicables = [];

        foreach ($items as $item) {
            $aplicaAEsteItem = false;

            // Verificar si aplica según el tipo de ámbito
            if ($descuento->aplica_a === 'producto') {
                $productosAplicables = $descuento->productos_aplicables ?? [];
                $productoId = is_object($item) ? $item->producto_id : ($item['producto_id'] ?? null);
                $aplicaAEsteItem = in_array($productoId, $productosAplicables);
            } elseif ($descuento->aplica_a === 'categoria') {
                $categoriasAplicables = $descuento->categorias_aplicables ?? [];
                $producto = is_object($item) ? $item->producto : null;

                if (!$producto && isset($item['producto_id'])) {
                    $producto = \App\Models\Producto::find($item['producto_id']);
                }

                $aplicaAEsteItem = $producto && in_array($producto->categoria_id, $categoriasAplicables);
            }

            // Si aplica a este item, agregarlo al cálculo
            if ($aplicaAEsteItem) {
                $precioTotal = is_object($item) ? $item->precio_total : ($item['precio_total'] ?? 0);
                $subtotalAplicable += $precioTotal;
                $itemsAplicables[] = $item;
            }
        }

        // Si no hay items aplicables, retornar 0
        if (empty($itemsAplicables) || $subtotalAplicable === 0) {
            return 0;
        }

        // Calcular el descuento sobre el subtotal de items aplicables
        $strategy = $this->getStrategy($descuento->tipo);

        if (!$strategy) {
            return 0;
        }

        return $strategy->calculate($descuento, $subtotalAplicable, $itemsAplicables);
    }

    /**
     * Obtener la estrategia correspondiente al tipo de descuento
     */
    private function getStrategy(string $tipo): ?DiscountStrategyInterface
    {
        return $this->strategies[$tipo] ?? null;
    }

    /**
     * Calcular descuentos aplicados a cada item individual
     */
    private function calculateItemDiscounts(array $items, array $descuentosAplicados): array
    {
        $descuentosPorItem = [];

        foreach ($descuentosAplicados as $descuentoData) {
            $descuento = $descuentoData['descuento'];

            // Si el descuento aplica a toda la orden, dividir proporcionalmente
            if ($descuento->aplica_a === 'orden' || $descuento->aplica_a === 'carrito') {
                $this->distributeOrderDiscount($items, $descuentoData, $descuentosPorItem);
            }
            // Si aplica a productos o categorías específicas
            elseif ($descuento->aplica_a === 'producto' || $descuento->aplica_a === 'categoria') {
                $this->distributeItemSpecificDiscount($items, $descuento, $descuentoData, $descuentosPorItem);
            }
        }

        return $descuentosPorItem;
    }

    /**
     * Distribuir descuento de orden proporcionalmente entre items
     */
    private function distributeOrderDiscount(array $items, array $descuentoData, array &$descuentosPorItem): void
    {
        $descuento = $descuentoData['descuento'];
        $montoTotal = $descuentoData['monto'];
        $subtotalCarrito = 0;

        // Calcular subtotal del carrito
        foreach ($items as $key => $item) {
            $precioTotal = is_object($item) ? $item->precio_total : ($item['precio_total'] ?? 0);
            $subtotalCarrito += $precioTotal;
        }

        if ($subtotalCarrito == 0) return;

        // Distribuir proporcionalmente
        foreach ($items as $key => $item) {
            $precioTotal = is_object($item) ? $item->precio_total : ($item['precio_total'] ?? 0);
            $proporcion = $precioTotal / $subtotalCarrito;
            $descuentoItem = $montoTotal * $proporcion;

            if (!isset($descuentosPorItem[$key])) {
                $descuentosPorItem[$key] = [];
            }

            $descuentosPorItem[$key][] = [
                'descuento_id' => $descuento->id,
                'nombre' => $descuento->nombre,
                'monto' => round($descuentoItem, 2),
                'porcentaje' => $descuento->tipo === 'porcentaje' ? $descuento->valor : null,
            ];
        }
    }

    /**
     * Distribuir descuento de producto/categoría específica
     */
    private function distributeItemSpecificDiscount(array $items, $descuento, array $descuentoData, array &$descuentosPorItem): void
    {
        $subtotalAplicable = 0;
        $itemsAplicables = [];

        // Identificar items aplicables y calcular subtotal
        foreach ($items as $key => $item) {
            $aplicaAEsteItem = false;

            if ($descuento->aplica_a === 'producto') {
                $productosAplicables = $descuento->productos_aplicables ?? [];
                $productoId = is_object($item) ? $item->producto_id : ($item['producto_id'] ?? null);
                $aplicaAEsteItem = in_array($productoId, $productosAplicables);
            } elseif ($descuento->aplica_a === 'categoria') {
                $categoriasAplicables = $descuento->categorias_aplicables ?? [];
                $producto = is_object($item) ? $item->producto : null;

                if (!$producto && isset($item['producto_id'])) {
                    $producto = \App\Models\Producto::find($item['producto_id']);
                }

                $aplicaAEsteItem = $producto && in_array($producto->categoria_id, $categoriasAplicables);
            }

            if ($aplicaAEsteItem) {
                $precioTotal = is_object($item) ? $item->precio_total : ($item['precio_total'] ?? 0);
                $subtotalAplicable += $precioTotal;
                $itemsAplicables[$key] = $item;
            }
        }

        if ($subtotalAplicable == 0) return;

        // Distribuir descuento entre items aplicables
        $montoTotal = $descuentoData['monto'];

        foreach ($itemsAplicables as $key => $item) {
            $precioTotal = is_object($item) ? $item->precio_total : ($item['precio_total'] ?? 0);
            $proporcion = $precioTotal / $subtotalAplicable;
            $descuentoItem = $montoTotal * $proporcion;

            if (!isset($descuentosPorItem[$key])) {
                $descuentosPorItem[$key] = [];
            }

            $descuentosPorItem[$key][] = [
                'descuento_id' => $descuento->id,
                'nombre' => $descuento->nombre,
                'monto' => round($descuentoItem, 2),
                'porcentaje' => $descuento->tipo === 'porcentaje' ? $descuento->valor : null,
            ];
        }
    }
}
