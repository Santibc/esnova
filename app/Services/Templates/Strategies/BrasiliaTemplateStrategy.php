<?php

namespace App\Services\Templates\Strategies;

use App\Services\Templates\AbstractTemplateStrategy;

/**
 * Class BrasiliaTemplateStrategy
 *
 * Estrategia para el template Brasilia.
 * Template dinámico inspirado en tiendas de moda con carruseles,
 * animaciones y diseño vibrante.
 *
 * @package App\Services\Templates\Strategies
 */
class BrasiliaTemplateStrategy extends AbstractTemplateStrategy
{
    /**
     * @var string Vista index
     */
    protected string $viewIndex = 'tienda.brasilia_index';

    /**
     * @var string Vista categoría
     */
    protected string $viewCategoria = 'tienda.brasilia_categoria';

    /**
     * @var string Vista producto
     */
    protected string $viewProducto = 'tienda.brasilia_producto';

    /**
     * @var string Layout base
     */
    protected string $layout = 'tienda.brasilia_layout';

    /**
     * {@inheritdoc}
     */
    public function getAssets(): array
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                asset('css/brasilia-theme.css'),
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js',
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                asset('js/brasilia-theme.js'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * Brasilia puede necesitar preparar datos de forma específica.
     * Por ejemplo, agregar configuraciones específicas del template.
     */
    public function prepareData(array $data): array
    {
        // Obtener configuraciones del template
        $showAdBars = $this->getConfigValue('mostrar_adbars', true);
        $enableAnimations = $this->getConfigValue('habilitar_animaciones', true);

        // Agregar configuraciones específicas de Brasilia al array de datos
        $data['brasiliaConfig'] = [
            'showAdBars' => $showAdBars,
            'enableAnimations' => $enableAnimations,
            'colorPrimario' => $this->getConfigValue('color_primario', '#1a1a1a'),
            'colorSecundario' => $this->getConfigValue('color_secundario', '#78b13f'),
        ];

        return $data;
    }
}
