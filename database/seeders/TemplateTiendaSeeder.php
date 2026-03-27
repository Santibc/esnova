<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateTienda;

/**
 * TemplateTiendaSeeder
 *
 * Seeder para poblar la tabla de templates de tienda con los templates
 * predeterminados del sistema: Default y Brasilia.
 *
 * @package Database\Seeders
 */
class TemplateTiendaSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para crear los templates iniciales
     *
     * Crea tres templates:
     * - Default: Template clásico y elegante, marcado como predeterminado
     * - Brasilia: Template dinámico inspirado en tiendas de moda
     * - Lima: Template moderno con header sticky y sliders Swiper
     *
     * Utiliza updateOrCreate para evitar duplicados en ejecuciones múltiples.
     *
     * @return void
     */
    public function run(): void
    {
        // Template Default - Clásico y elegante
        TemplateTienda::updateOrCreate(
            ['codigo' => 'default'],
            [
                'nombre' => 'Template Clásico',
                'descripcion' => 'Template elegante y moderno con diseño limpio. Ideal para todo tipo de productos.',
                'vista_index' => 'tienda.index',
                'vista_categoria' => 'tienda.categoria',
                'vista_producto' => 'tienda.producto',
                'layout' => 'tienda.layout',
                'preview_image' => 'images/templates/default-preview.jpg',
                'activo' => true,
                'es_default' => true,
                'orden' => 1,
                'configuracion' => [
                    'color_primario' => '#0d6efd',
                    'fuente_principal' => 'Roboto',
                ],
            ]
        );

        // Template Brasilia - Dinámico y vibrante
        TemplateTienda::updateOrCreate(
            ['codigo' => 'brasilia'],
            [
                'nombre' => 'Template Brasilia',
                'descripcion' => 'Template dinámico inspirado en tiendas de moda. Con carruseles, animaciones y diseño vibrante.',
                'vista_index' => 'tienda.brasilia_index',
                'vista_categoria' => 'tienda.brasilia_categoria',
                'vista_producto' => 'tienda.brasilia_producto',
                'layout' => 'tienda.brasilia_layout',
                'preview_image' => 'images/templates/brasilia-preview.jpg',
                'activo' => true,
                'es_default' => false,
                'orden' => 2,
                'configuracion' => [
                    'color_primario' => '#1a1a1a',
                    'color_secundario' => '#78b13f',
                    'mostrar_adbars' => true,
                    'habilitar_animaciones' => true,
                ],
            ]
        );

        // Template Lima - Moderno con Swiper sliders
        TemplateTienda::updateOrCreate(
            ['codigo' => 'lima'],
            [
                'nombre' => 'Template Lima',
                'descripcion' => 'Template moderno con header sticky, sliders Swiper y diseño profesional. Ideal para tiendas de indumentaria.',
                'vista_index' => 'tienda.lima_index',
                'vista_categoria' => 'tienda.lima_categoria',
                'vista_producto' => 'tienda.lima_producto',
                'layout' => 'tienda.lima_layout',
                'preview_image' => 'images/templates/lima-preview.svg',
                'activo' => true,
                'es_default' => false,
                'orden' => 3,
                'configuracion' => [
                    'sticky_header' => true,
                    'show_adbar' => true,
                    'show_topbar' => true,
                    'product_grid_columns_mobile' => 2,
                    'product_grid_columns_desktop' => 4,
                    'enable_animations' => true,
                ],
            ]
        );

        $this->command->info('Templates de tienda creados exitosamente.');
        $this->command->info('- Template Default (código: default) - Marcado como predeterminado');
        $this->command->info('- Template Brasilia (código: brasilia)');
        $this->command->info('- Template Lima (código: lima)');
    }
}
