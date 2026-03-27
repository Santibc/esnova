<x-app-layout>
  <x-slot name="header">Crear Descuento</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('descuentos.index') }}">Descuentos</a></li>
            <li class="breadcrumb-item active">Crear</li>
          </ol>
        </nav>
      </div>

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <form action="{{ route('descuentos.store') }}" method="POST">
            @csrf
            @include('descuentos.form')

            <div class="mt-4 pt-3 border-top">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Crear Descuento
              </button>
              <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
