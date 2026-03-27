<x-app-layout>
  <x-slot name="header">Productos</x-slot>

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

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <h4 class="text-2xl font-semibold mb-4">Listado de Productos</h4>

          <table id="productos-table" class="table-responsive w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-100">
              <tr>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Referencia</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Unidad Venta</th>
                <th>Unidad Empaque</th>
                <th>Extensión</th>
                <th>Variantes</th>
                <th>Info Envío</th>
                <th>Devoluciones</th>
                <th>Garantía</th>
                <th>Activo</th>
                <th>Descuentos</th>
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
    const table = $('#productos-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      scrollX: true,
      ajax: "{{ route('productos') }}",
      columns: [
        { data:'action',       orderable:false, searchable:false },
        { data:'imagen',       orderable:false, searchable:false },
        { data:'referencia',   name:'referencia' },
        { data:'nombre',       name:'nombre' },
        { data:'categoria',    orderable:false, searchable:false },
        { data:'unidad_venta', name:'unidad_venta' },
        { data:'unidad_empaque', name:'unidad_empaque' },
        { data:'extension',    name:'extension' },
        { data:'variantes',    name:'tiene_variantes' },
        { data:'info_envio',   name:'info_envio' },
        { data:'dias_devolucion', name:'dias_devolucion' },
        { data:'garantia',     name:'garantia' },
        { data:'activo',       name:'activo' },
        { data:'descuentos',   orderable:false, searchable:false },
      ],
      dom: "<'flex justify-between mb-4'<'relative'B>f>t<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",
      buttons: [
        { extend:'pageLength', className:'btn btn-outline-dark', text:'Filas ' },
        { extend:'colvis',     className:'btn btn-outline-dark', text:'Columnas', columns:':not(.noVis)' },
        { extend:'excelHtml5', className:'btn btn-outline-success', text:'Excel' },
        {
          text:'Nuevo',
          className: 'btn btn-outline-primary',
          action: function(e, dt, node, config) {
            window.location.href = "{{ route('productos.form') }}";
          }
        },
        {
          text:'<i class="bi bi-currency-dollar"></i> Actualizar Precios',
          className:'btn btn-outline-warning',
          action: () => window.location.href = "{{ route('productos.historial-precios') }}"
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

  // Funciones para los modales
  function verVariantes(productoId) {
    $.get(`/productos/${productoId}/variantes-ajax`, function(data) {
      $('#modalVariantesContent').html(data);
      $('#modalVariantes').modal('show');
    });
  }

  function verImagenes(productoId) {
    $.get(`/productos/${productoId}/imagenes-ajax`, function(data) {
      $('#modalImagenesContent').html(data);
      $('#modalImagenes').modal('show');
    });
  }

  function verPrecios(productoId) {
    $.get(`/productos/${productoId}/precios-ajax`, function(data) {
      $('#modalPreciosContent').html(data);
      $('#modalPrecios').modal('show');
    });
  }

  // Variable global para almacenar el producto actual
  let productoActualId = null;

  function verStock(productoId) {
    // Guardar el ID del producto actual
    productoActualId = productoId;
    
    // Actualizar el enlace del botón con el producto_id
    $('#btnIrGestionStock').attr('href', '{{ route("stock.index") }}?producto_id=' + productoId);
    
    // Cargar el contenido del modal
    $.get(`/productos/${productoId}/stock-ajax`, function(data) {
      $('#modalStockContent').html(data);
      $('#modalStock').modal('show');
    });
  }

  // Función para eliminar producto
  function eliminarProducto(productoId, productoNombre) {
    Swal.fire({
        title: '¿Eliminar producto?',
        html: `¿Está seguro que desea eliminar el producto <strong>${productoNombre}</strong>?<br><br>
               <small class="text-muted">El producto no se eliminará físicamente de la base de datos.<br>
               Se mantendrá el historial de compras asociadas.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("productos.eliminar") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: productoId
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Eliminado',
                        text: response.message,
                        icon: 'success',
                        timer: 3000
                    });
                    $('#productos-table').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    let message = 'Error al eliminar el producto';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    }
                    Swal.fire({
                        title: 'Error',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        }
    });
  }
  </script>
  @endpush

  <!-- Modal para ver stock -->
  <div class="modal fade" id="modalStock" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Stock del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalStockContent">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" id="btnIrGestionStock" class="btn btn-primary">
            <i class="bi bi-box-seam"></i> Ir a Gestión de Stock (Filtrado)
          </a>
          <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-box-seam"></i> Ver Todo el Stock
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para actualizar precios desde Excel -->
  <div class="modal fade" id="modalActualizarPrecios" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('productos.actualizar-precios-excel') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Actualizar Precios desde Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Archivo Excel</label>
              <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls" required>
              <small class="text-muted">Formato: Referencia | Export1 | Export2 | Local1 | Local2 | Local3 | Local4</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal para ver variantes -->
  <div class="modal fade" id="modalVariantes" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Variantes del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalVariantesContent">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para ver imágenes -->
  <div class="modal fade" id="modalImagenes" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Imágenes del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalImagenesContent">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para ver precios -->
  <div class="modal fade" id="modalPrecios" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Precios del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalPreciosContent">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>