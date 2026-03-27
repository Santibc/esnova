<x-app-layout>
  <x-slot name="header">Reporte de Movimientos de Stock</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Header --}}
      <div class="mb-6">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="text-2xl font-bold">
            <i class="bi bi-file-earmark-bar-graph"></i> Reporte de Movimientos - {{ $empresa->nombre }}
          </h2>
          <div>
            <a href="{{ route('stock.index') }}" class="btn btn-outline-primary">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="{{ route('stock.dashboard') }}" class="btn btn-outline-secondary">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          </div>
        </div>
      </div>

      {{-- Filtros --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
          <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h5>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('stock.reporte-movimiento') }}" id="formFiltros">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" 
                       value="{{ request('fecha_inicio', $fechaInicio->format('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}">
              </div>
              
              <div class="col-md-3 mb-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control" 
                       value="{{ request('fecha_fin', $fechaFin->format('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}">
              </div>
              
              <div class="col-md-3 mb-3">
                <label class="form-label">Tipo de Movimiento</label>
                <select name="tipo_movimiento" class="form-select">
                  <option value="">-- Todos --</option>
                  <option value="entrada" {{ request('tipo_movimiento') == 'entrada' ? 'selected' : '' }}>
                    Entradas
                  </option>
                  <option value="salida" {{ request('tipo_movimiento') == 'salida' ? 'selected' : '' }}>
                    Salidas
                  </option>
                  <option value="ajuste" {{ request('tipo_movimiento') == 'ajuste' ? 'selected' : '' }}>
                    Ajustes
                  </option>
                  <option value="reserva" {{ request('tipo_movimiento') == 'reserva' ? 'selected' : '' }}>
                    Reservas
                  </option>
                  <option value="liberacion" {{ request('tipo_movimiento') == 'liberacion' ? 'selected' : '' }}>
                    Liberaciones
                  </option>
                </select>
              </div>
              
              <div class="col-md-3 mb-3">
                <label class="form-label">Producto</label>
                <select name="producto_id" class="form-select select2-productos">
                  <option value="">-- Todos --</option>
                  @if(request('producto_id'))
                    @php
                      $productoSeleccionado = \App\Models\Producto::find(request('producto_id'));
                    @endphp
                    @if($productoSeleccionado)
                      <option value="{{ $productoSeleccionado->id }}" selected>
                        {{ $productoSeleccionado->referencia }} - {{ $productoSeleccionado->nombre }}
                      </option>
                    @endif
                  @endif
                </select>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ route('stock.reporte-movimiento') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-x-circle"></i> Limpiar
                </a>
                <button type="button" class="btn btn-success" onclick="exportarReporte()">
                  <i class="bi bi-download"></i> Exportar Excel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- Resumen --}}
      <div class="row mb-4">
        @php
          $totalEntradas = $movimientos->where('tipo_movimiento', 'entrada')->sum('cantidad');
          $totalSalidas = $movimientos->where('tipo_movimiento', 'salida')->sum('cantidad');
          $totalAjustes = $movimientos->where('tipo_movimiento', 'ajuste')->sum('cantidad');
        @endphp
        
        <div class="col-md-3">
          <div class="card text-white bg-success">
            <div class="card-body">
              <h6 class="card-title">Total Entradas</h6>
              <h3 class="mb-0">+{{ number_format($totalEntradas) }}</h3>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card text-white bg-danger">
            <div class="card-body">
              <h6 class="card-title">Total Salidas</h6>
              <h3 class="mb-0">-{{ number_format($totalSalidas) }}</h3>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card text-white bg-warning">
            <div class="card-body">
              <h6 class="card-title">Total Ajustes</h6>
              <h3 class="mb-0">{{ $totalAjustes >= 0 ? '+' : '' }}{{ number_format($totalAjustes) }}</h3>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card text-white bg-info">
            <div class="card-body">
              <h6 class="card-title">Total Movimientos</h6>
              <h3 class="mb-0">{{ number_format($movimientos->count()) }}</h3>
            </div>
          </div>
        </div>
      </div>

      {{-- Tabla de movimientos --}}
      <div class="card shadow">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bi bi-list"></i> 
            Detalle de Movimientos ({{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }})
          </h5>
        </div>
        <div class="card-body">
          @if($movimientos->isEmpty())
            <div class="text-center py-5">
              <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
              <p class="text-muted mt-3">No se encontraron movimientos con los filtros seleccionados</p>
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover" id="tablaMovimientos">
                <thead>
                  <tr>
                    <th>Fecha/Hora</th>
                    <th>Tipo</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Stock Ant.</th>
                    <th>Stock Nuevo</th>
                    <th>Origen</th>
                    <th>Usuario</th>
                    <th>Referencia</th>
                    <th>Motivo</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($movimientos as $movimiento)
                    <tr>
                      <td>
                        {{ $movimiento->created_at->format('d/m/Y') }}<br>
                        <small class="text-muted">{{ $movimiento->created_at->format('H:i') }}</small>
                      </td>
                      <td>
                        <span class="badge bg-{{ $movimiento->color_movimiento }}">
                          <i class="{{ $movimiento->icono_movimiento }}"></i>
                          {{ ucfirst($movimiento->tipo_movimiento) }}
                        </span>
                      </td>
                      <td>
                        <strong>{{ $movimiento->producto->referencia }}</strong><br>
                        <small class="text-muted">
                          {{ $movimiento->producto->nombre }}
                          @if($movimiento->variante)
                            <br>{{ $movimiento->variante->nombre_variante }}
                          @endif
                        </small>
                      </td>
                      <td class="text-center">
                        @if($movimiento->tipo_movimiento == 'salida')
                          <span class="text-danger fw-bold">-{{ $movimiento->cantidad }}</span>
                        @elseif($movimiento->tipo_movimiento == 'entrada')
                          <span class="text-success fw-bold">+{{ $movimiento->cantidad }}</span>
                        @elseif($movimiento->tipo_movimiento == 'ajuste')
                          <span class="text-warning fw-bold">
                            {{ $movimiento->cantidad >= 0 ? '+' : '' }}{{ $movimiento->cantidad }}
                          </span>
                        @else
                          {{ $movimiento->cantidad }}
                        @endif
                      </td>
                      <td class="text-center">{{ $movimiento->stock_anterior }}</td>
                      <td class="text-center fw-bold">{{ $movimiento->stock_nuevo }}</td>
                      <td>
                        <span class="badge bg-secondary">
                          {{ $movimiento->descripcion_origen }}
                        </span>
                      </td>
                      <td>{{ $movimiento->usuario->name }}</td>
                      <td>
                        @if($movimiento->referencia_documento)
                          <code>{{ $movimiento->referencia_documento }}</code>
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        @if($movimiento->motivo)
                          <small>{{ Str::limit($movimiento->motivo, 30) }}</small>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  @endpush

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Configurar Select2 para productos
      $('.select2-productos').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Todos --',
        allowClear: true,
        ajax: {
          url: '{{ route("stock.productos-json") }}',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term
            };
          },
          processResults: function (data) {
            return {
              results: data.results
            };
          },
          cache: true
        },
        minimumInputLength: 0
      });

      // DataTable
      $('#tablaMovimientos').DataTable({
        language: { url: '{{ asset("js/datatables/es-ES.json") }}' },
        order: [[0, 'desc']],
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
          'pageLength',
          'excel',
          'pdf',
          'print'
        ]
      });
    });

    function exportarReporte() {
      // Obtener los parámetros actuales del formulario
      const params = $('#formFiltros').serialize();
      window.location.href = '{{ route("stock.exportar") }}?' + params;
    }
  </script>
  @endpush
</x-app-layout>