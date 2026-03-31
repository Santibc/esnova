<x-app-layout>
  <x-slot name="header">
    {{ $empresa->exists ? 'Editar Mi Empresa' : 'Crear Mi Empresa' }}
  </x-slot>

  <div class="container py-4">
    {{-- Mostrar errores de validación general --}}
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

    <form method="POST" action="{{ route('empresa.guardar') }}" enctype="multipart/form-data" id="empresaForm">
      @csrf
      
      {{-- Información Básica --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Información Básica</h5>
        </div>
        <div class="card-body">
          <div class="row">
            {{-- Nombre --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Nombre de la Empresa <span class="text-danger">*</span></label>
              <input name="nombre" type="text"
                     class="form-control @error('nombre') is-invalid @enderror"
                     value="{{ old('nombre', $empresa->nombre) }}"
                     placeholder="Ej: Mi Tienda Online"
                     required>
              @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Descripción --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        placeholder="Describe tu empresa o negocio..."
                        maxlength="500">{{ old('descripcion', $empresa->descripcion) }}</textarea>
              @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Máximo 500 caracteres</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Información de Contacto --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Información de Contacto</h5>
        </div>
        <div class="card-body">
          <div class="row">
            {{-- Email --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Correo Electrónico</label>
              <input name="email" type="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $empresa->email) }}"
                     placeholder="contacto@miempresa.com">
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Teléfono --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Teléfono</label>
              <input name="telefono" type="text"
                     class="form-control @error('telefono') is-invalid @enderror"
                     value="{{ old('telefono', $empresa->telefono) }}"
                     placeholder="+57 300 123 4567">
              @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Dirección --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Dirección</label>
              <input name="direccion" type="text"
                     class="form-control @error('direccion') is-invalid @enderror"
                     value="{{ old('direccion', $empresa->direccion) }}"
                     placeholder="Calle 123 #45-67, Bogotá">
              @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- WhatsApp --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">WhatsApp</label>
              <input name="whatsapp" type="text"
                     class="form-control @error('whatsapp') is-invalid @enderror"
                     value="{{ old('whatsapp', $empresa->whatsapp) }}"
                     placeholder="+57 300 123 4567">
              @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Número con código de país</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Redes Sociales --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Redes Sociales</h5>
        </div>
        <div class="card-body">
          <div class="row">
            {{-- Facebook --}}
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook</label>
              <input name="facebook_url" type="url"
                     class="form-control @error('facebook_url') is-invalid @enderror"
                     value="{{ old('facebook_url', $empresa->facebook_url) }}"
                     placeholder="https://facebook.com/miempresa">
              @error('facebook_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Instagram --}}
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram</label>
              <input name="instagram_url" type="url"
                     class="form-control @error('instagram_url') is-invalid @enderror"
                     value="{{ old('instagram_url', $empresa->instagram_url) }}"
                     placeholder="https://instagram.com/miempresa">
              @error('instagram_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- TikTok --}}
            <div class="col-md-6 mb-3">
              <label class="form-label"><i class="bi bi-tiktok"></i> TikTok</label>
              <input name="tiktok_url" type="url"
                     class="form-control @error('tiktok_url') is-invalid @enderror"
                     value="{{ old('tiktok_url', $empresa->tiktok_url) }}"
                     placeholder="https://tiktok.com/@miempresa">
              @error('tiktok_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Imágenes --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Imágenes de la Empresa</h5>
        </div>
        <div class="card-body">
          <div class="row">
            {{-- Logo --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">Logo de la Empresa</label>
              <input type="file" name="logo" 
                     class="form-control @error('logo') is-invalid @enderror"
                     accept="image/jpeg,image/jpg,image/png,image/webp">
              @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted d-block mt-1">
                Formatos: JPG, PNG, WebP. Tamaño máximo: 2MB. Recomendado: 500x500px
              </small>
              
              @if($empresa->logo)
                <div class="mt-3">
                  <img src="{{ $empresa->logo_url }}" 
                       alt="Logo actual" 
                       class="img-thumbnail"
                       style="max-width: 150px;">
                  <p class="text-muted mt-1">Logo actual</p>
                </div>
              @endif
            </div>

            {{-- Imagen de Portada --}}
            <div class="col-md-6 mb-4">
              <label class="form-label">Imagen de Portada</label>
              <input type="file" name="imagen_portada" 
                     class="form-control @error('imagen_portada') is-invalid @enderror"
                     accept="image/jpeg,image/jpg,image/png,image/webp">
              @error('imagen_portada') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted d-block mt-1">
                Formatos: JPG, PNG, WebP. Tamaño máximo: 4MB. Recomendado: 1920x400px
              </small>
              
              @if($empresa->imagen_portada)
                <div class="mt-3">
                  <img src="{{ $empresa->imagen_portada_url }}" 
                       alt="Portada actual" 
                       class="img-thumbnail"
                       style="max-width: 300px;">
                  <p class="text-muted mt-1">Portada actual</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Carrusel de Imágenes --}}
      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Carrusel de Imágenes</h5>
          <button type="button" class="btn btn-sm btn-outline-primary" id="addCarrusel">
            <i class="bi bi-plus-circle"></i> Agregar Imagen
          </button>
        </div>
        <div class="card-body">
          {{-- Imágenes existentes del carrusel --}}
          @if($empresa->exists && $empresa->carruselImagenes->count() > 0)
            <div class="row mb-4">
              <div class="col-12">
                <h6>Imágenes Actuales del Carrusel:</h6>
              </div>
              @foreach($empresa->carruselImagenes as $imagen)
                <div class="col-md-6 mb-3">
                  <div class="card">
                    <img src="{{ $imagen->imagen_url }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                      <input type="hidden" name="carrusel_existente[{{ $imagen->id }}][id]" value="{{ $imagen->id }}">
                      
                      <div class="mb-2">
                        <label class="form-label">Título</label>
                        <input type="text" name="carrusel_existente[{{ $imagen->id }}][titulo]" 
                               class="form-control form-control-sm"
                               value="{{ $imagen->titulo }}">
                      </div>
                      
                      <div class="mb-2">
                        <label class="form-label">Orden</label>
                        <input type="number" name="carrusel_existente[{{ $imagen->id }}][orden]" 
                               class="form-control form-control-sm"
                               value="{{ $imagen->orden }}" min="0">
                      </div>
                      
                      <div class="form-check">
                        <input type="checkbox" name="carrusel_existente[{{ $imagen->id }}][eliminar]" 
                               value="1" class="form-check-input" 
                               id="eliminar_carrusel_{{ $imagen->id }}">
                        <label class="form-check-label text-danger" 
                               for="eliminar_carrusel_{{ $imagen->id }}">
                          Eliminar esta imagen
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <hr class="my-4">
          @endif
          
          {{-- Container para nuevas imágenes --}}
          <div id="carruselContainer">
            <h6>Agregar Nuevas Imágenes:</h6>
          </div>
        </div>
      </div>

      {{-- Configuración de Compras --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Configuración de Compras</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <label class="form-label">Monto Mínimo de Compra (COP)</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="monto_minimo_compra" class="form-control"
                       value="{{ old('monto_minimo_compra', $empresa->monto_minimo_compra ?? 60000) }}"
                       min="0" step="1000">
              </div>
              <small class="text-muted">Valor mínimo que debe alcanzar el carrito para proceder al pago. Dejar en 0 para desactivar.</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Horario de Atención --}}
      <div class="card shadow mb-4">
        <div class="card-header">
          <h5 class="mb-0">Horario de Atención</h5>
        </div>
        <div class="card-body">
          @php
            $dias = [
              'lunes' => 'Lunes',
              'martes' => 'Martes',
              'miercoles' => 'Miércoles',
              'jueves' => 'Jueves',
              'viernes' => 'Viernes',
              'sabado' => 'Sábado',
              'domingo' => 'Domingo'
            ];
            
            $horarioDefault = [
              'apertura' => '09:00',
              'cierre' => '18:00',
              'cerrado' => false
            ];
          @endphp
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Día</th>
                  <th>Hora Apertura</th>
                  <th>Hora Cierre</th>
                  <th>Cerrado</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dias as $key => $dia)
                  @php
                    $horario = $empresa->horario_atencion[$key] ?? $horarioDefault;
                  @endphp
                  <tr>
                    <td>{{ $dia }}</td>
                    <td>
                      <input type="time" 
                             name="horario_atencion[{{ $key }}][apertura]" 
                             class="form-control form-control-sm horario-input"
                             value="{{ old("horario_atencion.$key.apertura", $horario['apertura'] ?? '09:00') }}"
                             data-dia="{{ $key }}">
                    </td>
                    <td>
                      <input type="time" 
                             name="horario_atencion[{{ $key }}][cierre]" 
                             class="form-control form-control-sm horario-input"
                             value="{{ old("horario_atencion.$key.cierre", $horario['cierre'] ?? '18:00') }}"
                             data-dia="{{ $key }}">
                    </td>
                    <td>
                        <div class="form-check">
                            <input type="hidden" name="horario_atencion[{{ $key }}][cerrado]" value="0">
                            <input type="checkbox" 
                                name="horario_atencion[{{ $key }}][cerrado]" 
                                class="form-check-input cerrado-check"
                                value="1"
                                data-dia="{{ $key }}"
                                {{ old("horario_atencion.$key.cerrado", $horario['cerrado'] ?? false) ? 'checked' : '' }}>
                        </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- Botones --}}
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save"></i> {{ $empresa->exists ? 'Actualizar' : 'Crear' }} Empresa
        </button>
        <a href="{{ $empresa->exists ? route('empresa.index') : route('dashboard') }}" 
           class="btn btn-outline-secondary">
          <i class="bi bi-x-circle"></i> Cancelar
        </a>
      </div>
    </form>
  </div>

  @push('styles')
  <style>
    .carrusel-row {
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      padding: 1rem;
      margin-bottom: 1rem;
      background-color: #f8f9fa;
    }

    .horario-input:disabled {
      background-color: #e9ecef;
      cursor: not-allowed;
    }
  </style>
  @endpush

  @push('scripts')
  <script>
    $(document).ready(function() {
      // Contador para carrusel
      let carruselIndex = 0;
      
      // Agregar nueva imagen al carrusel
      $('#addCarrusel').click(function() {
        const template = `
          <div class="carrusel-row">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Imagen <span class="text-danger">*</span></label>
                <input type="file" name="carrusel[${carruselIndex}][imagen]" 
                       class="form-control" 
                       accept="image/jpeg,image/jpg,image/png,image/webp"
                       required>
                <small class="text-muted">Máximo 4MB. Recomendado: 1920x600px</small>
              </div>
              
              <div class="col-md-6 mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="carrusel[${carruselIndex}][titulo]" 
                       class="form-control"
                       placeholder="Título de la imagen">
              </div>
              
              <div class="col-md-12 mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="carrusel[${carruselIndex}][descripcion]" 
                          class="form-control" rows="2"
                          placeholder="Descripción breve"></textarea>
              </div>
              
              <div class="col-md-4 mb-3">
                <label class="form-label">Link (URL)</label>
                <input type="url" name="carrusel[${carruselIndex}][link]" 
                       class="form-control"
                       placeholder="https://ejemplo.com">
              </div>
              
              <div class="col-md-2 mb-3">
                <label class="form-label">Orden</label>
                <input type="number" name="carrusel[${carruselIndex}][orden]" 
                       class="form-control"
                       value="${carruselIndex}" min="0">
              </div>
              
              <div class="col-md-3 mb-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="carrusel[${carruselIndex}][fecha_inicio]" 
                       class="form-control">
              </div>
              
              <div class="col-md-3 mb-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="carrusel[${carruselIndex}][fecha_fin]" 
                       class="form-control">
              </div>
              
              <div class="col-12">
                <button type="button" class="btn btn-danger btn-sm removeCarrusel">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </div>
            </div>
          </div>
        `;
        
        $('#carruselContainer').append(template);
        carruselIndex++;
      });
      
      // Eliminar imagen del carrusel
      $(document).on('click', '.removeCarrusel', function() {
        $(this).closest('.carrusel-row').remove();
      });
      
      // Manejar horarios - deshabilitar inputs cuando está cerrado
      $('.cerrado-check').change(function() {
        const dia = $(this).data('dia');
        const cerrado = $(this).is(':checked');
        
        $(`input[name="horario_atencion[${dia}][apertura]"]`).prop('disabled', cerrado);
        $(`input[name="horario_atencion[${dia}][cierre]"]`).prop('disabled', cerrado);
      });
      
      // Ejecutar al cargar para establecer estado inicial
      $('.cerrado-check').trigger('change');
      
      // Validación del formulario
      $('#empresaForm').submit(function(e) {
        let isValid = true;
        
        // Validar que al menos tenga nombre
        const nombre = $('input[name="nombre"]').val();
        if (!nombre || nombre.trim() === '') {
          e.preventDefault();
          alert('El nombre de la empresa es obligatorio.');
          isValid = false;
        }
        
        return isValid;
      });
    });
  </script>
  @endpush
</x-app-layout>