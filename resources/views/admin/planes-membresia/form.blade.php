<x-app-layout>
  <x-slot name="header">
    {{ $plan->exists ? 'Editar Plan de Membresía' : 'Nuevo Plan de Membresía' }}
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="bi bi-credit-card"></i> 
          {{ $plan->exists ? 'Editar' : 'Crear' }} Plan de Membresía
        </h5>
      </div>
      <div class="card-body">
        {{-- Información sobre uso del plan --}}
        @if($plan->exists && ($empresasCount > 0 || $membresiasCount > 0))
          <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle"></i> 
            Este plan tiene <strong>{{ $empresasCount }}</strong> empresa(s) y <strong>{{ $membresiasCount }}</strong> membresía(s) asociadas.
            <br>
            <small>No podrá eliminar este plan mientras tenga empresas o membresías asociadas.</small>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.planes-membresia.guardar') }}" id="planForm">
          @csrf
          <input type="hidden" name="id" value="{{ old('id',$plan->id) }}">
          
          <div class="row">
            {{-- Nombre --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
              <input name="nombre" type="text"
                     class="form-control @error('nombre') is-invalid @enderror"
                     value="{{ old('nombre',$plan->nombre) }}"
                     placeholder="Ej: Plan Básico, Plan Premium, Plan Empresarial"
                     required>
              @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Precio --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Precio <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input name="precio" type="number" step="0.01" min="0"
                       class="form-control @error('precio') is-invalid @enderror"
                       value="{{ old('precio',$plan->precio) }}"
                       placeholder="0.00"
                       required>
              </div>
              @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Usar 0.00 para plan gratuito</small>
            </div>

            {{-- Límite de Productos --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Límite de Productos <span class="text-danger">*</span></label>
              <input name="limite_productos" type="number" min="1"
                     class="form-control @error('limite_productos') is-invalid @enderror"
                     value="{{ old('limite_productos',$plan->limite_productos) }}"
                     placeholder="10"
                     required>
              @error('limite_productos') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Cantidad máxima de productos que pueden crear</small>
            </div>

            {{-- Límite de Transacciones --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Límite de Transacciones</label>
              <input name="limite_transacciones" type="number" min="1"
                     class="form-control @error('limite_transacciones') is-invalid @enderror"
                     value="{{ old('limite_transacciones',$plan->limite_transacciones) }}"
                     placeholder="50">
              @error('limite_transacciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Número máximo de transacciones permitidas</small>
            </div>

            {{-- Porcentaje de Comisión --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Porcentaje de Comisión (%) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input name="porcentaje_comision" type="number" step="0.01" min="0" max="100"
                       class="form-control @error('porcentaje_comision') is-invalid @enderror"
                       value="{{ old('porcentaje_comision',$plan->porcentaje_comision) }}"
                       placeholder="5.00"
                       required>
                <span class="input-group-text">%</span>
              </div>
              @error('porcentaje_comision') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Porcentaje de comisión sobre ventas</small>
            </div>

            {{-- Comisión Fija --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Comisión Fija <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input name="comision_fija" type="number" step="0.01" min="0"
                       class="form-control @error('comision_fija') is-invalid @enderror"
                       value="{{ old('comision_fija',$plan->comision_fija) }}"
                       placeholder="900.00"
                       required>
              </div>
              @error('comision_fija') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Comisión fija por transacción</small>
            </div>

            {{-- Orden --}}
            <div class="col-md-6 mb-3">
              <label class="form-label">Orden de Visualización</label>
              <input name="orden" type="number" min="0"
                     class="form-control @error('orden') is-invalid @enderror"
                     value="{{ old('orden',$plan->orden ?? 0) }}"
                     placeholder="0">
              @error('orden') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Orden en que aparecerá en los listados (menor número = mayor prioridad)</small>
            </div>

            {{-- Descripción --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        placeholder="Descripción detallada del plan...">{{ old('descripcion',$plan->descripcion) }}</textarea>
              @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Características --}}
            <div class="col-md-12 mb-3">
              <label class="form-label">Características del Plan</label>
              <div id="caracteristicas-container">
                @php
                  $caracteristicas = old('caracteristicas', $plan->caracteristicas ?? []);
                  if (empty($caracteristicas)) $caracteristicas = [''];
                @endphp
                
                @foreach($caracteristicas as $index => $caracteristica)
                  <div class="caracteristica-item mb-2">
                    <div class="input-group">
                      <input name="caracteristicas[]" type="text"
                             class="form-control"
                             value="{{ $caracteristica }}"
                             placeholder="Ej: Soporte técnico 24/7">
                      <button type="button" class="btn btn-outline-danger eliminar-caracteristica" title="Eliminar">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
              <button type="button" id="agregar-caracteristica" class="btn btn-outline-primary btn-sm mt-2">
                <i class="bi bi-plus"></i> Agregar Característica
              </button>
              <small class="text-muted d-block">Características que se mostrarán a los usuarios</small>
            </div>

            {{-- Estado --}}
            <div class="col-md-12 mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                       {{ old('activo', $plan->activo ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">
                  Plan activo (visible para los usuarios)
                </label>
              </div>
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="marca_de_agua" name="marca_de_agua" value="1"
                       {{ old('marca_de_agua', $plan->marca_de_agua ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="marca_de_agua">
                  Marca de agua habilitada
                </label>
              </div>
            </div>
          </div>

          {{-- Botones --}}
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.planes-membresia.index') }}" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg"></i> {{ $plan->exists ? 'Actualizar' : 'Crear' }} Plan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    $(document).ready(function() {
      // Agregar nueva característica
      $('#agregar-caracteristica').click(function() {
        const newItem = `
          <div class="caracteristica-item mb-2">
            <div class="input-group">
              <input name="caracteristicas[]" type="text"
                     class="form-control"
                     placeholder="Ej: Soporte técnico 24/7">
              <button type="button" class="btn btn-outline-danger eliminar-caracteristica" title="Eliminar">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        `;
        $('#caracteristicas-container').append(newItem);
      });

      // Eliminar característica
      $(document).on('click', '.eliminar-caracteristica', function() {
        if ($('.caracteristica-item').length > 1) {
          $(this).closest('.caracteristica-item').remove();
        } else {
          // Si es la última, solo limpiar el input
          $(this).closest('.caracteristica-item').find('input').val('');
        }
      });

      // Validación del formulario
      $('#planForm').on('submit', function(e) {
        let valid = true;
        let nombre = $('input[name="nombre"]').val().trim();
        let precio = $('input[name="precio"]').val();
        let limite = $('input[name="limite_productos"]').val();

        if (!nombre) {
          valid = false;
          alert('El nombre del plan es obligatorio');
        }

        if (precio === '' || precio < 0) {
          valid = false;
          alert('El precio debe ser mayor o igual a 0');
        }

        if (!limite || limite < 1) {
          valid = false;
          alert('El límite de productos debe ser al menos 1');
        }

        if (!valid) {
          e.preventDefault();
        }
      });
    });
  </script>
  @endpush
</x-app-layout>