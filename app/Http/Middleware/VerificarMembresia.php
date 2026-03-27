<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\PlanMembresia;

class VerificarMembresia
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
        if (Auth::check()) {
            $empresa = Auth::user()->empresa;
            
            if ($empresa) {
                \Log::info("Verificando membresía para empresa ID: {$empresa->id}");
                $resultado = $empresa->verificarYActualizarMembresia();
                \Log::info("Resultado verificación: " . ($resultado ? 'exitoso' : 'sin cambios'));
                
                // Refrescar la empresa después de la verificación
                $empresa->refresh();
            }
        }
        
        return $next($request);
    }
}
