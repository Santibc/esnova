<x-app-layout>
  <x-slot name="header">Blog</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

      {{-- Tarjetas de estadísticas --}}
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Total Posts</h6>
                  <h3 class="mb-0">{{ $estadisticas['total_posts'] }}</h3>
                </div>
                <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Publicados</h6>
                  <h3 class="mb-0">{{ $estadisticas['publicados'] }}</h3>
                </div>
                <i class="bi bi-check-circle fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card bg-info text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Borradores</h6>
                  <h3 class="mb-0">{{ $estadisticas['borradores'] }}</h3>
                </div>
                <i class="bi bi-pencil-square fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-2xl font-semibold">Listado de Posts</h4>
            <div>
              <a href="{{ route('blog.form') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Post
              </a>
              <a href="{{ route('blog.categorias') }}" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-tags"></i> Categorías
              </a>
            </div>
          </div>

          <table id="blog-table" class="table-responsive w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100">
              <tr>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Publicación</th>
                <th>Orden</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const table = $('#blog-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      scrollX: true,
      ajax: "{{ url()->current() }}",
      columns: [
        { data:'action',           orderable:false, searchable:false },
        { data:'imagen_preview',   orderable:false, searchable:false },
        { data:'titulo',           name:'titulo' },
        { data:'categoria_nombre', name:'categoria_nombre', orderable:false, searchable:false },
        { data:'estado',           name:'activo', orderable:false, searchable:false },
        { data:'fecha_pub',        name:'publicado_en' },
        { data:'orden',            name:'orden' },
      ],
      order: [[6, 'asc']],
      dom: "<'flex justify-between mb-4'<'relative'B>f>t<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",
      buttons: [
        { extend:'pageLength', className:'btn btn-outline-dark', text:'Filas ' },
        { extend:'colvis',     className:'btn btn-outline-dark', text:'Columnas', columns:':not(.noVis)' },
        { extend:'excelHtml5', className:'btn btn-outline-success', text:'Excel' },
        {
          text:'<i class="bi bi-plus-circle"></i> Nuevo Post',
          className:'btn btn-outline-primary',
          action: () => window.location.href = "{{ route('blog.form') }}"
        }
      ],
      language: { url: '{{ asset("js/datatables/es-ES.json") }}' },
      lengthMenu: [[10,25,50,-1],[10,25,50,'Todos']]
    });

    table.on('buttons-action', () => {
      setTimeout(() => {
        $('.dt-button-collection')
          .addClass('bg-white border rounded shadow-md mt-2 p-2')
          .css({ position:'absolute','z-index':999,top:'calc(100% + .5rem)',left:0 });
        $('.dt-button-collection button')
          .removeClass()
          .addClass('block w-full text-left px-4 py-2 rounded hover:bg-gray-100');
      }, 50);
    });
  });

  // Función para cambiar estado (publicar/despublicar)
  function cambiarEstado(id) {
    Swal.fire({
      title: '¿Cambiar estado?',
      text: '¿Está seguro de cambiar el estado de este post?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, cambiar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/admin-blog/${id}/cambiar-estado`,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              $('#blog-table').DataTable().ajax.reload(null, false);
              Swal.fire({
                title: 'Actualizado',
                text: response.mensaje || 'Estado cambiado correctamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
              });
            }
          },
          error: function(xhr) {
            const msg = xhr.responseJSON?.error || 'Error al cambiar el estado del post.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  }

  // Función para eliminar post
  function eliminarPost(id) {
    Swal.fire({
      title: '¿Eliminar post?',
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
          url: `/admin-blog/${id}/eliminar`,
          method: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              $('#blog-table').DataTable().ajax.reload(null, false);
              Swal.fire({
                title: 'Eliminado',
                text: response.mensaje || 'Post eliminado correctamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
              });
            }
          },
          error: function(xhr) {
            const msg = xhr.responseJSON?.error || 'Error al eliminar el post.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  }
  </script>
  @endpush
</x-app-layout>
