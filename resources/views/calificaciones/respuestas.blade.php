<x-app-layout>
    <x-slot name="header">Respuestas Pendientes</x-slot>

    <style>
        .rating-stars {
            color: #ffc107;
        }
        .review-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }
        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        .parent-review {
            background: #f8f9fa;
            border-left: 3px solid #dee2e6;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabs de navegacion --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calificaciones.index') }}">
                        <i class="bi bi-clock me-1"></i> Pendientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('calificaciones.respuestas') }}">
                        <i class="bi bi-chat-dots me-1"></i> Respuestas
                        @if($calificaciones->total() > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $calificaciones->total() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calificaciones.aprobadas') }}">
                        <i class="bi bi-check-circle me-1"></i> Aprobadas
                    </a>
                </li>
            </ul>

            {{-- Contenido --}}
            @if($calificaciones->count() > 0)
                <div class="row">
                    @foreach($calificaciones as $calificacion)
                        <div class="col-12">
                            <div class="review-card">
                                {{-- Resena padre --}}
                                @if($calificacion->parent)
                                    <div class="parent-review">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="bi bi-reply text-muted"></i>
                                            <small class="text-muted">Respuesta a:</small>
                                        </div>
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="avatar-circle" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($calificacion->parent->nombre_autor, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong class="small">{{ $calificacion->parent->nombre_autor }}</strong>
                                                <div class="rating-stars small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $calificacion->parent->estrellas ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 small text-muted">{{ Str::limit($calificacion->parent->comentario, 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row align-items-center">
                                    {{-- Informacion del autor de la respuesta --}}
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($calificacion->nombre_autor, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $calificacion->nombre_autor }}</div>
                                                <small class="text-muted">{{ $calificacion->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Producto --}}
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Producto</small>
                                        @php
                                            $producto = $calificacion->parent->producto ?? $calificacion->producto;
                                        @endphp
                                        <a href="{{ route('tienda.producto', $producto->slug ?? '') }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($producto->nombre ?? 'Producto eliminado', 30) }}
                                        </a>
                                    </div>

                                    {{-- Contenido de la respuesta --}}
                                    <div class="col-md-5">
                                        <span class="badge bg-secondary mb-2">
                                            <i class="bi bi-chat-dots me-1"></i> Respuesta
                                        </span>
                                        @if($calificacion->comentario)
                                            <p class="mb-0 text-muted">{{ Str::limit($calificacion->comentario, 150) }}</p>
                                        @else
                                            <p class="mb-0 text-muted fst-italic">Sin comentario</p>
                                        @endif
                                    </div>

                                    {{-- Acciones --}}
                                    <div class="col-md-2 text-end">
                                        <form action="{{ route('calificaciones.aprobar', $calificacion->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Aprobar">
                                                <i class="bi bi-check-lg"></i> Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('calificaciones.rechazar', $calificacion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Estas seguro de rechazar esta respuesta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Rechazar">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginacion --}}
                @if($calificaciones->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $calificaciones->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-chat-dots fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No hay respuestas pendientes</h5>
                    <p class="text-muted">Todas las respuestas han sido revisadas</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
