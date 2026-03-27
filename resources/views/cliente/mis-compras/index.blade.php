@extends('cliente.layout')

@section('title', 'Mis Compras')

@section('header')
    <h1 class="mb-0"><i class="bi bi-bag"></i> Mis Compras</h1>
    <p class="text-muted mb-0">Historial de todas tus compras</p>
@endsection

@section('content')
    @if($compras->count() > 0)
        <div class="row">
            @foreach($compras as $compra)
                <div class="col-12">
                    <div class="content-card">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="mb-2 mb-md-0">
                                    <small class="text-muted">Pedido</small>
                                    <h5 class="mb-0">{{ $compra->numero_compra }}</h5>
                                    <small class="text-muted">{{ $compra->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2 mb-md-0">
                                    <small class="text-muted">Total</small>
                                    <h5 class="mb-0">${{ number_format($compra->total, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-2 mb-md-0">
                                    <small class="text-muted">Productos</small>
                                    <h5 class="mb-0">{{ $compra->items->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2 mb-md-0">
                                    <small class="text-muted">Estado</small>
                                    <div>
                                        @switch($compra->estado)
                                            @case('pendiente')
                                                <span class="badge badge-estado badge-pendiente">
                                                    <i class="bi bi-clock"></i> Pendiente
                                                </span>
                                                @break
                                            @case('procesando')
                                                <span class="badge badge-estado badge-pendiente">
                                                    <i class="bi bi-hourglass-split"></i> Procesando
                                                </span>
                                                @break
                                            @case('pagada')
                                                <span class="badge badge-estado badge-pagada">
                                                    <i class="bi bi-check-circle"></i> Pagada
                                                </span>
                                                @break
                                            @case('enviada')
                                                <span class="badge badge-estado badge-enviada">
                                                    <i class="bi bi-truck"></i> Enviada
                                                </span>
                                                @break
                                            @case('entregada')
                                                <span class="badge badge-estado badge-entregada">
                                                    <i class="bi bi-box-seam"></i> Entregada
                                                </span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge badge-estado badge-cancelada">
                                                    <i class="bi bi-x-circle"></i> Cancelada
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($compra->estado) }}</span>
                                        @endswitch

                                        {{-- Badge de metodo de pago --}}
                                        @if($compra->esMetodoOtro())
                                            <span class="badge bg-secondary ms-1" title="Pago manual">
                                                <i class="bi bi-wallet2"></i> Otro
                                            </span>
                                            @if($compra->estado === 'pendiente')
                                                <small class="d-block text-warning mt-1">
                                                    <i class="bi bi-hourglass-split"></i> En revision
                                                </small>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-md-end">
                                <a href="{{ route('cliente.compras.show', $compra->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                            </div>
                        </div>

                        @if($compra->puedeSerCalificada())
                            <hr>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-star text-warning"></i>
                                <small class="text-muted">Esta compra puede ser calificada</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $compras->links() }}
        </div>
    @else
        <div class="content-card text-center py-5">
            <i class="bi bi-bag-x display-1 text-muted mb-3"></i>
            <h3>No tienes compras aún</h3>
            <p class="text-muted mb-4">Cuando realices tu primera compra, aparecerá aquí.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Ir a la Tienda
            </a>
        </div>
    @endif
@endsection
