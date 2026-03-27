<x-app-layout>
    <x-slot name="header">Pago Rechazado</x-slot>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <!-- Icono de error -->
                        <div class="mb-4">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        </div>

                        <!-- Mensaje principal -->
                        <h2 class="text-danger mb-3">Pago Rechazado</h2>
                        <p class="lead text-muted mb-4">
                            Tu pago no pudo ser procesado correctamente
                        </p>

                        <!-- Detalles del pago -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Detalles del Intento de Pago</h5>
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <strong>Plan:</strong><br>
                                        <span class="text-muted">{{ $pagoMembresia->membresia->plan->nombre ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Monto:</strong><br>
                                        <span class="fw-bold">${{ number_format($pagoMembresia->monto, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <strong>Referencia de Pago:</strong><br>
                                        <code>{{ $pagoMembresia->referencia_pago }}</code>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <strong>Fecha del Intento:</strong><br>
                                        <span class="text-muted">{{ $pagoMembresia->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>

                                @if($pagoMembresia->observaciones)
                                <div class="mt-3">
                                    <strong>Motivo del Rechazo:</strong><br>
                                    <span class="text-muted">{{ $pagoMembresia->observaciones }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Razones comunes -->
                        <div class="alert alert-warning text-start">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Razones comunes de rechazo:</h6>
                            <ul class="mb-0">
                                <li>Fondos insuficientes en la cuenta</li>
                                <li>Datos de la tarjeta incorrectos</li>
                                <li>Tarjeta vencida o bloqueada</li>
                                <li>Límite de transacciones excedido</li>
                            </ul>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('membresias.show', $pagoMembresia->membresia->plan->slug ?? '#') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Intentar Nuevamente
                            </a>
                            <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Ver Planes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        Si el problema persiste, por favor contáctanos para recibir ayuda personalizada
                    </small>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            border: none;
            border-radius: 0.75rem;
        }
        .card-body {
            padding: 3rem 2rem;
        }
        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
        }
        .alert {
            border-radius: 0.5rem;
        }
        ul {
            list-style-type: disc;
            padding-left: 1.2rem;
        }
    </style>
    @endpush
</x-app-layout>