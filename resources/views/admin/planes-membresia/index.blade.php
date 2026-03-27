<x-app-layout>
  <x-slot name="header">Planes de Membresía</x-slot>

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
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Total Planes</h6>
                  <h3 class="mb-0">{{ $estadisticas['total_planes'] }}</h3>
                </div>
                <i class="bi bi-credit-card fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-white-50">Planes Activos</h6>
                  <h3 class="mb-0">{{ $estadisticas['planes_activos'] }}</h3>
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
                  <h6 class="text-white-50">Con Empresas</h6>
                  <h3 class="mb-0">{{ $estadisticas['planes_con_empresas'] }}</h3>
                </div>
                <i class="bi bi-building fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card bg-warning text-dark">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted">Plan Gratuito</h6>
                  <h3 class="mb-0">{{ $estadisticas['plan_gratuito'] }}</h3>
                </div>
                <i class="bi bi-gift fs-1 opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Botón Crear Nuevo --}}
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Listado de Planes</h4>
        <a href="{{ route('admin.planes-membresia.form') }}" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Nuevo Plan
        </a>
      </div>

      {{-- Tabla --}}
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="tabla-planes" class="table table-hover" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Precio</th>
                  <th>Límite Productos</th>
                  <th>Límite Transacciones</th>
                  <th>% Comisión</th>
                  <th>Comisión Fija</th>
                  <th>Orden</th>
                  <th>Empresas</th>
                  <th>Membresías</th>
                  <th>Estado</th>
                  <th>Marca de Agua</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    $(document).ready(function() {
      let tabla = $('#tabla-planes').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.planes-membresia.index') }}",
        columns: [
          { data: 'id', name: 'id' },
          { data: 'nombre', name: 'nombre' },
          { data: 'precio_formatted', name: 'precio' },
          { data: 'limite_productos', name: 'limite_productos' },
          { 
            data: 'limite_transacciones', 
            name: 'limite_transacciones',
            render: function(data) {
              return data ? data : 'Sin límite';
            }
          },
          { 
            data: 'porcentaje_comision', 
            name: 'porcentaje_comision',
            render: function(data) {
              return data + '%';
            }
          },
          { 
            data: 'comision_fija', 
            name: 'comision_fija',
            render: function(data) {
              return '$' + parseFloat(data).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 0});
            }
          },
          { data: 'orden', name: 'orden' },
          { data: 'empresas_count', name: 'empresas_count', orderable: false },
          { data: 'membresias_count', name: 'membresias_count', orderable: false },
          { data: 'activo', name: 'activo' },
          { data: 'marca_de_agua', name: 'marca_de_agua' },
          { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
          "processing": "Procesando...",
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "emptyTable": "Ningún dato disponible en esta tabla",
          "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "search": "Buscar:",
          "loadingRecords": "Cargando...",
          "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        pageLength: 25,
        responsive: true
      });
    });

    function cambiarEstado(id) {
      Swal.fire({
        title: '¿Cambiar estado del plan?',
        text: "Se activará o desactivará este plan de membresía",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.post(`{{ route('admin.planes-membresia.cambiar-estado', '') }}/${id}`, {})
            .done(function(response) {
              if (response.success) {
                Swal.fire('¡Éxito!', response.mensaje, 'success');
                $('#tabla-planes').DataTable().ajax.reload();
              }
            })
            .fail(function() {
              Swal.fire('Error', 'No se pudo cambiar el estado', 'error');
            });
        }
      });
    }

    function eliminarPlan(id) {
      Swal.fire({
        title: '¿Eliminar plan?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            url: `{{ route('admin.planes-membresia.eliminar', '') }}/${id}`,
            type: 'DELETE',
            success: function(response) {
              if (response.success) {
                Swal.fire('¡Eliminado!', response.mensaje, 'success');
                $('#tabla-planes').DataTable().ajax.reload();
              }
            },
            error: function(xhr) {
              let mensaje = 'No se pudo eliminar el plan';
              if (xhr.responseJSON && xhr.responseJSON.error) {
                mensaje = xhr.responseJSON.error;
              }
              Swal.fire('Error', mensaje, 'error');
            }
          });
        }
      });
    }
  </script>
  @endpush
</x-app-layout>