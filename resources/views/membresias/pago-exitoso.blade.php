<x-app-layout>
    <x-slot name="header">Pago Exitoso</x-slot>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <!-- Icono de éxito -->
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>

                        <!-- Mensaje principal -->
                        <h2 class="text-success mb-3">¡Pago Exitoso!</h2>
                        <p class="lead text-muted mb-4">
                            Tu membresía ha sido activada correctamente
                        </p>

                        <!-- Detalles del pago -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Detalles de la Membresía</h5>
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <strong>Plan:</strong><br>
                                        <span class="text-muted">{{ $pagoMembresia->membresia->plan->nombre ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Monto Pagado:</strong><br>
                                        <span class="text-success fw-bold">${{ number_format($pagoMembresia->monto, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <strong>Referencia de Pago:</strong><br>
                                        <code>{{ $pagoMembresia->referencia_pago }}</code>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <strong>Fecha de Activación:</strong><br>
                                        <span class="text-muted">{{ $pagoMembresia->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>¿Qué sigue?</strong><br>
                            Tu plan ya está activo. Puedes empezar a disfrutar de todas las funcionalidades incluidas en tu membresía.
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('membresias.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Ver Mis Membresías
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Ir al Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        ¿Necesitas ayuda? Contáctanos a través de nuestro correo de soporte
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
    </style>
    @endpush
</x-app-layout>