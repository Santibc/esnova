<x-app-layout>
  <x-slot name="header">Descuentos</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Mensajes de éxito --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Tarjetas de estadísticas --}}
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Total Descuentos</h6>
                  <h3 class="mb-0">{{ $descuentos->total() }}</h3>
                </div>
                <i class="bi bi-tag fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Activos</h6>
                  <h3 class="mb-0">{{ $descuentos->where('activo', true)->count() }}</h3>
                </div>
                <i class="bi bi-check-circle fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card bg-info text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Con Código</h6>
                  <h3 class="mb-0">{{ $descuentos->whereNotNull('codigo')->count() }}</h3>
                </div>
                <i class="bi bi-hash fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Automáticos</h6>
                  <h3 class="mb-0">{{ $descuentos->whereNull('codigo')->count() }}</h3>
                </div>
                <i class="bi bi-stars fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-2xl font-semibold">Gestión de Descuentos</h4>
            <a href="{{ route('descuentos.create') }}" class="btn btn-primary">
              <i class="bi bi-plus-circle"></i> Nuevo Descuento
            </a>
          </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="bg-gray-100">
                <tr>
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Valor</th>
                  <th>Usos</th>
                  <th>Vigencia</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @forelse($descuentos as $descuento)
                <tr>
                  <td>
                    <strong>{{ $descuento->codigo ?? 'AUTOMÁTICO' }}</strong>
                  </td>
                  <td>
                    {{ $descuento->nombre }}
                    @if($descuento->descripcion)
                      <br><small class="text-muted">{{ Str::limit($descuento->descripcion, 50) }}</small>
                    @endif
                  </td>
                  <td>
                    @if($descuento->tipo == 'porcentaje')
                      <span class="badge bg-primary">% Porcentaje</span>
                    @elseif($descuento->tipo == 'monto_fijo')
                      <span class="badge bg-success">$ Monto Fijo</span>
                    @endif
                  </td>
                  <td>
                    @if($descuento->tipo == 'porcentaje')
                      {{ $descuento->valor }}%
                    @else
                      ${{ number_format($descuento->valor, 0, ',', '.') }}
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-secondary">
                      {{ $descuento->usos_actuales }}
                      @if($descuento->limite_usos_total)
                        / {{ $descuento->limite_usos_total }}
                      @else
                        / ∞
                      @endif
                    </span>
                  </td>
                  <td>
                    @if($descuento->fecha_fin)
                      <small>Hasta {{ $descuento->fecha_fin->format('d/m/Y') }}</small>
                    @else
                      <span class="badge bg-info">Permanente</span>
                    @endif
                  </td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox"
                             id="toggle-{{ $descuento->id }}"
                             {{ $descuento->activo ? 'checked' : '' }}
                             onchange="toggleEstado({{ $descuento->id }})">
                    </div>
                  </td>
                  <td>
                    <a href="{{ route('descuentos.edit', $descuento->id) }}" class="btn btn-sm btn-outline-primary">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <button onclick="eliminar({{ $descuento->id }})" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div class="text-muted mb-3">
                      <i class="bi bi-tag" style="font-size: 3rem;"></i>
                      <p class="mt-2">No hay descuentos creados</p>
                    </div>
                    <a href="{{ route('descuentos.create') }}" class="btn btn-primary">
                      <i class="bi bi-plus-circle"></i> Crear Primer Descuento
                    </a>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-3">
            {{ $descuentos->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  function toggleEstado(id) {
    fetch(`/descuentos/${id}/toggle`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Estado actualizado',
          text: data.message,
          timer: 2000,
          showConfirmButton: false
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo actualizar el estado'
      });
    });
  }

  function eliminar(id) {
    Swal.fire({
      title: '¿Está seguro?',
      text: "Esta acción no se puede deshacer",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/descuentos/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
  </script>
  @endpush
</x-app-layout>
