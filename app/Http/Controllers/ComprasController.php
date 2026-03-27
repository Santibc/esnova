<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\TransaccionPago;
use App\Models\Envio;
use App\Services\WompiService;
use App\Mail\EnvioActualizado;
use App\Mail\PagoOtroAprobado;
use App\Mail\PagoOtroRechazado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $empresa = auth()->user()->empresa;
        
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                ->with('error', 'Debe crear su empresa primero.');
        }

        $query = Compra::where('empresa_id', $empresa->id)
            ->with(['ciudad', 'transaccionAprobada', 'envio']);

        // Filtros
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_compra', 'like', "%{$buscar}%")
                  ->orWhere('nombre_cliente', 'like', "%{$buscar}%")
                  ->orWhere('email_cliente', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Filtro por método de pago
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        $compras = $query->paginate(20)->withQueryString();

        // Estadísticas
        $estadisticas = [
            'total_compras' => Compra::where('empresa_id', $empresa->id)->count(),
            'compras_pagadas' => Compra::where('empresa_id', $empresa->id)->where('estado', 'pagada')->count(),
            'ventas_mes' => Compra::where('empresa_id', $empresa->id)
                ->where('estado', 'pagada')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
            'compras_pendientes' => Compra::where('empresa_id', $empresa->id)->where('estado', 'pendiente')->count(),
            'pendientes_revision' => Compra::where('empresa_id', $empresa->id)->pendientesRevision()->count(),
        ];

        return view('compras.index', compact('compras', 'estadisticas'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Compra $compra)
    {
        // Verificar que la compra pertenece a la empresa del usuario, excepto para admins
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $compra->load([
            'items.producto',
            'items.variante',
            'ciudad.departamento',
            'transaccionesPago',
            'envio',
            'comision',
            'revisor'
        ]);

        return view('compras.show', compact('compra'));
    }

    /**
     * Cambiar estado de la compra
     */
    public function cambiarEstado(Request $request, Compra $compra)
    {
        // Verificar que la compra pertenece a la empresa del usuario, excepto para admins
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:pendiente,procesando,pagada,enviada,entregada,cancelada,reembolsada',
            'notas' => 'nullable|string'
        ]);

        $estadoAnterior = $compra->estado;
        $nuevoEstado = $request->estado;

        DB::beginTransaction();

        try {
            // Actualizar estado
            $compra->estado = $nuevoEstado;
            if ($request->filled('notas')) {
                $compra->notas = $compra->notas . "\n\n[" . now()->format('d/m/Y H:i') . "] " . $request->notas;
            }
            $compra->save();

            // Acciones según el nuevo estado
            switch ($nuevoEstado) {
                case 'cancelada':
                    $this->cancelarCompra($compra);
                    break;
                    
                case 'reembolsada':
                    $this->procesarReembolso($compra);
                    break;
                    
                case 'enviada':
                    $this->marcarComoEnviada($compra);
                    break;
                    
                case 'entregada':
                    $this->marcarComoEntregada($compra);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar información de envío
     */
    public function actualizarEnvio(Request $request, Compra $compra)
    {
        // Verificar que la compra pertenece a la empresa del usuario, excepto para admins
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $request->validate([
            'transportadora' => 'required|string|max:255',
            'numero_guia' => 'required|string|max:255',
            'url_seguimiento' => 'nullable|url',
            'fecha_entrega_estimada' => 'nullable|date|after:today'
        ]);

        DB::beginTransaction();

        try {
            $envio = $compra->envio ?? new Envio(['compra_id' => $compra->id]);
            
            $envio->fill([
                'transportadora' => $request->transportadora,
                'numero_guia' => $request->numero_guia,
                'url_seguimiento' => $request->url_seguimiento,
                'fecha_entrega_estimada' => $request->fecha_entrega_estimada,
                'estado' => 'enviado',
                'fecha_envio' => $envio->fecha_envio ?? now()
            ]);
            
            $envio->save();

            // Actualizar estado de la compra
            if ($compra->estado !== 'enviada') {
                $compra->update(['estado' => 'enviada']);
            }

            // Cargar relaciones necesarias para el correo
            $compra->load(['items.producto', 'items.variante', 'ciudad.departamento', 'envio', 'empresa']);

            // Enviar correo al cliente
            Mail::to($compra->email_cliente)->send(new EnvioActualizado($compra));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Información de envío actualizada correctamente. Se ha enviado un correo al cliente con los detalles del envío.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar envío: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver timeline de la compra
     */
    public function timeline(Compra $compra)
    {
        // Verificar que la compra pertenece a la empresa del usuario, excepto para admins
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $timeline = [];

        // Creación de la compra
        $timeline[] = [
            'fecha' => $compra->created_at,
            'tipo' => 'creacion',
            'titulo' => 'Compra creada',
            'descripcion' => 'Se creó la orden de compra',
            'icono' => 'bi-cart-plus',
            'color' => 'primary'
        ];

        // Transacciones de pago
        foreach ($compra->transaccionesPago as $transaccion) {
            $timeline[] = [
                'fecha' => $transaccion->created_at,
                'tipo' => 'pago',
                'titulo' => 'Intento de pago',
                'descripcion' => 'Estado: ' . ucfirst($transaccion->estado),
                'icono' => 'bi-credit-card',
                'color' => $transaccion->estado === 'aprobada' ? 'success' : 'danger'
            ];

            if ($transaccion->fecha_procesamiento) {
                $timeline[] = [
                    'fecha' => $transaccion->fecha_procesamiento,
                    'tipo' => 'pago_procesado',
                    'titulo' => 'Pago procesado',
                    'descripcion' => 'Pago ' . $transaccion->estado,
                    'icono' => 'bi-check-circle',
                    'color' => 'success'
                ];
            }
        }

        // Envío
        if ($compra->envio) {
            if ($compra->envio->fecha_envio) {
                $timeline[] = [
                    'fecha' => $compra->envio->fecha_envio,
                    'tipo' => 'envio',
                    'titulo' => 'Pedido enviado',
                    'descripcion' => 'Transportadora: ' . $compra->envio->transportadora . ' - Guía: ' . $compra->envio->numero_guia,
                    'icono' => 'bi-truck',
                    'color' => 'info'
                ];
            }

            if ($compra->envio->fecha_entrega) {
                $timeline[] = [
                    'fecha' => $compra->envio->fecha_entrega,
                    'tipo' => 'entrega',
                    'titulo' => 'Pedido entregado',
                    'descripcion' => 'Entrega confirmada',
                    'icono' => 'bi-house-check',
                    'color' => 'success'
                ];
            }
        }

        // Ordenar por fecha
        usort($timeline, function($a, $b) {
            return $a['fecha']->timestamp - $b['fecha']->timestamp;
        });

        return response()->json($timeline);
    }

    /**
     * Exportar compras a Excel
     */
    public function exportar(Request $request)
    {
        $empresa = auth()->user()->empresa;
        
        $query = Compra::where('empresa_id', $empresa->id)
            ->with(['items', 'ciudad', 'transaccionAprobada']);

        // Aplicar los mismos filtros del index
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $compras = $query->get();

        // Aquí implementarías la exportación con Laravel Excel
        // Por ahora retorno un mensaje
        return back()->with('info', 'Funcionalidad de exportación en desarrollo');
    }

    /**
     * Métodos privados auxiliares
     */
    private function cancelarCompra($compra)
    {
        // Devolver stock
        foreach ($compra->items as $item) {
            $producto = $item->producto;
            if ($producto && $producto->controlar_stock) {
                $stock = $item->variante_producto_id 
                    ? $producto->stock()->where('variante_producto_id', $item->variante_producto_id)->first()
                    : $producto->stockPrincipal;
                
                if ($stock) {
                    $stock->entrada(
                        $item->cantidad, 
                        'devolucion', 
                        $compra->numero_compra,
                        'Compra cancelada'
                    );
                }
            }
        }

        // Cancelar comisión si existe
        if ($compra->comision && $compra->comision->estado === 'pendiente') {
            $compra->comision->update(['estado' => 'cancelada']);
        }
    }

    private function procesarReembolso($compra)
    {
        $transaccion = $compra->transaccionAprobada;
        if ($transaccion && $transaccion->id_transaccion_pasarela) {
            $wompiService = new WompiService();
            $resultado = $wompiService->procesarReembolso(
                $transaccion->id_transaccion_pasarela,
                null, // Reembolso total
                'Reembolso solicitado por el comercio'
            );

            if ($resultado['success']) {
                $transaccion->update([
                    'estado' => 'reembolsada',
                    'mensaje_error' => 'Reembolso procesado'
                ]);
            }
        }

        // Devolver stock (mismo proceso que cancelar)
        $this->cancelarCompra($compra);
    }

    private function marcarComoEnviada($compra)
    {
        if (!$compra->envio) {
            Envio::create([
                'compra_id' => $compra->id,
                'estado' => 'enviado',
                'fecha_envio' => now()
            ]);
        } else {
            $compra->envio->update([
                'estado' => 'enviado',
                'fecha_envio' => $compra->envio->fecha_envio ?? now()
            ]);
        }
    }

    private function marcarComoEntregada($compra)
    {
        if ($compra->envio) {
            $compra->envio->update([
                'estado' => 'entregado',
                'fecha_entrega' => now()
            ]);
        }
    }

    /**
     * Aprobar pago con método "otro"
     */
    public function aprobarPagoOtro(Compra $compra)
    {
        // Verificar permisos
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        // Verificar que sea una compra con método "otro" y estado pendiente
        if (!$compra->esMetodoOtro() || $compra->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Esta compra no puede ser aprobada'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Actualizar compra
            $compra->update([
                'estado' => 'pagada',
                'fecha_revision' => now(),
                'revisado_por' => auth()->id()
            ]);

            // Actualizar transacción de pago
            $compra->transaccionesPago()->update([
                'estado' => 'aprobada',
                'fecha_procesamiento' => now(),
                'metodo_pago' => 'pago_manual'
            ]);

            // Generar comisión
            $compra->generarComision();

            // Enviar email al cliente
            try {
                Mail::to($compra->email_cliente)->send(new PagoOtroAprobado($compra));
            } catch (\Exception $e) {
                Log::warning('Error enviando email de aprobación: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago aprobado correctamente. La compra ha sido marcada como pagada.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aprobar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar pago con método "otro"
     */
    public function rechazarPagoOtro(Request $request, Compra $compra)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ]);

        // Verificar permisos
        if (!auth()->user()->hasRole('admin') && $compra->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        // Verificar que sea una compra con método "otro" y estado pendiente
        if (!$compra->esMetodoOtro() || $compra->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Esta compra no puede ser rechazada'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Actualizar compra
            $compra->update([
                'estado' => 'cancelada',
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_revision' => now(),
                'revisado_por' => auth()->id()
            ]);

            // Actualizar transacción de pago
            $compra->transaccionesPago()->update([
                'estado' => 'rechazada',
                'mensaje_error' => $request->motivo_rechazo
            ]);

            // Liberar stock
            $this->cancelarCompra($compra);

            // Enviar email al cliente
            try {
                Mail::to($compra->email_cliente)->send(new PagoOtroRechazado($compra));
            } catch (\Exception $e) {
                Log::warning('Error enviando email de rechazo: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago rechazado. El stock ha sido liberado.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al rechazar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar: ' . $e->getMessage()
            ], 500);
        }
    }
}