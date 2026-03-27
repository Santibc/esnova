<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold text-dark">
            <i class="bi bi-images me-2"></i>Edición Banner - Carrusel
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('empresa.banner.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Slides Existentes --}}
            @if($empresa->bannerSlides->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-collection me-2"></i>Slides Actuales ({{ $empresa->bannerSlides->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($empresa->bannerSlides->sortBy('orden') as $slide)
                            <div class="slide-existente border rounded p-3 mb-4 {{ !$slide->activo ? 'bg-light opacity-75' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">
                                        <i class="bi bi-grip-vertical text-muted me-1"></i>
                                        Slide #{{ $slide->orden + 1 }}
                                        @if(!$slide->activo)
                                            <span class="badge bg-secondary ms-2">Inactivo</span>
                                        @else
                                            <span class="badge bg-success ms-2">Activo</span>
                                        @endif
                                    </h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input border-danger"
                                               name="slides_existentes[{{ $slide->id }}][eliminar]" value="1"
                                               id="eliminar_slide_{{ $slide->id }}">
                                        <label class="form-check-label text-danger fw-semibold" for="eliminar_slide_{{ $slide->id }}">
                                            <i class="bi bi-trash me-1"></i>Eliminar
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    {{-- Imagen actual + reemplazo --}}
                                    <div class="col-md-3">
                                        @if($slide->imagen)
                                            <div class="border rounded overflow-hidden mb-2" style="height: 140px;">
                                                <img src="{{ $slide->imagen_url }}" alt="Slide" class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="border rounded d-flex align-items-center justify-content-center mb-2 bg-light" style="height: 140px;">
                                                <span class="text-muted"><i class="bi bi-image fs-3"></i></span>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control form-control-sm"
                                               name="slides_existentes[{{ $slide->id }}][imagen]"
                                               accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <small class="text-muted">Cambiar imagen (opcional)</small>
                                    </div>

                                    {{-- Campos --}}
                                    <div class="col-md-9">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label form-label-sm fw-semibold">Título</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][titulo]"
                                                       value="{{ $slide->titulo }}" placeholder="Título del slide">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label form-label-sm fw-semibold">Orden</label>
                                                <input type="number" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][orden]"
                                                       value="{{ $slide->orden }}" min="0">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="slides_existentes[{{ $slide->id }}][activo]" value="0">
                                                    <input type="checkbox" class="form-check-input" role="switch"
                                                           name="slides_existentes[{{ $slide->id }}][activo]" value="1"
                                                           {{ $slide->activo ? 'checked' : '' }}>
                                                    <label class="form-check-label form-label-sm">Activo</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label form-label-sm fw-semibold">Subtítulo / Descripción</label>
                                                <textarea class="form-control form-control-sm"
                                                          name="slides_existentes[{{ $slide->id }}][subtitulo]"
                                                          rows="2" placeholder="Descripción breve del slide">{{ $slide->subtitulo }}</textarea>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label form-label-sm">Texto Botón 1</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][btn1_texto]"
                                                       value="{{ $slide->btn1_texto }}" placeholder="Ver Productos">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label form-label-sm">Enlace Botón 1</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][btn1_link]"
                                                       value="{{ $slide->btn1_link }}" placeholder="#productos">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label form-label-sm">Texto Botón 2</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][btn2_texto]"
                                                       value="{{ $slide->btn2_texto }}" placeholder="Explorar Categorías">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label form-label-sm">Enlace Botón 2</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       name="slides_existentes[{{ $slide->id }}][btn2_link]"
                                                       value="{{ $slide->btn2_link }}" placeholder="#categorias">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    No tienes slides en el banner. Agrega al menos uno para mostrar el carrusel en tu tienda.
                </div>
            @endif

            {{-- Agregar Nuevos Slides --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Agregar Nuevos Slides</h5>
                    <button type="button" class="btn btn-light btn-sm" id="btnAgregarSlide">
                        <i class="bi bi-plus-lg me-1"></i>Agregar Slide
                    </button>
                </div>
                <div class="card-body">
                    <div id="nuevos-slides-container">
                        {{-- Los slides nuevos se agregan aquí con JavaScript --}}
                    </div>
                    <div id="empty-new-slides" class="text-center text-muted py-3">
                        <i class="bi bi-arrow-up-circle fs-4 d-block mb-2"></i>
                        Haz clic en "Agregar Slide" para crear un nuevo slide del banner.
                    </div>
                </div>
            </div>

            {{-- Botón guardar --}}
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-check-circle me-2"></i>Guardar Banner
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .slide-existente {
            transition: opacity 0.3s ease;
        }
        .slide-nuevo {
            border: 2px dashed #0d6efd;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f8f9ff;
        }
        .slide-nuevo .btn-remove-slide {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function() {
            let slideIndex = 0;

            function updateEmptyMessage() {
                if ($('#nuevos-slides-container .slide-nuevo').length > 0) {
                    $('#empty-new-slides').hide();
                } else {
                    $('#empty-new-slides').show();
                }
            }

            $('#btnAgregarSlide').click(function() {
                const template = `
                    <div class="slide-nuevo position-relative mb-3">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-slide position-absolute" style="top: 0.5rem; right: 0.5rem; z-index: 2;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <h6 class="mb-3 text-primary"><i class="bi bi-plus-circle me-1"></i> Nuevo Slide</h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold">Imagen <span class="text-danger">*</span></label>
                                <input type="file" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][imagen]"
                                       accept="image/jpeg,image/png,image/jpg,image/webp" required>
                                <small class="text-muted">Máx 5MB. Recomendado: 1920x600px</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label form-label-sm fw-semibold">Título</label>
                                <input type="text" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][titulo]"
                                       placeholder="Ej: Descubre lo mejor para ti">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm fw-semibold">Orden</label>
                                <input type="number" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][orden]"
                                       value="${slideIndex}" min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label form-label-sm fw-semibold">Subtítulo / Descripción</label>
                                <textarea class="form-control form-control-sm"
                                          name="slides[${slideIndex}][subtitulo]"
                                          rows="2" placeholder="Descripción breve del slide"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm">Texto Botón 1</label>
                                <input type="text" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][btn1_texto]"
                                       placeholder="Ver Productos">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm">Enlace Botón 1</label>
                                <input type="text" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][btn1_link]"
                                       placeholder="#productos">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm">Texto Botón 2</label>
                                <input type="text" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][btn2_texto]"
                                       placeholder="Explorar Categorías">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label form-label-sm">Enlace Botón 2</label>
                                <input type="text" class="form-control form-control-sm"
                                       name="slides[${slideIndex}][btn2_link]"
                                       placeholder="#categorias">
                            </div>
                        </div>
                    </div>
                `;

                $('#nuevos-slides-container').append(template);
                slideIndex++;
                updateEmptyMessage();
            });

            $(document).on('click', '.btn-remove-slide', function() {
                $(this).closest('.slide-nuevo').remove();
                updateEmptyMessage();
            });

            // Efecto visual al marcar eliminar
            $(document).on('change', 'input[name*="[eliminar]"]', function() {
                const slideEl = $(this).closest('.slide-existente');
                if ($(this).is(':checked')) {
                    slideEl.css({'opacity': '0.4', 'border-color': '#dc3545'});
                } else {
                    slideEl.css({'opacity': '1', 'border-color': ''});
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
