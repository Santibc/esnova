<x-app-layout>
    <x-slot name="header">Pago en Proceso</x-slot>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <!-- Icono de proceso -->
                        <div class="mb-4">
                            <div class="spinner-border text-warning" role="status" style="width: 4rem; height: 4rem;">
                                <span class="visually-hidden">Procesando...</span>
                            </div>
                        </div>

                        <!-- Mensaje principal -->
                        <h2 class="text-warning mb-3">Pago en Proceso</h2>
                        <p class="lead text-muted mb-4">
                            Tu pago está siendo verificado por el procesador
                        </p>

                        <!-- Detalles del pago -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Detalles de la Transacción</h5>
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
                                        <strong>Fecha de Solicitud:</strong><br>
                                        <span class="text-muted">{{ $pagoMembresia->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado del procesamiento -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>¿Qué está sucediendo?</strong><br>
                            El procesador de pagos está verificando tu transacción. Este proceso puede tomar unos minutos.
                            Te notificaremos por correo electrónico una vez que se complete.
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-3 justify-content-center">
                            <button onclick="window.location.reload()" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Estado
                            </button>
                            <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Ver Mis Membresías
                            </a>
                        </div>

                        <!-- Tiempo estimado -->
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Tiempo estimado de procesamiento: 2-5 minutos
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Auto refresh -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        Esta página se actualizará automáticamente en <span id="countdown">30</span> segundos
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
        .spinner-border {
            border-width: 0.25rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Auto refresh después de 30 segundos
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                window.location.reload();
            }
        }, 1000);

        // Limpiar interval si el usuario navega antes
        window.addEventListener('beforeunload', () => {
            clearInterval(interval);
        });
    </script>
    @endpush
</x-app-layout>