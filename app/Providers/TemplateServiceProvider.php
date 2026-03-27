<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Templates\TemplateResolver;
use App\Repositories\TemplateRepository;

/**
 * TemplateServiceProvider
 *
 * Service Provider para el sistema de templates de tienda.
 * Registra los servicios y repositorios necesarios para la gestión
 * de templates dinámicos.
 *
 * @package App\Providers
 */
class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios en el contenedor
     *
     * TemplateResolver se registra como singleton para mantener
     * una única instancia durante el ciclo de vida de la aplicación.
     * TemplateRepository se registra con bind para permitir múltiples instancias.
     *
     * @return void
     */
    public function register(): void
    {
        // Registrar TemplateResolver como singleton
        // Esto asegura que el mapa de estrategias y la configuración
        // se mantengan consistentes durante toda la petición
        $this->app->singleton(TemplateResolver::class, function ($app) {
            return new TemplateResolver();
        });

        // Registrar TemplateRepository
        // Se usa bind en lugar de singleton para permitir
        // múltiples instancias si es necesario
        $this->app->bind(TemplateRepository::class, function ($app) {
            return new TemplateRepository();
        });
    }

    /**
     * Ejecuta acciones después de que todos los servicios están registrados
     *
     * Aquí se pueden registrar event listeners, observers,
     * o cualquier otra lógica de inicialización necesaria.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Obtiene los servicios proporcionados por este provider
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            TemplateResolver::class,
            TemplateRepository::class,
        ];
    }
}
