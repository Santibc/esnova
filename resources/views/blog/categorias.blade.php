<x-app-layout>
  <x-slot name="header">Categorías del Blog</x-slot>

  <div class="container py-4">
    {{-- Botón volver --}}
    <div class="mb-3">
      <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver al Blog
      </a>
    </div>

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

    {{-- Formulario para crear/editar categoría --}}
    <div class="card shadow mb-4">
      <div class="card-header">
        <h5 class="mb-0" id="form-titulo">
          <i class="bi bi-plus-circle"></i> Nueva Categoría
        </h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('blog.categorias.guardar') }}" id="categoriaForm">
          @csrf
          <input type="hidden" name="id" id="categoria_id" value="">

          <div class="row">
            {{-- Nombre --}}
            <div class="col-md-4 mb-3">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input name="nombre" id="categoria_nombre" type="text"
                     class="form-control @error('nombre') is-invalid @enderror"
                     value="{{ old('nombre') }}"
                     placeholder="Nombre de la categoría"
                     required>
              @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Descripción --}}
            <div class="col-md-4 mb-3">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" id="categoria_descripcion" rows="1"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        placeholder="Descripción breve (opcional)">{{ old('descripcion') }}</textarea>
              @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Orden --}}
            <div class="col-md-2 mb-3">
              <label class="form-label">Orden</label>
              <input name="orden" id="categoria_orden" type="number" min="0"
                     class="form-control @error('orden') is-invalid @enderror"
                     value="{{ old('orden', 0) }}">
              @error('orden') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Activo --}}
            <div class="col-md-2 mb-3">
              <label class="form-label">Activo</label>
              <div class="form-check mt-2">
                <input type="hidden" name="activo" value="0">
                <input class="form-check-input" type="checkbox"
                       name="activo" id="categoria_activo"
                       value="1" {{ old('activo', 1) ? 'checked' : '' }}>
                <label class="form-check-label" for="categoria_activo">Sí</label>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save"></i> Guardar Categoría
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
              <i class="bi bi-x-circle"></i> Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Tabla de categorías --}}
    <div class="card shadow">
      <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-tags"></i> Categorías existentes</h5>
      </div>
      <div class="card-body">
        @if($categorias->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th class="text-center">Posts</th>
                  <th class="text-center">Orden</th>
                  <th class="text-center">Activo</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categorias as $categoria)
                  <tr>
                    <td class="fw-semibold">{{ $categoria->nombre }}</td>
                    <td>
                      @if($categoria->descripcion)
                        {{ Str::limit($categoria->descripcion, 60) }}
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="badge bg-primary">{{ $categoria->posts_count }}</span>
                    </td>
                    <td class="text-center">{{ $categoria->orden }}</td>
                    <td class="text-center">
                      @if($categoria->activo)
                        <span class="badge bg-success">Activo</span>
                      @else
                        <span class="badge bg-secondary">Inactivo</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-outline-primary"
                              onclick="editarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}', '{{ addslashes($categoria->descripcion ?? '') }}', {{ $categoria->orden }}, {{ $categoria->activo ? 1 : 0 }})"
                              title="Editar">
                        <i class="bi bi-pencil"></i> Editar
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger"
                              onclick="eliminarCategoria({{ $categoria->id }})"
                              title="Eliminar">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center text-muted py-4">
            <i class="bi bi-tags fs-1 d-block mb-2 opacity-50"></i>
            <p>No hay categorías creadas. Use el formulario de arriba para crear la primera.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  // Editar categoría: llenar formulario con datos existentes
  function editarCategoria(id, nombre, descripcion, orden, activo) {
    document.getElementById('categoria_id').value = id;
    document.getElementById('categoria_nombre').value = nombre;
    document.getElementById('categoria_descripcion').value = descripcion;
    document.getElementById('categoria_orden').value = orden;
    document.getElementById('categoria_activo').checked = activo == 1;

    // Cambiar título del formulario
    document.getElementById('form-titulo').innerHTML = '<i class="bi bi-pencil"></i> Editar Categoría';

    // Scroll al formulario
    document.getElementById('categoriaForm').scrollIntoView({ behavior: 'smooth' });
  }

  // Limpiar formulario (volver al modo crear)
  function limpiarFormulario() {
    document.getElementById('categoria_id').value = '';
    document.getElementById('categoria_nombre').value = '';
    document.getElementById('categoria_descripcion').value = '';
    document.getElementById('categoria_orden').value = 0;
    document.getElementById('categoria_activo').checked = true;

    // Restaurar título del formulario
    document.getElementById('form-titulo').innerHTML = '<i class="bi bi-plus-circle"></i> Nueva Categoría';
  }

  // Eliminar categoría con SweetAlert + AJAX
  function eliminarCategoria(id) {
    Swal.fire({
      title: '¿Eliminar categoría?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/blog/categorias/${id}/eliminar`,
          method: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                title: 'Eliminada',
                text: response.mensaje || 'Categoría eliminada correctamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                location.reload();
              });
            }
          },
          error: function(xhr) {
            const msg = xhr.responseJSON?.error || 'Error al eliminar la categoría.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  }
  </script>
  @endpush
</x-app-layout>
