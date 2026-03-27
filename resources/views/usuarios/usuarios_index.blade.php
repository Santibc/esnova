<x-app-layout>
    <x-slot name="header">
        {{ __('Usuarios') }}
    </x-slot>

    <div class="py-12" style="padding-top: 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-2xl font-semibold mb-4">Usuarios</h4>

                    <div class="border border-gray-300 rounded-lg">
                        <div class="overflow-x-auto">

                        <table id="users-table" class="table-responsive w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr class="border-b border-gray-300">
                                    <th class="px-6 py-3" data-priority="1">Acciones</th>
                                    <th class="px-6 py-3">Nombre</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Roles</th>
                                    <th class="px-6 py-3">Empresa</th>
                                    <th class="px-6 py-3">Membresía</th>
                                    <th class="px-6 py-3">Tienda</th>
                                </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        ajax: "{{ route('usuarios') }}",
        columns: [
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'noVis'
            },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles',  name: 'roles', orderable: false, searchable: false },
            { data: 'empresa_info', name: 'empresa_info', orderable: false, searchable: false },
            { data: 'membresia_info', name: 'membresia_info', orderable: false, searchable: false },
            { data: 'tienda_link', name: 'tienda_link', orderable: false, searchable: false }
        ],

        dom: "<'flex flex-wrap justify-between items-center mb-4'<'relative'B>f>" + 
             "t" + 
             "<'flex justify-between items-center px-2 my-2'i<'pagination-wrapper'p>>",

        buttons: [
            {
                extend: 'pageLength',
                className: 'btn btn-outline-dark',
                text: 'Filas '
            },
            {
                extend: 'colvis',
                text: 'Columnas',
                columns: ':not(.noVis)',
                className: 'btn btn-outline-dark'
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'btn btn-outline-success'
            },
            {
                text: 'Nuevo',
                className: 'btn btn-outline-primary',
                action: function () {
                    window.location.href = "{{ route('usuarios.form') }}";
                }
            }
        ],
        language: {
            url: '{{ asset("js/datatables/es-ES.json") }}',
            buttons: {
                pageLength: {
                    _: "Mostrar %d filas",
                    '-1': "Mostrar todos"
                }
            }
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
    });

    table.on('buttons-action', function () {
        setTimeout(() => {
            $('.dt-button-collection')
                .addClass('bg-white border border-gray-300 rounded shadow-md mt-2 p-2')
                .css({
                    position: 'absolute',
                    'z-index': 999,
                    top: 'calc(100% + 0.5rem)',
                    left: '0',
                    right: 'auto'
                });

            $('.dt-button-collection button')
                .removeClass()
                .addClass('block w-full text-left text-sm text-gray-800 px-4 py-2 rounded hover:bg-gray-100 cursor-pointer transition-colors duration-150');
        }, 50);
    });

    // Manejar el cambio de estado de empresa
    $('#users-table').on('click', '.toggle-empresa-btn', function() {
        const button = $(this);
        const userId = button.data('user-id');
        const empresaId = button.data('empresa-id');
        const nuevoEstado = button.data('estado');
        const estadoTexto = nuevoEstado ? 'activar' : 'desactivar';

        if (confirm(`¿Está seguro de que desea ${estadoTexto} esta empresa?`)) {
            button.prop('disabled', true);

            $.ajax({
                url: "{{ route('usuarios.cambiar-estado-empresa') }}",
                method: 'POST',
                data: {
                    empresa_id: empresaId,
                    estado: nuevoEstado,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Recargar la tabla para mostrar los cambios
                        table.ajax.reload(null, false);
                        
                        // Mostrar mensaje de éxito
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('Error al cambiar el estado de la empresa');
                    button.prop('disabled', false);
                }
            });
        }
    });
});
</script>
@endpush

</x-app-layout>
