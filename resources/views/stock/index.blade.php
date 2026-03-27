<x-app-layout>
  <x-slot name="header">Gestión de Stock</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Alertas --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
          {{ session('warning') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Header con información de la empresa --}}
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-xl font-semibold">
          <i class="bi bi-box-seam"></i> Control de Stock - {{ $empresa->nombre }}
        </h3>
        <div>
          <a href="{{ route('stock.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
          <a href="{{ route('stock.reporte-movimiento') }}" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-bar-graph"></i> Reportes
          </a>
        </div>
      </div>

      {{-- Tarjetas de resumen --}}
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card border-success">
            <div class="card-body">
              <h6 class="card-title text-success">
                <i class="bi bi-check-circle"></i> Con Stock
              </h6>
              <p class="card-text display-6" id="productosConStock">-</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-warning">
            <div class="card-body">
              <h6 class="card-title text-warning">
                <i class="bi bi-exclamation-triangle"></i> Stock Bajo
              </h6>
              <p class="card-text display-6">{{ $productosConStockBajo }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-danger">
            <div class="card-body">
              <h6 class="card-title text-danger">
                <i class="bi bi-x-circle"></i> Sin Stock
              </h6>
              <p class="card-text display-6">{{ $productosSinStock }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-info">
            <div class="card-body">
              <h6 class="card-title text-info">
                <i class="bi bi-box-seam"></i> Total Items
              </h6>
              <p class="card-text display-6" id="totalItems">-</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Panel de Filtros --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
          <h5 class="mb-0">
            <i class="bi bi-funnel"></i> Filtros
            @if(request('producto_id'))
              <span class="badge bg-info ms-2">Filtro activo</span>
            @endif
          </h5>
        </div>
        <div class="card-body">
          <div class="row align-items-end">
            <div class="col-md-6">
              <label class="form-label">Buscar Producto</label>
              <select id="filtroProducto" class="form-select select2-productos w-100">
                <option value="">-- Todos los productos --</option>
                @if($productoFiltrado ?? false)
                  <option value="{{ $productoFiltrado->id }}" selected>
                    {{ $productoFiltrado->referencia }} - {{ $productoFiltrado->nombre }}
                    @if($productoFiltrado->tiene_variantes)
                      (Con variantes)
                    @endif
                  </option>
                @endif
              </select>
            </div>
            
            <div class="col-md-3">
              <label class="form-label">Estado de Stock</label>
              <select id="filtroEstado" class="form-select">
                <option value="">-- Todos --</option>
                <option value="con_stock">Con Stock</option>
                <option value="sin_stock">Sin Stock</option>
                <option value="stock_bajo">Stock Bajo</option>
              </select>
            </div>
            
            <div class="col-md-3">
              <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">
                  <i class="bi bi-check-circle"></i> Aplicar
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                  <i class="bi bi-x-circle"></i> Limpiar
                </button>
              </div>
            </div>
          </div>

          {{-- Información del filtro activo --}}
          @if($productoFiltrado ?? false)
            <div class="alert alert-info mt-3 mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong><i class="bi bi-info-circle"></i> Mostrando stock de:</strong>
                  <span class="ms-2">{{ $productoFiltrado->referencia }} - {{ $productoFiltrado->nombre }}</span>
                  @if($productoFiltrado->tiene_variantes)
                    <span class="badge bg-secondary ms-2">
                      {{ $productoFiltrado->variantes->count() }} variantes
                    </span>
                  @endif
                </div>
                <div>
                  <a href="{{ route('productos.form', $productoFiltrado->id) }}" 
                     class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i> Editar Producto
                  </a>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Tabla de stock --}}
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <h4 class="text-2xl font-semibold mb-4">Inventario de Productos</h4>

          <table id="stock-table" class="table-responsive w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100">
              <tr>
                <th>Producto</th>
                <th>Stock Actual</th>
                <th>Disp./Reserv.</th>
                <th>Mín/Máx</th>
                <th>Ubicación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Entrada de Stock --}}
  <div class="modal fade" id="modalEntrada" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formEntrada">
          @csrf
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Entrada de Stock</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="entrada_stock_id" name="stock_id">
            
            <div class="mb-3">
              <label class="form-label">Producto</label>
              <input type="text" class="form-control" id="entrada_producto" readonly>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Cantidad <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="cantidad" min="1" required>
              <small class="text-muted">Stock actual: <span id="entrada_stock_actual"></span></small>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Referencia (Factura/Orden)</label>
              <input type="text" class="form-control" name="referencia" placeholder="Ej: FAC-001">
            </div>
            
            <div class="mb-3">
              <label class="form-label">Motivo/Observaciones</label>
              <textarea class="form-control" name="motivo" rows="2" placeholder="Opcional"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle"></i> Registrar Entrada
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Salida de Stock --}}
  <div class="modal fade" id="modalSalida" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formSalida">
          @csrf
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title"><i class="bi bi-dash-circle"></i> Salida de Stock</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="salida_stock_id" name="stock_id">
            
            <div class="mb-3">
              <label class="form-label">Producto</label>
              <input type="text" class="form-control" id="salida_producto" readonly>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Cantidad <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="cantidad" min="1" required>
              <small class="text-muted">Stock disponible: <span id="salida_stock_disponible" class="fw-bold"></span></small>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Referencia (Factura/Orden)</label>
              <input type="text" class="form-control" name="referencia" placeholder="Ej: VTA-001">
            </div>
            
            <div class="mb-3">
              <label class="form-label">Motivo/Observaciones</label>
              <textarea class="form-control" name="motivo" rows="2" placeholder="Opcional"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-check-circle"></i> Registrar Salida
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Ajuste de Stock --}}
  <div class="modal fade" id="modalAjuste" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formAjuste">
          @csrf
          <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="bi bi-gear"></i> Ajuste de Inventario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="ajuste_stock_id" name="stock_id">
            
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle"></i> 
              Los ajustes de inventario deben documentarse adecuadamente.
            </div>
            
            <div class="mb-3">
              <label class="form-label">Producto</label>
              <input type="text" class="form-control" id="ajuste_producto" readonly>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Stock Actual</label>
              <input type="text" class="form-control" id="ajuste_stock_actual" readonly>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Nueva Cantidad <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="nueva_cantidad" min="0" required>
              <small class="text-muted">Ingrese la cantidad real encontrada en inventario</small>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Motivo del Ajuste <span class="text-danger">*</span></label>
              <textarea class="form-control" name="motivo" rows="3" required 
                        placeholder="Explique el motivo del ajuste de inventario"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-warning">
              <i class="bi bi-check-circle"></i> Realizar Ajuste
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Configuración de Stock --}}
  <div class="modal fade" id="modalConfiguracion" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="formConfiguracion">
          @csrf
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title"><i class="bi bi-sliders"></i> Configuración de Stock</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="config_stock_id" name="stock_id">
            
            <div class="mb-3">
              <label class="form-label">Producto</label>
              <input type="text" class="form-control" id="config_producto" readonly>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="stock_minimo" id="config_stock_minimo" min="0" required>
                <small class="text-muted">Alerta cuando baje de este nivel</small>
              </div>
              
              <div class="col-md-6 mb-3">
                <label class="form-label">Stock Máximo</label>
                <input type="number" class="form-control" name="stock_maximo" id="config_stock_maximo" min="0">
                <small class="text-muted">Opcional: límite máximo</small>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Ubicación en Bodega</label>
              <input type="text" class="form-control" name="ubicacion" id="config_ubicacion" 
                     placeholder="Ej: Pasillo A, Estante 3">
            </div>
            
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="alerta_stock_bajo" 
                       id="config_alerta" value="1" checked>
                <label class="form-check-label" for="config_alerta">
                  Activar alerta de stock bajo
                </label>
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Notas</label>
              <textarea class="form-control" name="notas" id="config_notas" rows="2"
                        placeholder="Información adicional sobre este producto"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-info">
              <i class="bi bi-save"></i> Guardar Configuración
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Historial --}}
  <div class="modal fade" id="modalHistorial" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-clock-history"></i> Historial de Movimientos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="contenidoHistorial">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
  document.addEventListener('DOMContentLoaded', () => {
    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const productoId = urlParams.get('producto_id');
    const estadoFiltro = urlParams.get('estado');
    
    // Configurar Select2 para búsqueda de productos
    $('.select2-productos').select2({
      theme: 'bootstrap-5',
      width: '100%',
      placeholder: '-- Todos los productos --',
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
      minimumInputLength: 0,
      language: {
        searching: function() {
          return "Buscando...";
        },
        noResults: function() {
          return "No se encontraron resultados";
        },
        inputTooShort: function() {
          return "Escribe para buscar";
        }
      }
    });

    // Si hay un filtro de estado, seleccionarlo
    if (estadoFiltro) {
      $('#filtroEstado').val(estadoFiltro);
    }
    
    // Configurar DataTable
    const table = $('#stock-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      scrollX: true,
      ajax: {
        url: "{{ route('stock.index') }}",
        data: function(d) {
          // Agregar filtros al request
          if (productoId) {
            d.producto_id = productoId;
          }
          const estado = $('#filtroEstado').val();
          if (estado) {
            d.estado = estado;
          }
        }
      },
      columns: [
        { data: 'producto_info', name: 'producto_id' },
        { data: 'stock_actual', orderable: false },
        { data: 'disponible_reservado', orderable: false },
        { data: 'stock_minimo_maximo', orderable: false },
        { data: 'ubicacion', name: 'ubicacion' },
        { data: 'action', orderable: false, searchable: false }
      ],
      dom: "<'flex justify-between mb-4'<'relative'B>f>t<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",
      buttons: [
        { extend: 'pageLength', className: 'btn btn-outline-dark', text: 'Filas' },
        { extend: 'colvis', className: 'btn btn-outline-dark', text: 'Columnas' },
        { extend: 'excelHtml5', className: 'btn btn-outline-success', text: 'Excel' },
        {
          text: '<i class="bi bi-arrow-repeat"></i> Inicializar Stock', 
          className: 'btn btn-outline-warning',
          action: () => {
            if (confirm('¿Desea inicializar el stock para todos los productos de su empresa?')) {
              inicializarStock();
            }
          }
        }
      ],
      language: { url: '{{ asset("js/datatables/es-ES.json") }}' },
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Todos']],
      drawCallback: function(settings) {
        // Actualizar contadores
        const info = this.api().page.info();
        $('#totalItems').text(info.recordsTotal);
        
        // Calcular productos con stock
        const data = this.api().rows({page:'current'}).data();
        let conStock = 0;
        data.each(function(row) {
          // Extraer el número del badge de stock
          const match = row.stock_actual.match(/>(\d+)</);
          if (match && parseInt(match[1]) > 0) {
            conStock++;
          }
        });
        $('#productosConStock').text(conStock);
      }
    });

    // Función para aplicar filtros
    window.aplicarFiltros = function() {
      const productoId = $('#filtroProducto').val();
      const estado = $('#filtroEstado').val();
      
      let url = "{{ route('stock.index') }}";
      const params = [];
      
      if (productoId) {
        params.push('producto_id=' + productoId);
      }
      if (estado) {
        params.push('estado=' + estado);
      }
      
      if (params.length > 0) {
        url += '?' + params.join('&');
      }
      
      window.location.href = url;
    };

    // Función para limpiar filtros
    window.limpiarFiltros = function() {
      window.location.href = "{{ route('stock.index') }}";
    };

    // Funciones para los modales
    window.entradaStock = function(stockId) {
      $.get(`/stock/${stockId}/obtener`, function(data) {
        // Limpiar formulario PRIMERO
        $('#formEntrada')[0].reset();

        // Luego establecer los valores
        $('#entrada_stock_id').val(stockId);
        $('#entrada_producto').val(data.producto_nombre + (data.variante_nombre ? ' - ' + data.variante_nombre : ''));
        $('#entrada_stock_actual').text(data.stock.cantidad_disponible);

        $('#modalEntrada').modal('show');
      });
    };

    window.salidaStock = function(stockId) {
      $.get(`/stock/${stockId}/obtener`, function(data) {
        // Limpiar formulario PRIMERO
        $('#formSalida')[0].reset();

        // Luego establecer los valores
        $('#salida_stock_id').val(stockId);
        $('#salida_producto').val(data.producto_nombre + (data.variante_nombre ? ' - ' + data.variante_nombre : ''));
        $('#salida_stock_disponible').text(data.stock.stock_real);

        // Establecer máximo en el input
        $('input[name="cantidad"]', '#formSalida').attr('max', data.stock.stock_real);

        $('#modalSalida').modal('show');
      });
    };

    window.ajusteStock = function(stockId) {
      $.get(`/stock/${stockId}/obtener`, function(data) {
        // Limpiar formulario PRIMERO
        $('#formAjuste')[0].reset();

        // Luego establecer los valores
        $('#ajuste_stock_id').val(stockId);
        $('#ajuste_producto').val(data.producto_nombre + (data.variante_nombre ? ' - ' + data.variante_nombre : ''));
        $('#ajuste_stock_actual').val(data.stock.cantidad_disponible);

        $('#modalAjuste').modal('show');
      });
    };

    window.configurarStock = function(stockId) {
      $.get(`/stock/${stockId}/obtener`, function(data) {
        $('#config_stock_id').val(stockId);
        $('#config_producto').val(data.producto_nombre + (data.variante_nombre ? ' - ' + data.variante_nombre : ''));
        $('#config_stock_minimo').val(data.stock.stock_minimo);
        $('#config_stock_maximo').val(data.stock.stock_maximo);
        $('#config_ubicacion').val(data.stock.ubicacion);
        $('#config_alerta').prop('checked', data.stock.alerta_stock_bajo);
        $('#config_notas').val(data.stock.notas);
        $('#modalConfiguracion').modal('show');
      });
    };

    window.verHistorial = function(productoId, varianteId) {
      $('#contenidoHistorial').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
      $('#modalHistorial').modal('show');
      
      $.get('/stock/historial', { producto_id: productoId, variante_id: varianteId }, function(response) {
        $('#contenidoHistorial').html(response.html);
      }).fail(function() {
        $('#contenidoHistorial').html('<div class="alert alert-danger">Error al cargar el historial</div>');
      });
    };

    // Formulario de entrada
    $('#formEntrada').on('submit', function(e) {
      e.preventDefault();
      
      const btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
      
      $.ajax({
        url: "{{ route('stock.entrada') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            $('#modalEntrada').modal('hide');
            table.ajax.reload();
            toastr.success(response.message);
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido';
          toastr.error(message);
        },
        complete: function() {
          btn.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Registrar Entrada');
        }
      });
    });

    // Formulario de salida
    $('#formSalida').on('submit', function(e) {
      e.preventDefault();
      
      const btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
      
      $.ajax({
        url: "{{ route('stock.salida') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            $('#modalSalida').modal('hide');
            table.ajax.reload();
            toastr.success(response.message);
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido';
          toastr.error(message);
        },
        complete: function() {
          btn.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Registrar Salida');
        }
      });
    });

    // Formulario de ajuste
    $('#formAjuste').on('submit', function(e) {
      e.preventDefault();
      
      if (!confirm('¿Está seguro de realizar este ajuste de inventario?')) {
        return;
      }
      
      const btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
      
      $.ajax({
        url: "{{ route('stock.ajuste') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            $('#modalAjuste').modal('hide');
            table.ajax.reload();
            toastr.success(response.message);
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido';
          toastr.error(message);
        },
        complete: function() {
          btn.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Realizar Ajuste');
        }
      });
    });

    // Formulario de configuración
    $('#formConfiguracion').on('submit', function(e) {
      e.preventDefault();
      
      const btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');
      
      $.ajax({
        url: "{{ route('stock.configurar') }}",
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            $('#modalConfiguracion').modal('hide');
            table.ajax.reload();
            toastr.success(response.message);
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido';
          toastr.error(message);
        },
        complete: function() {
          btn.prop('disabled', false).html('<i class="bi bi-save"></i> Guardar Configuración');
        }
      });
    });

    // Inicializar stock
    function inicializarStock() {
      $.ajax({
        url: "{{ route('stock.inicializar-todos') }}",
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        beforeSend: function() {
          Swal.fire({
            title: 'Procesando',
            text: 'Inicializando stock...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
        },
        success: function(response) {
          if (response.success) {
            table.ajax.reload();
            Swal.fire('¡Éxito!', response.message, 'success');
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON ? xhr.responseJSON.message : 'Error desconocido';
          Swal.fire('Error', message, 'error');
        }
      });
    }

    // Limpiar formularios y restaurar botones cuando se cierran los modales
    $('.modal').on('hidden.bs.modal', function() {
      const modal = $(this);
      const form = modal.find('form');

      // Resetear el formulario
      if (form.length) {
        form[0].reset();
      }

      // Restaurar botones de submit a su estado original
      modal.find('button[type="submit"]').each(function() {
        const btn = $(this);
        btn.prop('disabled', false);

        // Restaurar texto original del botón según el modal
        const modalId = modal.attr('id');
        switch(modalId) {
          case 'modalEntrada':
            btn.html('<i class="bi bi-check-circle"></i> Registrar Entrada');
            break;
          case 'modalSalida':
            btn.html('<i class="bi bi-check-circle"></i> Registrar Salida');
            break;
          case 'modalAjuste':
            btn.html('<i class="bi bi-check-circle"></i> Realizar Ajuste');
            break;
          case 'modalConfiguracion':
            btn.html('<i class="bi bi-save"></i> Guardar Configuración');
            break;
        }
      });
    });

    // Atajos de teclado
    $(document).keydown(function(e) {
      // Alt + F para abrir filtros
      if (e.altKey && e.key === 'f') {
        e.preventDefault();
        $('#filtroProducto').select2('open');
      }
      // Alt + L para limpiar filtros
      if (e.altKey && e.key === 'l') {
        e.preventDefault();
        limpiarFiltros();
      }
    });
  });
  </script>
  @endpush
</x-app-layout>