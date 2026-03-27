<x-app-layout>
  <x-slot name="header">Dashboard de Stock</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Header con información de la empresa --}}
      <div class="mb-6">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="text-2xl font-bold">
            <i class="bi bi-speedometer2"></i> Dashboard de Stock - {{ $empresa->nombre }}
          </h2>
          <div>
            <a href="{{ route('stock.index') }}" class="btn btn-outline-primary">
              <i class="bi bi-box-seam"></i> Gestión de Stock
            </a>
            <a href="{{ route('stock.reporte-movimiento') }}" class="btn btn-outline-secondary">
              <i class="bi bi-file-earmark-bar-graph"></i> Reportes
            </a>
          </div>
        </div>
      </div>

      {{-- Tarjetas de resumen principal --}}
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card border-primary h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-1">Total Productos</h6>
                  <h3 class="mb-0">{{ $totalProductos }}</h3>
                  <small class="text-muted">Con control de stock</small>
                </div>
                <div class="text-primary opacity-75">
                  <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-success h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-1">Con Stock</h6>
                  <h3 class="mb-0 text-success">{{ $productosConStock }}</h3>
                  <small class="text-muted">Disponibles</small>
                </div>
                <div class="text-success opacity-75">
                  <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-warning h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-1">Stock Bajo</h6>
                  <h3 class="mb-0 text-warning">{{ $productosStockBajo }}</h3>
                  <small class="text-muted">Requieren atención</small>
                </div>
                <div class="text-warning opacity-75">
                  <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card border-danger h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-1">Sin Stock</h6>
                  <h3 class="mb-0 text-danger">{{ $productosSinStock }}</h3>
                  <small class="text-muted">Agotados</small>
                </div>
                <div class="text-danger opacity-75">
                  <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Movimientos del mes --}}
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-light">
              <h5 class="mb-0">
                <i class="bi bi-arrow-down-up"></i> Movimientos del Mes
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 text-center border-end">
                  <h6 class="text-success">Entradas</h6>
                  <h2 class="text-success mb-0">+{{ number_format($entradasMes) }}</h2>
                  <small class="text-muted">Unidades ingresadas</small>
                </div>
                <div class="col-md-6 text-center">
                  <h6 class="text-danger">Salidas</h6>
                  <h2 class="text-danger mb-0">-{{ number_format($salidasMes) }}</h2>
                  <small class="text-muted">Unidades vendidas</small>
                </div>
              </div>
              
              <hr class="my-3">
              
              <div class="text-center">
                <h6 class="text-muted">Balance del Mes</h6>
                @php
                  $balance = $entradasMes - $salidasMes;
                  $colorBalance = $balance >= 0 ? 'success' : 'danger';
                  $signoBalance = $balance >= 0 ? '+' : '';
                @endphp
                <h3 class="text-{{ $colorBalance }}">{{ $signoBalance }}{{ number_format($balance) }}</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-light">
              <h5 class="mb-0">
                <i class="bi bi-graph-up"></i> Estadísticas Rápidas
              </h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span>Productos con stock</span>
                  <span class="fw-bold">{{ $productosConStock }}/{{ $totalProductos }}</span>
                </div>
                <div class="progress" style="height: 20px;">
                  @php
                    $porcentajeConStock = $totalProductos > 0 ? ($productosConStock / $totalProductos) * 100 : 0;
                  @endphp
                  <div class="progress-bar bg-success" role="progressbar" 
                       style="width: {{ $porcentajeConStock }}%"
                       aria-valuenow="{{ $porcentajeConStock }}" 
                       aria-valuemin="0" 
                       aria-valuemax="100">
                    {{ number_format($porcentajeConStock, 1) }}%
                  </div>
                </div>
              </div>
@php
  $totalCriticos = $productosStockBajo + $productosSinStock;
  $porcentajeCriticos = $totalProductos > 0 ? ($totalCriticos / $totalProductos) * 100 : 0;
@endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span>Productos críticos</span>
                   <span class="fw-bold">{{ $totalCriticos }}</span>
                </div>
