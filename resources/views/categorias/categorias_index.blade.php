<x-app-layout>
  <x-slot name="header">Categorías</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Mensajes de éxito/error --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      
      @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
          {{ session('warning') }}
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
                  <h6 class="text-white-50">Total Categorías</h6>
                  <h3 class="mb-0">{{ $estadisticas['total_categorias'] }}</h3>
                </div>
                <i class="bi bi-tags fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Categorías Activas</h6>
                  <h3 class="mb-0">{{ $estadisticas['categorias_activas'] }}</h3>
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
                  <h6 class="text-white-50">Con Productos</h6>
                  <h3 class="mb-0">{{ $estadisticas['categorias_con_productos'] }}</h3>
                </div>
                <i class="bi bi-box-seam fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-2xl font-semibold">Categorías de {{ $empresa->nombre }}</h4>
            <div class="text-muted">
              <i class="bi bi-building"></i> Gestión de categorías exclusivas de su empresa
            </div>
          </div>

          <table id="categorias-table" class="table-responsive w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100">
              <tr>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Descripción</th>
                <th>Productos</th>
                <th>Orden</th>
                <th>Activo</th>
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
    const table = $('#categorias-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      scrollX: true,
      ajax: "{{ route('categorias') }}",
      columns: [
        { data:'action',    orderable:false, searchable:false },
        { data:'imagen_preview', orderable:false, searchable:false },
        { data:'nombre',    name:'nombre' },
        { data:'slug',      name:'slug' },
        { 
          data:'descripcion', 
          name:'descripcion',
          render: data => data ? (data.length > 50 ? data.substr(0,50)+'…' : data) : ''
        },
        { 
          data:'productos_count', 
          name:'productos_count',
          searchable: false,
          render: data => `<span class="badge bg-primary">${data}</span>`
        },
        { data:'orden',     name:'orden' },
        { 
          data:'activo',    
          name:'activo',
          render: data => data === 'Sí' 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>'
        },
      ],
      order: [[6, 'asc']], // Ordenar por columna "orden"
      dom: "<'flex justify-between mb-4'<'relative'B>f>t<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",
      buttons: [
        { extend:'pageLength', className:'btn btn-outline-dark', text:'Filas ' },
        { extend:'colvis',     className:'btn btn-outline-dark', text:'Columnas', columns:':not(.noVis)' },
        { extend:'excelHtml5', className:'btn btn-outline-success', text:'Excel' },
        {
          text:'<i class="bi bi-plus-circle"></i> Nueva Categoría', 
          className:'btn btn-outline-primary',
          action: () => window.location.href = "{{ route('categorias.form') }}"
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

  // Función para cambiar estado
  function cambiarEstado(categoriaId) {
    $.ajax({
      url: `/categorias/${categoriaId}/cambiar-estado`,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if (response.success) {
          // Recargar tabla
          $('#categorias-table').DataTable().ajax.reload();
          
          // Mostrar notificación
          toastr.success(response.mensaje);
          
          // Actualizar estadísticas
          location.reload();
        }
      },
      error: function() {
        toastr.error('Error al cambiar el estado de la categoría');
      }
    });
  }

  // Función para eliminar categoría
  function eliminarCategoria(categoriaId) {
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
        $.ajax({
          url: `/categorias/${categoriaId}`,
          method: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              // Recargar tabla
              $('#categorias-table').DataTable().ajax.reload();
              
              // Mostrar notificación
              toastr.success(response.mensaje);
              
              // Actualizar estadísticas
              location.reload();
            }
          },
          error: function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response.error || 'Error al eliminar la categoría');
          }
        });
      }
    });
  }
  </script>
  @endpush
</x-app-layout>