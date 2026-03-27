<?php

namespace App\Services\Templates;

use App\Services\Templates\Contracts\TemplateStrategyInterface;

/**
 * Class AbstractTemplateStrategy
 *
 * Clase base abstracta para todas las estrategias de templates.
 * Implementa comportamiento común y define propiedades compartidas.
 * Aplica el patrón Template Method para proveer una implementación base.
 *
 * @package App\Services\Templates
 */
abstract class AbstractTemplateStrategy implements TemplateStrategyInterface
{
    /**
     * @var string Nombre de la vista index
     */
    protected string $viewIndex;

    /**
     * @var string Nombre de la vista categoría
     */
    protected string $viewCategoria;

    /**
     * @var string Nombre de la vista producto
     */
    protected string $viewProducto;

    /**
     * @var string Nombre del layout
     */
    protected string $layout;

    /**
     * @var array Configuración del template
     */
    protected array $config;

    /**
     * Constructor
     *
     * @param array $config Configuración opcional del template
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewIndex(): string
    {
        return $this->viewIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewCategoria(): string
    {
        return $this->viewCategoria;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewProducto(): string
    {
        return $this->viewProducto;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * {@inheritdoc}
     *
     * Implementación por defecto que retorna los datos sin modificar.
     * Las clases hijas pueden sobrescribir este método para agregar
     * lógica específica de transformación de datos.
     */
    public function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * Implementación por defecto que retorna arrays vacíos.
     * Las clases hijas deben sobrescribir este método para retornar
     * los assets específicos de su template.
     */
    public function getAssets(): array
    {
        return [
            'css' => [],
            'js' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Obtener un valor de configuración específico
     *
     * @param string $key Clave de configuración
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    protected function getConfigValue(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Verificar si una configuración existe
     *
     * @param string $key Clave de configuración
     * @return bool
     */
    protected function hasConfig(string $key): bool
    {
        return isset($this->config[$key]);
    }
}
