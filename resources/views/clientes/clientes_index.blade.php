<x-app-layout>
    <x-slot name="header">Clientes</x-slot>

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

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="text-2xl font-semibold">Clientes de {{ $empresa->nombre }}</h4>
              <div class="text-muted">
                <i class="bi bi-building"></i> Empresa: <strong>{{ $empresa->nombre }}</strong>
              </div>
            </div>

            <table id="clientes-table" class="table-responsive w-full text-sm text-left">
              <thead class="text-xs uppercase bg-gray-100">
                <tr>
                  <th>Acciones</th>
                  <th>Identificación</th>
                  <th>Contacto</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>País</th>
                  <th>Ciudad</th>
                  <th>Vendedor</th>
                  <th>Lista Precio</th>
                  <th>Activo</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal para ver enlaces de acceso --}}
    <div class="modal fade" id="modalEnlaces" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Enlaces de Acceso del Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" id="modalEnlacesContent">
            <div class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      const table = $('#clientes-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: "{{ route('clientes') }}",
        columns: [
          { data:'action',                orderable:false, searchable:false },
          { data:'numero_identificacion', name:'numero_identificacion' },
          { data:'nombre_contacto',       name:'nombre_contacto' },
          { data:'email',                 name:'email' },
          { data:'telefono',              name:'telefono' },
          { data:'pais',                  name:'pais',      orderable:false, searchable:false },
          { data:'ciudad',                name:'ciudad',    orderable:false, searchable:false },
          { data:'vendedor',              orderable:false, searchable:false },
          { data:'lista_precio',          orderable:false, searchable:false },
          { data:'activo',                name:'activo' },
        ],
        dom: "<'flex justify-between mb-4'<'relative'B>f>t<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",
        buttons: [
          { extend:'pageLength', className:'btn btn-outline-dark', text:'Filas ' },
          { extend:'colvis',     className:'btn btn-outline-dark', text:'Columnas', columns:':not(.noVis)' },
          { extend:'excelHtml5', className:'btn btn-outline-success', text:'Excel' },
          {
            text:'<i class="bi bi-plus-circle"></i> Nuevo Cliente', 
            className:'btn btn-outline-primary',
            action: () => window.location.href = "{{ route('clientes.form') }}"
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

    // Función para cambiar estado del cliente
    function cambiarEstado(clienteId) {
      if (!confirm('¿Está seguro de cambiar el estado de este cliente?')) {
        return;
      }
      
      $.ajax({
        url: `/clientes/${clienteId}/cambiar-estado`,
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            // Recargar tabla
            $('#clientes-table').DataTable().ajax.reload();
            
            // Mostrar notificación
            if (typeof toastr !== 'undefined') {
              toastr.success(response.mensaje);
            } else {
              alert(response.mensaje);
            }
          }
        },
        error: function() {
          if (typeof toastr !== 'undefined') {
            toastr.error('Error al cambiar el estado del cliente');
          } else {
            alert('Error al cambiar el estado del cliente');
          }
        }
      });
    }

    // Función para ver enlaces de acceso
    function verEnlaces(clienteId) {
      $.get(`/clientes/${clienteId}/enlaces-ajax`, function(data) {
        $('#modalEnlacesContent').html(data);
        $('#modalEnlaces').modal('show');
      }).fail(function() {
        $('#modalEnlacesContent').html('<div class="alert alert-danger">Error al cargar los enlaces</div>');
      });
    }

    // Función para copiar enlace al portapapeles
    function copiarEnlace(url) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function() {
          if (typeof toastr !== 'undefined') {
            toastr.success('Enlace copiado al portapapeles');
          } else {
            alert('Enlace copiado al portapapeles');
          }
        }, function() {
          // Fallback si falla
          copiarEnlaceFallback(url);
        });
      } else {
        // Fallback para navegadores antiguos
        copiarEnlaceFallback(url);
      }
    }

    // Fallback para copiar al portapapeles
    function copiarEnlaceFallback(url) {
      const textArea = document.createElement("textarea");
      textArea.value = url;
      textArea.style.position = "fixed";
      textArea.style.left = "-999999px";
      textArea.style.top = "-999999px";
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      
      try {
        document.execCommand('copy');
        if (typeof toastr !== 'undefined') {
          toastr.success('Enlace copiado al portapapeles');
        } else {
          alert('Enlace copiado al portapapeles');
        }
      } catch (err) {
        if (typeof toastr !== 'undefined') {
          toastr.error('Error al copiar el enlace');
        } else {
          alert('Error al copiar el enlace');
        }
      }
      
      document.body.removeChild(textArea);
    }
    </script>
    @endpush
</x-app-layout>