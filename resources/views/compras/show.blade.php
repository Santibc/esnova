<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalle de Compra #{{ $compra->numero_compra }}</h2>
            <a href="{{ route('compras') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </x-slot>

    <style>
        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .status-pendiente { background: #fef3c7; color: #92400e; }
        .status-procesando { background: #dbeafe; color: #1e40af; }
        .status-pagada { background: #d1fae5; color: #065f46; }
        .status-enviada { background: #e0e7ff; color: #3730a3; }
        .status-entregada { background: #d1fae5; color: #065f46; }
        .status-cancelada { background: #fee2e2; color: #991b1b; }
        .status-reembolsada { background: #f3f4f6; color: #374151; }

        .timeline {
            position: relative;
            padding: 0;
            list-style: none;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 1.5rem;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            padding-left: 4rem;
            padding-bottom: 2rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: 0.75rem;
            width: 3rem;
            height: 3rem;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .timeline-icon.primary { border-color: #3730a3; color: #3730a3; }
        .timeline-icon.success { border-color: #10b981; color: #10b981; }
        .timeline-icon.danger { border-color: #ef4444; color: #ef4444; }
        .timeline-icon.info { border-color: #3b82f6; color: #3b82f6; }

        .info-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            height: 100%;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            color: #111827;
            font-weight: 500;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-right: 1rem;
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header con estado y acciones --}}
            <div class="bg-white shadow-sm rounded-4 p-6 mb-6">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3">
                            <span class="status-badge status-{{ $compra->estado }}">
                                {{ ucfirst($compra->estado) }}
                            </span>
                            <span class="text-muted">
                                Creada {{ $compra->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        @if(in_array($compra->estado, ['pendiente', 'procesando', 'pagada', 'enviada']))
                            <button class="btn btn-outline-success" onclick="actualizarEnvio({{ $compra->id }})">
                                <i class="bi bi-truck"></i> Actualizar Envío
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="verTimeline()">
                            <i class="bi bi-clock-history"></i> Timeline
                        </button>
                        <button class="btn btn-outline-secondary" onclick="imprimirCompra()">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-6">
                {{-- Información del Cliente --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <h5 class="mb-4">
                            <i class="bi bi-person me-2"></i>Información del Cliente
                        </h5>
                        
                        <div class="mb-3">
                            <div class="info-label">Nombre</div>
                            <div class="info-value">{{ $compra->nombre_cliente }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <a href="mailto:{{ $compra->email_cliente }}">{{ $compra->email_cliente }}</a>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="info-label">Teléfono</div>
                            <div class="info-value">
                                <a href="tel:{{ $compra->telefono_cliente }}">{{ $compra->telefono_cliente }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información de Envío --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <h5 class="mb-4">
                            <i class="bi bi-geo-alt me-2"></i>Información de Envío
                        </h5>
                        
                        <div class="mb-3">
                            <div class="info-label">Dirección</div>
                            <div class="info-value">{{ $compra->direccion_envio }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="info-label">Ciudad</div>
                            <div class="info-value">
                                {{ $compra->ciudad->nombre }}, {{ $compra->ciudad->departamento->nombre }}
                            </div>
                        </div>
                        
                        @if($compra->envio)
                            <hr>
                            <div class="mb-3">
                                <div class="info-label">Transportadora</div>
                                <div class="info-value">{{ $compra->envio->transportadora }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="info-label">Número de guía</div>
                                <div class="info-value">{{ $compra->envio->numero_guia }}</div>
                            </div>
                            
                            @if($compra->envio->url_seguimiento)
                                <a href="{{ $compra->envio->url_seguimiento }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-seam"></i> Rastrear envío
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Información de Pago --}}
                <div class="col-lg-4">
                    <div class="info-card">
                        <h5 class="mb-4">
                            <i class="bi bi-credit-card me-2"></i>Información de Pago
                        </h5>
                        
                        <div class="mb-3">
                            <div class="info-label">Método de pago</div>
                            <div class="info-value">
                                {{ $compra->transaccionAprobada ? ucfirst($compra->transaccionAprobada->metodo_pago ?? 'Wompi') : 'Sin pago' }}
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="info-label">Estado del pago</div>
                            <div class="info-value">
                                @if($compra->transaccionAprobada)
                                    <span class="badge bg-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger">Pendiente</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($compra->transaccionAprobada)
                            <div class="mb-3">
                                <div class="info-label">ID Transaccion</div>
                                <div class="info-value text-truncate">
                                    {{ $compra->transaccionAprobada->referencia_transaccion }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="info-label">Fecha de pago</div>
                                <div class="info-value">
                                    {{ $compra->transaccionAprobada->fecha_procesamiento ? $compra->transaccionAprobada->fecha_procesamiento->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Seccion de Pago Otro (si aplica) --}}
                @if($compra->esMetodoOtro())
                <div class="col-lg-12">
                    <div class="info-card" style="border: 2px solid {{ $compra->estado === 'pendiente' ? '#f59e0b' : ($compra->estado === 'pagada' ? '#10b981' : '#ef4444') }};">
                        <h5 class="mb-4">
                            <i class="bi bi-wallet2 me-2"></i>Informacion del Pago Manual
                            @if($compra->estado === 'pendiente')
                                <span class="badge bg-warning text-dark ms-2">Pendiente de revision</span>
                            @elseif($compra->estado === 'pagada')
                                <span class="badge bg-success ms-2">Aprobado</span>
                            @elseif($compra->estado === 'cancelada')
                                <span class="badge bg-danger ms-2">Rechazado</span>
                            @endif
                        </h5>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <div class="info-label">Mensaje del cliente</div>
                                    <div class="info-value p-3 rounded" style="background: #f3f4f6; white-space: pre-wrap;">{{ $compra->mensaje_pago ?? 'Sin mensaje' }}</div>
                                </div>

                                @if($compra->tieneArchivoPago())
                                <div class="mb-3">
                                    <div class="info-label">Archivo adjunto</div>
                                    <div class="info-value">
                                        <a href="{{ $compra->urlArchivoPago() }}" target="_blank" class="btn btn-outline-primary">
                                            <i class="bi bi-file-earmark-image me-2"></i>Ver comprobante
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($compra->estado === 'cancelada' && $compra->motivo_rechazo)
                                <div class="alert alert-danger mt-3">
                                    <strong><i class="bi bi-x-circle me-2"></i>Motivo del rechazo:</strong>
                                    <p class="mb-0 mt-2">{{ $compra->motivo_rechazo }}</p>
                                </div>
                                @endif

                                @if($compra->estado === 'pagada' && $compra->fecha_revision)
                                <div class="alert alert-success mt-3">
                                    <strong><i class="bi bi-check-circle me-2"></i>Pago aprobado</strong>
                                    <p class="mb-0 mt-2">
                                        Aprobado el {{ $compra->fecha_revision->format('d/m/Y H:i') }}
                                        @if($compra->revisor)
                                            por {{ $compra->revisor->name }}
                                        @endif
                                    </p>
                                </div>
                                @endif
                            </div>

                            @if($compra->estado === 'pendiente')
                            <div class="col-md-4">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success btn-lg" onclick="aprobarPago({{ $compra->id }})">
                                        <i class="bi bi-check-circle me-2"></i>Aprobar Pago
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="mostrarModalRechazo()">
                                        <i class="bi bi-x-circle me-2"></i>Rechazar Pago
                                    </button>
                                </div>
                                <div class="mt-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Al aprobar, se marcara como pagada y se generara la comision.
                                    Al rechazar, se liberara el stock y se cancelara la compra.
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Productos --}}
            <div class="bg-white shadow-sm rounded-4 p-6 mb-6">
                <h5 class="mb-4">
                    <i class="bi bi-box me-2"></i>Productos ({{ $compra->items->count() }})
                </h5>
                
                @foreach($compra->items as $item)
                    <div class="product-item">
                        <img src="{{ $item->producto->url_imagen_principal }}" 
                             alt="{{ $item->nombre_producto }}" 
                             class="product-image">
                        
                        <div class="flex-grow-1">
                            <div class="fw-medium">{{ $item->nombre_producto }}</div>
                            @if($item->info_variante)
                                <div class="text-muted small">{{ $item->info_variante }}</div>
                            @endif
                            <div class="text-muted small">Ref: {{ $item->referencia_producto }}</div>
                        </div>
                        
                        <div class="text-center" style="min-width: 100px;">
                            <div class="text-muted small">Cantidad</div>
                            <div class="fw-medium">{{ $item->cantidad }}</div>
                        </div>
                        
                        <div class="text-center" style="min-width: 120px;">
                            <div class="text-muted small">Precio unitario</div>
                            <div class="fw-medium">${{ number_format($item->precio_unitario, 0, ',', '.') }}</div>
                        </div>
                        
                        <div class="text-end" style="min-width: 120px;">
                            <div class="text-muted small">Subtotal</div>
                            <div class="fw-bold">${{ number_format($item->precio_total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
                
                {{-- Totales --}}
                <div class="border-top pt-4 mt-4">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>${{ number_format($compra->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($compra->costo_envio > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Costo de envío</span>
                                    <span>${{ number_format($compra->costo_envio, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($compra->impuestos > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Impuestos</span>
                                    <span>${{ number_format($compra->impuestos, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                                <span>Total</span>
                                <span>${{ number_format($compra->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notas --}}
            @if($compra->notas)
                <div class="bg-white shadow-sm rounded-4 p-6">
                    <h5 class="mb-3">
                        <i class="bi bi-chat-left-text me-2"></i>Notas
                    </h5>
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $compra->notas }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Timeline --}}
    <div class="modal fade" id="modalTimeline" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Timeline de la Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="timeline" id="timelineContent">
                        <li class="timeline-item">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal rechazar pago --}}
    @if($compra->esMetodoOtro() && $compra->estado === 'pendiente')
    <div class="modal fade" id="modalRechazo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formRechazo" action="{{ route('compras.rechazar-pago', $compra) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Rechazar Pago</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Atención:</strong> Al rechazar este pago, se liberará el stock reservado y se cancelará la compra. El cliente recibirá un correo con el motivo del rechazo.
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Motivo del rechazo *</strong></label>
                            <textarea class="form-control" name="motivo_rechazo" rows="4" required
                                      placeholder="Explique al cliente por qué se rechaza el pago..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" id="btnRechazar">
                            <i class="bi bi-x-circle me-2"></i>Confirmar Rechazo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal actualizar envío --}}
    <div class="modal fade" id="modalEnvio" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEnvio">
                    <div class="modal-header">
                        <h5 class="modal-title">Actualizar Información de Envío</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="compraIdEnvio">
                        
                        <div class="mb-3">
                            <label class="form-label">Transportadora *</label>
                            <input type="text" class="form-control" name="transportadora" required
                                   value="{{ $compra->envio->transportadora ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Número de guía *</label>
                            <input type="text" class="form-control" name="numero_guia" required
                                   value="{{ $compra->envio->numero_guia ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL de seguimiento</label>
                            <input type="url" class="form-control" name="url_seguimiento"
                                   value="{{ $compra->envio->url_seguimiento ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Fecha estimada de entrega</label>
                            <input type="date" class="form-control" name="fecha_entrega_estimada" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   value="{{ $compra->envio->fecha_entrega_estimada ?? '' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnActualizarEnvio">
                            <span class="btn-text">
                                <i class="bi bi-truck me-2"></i>Actualizar Envío
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Enviando correo...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function verTimeline() {
            const modal = new bootstrap.Modal(document.getElementById('modalTimeline'));
            modal.show();
            
            // Cargar timeline
            $.ajax({
                url: "{{ route('compras.timeline', $compra) }}",
                success: function(timeline) {
                    let html = '';
                    
                    timeline.forEach(function(evento) {
                        const fecha = new Date(evento.fecha);
                        html += `
                            <li class="timeline-item">
                                <div class="timeline-icon ${evento.color}">
                                    <i class="bi ${evento.icono}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">${evento.titulo}</h6>
                                    <p class="text-muted mb-1">${evento.descripcion}</p>
                                    <small class="text-muted">
                                        ${fecha.toLocaleDateString()} ${fecha.toLocaleTimeString()}
                                    </small>
                                </div>
                            </li>
                        `;
                    });
                    
                    $('#timelineContent').html(html);
                },
                error: function() {
                    $('#timelineContent').html('<li class="text-danger">Error al cargar el timeline</li>');
                }
            });
        }

        function imprimirCompra() {
            window.print();
        }

        // Actualizar envío
        function actualizarEnvio(compraId) {
            $('#compraIdEnvio').val(compraId);
            
            const modal = new bootstrap.Modal(document.getElementById('modalEnvio'));
            modal.show();
        }

        // Submit envío
        $('#formEnvio').on('submit', function(e) {
            e.preventDefault();
            
            const compraId = $('#compraIdEnvio').val();
            const data = $(this).serialize();
            const submitBtn = $('#btnActualizarEnvio');
            
            // Mostrar estado de carga
            submitBtn.prop('disabled', true);
            submitBtn.find('.btn-text').addClass('d-none');
            submitBtn.find('.btn-loading').removeClass('d-none');
            
            $.ajax({
                url: `/compras/${compraId}/actualizar-envio`,
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Restaurar botón
                        submitBtn.find('.btn-loading').addClass('d-none');
                        submitBtn.find('.btn-text').removeClass('d-none');
                        submitBtn.prop('disabled', false);
                        
                        // Cerrar modal
                        $('#modalEnvio').modal('hide');
                        
                        // Mostrar SweetAlert de éxito
                        Swal.fire({
                            title: '¡Envío Actualizado!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#28a745',
                            timer: 5000,
                            timerProgressBar: true,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    // Restaurar botón en caso de error
                    submitBtn.find('.btn-loading').addClass('d-none');
                    submitBtn.find('.btn-text').removeClass('d-none');
                    submitBtn.prop('disabled', false);
                    
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Error al actualizar el envío',
                        icon: 'error',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });

        // Aprobar pago manual
        function aprobarPago(compraId) {
            Swal.fire({
                title: '¿Aprobar este pago?',
                html: `
                    <p>Al aprobar el pago:</p>
                    <ul class="text-start">
                        <li>La compra se marcará como <strong>pagada</strong></li>
                        <li>Se generará la comisión correspondiente</li>
                        <li>El cliente recibirá un correo de confirmación</li>
                    </ul>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Sí, aprobar pago',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Aprobando pago y enviando notificación',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: `/compras/${compraId}/aprobar-pago`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: '¡Pago Aprobado!',
                                text: response.message || 'El pago ha sido aprobado exitosamente.',
                                icon: 'success',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Error al aprobar el pago',
                                icon: 'error',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        }

        // Mostrar modal de rechazo
        function mostrarModalRechazo() {
            const modal = new bootstrap.Modal(document.getElementById('modalRechazo'));
            modal.show();
        }

        // Submit rechazo con SweetAlert
        @if($compra->esMetodoOtro() && $compra->estado === 'pendiente')
        $('#formRechazo').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const motivo = $(form).find('textarea[name="motivo_rechazo"]').val();

            if (!motivo.trim()) {
                Swal.fire({
                    title: 'Campo requerido',
                    text: 'Debe ingresar el motivo del rechazo',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            Swal.fire({
                title: '¿Confirmar rechazo?',
                html: `
                    <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                    <p>El cliente será notificado con el siguiente motivo:</p>
                    <div class="alert alert-secondary text-start">${motivo}</div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-x-circle me-2"></i>Sí, rechazar pago',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cerrar modal
                    $('#modalRechazo').modal('hide');

                    // Mostrar loading
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Rechazando pago y liberando stock',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar formulario via AJAX
                    $.ajax({
                        url: $(form).attr('action'),
                        method: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Pago Rechazado',
                                text: response.message || 'El pago ha sido rechazado y el stock liberado.',
                                icon: 'info',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#3b82f6'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Error al rechazar el pago',
                                icon: 'error',
                                confirmButtonText: 'Entendido',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        });
        @endif
    </script>
    @endpush
</x-app-layout>