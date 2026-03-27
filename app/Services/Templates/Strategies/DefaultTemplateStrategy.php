<?php

namespace App\Services\Templates\Strategies;

use App\Services\Templates\AbstractTemplateStrategy;

/**
 * Class DefaultTemplateStrategy
 *
 * Estrategia para el template por defecto del sistema.
 * Este es el template original/clásico de la tienda.
 *
 * @package App\Services\Templates\Strategies
 */
class DefaultTemplateStrategy extends AbstractTemplateStrategy
{
    /**
     * @var string Vista index
     */
    protected string $viewIndex = 'tienda.index';

    /**
     * @var string Vista categoría
     */
    protected string $viewCategoria = 'tienda.categoria';

    /**
     * @var string Vista producto
     */
    protected string $viewProducto = 'tienda.producto';

    /**
     * @var string Layout base
     */
    protected string $layout = 'tienda.layout';

    /**
     * {@inheritdoc}
     */
    public function getAssets(): array
    {
        return [
            'css' => [
                // El template default no tiene CSS adicional específico
                // ya que usa los estilos base del sistema
            ],
            'js' => [
                // El template default no tiene JS adicional específico
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * El template default no necesita preparar datos adicionales,
     * retorna los datos tal como los recibe.
     */
    public function prepareData(array $data): array
    {
        return $data;
    }
}
