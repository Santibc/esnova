<x-app-layout>
    <x-slot name="header">

            {{ __('Inicio') }}

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    @if (Auth::user()->hasRole('admin'))
                        {{ __("Rol admin") }}
                    @elseif (Auth::user()->hasRole('empresa'))
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Estado de Membresía</h3>
                            @if ($membresiaActiva)
                                @if ($membresiaActiva->planMembresia && $membresiaActiva->planMembresia->precio > 0)
                                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                                        <strong>Plan Activo:</strong> {{ $membresiaActiva->planMembresia->nombre }} <br>
                                        @if ($membresiaActiva->fecha_fin)
                                            <strong>Fecha de Vencimiento:</strong> {{ \Carbon\Carbon::parse($membresiaActiva->fecha_fin)->format('d/m/Y') }}
                                            @if (\Carbon\Carbon::parse($membresiaActiva->fecha_fin)->diffInDays(now()) <= 7)
                                                <br><span class="text-red-600 font-semibold">⚠️ Su membresía vence pronto</span>
                                            @endif
                                        @else
                                            <br><strong>Membresía:</strong> Permanente
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
                                        <strong>Plan Gratuito Activo</strong>
                                    </div>
                                @endif
                            @else
                                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                                    <strong>Sin membresía activa</strong>
                                </div>
                            @endif
                        </div>
                        <p>Bienvenido a tu panel de control.</p>
                    @else
                        <p>Closer.</p>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
