<?php

namespace App\Services\Templates\Strategies;

use App\Services\Templates\AbstractTemplateStrategy;

class LimaTemplateStrategy extends AbstractTemplateStrategy
{
    protected string $viewIndex = 'tienda.lima_index';
    protected string $viewCategoria = 'tienda.lima_categoria';
    protected string $viewProducto = 'tienda.lima_producto';
    protected string $layout = 'tienda.lima_layout';

    /**
     * Return specific assets for Lima template
     *
     * @return array
     */
    public function getAssets(): array
    {
        return [
            'css' => [
                'https://fonts.googleapis.com/css?family=Lexend+Exa:400,700|Lexend:400,700&display=swap',
                'https://unpkg.com/swiper@4.4.2/dist/css/swiper.min.css',
                asset('css/lima-theme.css'),
            ],
            'js' => [
                'https://code.jquery.com/jquery-3.6.0.min.js',
                'https://unpkg.com/swiper@4.4.2/dist/js/swiper.min.js',
                asset('js/lima-theme.js'),
            ],
        ];
    }

    /**
     * Transform/prepare data specific to Lima template
     *
     * @param array $data
     * @return array
     */
    public function prepareData(array $data): array
    {
        // Add Lima-specific configuration
        $data['limaConfig'] = [
            'stickyHeader' => $this->getConfigValue('sticky_header', true),
            'showAdbar' => $this->getConfigValue('show_adbar', true),
            'showTopbar' => $this->getConfigValue('show_topbar', true),
            'productGridColumnsMobile' => $this->getConfigValue('product_grid_columns_mobile', 2),
            'productGridColumnsDesktop' => $this->getConfigValue('product_grid_columns_desktop', 4),
            'enableAnimations' => $this->getConfigValue('enable_animations', true),
        ];

        // Ensure required data exists
        if (!isset($data['categorias'])) {
            $data['categorias'] = collect([]);
        }

        if (!isset($data['descuentosActivos'])) {
            $data['descuentosActivos'] = collect([]);
        }

        return $data;
    }

    /**
     * Get Lima template specific configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'template_name' => 'Lima',
            'template_version' => '1.0.0',
            'template_author' => 'Betoge',
            'requires' => [
                'swiper' => '4.4.2',
                'jquery' => '3.6.0',
                'bootstrap_grid' => '4.1.3',
            ],
        ]);
    }
}
