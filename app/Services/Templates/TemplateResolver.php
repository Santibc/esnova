<?php

namespace App\Services\Templates;

use App\Models\Empresa;
use App\Models\TemplateTienda;
use App\Services\Templates\Contracts\TemplateStrategyInterface;
use App\Services\Templates\Strategies\DefaultTemplateStrategy;
use App\Services\Templates\Strategies\BrasiliaTemplateStrategy;
use App\Services\Templates\Strategies\LimaTemplateStrategy;
use Illuminate\Support\Facades\Cache;

/**
 * TemplateResolver - Context del patrón Strategy
 *
 * Resuelve y gestiona las estrategias de templates para las empresas.
 * Implementa cache para optimizar el rendimiento.
 *
 * @package App\Services\Templates
 */
class TemplateResolver
{
    /**
     * Mapeo de códigos de template a clases Strategy
     *
     * @var array<string, string>
     */
    private array $strategyMap = [
        'default' => DefaultTemplateStrategy::class,
        'brasilia' => BrasiliaTemplateStrategy::class,
        'lima' => LimaTemplateStrategy::class,
    ];

    /**
     * Resuelve la estrategia de template para una empresa específica
     *
     * Utiliza cache para mejorar el rendimiento. Si la empresa no tiene
     * un template asignado, retorna la estrategia default.
     *
     * @param Empresa $empresa La empresa para la cual resolver el template
     * @return TemplateStrategyInterface La estrategia de template correspondiente
     * @throws \InvalidArgumentException Si la clase Strategy no existe o no implementa la interfaz
     */
    public function resolveForEmpresa(Empresa $empresa): TemplateStrategyInterface
    {
        $cacheKey = "template_strategy_empresa_{$empresa->id}";

        return Cache::remember($cacheKey, 3600, function () use ($empresa) {
            // Obtener el template asignado a la empresa o el default
            $template = $empresa->templateTienda()->where('activo', true)->first();

            if (!$template) {
                // Si no tiene template asignado, buscar el default
                $template = TemplateTienda::where('es_default', true)
                    ->where('activo', true)
                    ->first();
            }

            if (!$template) {
                // Fallback al template default por código
                $template = TemplateTienda::where('codigo', 'default')->first();
            }

            if (!$template) {
                // Último fallback: crear estrategia default directamente
                return new DefaultTemplateStrategy([]);
            }

            return $this->createStrategy($template);
        });
    }

    /**
     * Crea una instancia de estrategia basada en el template proporcionado
     *
     * @param TemplateTienda $template El template desde el cual crear la estrategia
     * @return TemplateStrategyInterface La estrategia creada
     * @throws \InvalidArgumentException Si la clase Strategy no existe o no implementa la interfaz
     */
    public function createStrategy(TemplateTienda $template): TemplateStrategyInterface
    {
        $codigo = $template->codigo;

        if (!isset($this->strategyMap[$codigo])) {
            // Si no existe el código en el mapa, usar default
            $codigo = 'default';
        }

        $strategyClass = $this->strategyMap[$codigo];

        if (!class_exists($strategyClass)) {
            throw new \InvalidArgumentException(
                "La clase Strategy '{$strategyClass}' no existe para el template '{$codigo}'"
            );
        }

        // Pasar solo el array de configuración al constructor de la Strategy
        $config = $template->configuracion ?? [];
        $strategy = new $strategyClass($config);

        if (!$strategy instanceof TemplateStrategyInterface) {
            throw new \InvalidArgumentException(
                "La clase '{$strategyClass}' debe implementar TemplateStrategyInterface"
            );
        }

        return $strategy;
    }

    /**
     * Limpia el cache de estrategia para una empresa específica
     *
     * Debe llamarse cuando se cambia el template de una empresa
     * o cuando se modifican los datos del template.
     *
     * @param Empresa $empresa La empresa cuyo cache se debe limpiar
     * @return void
     */
    public function clearCache(Empresa $empresa): void
    {
        $cacheKey = "template_strategy_empresa_{$empresa->id}";
        Cache::forget($cacheKey);
    }

    /**
     * Registra una nueva estrategia en el mapa de estrategias
     *
     * Permite agregar dinámicamente nuevas estrategias sin modificar el código.
     *
     * @param string $codigo El código identificador del template
     * @param string $strategyClass El nombre completo de la clase Strategy (FQCN)
     * @return void
     * @throws \InvalidArgumentException Si la clase no existe o no implementa la interfaz
     */
    public function registerStrategy(string $codigo, string $strategyClass): void
    {
        if (!class_exists($strategyClass)) {
            throw new \InvalidArgumentException(
                "La clase Strategy '{$strategyClass}' no existe"
            );
        }

        $reflection = new \ReflectionClass($strategyClass);

        if (!$reflection->implementsInterface(TemplateStrategyInterface::class)) {
            throw new \InvalidArgumentException(
                "La clase '{$strategyClass}' debe implementar TemplateStrategyInterface"
            );
        }

        $this->strategyMap[$codigo] = $strategyClass;
    }

    /**
     * Obtiene el mapa completo de estrategias registradas
     *
     * @return array<string, string>
     */
    public function getStrategyMap(): array
    {
        return $this->strategyMap;
    }
}
