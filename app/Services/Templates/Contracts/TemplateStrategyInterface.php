<?php

namespace App\Services\Templates\Contracts;

/**
 * Interface TemplateStrategyInterface
 *
 * Define el contrato para las estrategias de templates de tienda.
 * Cada template implementa esta interface para definir sus vistas y comportamiento.
 *
 * @package App\Services\Templates\Contracts
 */
interface TemplateStrategyInterface
{
    /**
     * Obtener nombre de la vista index (página principal de la tienda)
     *
     * @return string Nombre de la vista (ej: 'tienda.index')
     */
    public function getViewIndex(): string;

    /**
     * Obtener nombre de la vista de categoría (listado de productos)
     *
     * @return string Nombre de la vista (ej: 'tienda.categoria')
     */
    public function getViewCategoria(): string;

    /**
     * Obtener nombre de la vista de detalle de producto
     *
     * @return string Nombre de la vista (ej: 'tienda.producto')
     */
    public function getViewProducto(): string;

    /**
     * Obtener nombre del layout base del template
     *
     * @return string Nombre del layout (ej: 'tienda.layout')
     */
    public function getLayout(): string;

    /**
     * Preparar/transformar datos específicos del template
     *
     * Permite a cada template modificar o agregar datos antes de pasarlos a la vista.
     * Por ejemplo, un template podría agregar configuraciones específicas,
     * transformar estructuras de datos, o aplicar lógica de presentación.
     *
     * @param array $data Datos originales
     * @return array Datos transformados/preparados
     */
    public function prepareData(array $data): array;

    /**
     * Obtener assets específicos del template (CSS/JS)
     *
     * Retorna un array con las rutas a los archivos CSS y JavaScript
     * específicos de este template.
     *
     * @return array ['css' => [...], 'js' => [...]]
     */
    public function getAssets(): array;

    /**
     * Obtener configuración del template
     *
     * Retorna configuraciones específicas del template como colores,
     * fuentes, opciones de visualización, etc.
     *
     * @return array Configuración del template
     */
    public function getConfig(): array;
}
