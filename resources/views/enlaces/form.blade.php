<x-app-layout>
  <x-slot name="header">Crear Enlace de Acceso</x-slot>

  <div class="container py-4">
    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>Por favor corrija los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Mensajes de éxito/error --}}
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

    <div class="row justify-content-center">
      <div class="col-md-8">
        <form method="POST" action="{{ route('enlaces.guardar') }}">
          @csrf
          
          {{-- Información del Enlace --}}
          <div class="card shadow mb-4">
            <div class="card-header">
              <h5 class="mb-0">Nuevo Enlace de Acceso al Catálogo</h5>
            </div>
            <div class="card-body">
              <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle"></i> 
                Este enlace permitirá al cliente acceder al catálogo de productos sin necesidad de autenticarse.
                El cliente podrá ver los productos, agregar al carrito y enviar solicitudes de cotización.
              </div>

              {{-- Cliente --}}
              <div class="mb-3">
                <label class="form-label">Cliente <span class="text-danger">*</span></label>
                <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                  <option value="">-- Seleccionar Cliente --</option>
                  @foreach($clientes as $id => $nombre)
                    <option value="{{ $id }}" {{ old('cliente_id') == $id ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                  @endforeach
                </select>
                @error('cliente_id') 
                  <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
                <small class="text-muted">
                  Seleccione el cliente que tendrá acceso al catálogo mediante este enlace.
                </small>
              </div>

              {{-- Días de validez --}}
              <div class="mb-3">
                <label class="form-label">Días de Validez <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" 
                         name="dias_validos" 
                         class="form-control @error('dias_validos') is-invalid @enderror"
                         value="{{ old('dias_validos', 7) }}"
                         min="1" 
                         max="365"
                         required>
                  <span class="input-group-text">días</span>
                  @error('dias_validos') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                  @enderror
                </div>
                <small class="text-muted">
                  El enlace expirará después de los días especificados. Máximo 365 días.
                </small>
              </div>

              {{-- Mostrar precios --}}
              <div class="mb-3">
                <label class="form-label">Mostrar Precios <span class="text-danger">*</span></label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mostrar_precios" 
                           id="mostrar_precios_si" value="1" 
                           {{ old('mostrar_precios', '1') == '1' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="mostrar_precios_si">
                      <i class="bi bi-eye text-success"></i> Sí mostrar precios
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mostrar_precios" 
                           id="mostrar_precios_no" value="0" 
                           {{ old('mostrar_precios') == '0' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="mostrar_precios_no">
                      <i class="bi bi-eye-slash text-warning"></i> No mostrar precios
                    </label>
                  </div>
                </div>
                <small class="text-muted">
                  Si selecciona "No", el cliente podrá ver los productos pero no los precios. 
                  Los precios se aplicarán según la lista asignada al cliente cuando se procese la solicitud.
                </small>
              </div>

              {{-- Mostrar stock --}}
              <div class="mb-3">
                <label class="form-label">Mostrar Stock <span class="text-danger">*</span></label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mostrar_stock" 
                           id="mostrar_stock_si" value="1" 
                           {{ old('mostrar_stock', '1') == '1' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="mostrar_stock_si">
                      <i class="bi bi-box-seam text-success"></i> Sí mostrar stock
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mostrar_stock" 
                           id="mostrar_stock_no" value="0" 
                           {{ old('mostrar_stock') == '0' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="mostrar_stock_no">
                      <i class="bi bi-box text-warning"></i> No mostrar stock
                    </label>
                  </div>
                </div>
                <small class="text-muted">
                  Si selecciona "No", el cliente podrá ver los productos pero no las cantidades disponibles. 
                  Esto es útil para catálogos donde no se desea mostrar información de inventario.
                </small>
              </div>

              {{-- Notas --}}
              <div class="mb-3">
                <label class="form-label">Notas Internas (opcional)</label>
                <textarea name="notas" 
                          class="form-control @error('notas') is-invalid @enderror" 
                          rows="3"
                          placeholder="Agregue cualquier nota o comentario sobre este enlace...">{{ old('notas') }}</textarea>
                @error('notas') 
                  <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
                <small class="text-muted">
                  Estas notas son solo para uso interno y no serán visibles para el cliente.
                </small>
              </div>
            </div>
          </div>

          {{-- Vista previa de configuración --}}
          <div class="card shadow mb-4">
            <div class="card-header">
              <h6 class="mb-0">Vista Previa de la Configuración</h6>
            </div>
            <div class="card-body">
              <div id="vistaPrevia" class="text-muted">
                <p class="mb-2"><i class="bi bi-person"></i> <strong>Cliente:</strong> <span id="previewCliente">No seleccionado</span></p>
                <p class="mb-2"><i class="bi bi-calendar-check"></i> <strong>Validez:</strong> <span id="previewValidez">7 días</span></p>
                <p class="mb-2"><i class="bi bi-tag"></i> <strong>Precios:</strong> <span id="previewPrecios">Visibles</span></p>
                <p class="mb-2"><i class="bi bi-box-seam"></i> <strong>Stock:</strong> <span id="previewStock">Visible</span></p>
                <p class="mb-0"><i class="bi bi-calendar-x"></i> <strong>Expirará el:</strong> <span id="previewExpira">{{ now()->addDays(7)->format('d/m/Y') }}</span></p>
              </div>
            </div>
          </div>

          {{-- Botones --}}
          <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-link-45deg"></i> Crear Enlace
            </button>
            <a href="{{ route('enlaces') }}" class="btn btn-outline-secondary">
              <i class="bi bi-x-circle"></i> Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    $(document).ready(function() {
      // Actualizar vista previa
      function actualizarVistaPrevia() {
        // Cliente
        const clienteSelect = $('select[name="cliente_id"]');
        const clienteText = clienteSelect.find('option:selected').text();
        $('#previewCliente').text(clienteText !== '-- Seleccionar Cliente --' ? clienteText : 'No seleccionado');
        
        // Días de validez
        const dias = $('input[name="dias_validos"]').val() || 7;
        $('#previewValidez').text(dias + ' día' + (dias != 1 ? 's' : ''));
        
        // Mostrar precios
        const mostrarPrecios = $('input[name="mostrar_precios"]:checked').val();
        $('#previewPrecios').html(mostrarPrecios == '1' 
          ? '<span class="text-success">Visibles</span>' 
          : '<span class="text-warning">Ocultos</span>');
        
        // Mostrar stock
        const mostrarStock = $('input[name="mostrar_stock"]:checked').val();
        $('#previewStock').html(mostrarStock == '1' 
          ? '<span class="text-success">Visible</span>' 
          : '<span class="text-warning">Oculto</span>');
        
        // Fecha de expiración
        const fechaExpira = new Date();
        fechaExpira.setDate(fechaExpira.getDate() + parseInt(dias));
        $('#previewExpira').text(fechaExpira.toLocaleDateString('es-ES'));
      }
      
      // Eventos para actualizar vista previa
      $('select[name="cliente_id"], input[name="dias_validos"], input[name="mostrar_precios"], input[name="mostrar_stock"]').on('change input', actualizarVistaPrevia);
      
      // Actualizar al cargar
      actualizarVistaPrevia();
      
      // Validación adicional
      $('form').on('submit', function(e) {
        const cliente = $('select[name="cliente_id"]').val();
        if (!cliente) {
          e.preventDefault();
          alert('Por favor seleccione un cliente');
          return false;
        }
        
        const dias = parseInt($('input[name="dias_validos"]').val());
        if (isNaN(dias) || dias < 1 || dias > 365) {
          e.preventDefault();
          alert('Los días de validez deben estar entre 1 y 365');
          return false;
        }
      });
    });
  </script>
  @endpush
</x-app-layout>