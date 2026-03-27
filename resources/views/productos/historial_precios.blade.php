<x-app-layout>
  <x-slot name="header">Historial de Actualización de Precios</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      
      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4">
          {{ session('warning') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-2xl font-semibold">Historial de Actualizaciones</h4>
            <div>
              <button onclick="$('#modalActualizarPrecios').modal('show')" class="btn btn-primary">
                <i class="bi bi-upload"></i> Nueva Actualización
              </button>
              
              <!-- Dropdown para descargar plantillas -->
              <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                  <i class="bi bi-download"></i> Descargar Plantilla
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="{{ route('productos.descargar-plantilla-excel') }}">
                      <i class="bi bi-file-earmark-excel text-success"></i> Formato Excel (.xlsx)
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('productos.descargar-plantilla-csv') }}">
                      <i class="bi bi-file-earmark-text text-primary"></i> Formato CSV (;)
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <table id="historial-table" class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Archivo</th>
                <th>Estado</th>
                <th>Resultados</th>
                <th>% Éxito</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para actualizar precios COMPLETO Y CORREGIDO -->
  <div class="modal fade" id="modalActualizarPrecios" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('productos.actualizar-precios-excel') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="bi bi-currency-dollar"></i> Actualizar Precios desde Archivo
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- Alertas de información -->
            <div class="alert alert-info">
              <h6 class="alert-heading">
                <i class="bi bi-info-circle-fill"></i> Instrucciones de Formato
              </h6>
              <hr>
              <div class="row">
                <div class="col-md-6">
                  <p class="mb-2"><strong>Formato Excel (.xlsx):</strong></p>
                  <ul class="small mb-0">
                    <li>Columnas separadas automáticamente</li>
                    <li>Formato de moneda aplicado</li>
                    <li>Fácil edición en Excel/LibreOffice</li>
                    <li>Incluye productos existentes</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <p class="mb-2"><strong>Formato CSV:</strong></p>
                  <ul class="small mb-0">
                    <li>Separador: punto y coma (;)</li>
                    <li>Compatible con Excel español</li>
                    <li>Codificación UTF-8 con BOM</li>
                    <li>Editable en cualquier editor de texto</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Estructura del archivo -->
            <div class="alert alert-warning">
              <h6 class="alert-heading">
                <i class="bi bi-table"></i> Estructura Requerida
              </h6>
              <table class="table table-sm table-bordered mt-2 mb-0">
                <thead class="table-dark">
                  <tr>
                    <th>Referencia</th>
                    <th>Export1</th>
                    <th>Export2</th>
                    <th>Local1</th>
                    <th>Local2</th>
                    <th>Local3</th>
                    <th>Local4</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><code>PROD001</code></td>
                    <td>100.00</td>
                    <td>110.00</td>
                    <td>90.00</td>
                    <td>95.00</td>
                    <td>92.00</td>
                    <td>93.00</td>
                  </tr>
                  <tr>
                    <td><code>PROD002</code></td>
                    <td>200.00</td>
                    <td><em class="text-muted">vacío</em></td>
                    <td>180.00</td>
                    <td><em class="text-muted">vacío</em></td>
                    <td>185.00</td>
                    <td>187.00</td>
                  </tr>
                </tbody>
              </table>
              <small class="text-muted">
                <i class="bi bi-lightbulb"></i> Las celdas vacías no actualizarán el precio (mantienen valor anterior)
              </small>
            </div>

            <!-- Campo de archivo -->
            <div class="mb-3">
              <label class="form-label fw-bold">
                <i class="bi bi-file-earmark-arrow-up"></i> Seleccionar Archivo
              </label>
              <input type="file" 
                     name="archivo" 
                     class="form-control form-control-lg" 
                     accept=".xlsx,.xls,.csv" 
                     required
                     onchange="mostrarInfoArchivo(this)">
              <div class="form-text">
                Formatos aceptados: Excel (.xlsx, .xls) o CSV (.csv) - Máximo 10MB
              </div>
              <div id="info-archivo" class="mt-2"></div>
            </div>

            <!-- Notas importantes -->
            <div class="alert alert-secondary">
              <h6 class="alert-heading">
                <i class="bi bi-exclamation-triangle"></i> Notas Importantes
              </h6>
              <ul class="small mb-0">
                <li>La columna <strong>Referencia</strong> debe coincidir exactamente con los códigos en el sistema</li>
                <li>Los precios deben ser números positivos (pueden incluir decimales)</li>
                <li>El sistema procesará todas las filas válidas, aunque algunas tengan errores</li>
                <li>Recibirá un reporte detallado al finalizar el proceso</li>
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-upload"></i> Procesar Archivo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal para ver detalles (sin cambios) -->
  <div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles de la Actualización</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <h6>Información General</h6>
              <table class="table table-sm">
                <tr><td><strong>ID:</strong></td><td id="detalle-id"></td></tr>
                <tr><td><strong>Usuario:</strong></td><td id="detalle-usuario"></td></tr>
                <tr><td><strong>Fecha:</strong></td><td id="detalle-fecha"></td></tr>
                <tr><td><strong>Archivo:</strong></td><td id="detalle-archivo"></td></tr>
                <tr><td><strong>Estado:</strong></td><td id="detalle-estado"></td></tr>
                <tr><td><strong>Total filas:</strong></td><td id="detalle-total"></td></tr>
                <tr><td><strong>Exitosas:</strong></td><td id="detalle-exitosas"></td></tr>
                <tr><td><strong>Fallidas:</strong></td><td id="detalle-fallidas"></td></tr>
              </table>
            </div>
            
            <div class="col-md-8">
              <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" data-bs-toggle="tab" href="#tab-procesados">
                    Productos Actualizados (<span id="count-procesados">0</span>)
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#tab-errores">
                    Errores (<span id="count-errores">0</span>)
                  </a>
                </li>
              </ul>
              
              <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="tab-procesados">
                  <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Fila</th>
                          <th>Referencia</th>
                          <th>Lista</th>
                          <th>Precio Anterior</th>
                          <th>Precio Nuevo</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-procesados"></tbody>
                    </table>
                  </div>
                </div>
                
                <div class="tab-pane fade" id="tab-errores">
                  <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Fila</th>
                          <th>Referencia</th>
                          <th>Error</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-errores"></tbody>
                    </table>
                  </div>
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
    $(document).ready(function() {
      const table = $('#historial-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('productos.historial-precios') }}",
        columns: [
          { data: 'id', name: 'id' },
          { data: 'fecha', name: 'created_at' },
          { data: 'usuario', name: 'usuario.name' },
          { data: 'nombre_archivo', name: 'nombre_archivo' },
          { data: 'estado_badge', name: 'estado' },
          { data: 'resultados', searchable: false },
          { data: 'porcentaje', searchable: false },
          { data: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
          url: '{{ asset("js/datatables/es-ES.json") }}'
        }
      });

      // Auto-abrir detalles si hay una actualización reciente
      @if(session('actualizacion_id'))
        setTimeout(() => verDetalles({{ session('actualizacion_id') }}), 500);
      @endif
    });

    function verDetalles(id) {
      $.get(`/productos/actualizacion-precios/${id}`, function(response) {
        // Llenar información general
        $('#detalle-id').text(response.actualizacion.id);
        $('#detalle-usuario').text(response.actualizacion.usuario.name);
        $('#detalle-fecha').text(new Date(response.actualizacion.created_at).toLocaleString());
        $('#detalle-archivo').text(response.actualizacion.nombre_archivo);
        $('#detalle-estado').html(`<span class="badge bg-${response.actualizacion.estado == 'completado' ? 'success' : 'warning'}">${response.actualizacion.estado}</span>`);
        $('#detalle-total').text(response.actualizacion.total_filas);
        $('#detalle-exitosas').html(`<span class="text-success">${response.actualizacion.actualizaciones_exitosas}</span>`);
        $('#detalle-fallidas').html(`<span class="text-danger">${response.actualizacion.actualizaciones_fallidas}</span>`);
        
        // Llenar procesados
        $('#count-procesados').text(response.procesados.length);
        let procesadosHtml = '';
        response.procesados.forEach(item => {
          procesadosHtml += `
            <tr>
              <td>${item.fila}</td>
              <td><code>${item.referencia}</code></td>
              <td>${item.lista_precio}</td>
              <td>${item.precio_anterior ? '$' + parseFloat(item.precio_anterior).toFixed(2) : '-'}</td>
              <td class="text-success">$${parseFloat(item.precio_nuevo).toFixed(2)}</td>
            </tr>
          `;
        });
        $('#tbody-procesados').html(procesadosHtml || '<tr><td colspan="5" class="text-center">No hay registros</td></tr>');
        
        // Llenar errores
        $('#count-errores').text(response.errores.length);
        let erroresHtml = '';
        response.errores.forEach(item => {
          erroresHtml += `
            <tr>
              <td>${item.fila}</td>
              <td><code>${item.referencia || '-'}</code></td>
              <td class="text-danger">${item.mensaje}</td>
            </tr>
          `;
        });
        $('#tbody-errores').html(erroresHtml || '<tr><td colspan="3" class="text-center">No hay errores</td></tr>');
        
        $('#modalDetalles').modal('show');
      });
    }

    // Función para mostrar información del archivo seleccionado
    function mostrarInfoArchivo(input) {
      const file = input.files[0];
      if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        const fileExt = fileName.split('.').pop().toLowerCase();
        
        let iconClass = 'bi-file-earmark';
        let badgeClass = 'secondary';
        
        if (fileExt === 'xlsx' || fileExt === 'xls') {
          iconClass = 'bi-file-earmark-excel';
          badgeClass = 'success';
        } else if (fileExt === 'csv') {
          iconClass = 'bi-file-earmark-text';
          badgeClass = 'primary';
        }
        
        const infoHtml = `
          <div class="alert alert-light border">
            <i class="bi ${iconClass} text-${badgeClass}"></i>
            <strong>${fileName}</strong>
            <span class="badge bg-${badgeClass} ms-2">${fileExt.toUpperCase()}</span>
            <span class="text-muted ms-2">(${fileSize} MB)</span>
          </div>
        `;
        
        document.getElementById('info-archivo').innerHTML = infoHtml;
      }
    }
  </script>
  @endpush
</x-app-layout>