<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarEmpresa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene empresa
        if (!auth()->user()->empresa) {
            // Rutas que no requieren empresa
            $rutasExentas = [
                'empresa.index',
                'empresa.crear',
                'empresa.form',
                'empresa.guardar',
                'dashboard',
                'logout'
            ];

            // Si la ruta actual no está exenta, redirigir a crear empresa
            if (!in_array($request->route()->getName(), $rutasExentas)) {
                return redirect()->route('empresa.crear')
                    ->with('warning', 'Debe crear su empresa antes de acceder a esta sección.');
            }
        }

        return $next($request);
    }
}