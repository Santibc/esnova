<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3">Detalle de Comisiones - {{ $empresa->nombre }}</h1>
                        <p class="text-muted mb-0">
                            Comisión: {{ $empresa->planMembresia->porcentaje_comision ?? 0 }}% + ${{ number_format($empresa->planMembresia->comision_fija ?? 0, 0) }} por venta
                        </p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('admin.dashboard.empresa', $empresa->id) }}" method="GET" class="row g-3">
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
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Ventas Totales</h6>
                        <h3>${{ number_format($resumen['ventas_totales'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Comisiones Admin</h6>
                        <h3 class="text-success">${{ number_format($resumen['comisiones_totales'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Empresa</h6>
                        <h3>${{ number_format($resumen['total_empresa'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning">
                    <div class="card-body">
                        <h6 class="text-dark">Pendiente de Pago</h6>
                        <h3 class="text-dark">${{ number_format($resumen['pendiente_pagar'], 0, ',', '.') }}</h3>
                        @if($resumen['pendiente_pagar'] > 0)
                            <button class="btn btn-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#pagarModal">
                                <i class="bi bi-cash"></i> Pagar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detalle de Comisiones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Orden #</th>
                                <th>Cliente</th>
                                <th class="text-end">Venta</th>
                                <th class="text-end">Comisión Admin</th>
                                <th class="text-end">Para Empresa</th>
                                <th>Estado</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comisiones as $comision)
                            <tr>
                                <td>{{ $comision->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('compras.show', $comision->compra_id) }}" target="_blank">
                                        {{ $comision->compra->numero_compra }}
                                    </a>
                                </td>
                                <td>{{ $comision->compra->nombre_cliente }}</td>
                                <td class="text-end">${{ number_format($comision->monto_venta, 0, ',', '.') }}</td>
                                <td class="text-end text-success">
                                    ${{ number_format($comision->monto_comision, 0, ',', '.') }}
                                </td>
                                <td class="text-end">${{ number_format($comision->monto_empresa, 0, ',', '.') }}</td>
                                <td>
                                    @if($comision->estado == 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($comision->estado == 'pagada')
                                        <span class="badge bg-success">Pagada</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $comision->estado }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#detalleModal{{ $comision->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay comisiones en el período seleccionado</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $comisiones->links() }}
                </div>
            </div>
        </div>
    </div>

    @if($resumen['pendiente_pagar'] > 0)
    <div class="modal fade" id="pagarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.dashboard.pagar') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Pago - {{ $empresa->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                        
                        <div class="alert alert-info">
                            <strong>Total a pagar:</strong> ${{ number_format($resumen['pendiente_pagar'], 0, ',', '.') }}
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="transferencia">Transferencia Bancaria</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Referencia/Número de Comprobante</label>
                            <input type="text" name="referencia_pago" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Observaciones (opcional)</label>
                            <textarea name="observaciones" class="form-control" rows="2"></textarea>
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

    @foreach($comisiones as $comision)
    <div class="modal fade" id="detalleModal{{ $comision->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Comisión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Productos vendidos:</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comision->compra->items as $item)
                            <tr>
                                <td>{{ $item->nombre_producto }}</td>
                                <td>{{ $item->cantidad }}</td>
                                <td>${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                                <td>${{ number_format($item->precio_total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Subtotal:</strong> ${{ number_format($comision->compra->subtotal, 0, ',', '.') }}</p>
                            <p><strong>Total Venta:</strong> ${{ number_format($comision->monto_venta, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Comisión ({{ $comision->porcentaje_comision }}% + ${{ number_format($empresa->planMembresia->comision_fija ?? 0, 0) }}):</strong> 
                               ${{ number_format($comision->monto_comision, 0, ',', '.') }}</p>
                            <p><strong>Para la empresa:</strong> ${{ number_format($comision->monto_empresa, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    @if($comision->estado == 'pagada')
                    <hr>
                    <p><strong>Fecha de pago:</strong> {{ $comision->fecha_pago }}</p>
                    <p><strong>Referencia:</strong> {{ $comision->referencia_pago }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</x-app-layout>