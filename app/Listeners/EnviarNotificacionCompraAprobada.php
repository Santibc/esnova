<?php

namespace App\Listeners;

use App\Events\CompraAprobada;
use App\Mail\CompraAprobadaVendedor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacionCompraAprobada implements ShouldQueue
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
    public function handle(CompraAprobada $event): void
    {
        $compra = $event->compra;
        $empresa = $compra->empresa;

        if (!$empresa) {
            Log::warning("CompraAprobada: Compra #{$compra->id} no tiene empresa asociada");
            return;
        }

        // Obtener el usuario dueño de la empresa
        $usuario = $empresa->usuario;

        if (!$usuario || !$usuario->email) {
            Log::warning("CompraAprobada: Empresa #{$empresa->id} no tiene usuario con email");
            return;
        }

        try {
            Mail::to($usuario->email)
                ->send(new CompraAprobadaVendedor($compra));

            Log::info("CompraAprobada: Email enviado a {$usuario->email} para compra #{$compra->numero_compra}");
        } catch (\Exception $e) {
            Log::error("CompraAprobada: Error al enviar email - {$e->getMessage()}");
        }
    }
}
