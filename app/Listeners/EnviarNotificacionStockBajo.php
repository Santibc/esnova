<?php

namespace App\Listeners;

use App\Events\StockBajoAlcanzado;
use App\Mail\AlertaStockBajo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacionStockBajo implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StockBajoAlcanzado $event): void
    {
        $stock = $event->stock;
        $producto = $stock->producto;

        if (!$producto) {
            Log::warning("StockBajoAlcanzado: Stock #{$stock->id} no tiene producto asociado");
            return;
        }

        $empresa = $producto->empresa;

        if (!$empresa) {
            Log::warning("StockBajoAlcanzado: Producto #{$producto->id} no tiene empresa asociada");
            return;
        }

        // Obtener el usuario dueño de la empresa
        $usuario = $empresa->usuario;

        if (!$usuario || !$usuario->email) {
            Log::warning("StockBajoAlcanzado: Empresa #{$empresa->id} no tiene usuario con email");
            return;
        }

        try {
            Mail::to($usuario->email)
                ->send(new AlertaStockBajo(
                    $stock,
                    $event->stockAnterior,
                    $event->stockActual
                ));

            $nombreProducto = $producto->nombre;
            Log::info("StockBajoAlcanzado: Email enviado a {$usuario->email} para producto '{$nombreProducto}'");
        } catch (\Exception $e) {
            Log::error("StockBajoAlcanzado: Error al enviar email - {$e->getMessage()}");
        }
    }
}
