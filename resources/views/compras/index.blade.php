<x-app-layout>
    <x-slot name="header">Gestión de Compras</x-slot>

    <style>
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
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

        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
            cursor: pointer;
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

            {{-- Estadísticas --}}
            <div class="row g-3 mb-6">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="stats-card h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Compras</p>
                                <p class="h4 fw-bold text-gray-900 mb-0">{{ $estadisticas['total_compras'] }}</p>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-cart-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="stats-card h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Compras Pagadas</p>
                                <p class="h4 fw-bold text-success mb-0">{{ $estadisticas['compras_pagadas'] }}</p>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="stats-card h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Ventas del Mes</p>
                                <p class="h4 fw-bold text-purple-600 mb-0">${{ number_format($estadisticas['ventas_mes'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-purple-500">
                                <i class="bi bi-currency-dollar fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="stats-card h-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Pendientes</p>
                                <p class="h4 fw-bold text-warning mb-0">{{ $estadisticas['compras_pendientes'] }}</p>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-hourglass-split fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                @if(($estadisticas['pendientes_revision'] ?? 0) > 0)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="stats-card h-100" style="background: linear-gradient(135deg, #fef3c7, #fde68a); border: 2px solid #f59e0b;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-sm mb-1" style="color: #92400e;">Pagos por Revisar</p>
                                <p class="h4 fw-bold mb-0" style="color: #92400e;">{{ $estadisticas['pendientes_revision'] }}</p>
                            </div>
                            <div style="color: #f59e0b;">
                                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Card principal --}}
            <div class="bg-white shadow-sm rounded-4 overflow-hidden">
                {{-- Header con filtros --}}
                <div class="p-6 border-bottom">
                    <div class="row align-items-center mb-4">
                        <div class="col">
                            <h3 class="mb-0 fw-bold">Listado de Compras</h3>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filtros">
                                <i class="bi bi-funnel"></i> Filtros
                            </button>
                            <a href="{{ route('compras.exportar', request()->all()) }}" class="btn btn-outline-success">
                                <i class="bi bi-download"></i> Exportar
                            </a>
                        </div>
                    </div>

                    {{-- Filtros colapsables --}}
                    <div class="collapse {{ request()->hasAny(['buscar', 'estado', 'fecha_desde', 'fecha_hasta', 'metodo_pago']) ? 'show' : '' }}" id="filtros">
                        <form method="GET" action="{{ route('compras') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Buscar</label>
                                <input type="text"
                                       class="form-control"
                                       name="buscar"
                                       placeholder="Numero, cliente, email..."
                                       value="{{ request('buscar') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">Estado</label>
                                <select class="form-select" name="estado">
                                    <option value="">Todos</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="procesando" {{ request('estado') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                    <option value="pagada" {{ request('estado') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                                    <option value="enviada" {{ request('estado') == 'enviada' ? 'selected' : '' }}>Enviada</option>
                                    <option value="entregada" {{ request('estado') == 'entregada' ? 'selected' : '' }}>Entregada</option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="reembolsada" {{ request('estado') == 'reembolsada' ? 'selected' : '' }}>Reembolsada</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">Metodo Pago</label>
                                <select class="form-select" name="metodo_pago">
                                    <option value="">Todos</option>
                                    <option value="wompi" {{ request('metodo_pago') == 'wompi' ? 'selected' : '' }}>Wompi</option>
                                    <option value="otro" {{ request('metodo_pago') == 'otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">Fecha desde</label>
                                <input type="date"
                                       class="form-control"
                                       name="fecha_desde"
                                       value="{{ request('fecha_desde') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">Fecha hasta</label>
                                <input type="date"
                                       class="form-control"
                                       name="fecha_hasta"
                                       value="{{ request('fecha_hasta') }}">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-1" title="Buscar">
                                    <i class="bi bi-search"></i>
                                </button>
                                <a href="{{ route('compras') }}" class="btn btn-outline-secondary" title="Limpiar">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-6 py-3">Orden</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Pago</th>
                                <th>Envío</th>
                                <th class="text-end px-6">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($compras as $compra)
                                <tr onclick="window.location='{{ route('compras.show', $compra) }}'" style="cursor: pointer;">
                                    <td class="px-6">
                                        <div class="fw-semibold">{{ $compra->numero_compra }}</div>
                                        <div class="text-muted small">{{ $compra->total_items }} items</div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $compra->nombre_cliente }}</div>
                                        <div class="text-muted small">{{ $compra->email_cliente }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $compra->created_at->format('d/m/Y') }}</div>
                                        <div class="text-muted small">{{ $compra->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="fw-bold">${{ number_format($compra->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $compra->estado }}">
                                            {{ ucfirst($compra->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($compra->metodo_pago === 'otro')
                                            <span class="badge bg-secondary" title="Pago manual">
                                                <i class="bi bi-wallet2"></i> Otro
                                            </span>
                                            @if($compra->estado === 'pendiente')
                                                <span class="badge bg-warning text-dark" title="Requiere revision">
                                                    <i class="bi bi-exclamation-circle"></i>
                                                </span>
                                            @endif
                                        @else
                                            @if($compra->transaccionAprobada)
                                                <span class="badge bg-success" title="Pagado con Wompi">
                                                    <i class="bi bi-credit-card"></i> Wompi
                                                </span>
                                            @else
                                                <span class="badge bg-info" title="Wompi pendiente">
                                                    <i class="bi bi-credit-card"></i> Wompi
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($compra->envio)
                                            @if($compra->envio->estado == 'entregado')
                                                <i class="bi bi-house-check text-success" title="Entregado"></i>
                                            @elseif($compra->envio->estado == 'enviado' || $compra->envio->estado == 'en_transito')
                                                <i class="bi bi-truck text-info" title="En camino"></i>
                                            @else
                                                <i class="bi bi-box text-warning" title="Preparando"></i>
                                            @endif
                                        @else
                                            <i class="bi bi-dash text-muted"></i>
                                        @endif
                                    </td>
                                    <td class="text-end px-6" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('compras.show', $compra) }}">
                                                        <i class="bi bi-eye me-2"></i> Ver detalle
                                                    </a>
                                                </li>
                                                @if(in_array($compra->estado, ['pendiente', 'procesando', 'pagada', 'enviada']))
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="actualizarEnvio({{ $compra->id }})">
                                                        <i class="bi bi-truck me-2"></i> Actualizar envío
                                                    </a>
                                                </li>
                                                @endif
     {{--                                            <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="cambiarEstado({{ $compra->id }}, '{{ $compra->estado }}')">
                                                        <i class="bi bi-arrow-repeat me-2"></i> Cambiar estado
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            No se encontraron compras
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($compras->hasPages())
                    <div class="px-6 py-4 border-top">
                        {{ $compras->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal cambiar estado --}}
    <div class="modal fade" id="modalEstado" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEstado">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar Estado de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="compraId">
                        
                        <div class="mb-3">
                            <label class="form-label">Estado actual</label>
                            <input type="text" class="form-control" id="estadoActual" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nuevo estado</label>
                            <select class="form-select" id="nuevoEstado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="procesando">Procesando</option>
                                <option value="pagada">Pagada</option>
                                <option value="enviada">Enviada</option>
                                <option value="entregada">Entregada</option>
                                <option value="cancelada">Cancelada</option>
                                <option value="reembolsada">Reembolsada</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notas (opcional)</label>
                            <textarea class="form-control" name="notas" rows="3"></textarea>
                        </div>
                        
                        <div class="alert alert-warning d-none" id="alertaCambioEstado">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <span id="mensajeAlerta"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Actualizar Estado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                            <input type="text" class="form-control" name="transportadora" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Número de guía *</label>
                            <input type="text" class="form-control" name="numero_guia" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL de seguimiento</label>
                            <input type="url" class="form-control" name="url_seguimiento">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Fecha estimada de entrega</label>
                            <input type="date" class="form-control" name="fecha_entrega_estimada" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
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
        // Cambiar estado
        function cambiarEstado(compraId, estadoActual) {
            $('#compraId').val(compraId);
            $('#estadoActual').val(estadoActual.charAt(0).toUpperCase() + estadoActual.slice(1));
            $('#nuevoEstado').val('');
            $('#alertaCambioEstado').addClass('d-none');
            
            const modal = new bootstrap.Modal(document.getElementById('modalEstado'));
            modal.show();
        }

        // Mostrar alertas según el estado seleccionado
        $('#nuevoEstado').on('change', function() {
            const nuevoEstado = $(this).val();
            const alertDiv = $('#alertaCambioEstado');
            const mensaje = $('#mensajeAlerta');
            
            alertDiv.addClass('d-none');
            
            switch(nuevoEstado) {
                case 'cancelada':
                    mensaje.text('Al cancelar la compra se devolverá el stock de los productos.');
                    alertDiv.removeClass('d-none');
                    break;
                case 'reembolsada':
                    mensaje.text('Se procesará el reembolso a través de la pasarela de pago y se devolverá el stock.');
                    alertDiv.removeClass('d-none');
                    break;
            }
        });

        // Submit cambio de estado
        $('#formEstado').on('submit', function(e) {
            e.preventDefault();
            
            const compraId = $('#compraId').val();
            const data = $(this).serialize();
            
            $.ajax({
                url: `/compras/${compraId}/cambiar-estado`,
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error al actualizar el estado');
                }
            });
        });

        // Actualizar envío
        function actualizarEnvio(compraId) {
            $('#compraIdEnvio').val(compraId);
            $('#formEnvio')[0].reset();
            
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
    </script>
    @endpush
</x-app-layout>