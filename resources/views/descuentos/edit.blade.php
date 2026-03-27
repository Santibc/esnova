<x-app-layout>
  <x-slot name="header">Editar Descuento</x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('descuentos.index') }}">Descuentos</a></li>
            <li class="breadcrumb-item active">Editar: {{ $descuento->nombre }}</li>
          </ol>
        </nav>
      </div>

      @if($descuento->usos_actuales > 0)
        <div class="alert alert-info mb-4">
          <i class="bi bi-info-circle"></i>
          <strong>Información:</strong> Este descuento ya ha sido usado {{ $descuento->usos_actuales }} vez/veces.
          Ten cuidado al modificar sus parámetros.
        </div>
      @endif

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
          <form action="{{ route('descuentos.update', $descuento->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('descuentos.form')

            <div class="mt-4 pt-3 border-top">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Actualizar Descuento
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
