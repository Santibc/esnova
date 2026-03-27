<x-app-layout>
    <x-slot name="header">
        {{ $cliente->exists ? 'Editar Cliente' : 'Nuevo Cliente' }}
    </x-slot>
    
    @push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    @endpush
    
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

      <div class="card shadow">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bi bi-person-plus"></i> 
            {{ $cliente->exists ? 'Editar' : 'Registrar' }} Cliente para {{ $empresa->nombre }}
          </h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('clientes.guardar') }}">
            @csrf
            <input type="hidden" name="id" value="{{ old('id',$cliente->id) }}">
            <input type="hidden" name="pais_id" value="{{ $pais_id }}">

            {{-- Información básica --}}
            <h6 class="mb-3 text-muted">Información Básica</h6>
            <div class="row">
              {{-- Identificación --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Identificación <span class="text-danger">*</span></label>
                <input name="numero_identificacion" type="text"
                       class="form-control @error('numero_identificacion') is-invalid @enderror"
                       value="{{ old('numero_identificacion',$cliente->numero_identificacion) }}"
                       placeholder="NIT o Cédula"
                       required>
                @error('numero_identificacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              {{-- Contacto --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Nombre de Contacto <span class="text-danger">*</span></label>
                <input name="nombre_contacto" type="text"
                       class="form-control @error('nombre_contacto') is-invalid @enderror"
                       value="{{ old('nombre_contacto',$cliente->nombre_contacto) }}"
                       placeholder="Nombre completo del contacto"
                       required>
                @error('nombre_contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              {{-- Email --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input name="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email',$cliente->email) }}"
                       placeholder="correo@ejemplo.com"
                       required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              {{-- Teléfono --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input name="telefono" type="text"
                       class="form-control @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono',$cliente->telefono) }}"
                       placeholder="+57 300 123 4567">
                @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <hr class="my-4">

            {{-- Ubicación --}}
            <h6 class="mb-3 text-muted">Ubicación</h6>
            <div class="row">
              {{-- Departamento --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Departamento <span class="text-danger">*</span></label>
                <select id="departamento-select" name="departamento_id" 
                        class="form-select select2 @error('departamento_id') is-invalid @enderror"
                        required>
                  <option value="">-- Seleccionar --</option>
                  @foreach($departamentos as $id => $nombre)
                    <option value="{{ $id }}"
                      {{ old('departamento_id',$cliente->ciudad->departamento_id ?? '') == $id ? 'selected':'' }}>
                      {{ $nombre }}
                    </option>
                  @endforeach
                </select>
                @error('departamento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              {{-- Ciudad --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Ciudad <span class="text-danger">*</span></label>
                <select id="ciudad-select" name="ciudad_id" 
                        class="form-select select2 @error('ciudad_id') is-invalid @enderror"
                        required>
                  <option value="">-- Seleccionar --</option>
                  {{-- Si editamos, pre-cargamos --}}
                  @if($cliente->exists && $cliente->ciudad)
                    @foreach(\App\Models\Ciudad::where('departamento_id',$cliente->ciudad->departamento_id)->pluck('nombre','id') as $id=>$ciudad)
                      <option value="{{ $id }}"
                        {{ old('ciudad_id',$cliente->ciudad_id)==$id ? 'selected':'' }}>
                        {{ $ciudad }}
                      </option>
                    @endforeach
                  @endif
                </select>
                @error('ciudad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <hr class="my-4">

            {{-- Configuración comercial --}}
            <h6 class="mb-3 text-muted">Configuración Comercial</h6>
            <div class="row">
              {{-- Vendedor --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Vendedor Asignado <span class="text-danger">*</span></label>
                <select name="vendedor_id" 
                        class="form-select @error('vendedor_id') is-invalid @enderror"
                        required>
                  <option value="">-- Seleccionar --</option>
                  @foreach($vendedores as $id=>$name)
                    <option value="{{ $id }}"
                      {{ old('vendedor_id',$cliente->vendedor_id)==$id ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                </select>
                @error('vendedor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              {{-- Lista de Precio --}}
              <div class="col-md-6 mb-3">
                <label class="form-label">Lista de Precio <span class="text-danger">*</span></label>
                <select name="lista_precio_id" 
                        class="form-select @error('lista_precio_id') is-invalid @enderror"
                        required>
                  <option value="">-- Seleccionar --</option>
                  @foreach($listas as $id=>$nombre)
                    <option value="{{ $id }}"
                      {{ old('lista_precio_id',$cliente->lista_precio_id)==$id ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                  @endforeach
                </select>
                @error('lista_precio_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Define los precios que verá este cliente</small>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ $cliente->exists ? 'Actualizar' : 'Guardar' }} Cliente
              </button>
              <a href="{{ route('clientes') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    @push('scripts')
    <!-- jQuery + Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    $(document).ready(function(){
      // Inicializamos Select2
      $('.select2').select2({
        theme: 'bootstrap-5', 
        width: '100%',
        placeholder: '-- Seleccionar --',
        allowClear: false
      });

      // Al cambiar departamento, recargamos ciudades
      $('#departamento-select').on('change', function(){
        let depId = $(this).val();
        $('#ciudad-select').empty().append('<option>Buscando…</option>');
        
        if (!depId) {
          $('#ciudad-select').empty().append('<option value="">-- Seleccionar --</option>');
          $('#ciudad-select').trigger('change');
          return;
        }
        
        $.getJSON("{{ route('ajax.ciudades') }}", { departamento_id: depId })
         .done(function(data){
           let $ciudad = $('#ciudad-select').empty().append('<option value="">-- Seleccionar --</option>');
           data.forEach(c => {
             $ciudad.append(`<option value="${c.id}">${c.nombre}</option>`);
           });
           $ciudad.trigger('change');
         })
         .fail(function() {
           $('#ciudad-select').empty().append('<option value="">Error al cargar ciudades</option>');
           toastr.error('Error al cargar las ciudades');
         });
      });
    });
    </script>
    @endpush
</x-app-layout>