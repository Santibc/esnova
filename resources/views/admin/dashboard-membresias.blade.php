{{-- resources/views/admin/dashboard-membresias.blade.php --}}
<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0">Dashboard de Membresías</h1>
                    <div>
                        <span class="text-muted">Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.dashboard.membresias') }}" method="GET" class="row g-3 align-items-end">
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
                                <a href="{{ route('admin.dashboard.membresias') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- Estadísticas principales --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Ingresos Totales
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($statsMembresias['ingresos_total'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">
                                    @if($statsMembresias['crecimiento_porcentual'] > 0)
                                        <i class="bi bi-arrow-up text-success"></i>
                                        +{{ number_format($statsMembresias['crecimiento_porcentual'], 1) }}% vs mes anterior
                                    @elseif($statsMembresias['crecimiento_porcentual'] < 0)
                                        <i class="bi bi-arrow-down text-danger"></i>
                                        {{ number_format($statsMembresias['crecimiento_porcentual'], 1) }}% vs mes anterior
                                    @else
                                        Sin cambios vs mes anterior
                                    @endif
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
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Membresías Activas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statsMembresias['membresias_activas'] ?? 0) }}
                                </div>
                                <small class="text-muted">Total en la plataforma</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                                    Nuevas Membresías
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statsMembresias['membresias_totales'] ?? 0) }}
                                </div>
                                <small class="text-muted">En el período seleccionado</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-plus-circle fa-2x text-gray-300"></i>
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
                                    Promedio por Membresía
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($statsMembresias['promedio_ingresos_membresia'] ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Ingreso promedio</small>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calculator fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas adicionales --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Membresías Pagadas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statsMembresias['membresias_pagadas'] ?? 0) }}
                                </div>
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
                                    Membresías Pendientes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statsMembresias['membresias_pendientes'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Membresías Canceladas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($statsMembresias['membresias_canceladas'] ?? 0) }}
                                </div>
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
                                    Tasa de Renovación
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($resumenRenovaciones['tasa_renovacion'] ?? 0, 1) }}%
                                </div>
                                <small class="text-muted">{{ $resumenRenovaciones['renovaciones'] ?? 0 }} renovaciones</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfica de evolución --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Evolución de Membresías</h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="btnIngresos">Ingresos</button>
                            <button type="button" class="btn btn-outline-secondary" id="btnCantidad">Cantidad</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="membresiaChart" style="height: 320px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de ingresos por plan --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ingresos por Plan de Membresía</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaPlanMembresias">
                                <thead>
                                    <tr>
                                        <th>Plan de Membresía</th>
                                        <th class="text-center">Precio Plan</th>
                                        <th class="text-center">Total Contratadas</th>
                                        <th class="text-end">Ingresos Total</th>
                                        <th class="text-center">Activas</th>
                                        <th class="text-center">Pendientes</th>
                                        <th class="text-center">Canceladas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ingresosPorPlan as $plan)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold">{{ $plan->nombre }}</div>
                                            </td>
                                            <td class="text-center">
                                                ${{ number_format($plan->precio, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $plan->total_membresias }}</span>
                                            </td>
                                            <td class="text-end font-weight-bold text-success">
                                                ${{ number_format($plan->ingresos_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $plan->membresias_activas }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $plan->membresias_pendientes }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $plan->membresias_canceladas }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay datos de membresías en el período seleccionado
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if($ingresosPorPlan->isNotEmpty())
                                    <tfoot>
                                        <tr class="table-secondary font-weight-bold">
                                            <th>TOTALES</th>
                                            <th></th>
                                            <th class="text-center">
                                                <span class="badge bg-dark">{{ $ingresosPorPlan->sum('total_membresias') }}</span>
                                            </th>
                                            <th class="text-end">${{ number_format($ingresosPorPlan->sum('ingresos_total'), 0, ',', '.') }}</th>
                                            <th class="text-center">
                                                <span class="badge bg-success">{{ $ingresosPorPlan->sum('membresias_activas') }}</span>
                                            </th>
                                            <th class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $ingresosPorPlan->sum('membresias_pendientes') }}</span>
                                            </th>
                                            <th class="text-center">
                                                <span class="badge bg-danger">{{ $ingresosPorPlan->sum('membresias_canceladas') }}</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla por empresa --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Membresías por Empresa</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaEmpresasMembresias">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th class="text-center">Membresías</th>
                                        <th class="text-end">Total Pagado</th>
                                        <th>Planes Contratados</th>
                                        <th class="text-center">Estado</th>
                                        <th>Inicio Membresía</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($membresiasPorEmpresa as $empresa)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold">{{ $empresa->nombre }}</div>
                                                <small class="text-muted">{{ $empresa->email }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $empresa->total_membresias }}</span>
                                                <small class="d-block text-muted">{{ $empresa->membresias_activas }} activas</small>
                                            </td>
                                            <td class="text-end font-weight-bold">
                                                ${{ number_format($empresa->total_pagado, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $empresa->planes_contratados ?: 'Ninguno' }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if($empresa->membresias_activas > 0)
                                                    @if($empresa->precio_plan_activo > 0)
                                                        @if($empresa->fecha_vencimiento)
                                                            @php
                                                                $diasRestantes = now()->diffInDays(\Carbon\Carbon::parse($empresa->fecha_vencimiento), false);
                                                            @endphp
                                                            @if($diasRestantes > 7)
                                                                <span class="badge bg-success">Activa</span>
                                                            @elseif($diasRestantes > 0)
                                                                <span class="badge bg-warning text-dark">Expira en {{ $diasRestantes }} días</span>
                                                            @else
                                                                <span class="badge bg-danger">Vencida</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-success">Activa</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info">Plan Gratuito Activo</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Sin membresía</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($empresa->ultima_membresia)
                                                    <small>{{ \Carbon\Carbon::parse($empresa->ultima_membresia)->format('d/m/Y') }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                No hay datos en el período seleccionado
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Membresías por vencer --}}
        @if($membresiasPorVencer->isNotEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card shadow border-warning">
                        <div class="card-header bg-warning text-dark py-3">
                            <h6 class="m-0 font-weight-bold">
                                <i class="bi bi-clock-fill"></i>
                                Membresías por Vencer (Próximos 30 días)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Empresa</th>
                                            <th>Plan</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Días Restantes</th>
                                            <th class="text-end">Valor Pagado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($membresiasPorVencer as $membresia)
                                            <tr class="{{ $membresia->dias_restantes <= 7 ? 'table-danger' : 'table-warning' }}">
                                                <td>
                                                    <div class="font-weight-bold">{{ $membresia->empresa_nombre }}</div>
                                                    <small class="text-muted">{{ $membresia->email }}</small>
                                                </td>
                                                <td>{{ $membresia->plan_nombre }}</td>
                                                <td>{{ \Carbon\Carbon::parse($membresia->fecha_fin)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge {{ $membresia->dias_restantes <= 7 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                        {{ $membresia->dias_restantes }} días
                                                    </span>
                                                </td>
                                                <td class="text-end">${{ number_format($membresia->precio_pagado, 0, ',', '.') }}</td>
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

    @push('styles')
        <style>
            .card { border: none; border-radius: 0.35rem; }
            .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
            .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
            .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
            .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
            .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
            .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
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
            // Datos para el gráfico
            const labels = {!! ($membresiasMensuales->pluck('periodo') ?? collect())->toJson() !!};
            const ingresos = {!! ($membresiasMensuales->pluck('ingresos') ?? collect())->toJson() !!};
            const cantidades = {!! ($membresiasMensuales->pluck('membresias') ?? collect())->toJson() !!};

            const formatCOP = (n) => '$' + Number(n || 0).toLocaleString('es-CO');

            const ctx = document.getElementById('membresiaChart').getContext('2d');
            let membresiaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: ingresos,
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
                                    if (context.dataset.label === 'Cantidad de Membresías') {
                                        return 'Cantidad: ' + context.parsed.y;
                                    }
                                    return 'Ingresos: ' + formatCOP(context.parsed.y);
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

            // Toggle Ingresos / Cantidad
            document.getElementById('btnIngresos').addEventListener('click', function () {
                this.classList.add('active');
                document.getElementById('btnCantidad').classList.remove('active');
                membresiaChart.data.datasets[0].data = ingresos;
                membresiaChart.data.datasets[0].label = 'Ingresos ($)';
                membresiaChart.options.scales.y.ticks.callback = (value) => formatCOP(value);
                membresiaChart.update();
            });

            document.getElementById('btnCantidad').addEventListener('click', function () {
                this.classList.add('active');
                document.getElementById('btnIngresos').classList.remove('active');
                membresiaChart.data.datasets[0].data = cantidades;
                membresiaChart.data.datasets[0].label = 'Cantidad de Membresías';
                membresiaChart.options.scales.y.ticks.callback = (value) => value;
                membresiaChart.update();
            });

            // Mensajes flash
            @if(session('success'))
                alert(@json(session('success')));
            @endif
            @if(session('error'))
                alert(@json(session('error')));
            @endif
        });
        </script>
    @endpush
</x-app-layout>