<x-app-layout>
    <x-slot name="header">Calificaciones Aprobadas</x-slot>

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
        }
        .avatar-circle {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF00C1, #0B00F9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .review-image-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .review-image-thumb:hover {
            transform: scale(1.1);
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

            {{-- Tabs de navegación --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calificaciones.index') }}">
                        <i class="bi bi-clock me-1"></i> Pendientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calificaciones.respuestas') }}">
                        <i class="bi bi-chat-dots me-1"></i> Respuestas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('calificaciones.aprobadas') }}">
                        <i class="bi bi-check-circle me-1"></i> Aprobadas
                        @if($calificaciones->total() > 0)
                            <span class="badge bg-success ms-1">{{ $calificaciones->total() }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            {{-- Contenido --}}
            @if($calificaciones->count() > 0)
                <div class="row">
                    @foreach($calificaciones as $calificacion)
                        <div class="col-12">
                            <div class="review-card">
                                <div class="row align-items-center">
                                    {{-- Información del autor --}}
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($calificacion->nombre_autor, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $calificacion->nombre_autor }}</div>
                                                <div class="rating-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $calificacion->estrellas ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">{{ $calificacion->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Producto --}}
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Producto</small>
                                        <a href="{{ route('tienda.producto', $calificacion->producto->slug ?? '') }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($calificacion->producto->nombre ?? 'Producto eliminado', 30) }}
                                        </a>
                                    </div>

                                    {{-- Contenido de la reseña --}}
                                    <div class="col-md-3">
                                        @if($calificacion->titulo)
                                            <strong>{{ $calificacion->titulo }}</strong>
                                        @endif
                                        @if($calificacion->comentario)
                                            <p class="mb-0 text-muted">{{ Str::limit($calificacion->comentario, 120) }}</p>
                                        @else
                                            <p class="mb-0 text-muted fst-italic">Sin comentario</p>
                                        @endif
                                    </div>

                                    {{-- Imagen --}}
                                    <div class="col-md-1 text-center">
                                        @if($calificacion->imagen)
                                            <img src="{{ asset($calificacion->imagen) }}"
                                                 alt="Imagen reseña"
                                                 class="review-image-thumb"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#modalImagen{{ $calificacion->id }}">
                                        @endif
                                    </div>

                                    {{-- Estado y estadísticas --}}
                                    <div class="col-md-3 text-end">
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i> Aprobada
                                        </span>
                                        @if($calificacion->respuestas_aprobadas_count > 0)
                                            <span class="badge bg-info ms-1">
                                                <i class="bi bi-chat-dots me-1"></i> {{ $calificacion->respuestas_aprobadas_count }} respuestas
                                            </span>
                                        @endif
                                        <form action="{{ route('calificaciones.rechazar', $calificacion->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('¿Estás seguro de eliminar esta calificación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if($calificaciones->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $calificaciones->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No hay calificaciones aprobadas</h5>
                    <p class="text-muted">Aún no se han aprobado calificaciones</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modales para imágenes --}}
    @foreach($calificaciones as $calificacion)
        @if($calificacion->imagen)
            <div class="modal fade" id="modalImagen{{ $calificacion->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Imagen de {{ $calificacion->nombre_autor }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset($calificacion->imagen) }}" alt="Imagen reseña" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</x-app-layout>
