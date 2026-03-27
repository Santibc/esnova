@extends('cliente.layout')

@section('title', 'Pedido ' . $compra->numero_compra)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('cliente.compras') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left"></i> Volver a Mis Compras
            </a>
            <h1 class="mb-0"><i class="bi bi-receipt"></i> Pedido {{ $compra->numero_compra }}</h1>
            <p class="text-muted mb-0">Realizado el {{ $compra->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            @switch($compra->estado)
                @case('pendiente')
                    <span class="badge badge-estado badge-pendiente fs-6">
                        <i class="bi bi-clock"></i> Pendiente
                    </span>
                    @break
                @case('procesando')
                    <span class="badge badge-estado badge-pendiente fs-6">
                        <i class="bi bi-hourglass-split"></i> Procesando
                    </span>
                    @break
                @case('pagada')
                    <span class="badge badge-estado badge-pagada fs-6">
                        <i class="bi bi-check-circle"></i> Pagada
                    </span>
                    @break
                @case('enviada')
                    <span class="badge badge-estado badge-enviada fs-6">
                        <i class="bi bi-truck"></i> Enviada
                    </span>
                    @break
                @case('entregada')
                    <span class="badge badge-estado badge-entregada fs-6">
                        <i class="bi bi-box-seam"></i> Entregada
                    </span>
                    @break
                @case('cancelada')
                    <span class="badge badge-estado badge-cancelada fs-6">
                        <i class="bi bi-x-circle"></i> Cancelada
                    </span>
                    @break
            @endswitch
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Productos -->
        <div class="col-lg-8">
            <div class="content-card">
                <h5 class="mb-4"><i class="bi bi-box"></i> Productos</h5>

                @foreach($compra->items as $item)
                    <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ $item->producto->url_imagen_principal ?? asset('images/no-image.png') }}"
                             alt="{{ $item->nombre_producto }}"
                             class="rounded"
                             style="width: 80px; height: 80px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->nombre_producto }}</h6>
                            @if($item->info_variante)
                                <small class="text-muted">{{ $item->info_variante }}</small>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted">
                                    {{ $item->cantidad }} x ${{ number_format($item->precio_unitario, 0, ',', '.') }}
                                </span>
                                <strong>${{ number_format($item->precio_total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Información de envío -->
            @if($compra->envio)
                <div class="content-card">
                    <h5 class="mb-4"><i class="bi bi-truck"></i> Información de Envío</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Transportadora:</strong></p>
                            <p class="text-muted">{{ $compra->envio->transportadora ?? 'Por definir' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Número de guía:</strong></p>
                            <p class="text-muted">{{ $compra->envio->numero_guia ?? 'Por definir' }}</p>
                        </div>
                        @if($compra->envio->url_seguimiento)
                            <div class="col-12">
                                <a href="{{ $compra->envio->url_seguimiento }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right"></i> Rastrear Envío
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Información de pago manual -->
            @if($compra->esMetodoOtro())
                <div class="content-card" style="border-left: 4px solid {{ $compra->estado === 'pendiente' ? '#f59e0b' : ($compra->estado === 'pagada' ? '#10b981' : '#ef4444') }};">
                    <h5 class="mb-4">
                        <i class="bi bi-wallet2"></i> Información de Pago
                        @if($compra->estado === 'pendiente')
                            <span class="badge bg-warning text-dark ms-2">En revision</span>
                        @elseif($compra->estado === 'pagada')
                            <span class="badge bg-success ms-2">Aprobado</span>
                        @elseif($compra->estado === 'cancelada')
                            <span class="badge bg-danger ms-2">Rechazado</span>
                        @endif
                    </h5>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Metodo de pago:</strong></p>
                        <p class="text-muted mb-0">Pago manual (transferencia, efectivo, etc.)</p>
                    </div>

                    @if($compra->mensaje_pago)
                        <div class="mb-3">
                            <p class="mb-1"><strong>Mensaje enviado:</strong></p>
                            <div class="p-3 rounded" style="background: #f3f4f6; white-space: pre-wrap;">{{ $compra->mensaje_pago }}</div>
                        </div>
                    @endif

                    @if($compra->tieneArchivoPago())
                        <div class="mb-3">
                            <p class="mb-1"><strong>Archivo adjunto:</strong></p>
                            <a href="{{ $compra->urlArchivoPago() }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-image me-1"></i> Ver comprobante
                            </a>
                        </div>
                    @endif

                    @if($compra->estado === 'pendiente')
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Tu pago esta siendo revisado. Te notificaremos por correo cuando sea aprobado.
                        </div>
                    @elseif($compra->estado === 'pagada' && $compra->fecha_revision)
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Pago aprobado</strong> el {{ $compra->fecha_revision->format('d/m/Y H:i') }}
                        </div>
                    @elseif($compra->estado === 'cancelada' && $compra->motivo_rechazo)
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-x-circle me-2"></i>
                            <strong>Pago rechazado</strong>
                            <p class="mb-0 mt-2">{{ $compra->motivo_rechazo }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Resumen -->
        <div class="col-lg-4">
            <div class="content-card">
                <h5 class="mb-4"><i class="bi bi-receipt-cutoff"></i> Resumen</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>${{ number_format($compra->subtotal, 0, ',', '.') }}</span>
                </div>

                @if($compra->descuento_total > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Descuento</span>
                        <span>-${{ number_format($compra->descuento_total, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Envío</span>
                    <span>
                        @if($compra->costo_envio > 0)
                            ${{ number_format($compra->costo_envio, 0, ',', '.') }}
                        @else
                            Por calcular
                        @endif
                    </span>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong class="fs-5">${{ number_format($compra->total, 0, ',', '.') }}</strong>
                </div>
            </div>

            <!-- Dirección de envío -->
            <div class="content-card">
                <h5 class="mb-4"><i class="bi bi-geo-alt"></i> Dirección de Envío</h5>

                <p class="mb-1"><strong>{{ $compra->nombre_cliente }}</strong></p>
                <p class="mb-1 text-muted">{{ $compra->direccion_envio }}</p>
                <p class="mb-1 text-muted">{{ $compra->ciudad->nombre ?? '' }}, {{ $compra->ciudad->departamento->nombre ?? '' }}</p>
                <p class="mb-0 text-muted">{{ $compra->telefono_cliente }}</p>
            </div>

            @if($compra->notas)
                <div class="content-card">
                    <h5 class="mb-3"><i class="bi bi-chat-text"></i> Notas</h5>
                    <p class="text-muted mb-0">{{ $compra->notas }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