<div class="progress" style="height: 20px;">
  <div class="progress-bar bg-danger" role="progressbar"
       style="width: {{ $porcentajeCriticos }}%"
       aria-valuenow="{{ $porcentajeCriticos }}"
       aria-valuemin="0"
       aria-valuemax="100">
    {{ number_format($porcentajeCriticos, 1) }}%
  </div>
</div>
              </div>

              <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i>
                <strong>Tasa de rotación:</strong> 
                @if($entradasMes > 0)
                  {{ number_format(($salidasMes / $entradasMes) * 100, 1) }}% del inventario
                @else
                  No hay entradas este mes
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Productos con mayor rotación y productos críticos --}}
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-light">
              <h5 class="mb-0">
                <i class="bi bi-fire"></i> Top 10 - Mayor Rotación (Último mes)
              </h5>
            </div>
            <div class="card-body">
              @if($productosTopRotacion->isEmpty())
                <p class="text-center text-muted">No hay datos de rotación disponibles</p>
              @else
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th class="text-end">Unidades Vendidas</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($productosTopRotacion as $item)
                        @php
                          $producto = \App\Models\Producto::find($item->producto_id);
                        @endphp
                        @if($producto)
                          <tr>
                            <td>
                              <strong>{{ $producto->referencia }}</strong><br>
                              <small class="text-muted">{{ $producto->nombre }}</small>
                            </td>
                            <td class="text-end">
                              <span class="badge bg-primary">{{ number_format($item->total_movimiento) }}</span>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
            <div class="card-footer text-center">
              <a href="{{ route('stock.reporte-movimiento') }}?tipo_movimiento=salida" class="btn btn-sm btn-outline-primary">
                Ver reporte completo
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-light">
              <h5 class="mb-0">
                <i class="bi bi-exclamation-octagon"></i> Productos Críticos - Stock Bajo
              </h5>
            </div>
            <div class="card-body">
              @if($productosCriticos->isEmpty())
                <p class="text-center text-muted">No hay productos con stock crítico</p>
              @else
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Mínimo</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($productosCriticos as $stock)
                        <tr class="{{ $stock->stock_real <= 0 ? 'table-danger' : 'table-warning' }}">
                          <td>
                            <strong>{{ $stock->producto->referencia }}</strong><br>
                            <small class="text-muted">
                              {{ $stock->producto->nombre }}
                              @if($stock->variante)
                                - {{ $stock->variante->nombre_variante }}
                              @endif
                            </small>
                          </td>
                          <td class="text-center">
                            <span class="badge bg-{{ $stock->stock_real <= 0 ? 'danger' : 'warning' }}">
                              {{ $stock->stock_real }}
                            </span>
                          </td>
                          <td class="text-center">
                            {{ $stock->stock_minimo }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
            <div class="card-footer text-center">
              <a href="{{ route('stock.index') }}?estado=stock_bajo" class="btn btn-sm btn-outline-warning">
                Ver todos los productos con stock bajo
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Acciones rápidas --}}
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card bg-light">
            <div class="card-body">
              <h5 class="card-title">
                <i class="bi bi-lightning"></i> Acciones Rápidas
              </h5>
              <div class="row">
                <div class="col-md-3 mb-2">
                  <a href="{{ route('stock.index') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-box-seam"></i> Gestionar Stock
                  </a>
                </div>
                <div class="col-md-3 mb-2">
                  <a href="{{ route('stock.reporte-movimiento') }}" class="btn btn-outline-info w-100">
                    <i class="bi bi-file-earmark-text"></i> Ver Movimientos
                  </a>
                </div>
                <div class="col-md-3 mb-2">
                  <a href="{{ route('productos.form') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                  </a>
                </div>
                <div class="col-md-3 mb-2">
                  <button class="btn btn-outline-warning w-100" onclick="exportarInventario()">
                    <i class="bi bi-download"></i> Exportar Inventario
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    function exportarInventario() {
      // TODO: Implementar exportación
      alert('Función de exportación en desarrollo');
    }

    // Actualizar dashboard cada 5 minutos
    setInterval(function() {
      if (document.visibilityState === 'visible') {
        location.reload();
      }
    }, 300000); // 5 minutos
  </script>
  @endpush
</x-app-layout>