<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

class ActiveLinkHelper
{
    /**
     * Devuelve las clases CSS para un enlace de navegación activo o inactivo.
     *
     * @param string|array $paths Uno o varios caminos para verificar (ej. 'dashboard', 'usuarios/*', '/').
     * @param string $activeClasses Las clases a aplicar si el enlace está activo.
     * @param string $inactiveClasses Las clases a aplicar si el enlace está inactivo.
     * @return string
     */
    public static function activeLinkClasses($paths, $activeClasses = '', $inactiveClasses = '')
    {
        // Si no se proporcionan clases activas/inactivas, usamos las predeterminadas.
        if (empty($activeClasses)) {
            $activeClasses = 'text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-200';
        }
        if (empty($inactiveClasses)) {
            $inactiveClasses = 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700';
        }

        // Si es un solo camino, lo convertimos a array para procesar uniformemente
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            // Elimina el '/' inicial para request()->is() si existe, a menos que sea solo '/'
            $cleanPath = ($path === '/') ? '/' : ltrim($path, '/');

            // Verifica si la ruta actual coincide
            if (Request::is($cleanPath)) {
                return $activeClasses;
            }
        }

        return $inactiveClasses;
    }
}