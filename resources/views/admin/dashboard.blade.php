{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0">Dashboard de Ventas y Comisiones</h1>
                    <div>
                        <span class="text-muted">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                       value="{{ $fechaInicio->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                       value="{{ $fechaFin->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Aplicar Filtros
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Ventas Totales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($stats['ventas_totales'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">{{ $stats['numero_ventas'] ?? 0 }} transacciones</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cart-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Comisiones Ganadas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($stats['comisiones_totales'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">
                                    @php
                                        $vt = (float)($stats['ventas_totales'] ?? 0);
                                        $ct = (float)($stats['comisiones_totales'] ?? 0);
                                    @endphp
                                    {{ $vt > 0 ? number_format(($ct / $vt) * 100, 2) : '0.00' }}% del total
                                </small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pendiente de Pago
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($stats['comisiones_pendientes'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">A empresas</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Ticket Promedio
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($stats['ticket_promedio'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Por transacción</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfica --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Evolución de Ventas</h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="btnVentas">Ventas</button>
                            <button type="button" class="btn btn-outline-secondary" id="btnTransacciones">Transacciones</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="ventasChart" style="height: 320px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla por empresa --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Resumen por Empresa</h6>
                        <span class="text-muted small">
                            <i class="bi bi-info-circle"></i>
                            Comisiones configuradas por plan de membresía
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaEmpresas">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th class="text-center">Ventas</th>
                                        <th class="text-end">Total Vendido</th>
                                        <th class="text-end">Comisión Admin</th>
                                        <th class="text-end">Para la Empresa</th>
                                        <th class="text-end">Pendiente Pagar</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ventasPorEmpresa as $empresa)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div class="font-weight-bold">{{ $empresa->nombre }}</div>
                                                        <small class="text-muted">
                                                            Comisión: {{ $empresa->planMembresia->porcentaje_comision ?? 0 }}% + ${{ number_format($empresa->planMembresia->comision_fija ?? 0, 0, ',', '.') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $empresa->numero_ventas }}</span>
                                            </td>
                                            <td class="text-end font-weight-bold">
                                                ${{ number_format($empresa->ventas_totales, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end text-success">
                                                ${{ number_format($empresa->comisiones_totales, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                ${{ number_format($empresa->total_empresa, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                @if(($empresa->pendiente_pagar ?? 0) > 0)
                                                    <span class="badge bg-warning text-dark fs-6">
                                                        ${{ number_format($empresa->pendiente_pagar, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">$0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.dashboard.empresa', $empresa->id) }}"
                                                       class="btn btn-outline-info" title="Ver detalle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if(($empresa->pendiente_pagar ?? 0) > 0)
                                                        <button class="btn btn-outline-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#pagarModal{{ $empresa->id }}"
                                                                title="Registrar pago">
                                                            <i class="bi bi-cash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay datos de ventas en el período seleccionado
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if($ventasPorEmpresa->isNotEmpty())
                                    <tfoot>
                                        <tr class="table-secondary font-weight-bold">
                                            <th>TOTALES</th>
                                            <th class="text-center">
                                                <span class="badge bg-dark">{{ $ventasPorEmpresa->sum('numero_ventas') }}</span>
                                            </th>
                                            <th class="text-end">${{ number_format($ventasPorEmpresa->sum('ventas_totales'), 0, ',', '.') }}</th>
                                            <th class="text-end text-success">${{ number_format($ventasPorEmpresa->sum('comisiones_totales'), 0, ',', '.') }}</th>
                                            <th class="text-end">${{ number_format($ventasPorEmpresa->sum('total_empresa'), 0, ',', '.') }}</th>
                                            <th class="text-end">
                                                <span class="badge bg-warning text-dark fs-6">
                                                    ${{ number_format($ventasPorEmpresa->sum('pendiente_pagar'), 0, ',', '.') }}
                                                </span>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resumen de pagos pendientes --}}
        @if($comisionesPendientes->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card shadow border-warning">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="m-0 font-weight-bold">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Resumen de Pagos Pendientes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-info-circle"></i>
                                Tienes <strong>${{ number_format($comisionesPendientes->sum('total_pagar'), 0, ',', '.') }}</strong>
                                pendientes de pago a <strong>{{ $comisionesPendientes->count() }}</strong> empresas.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Empresa</th>
                                            <th class="text-center">Comisiones</th>
                                            <th>Período</th>
                                            <th class="text-end">Total a Pagar</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($comisionesPendientes as $pendiente)
                                            <tr>
                                                <td class="font-weight-bold">{{ $pendiente->nombre }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $pendiente->numero_comisiones }}</span>
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ \Carbon\Carbon::parse($pendiente->primera_comision)->format('d/m/Y') }} -
                                                        {{ \Carbon\Carbon::parse($pendiente->ultima_comision)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <strong class="text-warning">${{ number_format($pendiente->total_pagar, 0, ',', '.') }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#pagarModal{{ $pendiente->id }}">
                                                        <i class="bi bi-cash"></i> Pagar ahora
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modales de pago para ventasPorEmpresa --}}
    @foreach($ventasPorEmpresa as $empresa)
        @if(($empresa->pendiente_pagar ?? 0) > 0)
            <div class="modal fade" id="pagarModal{{ $empresa->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.dashboard.pagar') }}" method="POST" onsubmit="return confirmarPago(this)">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Registrar Pago - {{ $empresa->nombre }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                                <div class="alert alert-info mb-3">
                                    <h6 class="alert-heading">Resumen del pago:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small>Total vendido:</small><br>
                                            <strong>${{ number_format($empresa->ventas_totales, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small>Comisión retenida:</small><br>
                                            <strong>${{ number_format($empresa->comisiones_totales, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <small>TOTAL A PAGAR:</small><br>
                                        <h4 class="mb-0">${{ number_format($empresa->pendiente_pagar, 0, ',', '.') }}</h4>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                    <select name="metodo_pago" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="transferencia">Transferencia Bancaria</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Referencia/Número de Comprobante <span class="text-danger">*</span></label>
                                    <input type="text" name="referencia_pago" class="form-control" required
                                           placeholder="Ej: TRF-123456 o CHQ-789">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observaciones (opcional)</label>
                                    <textarea name="observaciones" class="form-control" rows="2"
                                              placeholder="Información adicional del pago"></textarea>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmar{{ $empresa->id }}" required>
                                    <label class="form-check-label" for="confirmar{{ $empresa->id }}">
                                        Confirmo que he realizado el pago por el monto indicado
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Confirmar Pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Modales de pago para comisionesPendientes --}}
    @foreach($comisionesPendientes as $pendiente)
        <div class="modal fade" id="pagarModal{{ $pendiente->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.dashboard.pagar') }}" method="POST" onsubmit="return confirmarPago(this)">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Registrar Pago - {{ $pendiente->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="empresa_id" value="{{ $pendiente->id }}">

                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">Resumen del pago:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small>Comisiones pendientes:</small><br>
                                        <strong>{{ $pendiente->numero_comisiones }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small>Período:</small><br>
                                        <strong>
                                            {{ \Carbon\Carbon::parse($pendiente->primera_comision)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($pendiente->ultima_comision)->format('d/m/Y') }}
                                        </strong>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <small>TOTAL A PAGAR:</small><br>
                                    <h4 class="mb-0">${{ number_format($pendiente->total_pagar, 0, ',', '.') }}</h4>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Referencia/Número de Comprobante <span class="text-danger">*</span></label>
                                <input type="text" name="referencia_pago" class="form-control" required
                                       placeholder="Ej: TRF-123456 o CHQ-789">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones (opcional)</label>
                                <textarea name="observaciones" class="form-control" rows="2"
                                          placeholder="Información adicional del pago"></textarea>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarPendiente{{ $pendiente->id }}" required>
                                <label class="form-check-label" for="confirmarPendiente{{ $pendiente->id }}">
                                    Confirmo que he realizado el pago por el monto indicado
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirmar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('styles')
        <style>
            .card { border: none; border-radius: 0.35rem; }
            .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
            .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
            .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
            .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
            .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
            .text-xs { font-size: .7rem; }
            .shadow { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15) !important; }
            .chart-area { position: relative; height: 10rem; width: 100%; }
            @media (min-width: 768px) { .chart-area { height: 20rem; } }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para el gráfico (aseguramos arrays válidos)
            const labels = {!! ($ventasDiarias->pluck('fecha') ?? collect())->toJson() !!};
            const ventas = {!! ($ventasDiarias->pluck('total') ?? collect())->toJson() !!};
            const cantidades = {!! ($ventasDiarias->pluck('ventas') ?? collect())->toJson() !!};

            const formatCOP = (n) => '$' + Number(n || 0).toLocaleString('es-CO');

            const ctx = document.getElementById('ventasChart').getContext('2d');
            let ventasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas ($)',
                        data: ventas,
                        borderColor: 'rgb(78, 115, 223)',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    if (context.dataset.label === 'Número de transacciones') {
                                        return 'Total: ' + context.parsed.y;
                                    }
                                    return 'Total: ' + formatCOP(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2]
                            },
                            ticks: {
                                callback: function (value) { return formatCOP(value); }
                            }
                        }
                    }
                }
            });

            // Toggle Ventas / Transacciones
            document.getElementById('btnVentas').addEventListener('click', function () {
                this.classList.add('active');
                document.getElementById('btnTransacciones').classList.remove('active');
                ventasChart.data.datasets[0].data = ventas;
                ventasChart.data.datasets[0].label = 'Ventas ($)';
                ventasChart.options.scales.y.ticks.callback = (value) => formatCOP(value);
                ventasChart.update();
            });

            document.getElementById('btnTransacciones').addEventListener('click', function () {
                this.classList.add('active');
                document.getElementById('btnVentas').classList.remove('active');
                ventasChart.data.datasets[0].data = cantidades;
                ventasChart.data.datasets[0].label = 'Número de transacciones';
                ventasChart.options.scales.y.ticks.callback = (value) => value;
                ventasChart.update();
            });

            // Confirmar pago (expuesto en window porque lo usa el formulario)
            window.confirmarPago = function (form) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Confirmar pago?',
                    text: '¿Está seguro de registrar este pago? Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                return false;
            };

            // Mensajes flash
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: @json(session('success')),
                    confirmButtonColor: '#198754'
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
        </script>
    @endpush
</x-app-layout>
