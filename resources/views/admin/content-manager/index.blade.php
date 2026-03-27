

<x-app-layout>
    <x-slot name="slot">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Gestor de Contenido
                    </h4>
                    <small>Gestiona el contenido y SEO de las páginas del sitio web</small>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Página</th>
                                    <th width="25%">Título</th>
                                    <th width="30%">Descripción</th>
                                    <th width="10%" class="text-center">Estado</th>
                                    <th width="10%" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>{{ $page->id }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $page->name }}</span>
                                        </td>
                                        <td>{{ $page->title }}</td>
                                        <td>
                                            {{ Str::limit($page->description, 80) }}
                                        </td>
                                        <td class="text-center">
                                            @if($page->is_active)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Activo
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.content-manager.edit', $page->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Editar contenido">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-file-earmark-x fs-1 mb-3 d-block"></i>
                                            No hay páginas registradas
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pages->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    </x-slot>
</x-app-layout>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%);
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 0, 200, 0.05);
}

.btn-outline-primary {
    border-color: #ff00c8;
    color: #ff00c8;
}

.btn-outline-primary:hover {
    background-color: #ff00c8;
    border-color: #ff00c8;
}
</style>