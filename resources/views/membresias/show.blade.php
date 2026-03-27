<x-app-layout>
  <x-slot name="header">Cambiar de Plan</x-slot>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Cambiar a {{ $plan->nombre }}</h3>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Plan Actual</h5>
                            <div class="bg-light p-3 rounded">
                                <h6>{{ $empresa->planMembresia->nombre }}</h6>
                                <p class="mb-1">{{ $empresa->limite_productos }} productos</p>
                                <p class="mb-0">{{ $empresa->planMembresia->porcentaje_comision ?? 0 }}% + ${{ number_format($empresa->planMembresia->comision_fija ?? 0, 0) }} por transacción</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Nuevo Plan</h5>
                            <div class="bg-primary text-white p-3 rounded">
                                <h6>{{ $plan->nombre }}</h6>
                                <p class="mb-1">{{ $plan->limite_productos }} productos</p>
                                <p class="mb-0">{{ $plan->porcentaje_comision }}% + ${{ number_format($plan->comision_fija, 0) }} por transacción</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong>
                        <ul class="mb-0 mt-2">
                            <li>El cambio de plan es inmediato</li>
                            @if($plan->precio > 0)
                            <li>Se te cobrará ${{ number_format($plan->precio, 0) }} mensualmente</li>
                            <li>Puedes cancelar en cualquier momento</li>
                            @else
                            <li>Al cambiar al plan gratuito, tus límites se reducirán</li>
                            <li>Si tienes más de {{ $plan->limite_productos }} productos, deberás desactivar algunos</li>
                            @endif
                        </ul>
                    </div>
                    
                    @if($empresa->productos()->count() > $plan->limite_productos)
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        <strong>Atención:</strong> Tienes {{ $empresa->productos()->count() }} productos activos, 
                        pero este plan solo permite {{ $plan->limite_productos }}. Deberás desactivar 
                        {{ $empresa->productos()->count() - $plan->limite_productos }} productos antes de cambiar.
                    </div>
                    @endif
                    
                    <form action="{{ route('membresias.comprar', $plan->id) }}" method="POST">
                        @csrf
                        
                        @if($plan->precio == 0 && $empresa->tienePlanPremium())
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="confirmar" id="confirmar" required>
                            <label class="form-check-label" for="confirmar">
                                Entiendo que al cambiar al plan gratuito perderé los beneficios de mi plan actual
                            </label>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                            
                            @if($empresa->productos()->count() <= $plan->limite_productos)
                            <button type="submit" class="btn btn-primary">
                                @if($plan->precio > 0)
                                    <i class="bi bi-credit-card"></i> Proceder al Pago
                                @else
                                    <i class="bi bi-check"></i> Cambiar a Plan Gratuito
                                @endif
                            </button>
                            @else
                            <button type="button" class="btn btn-primary" disabled>
                                No puedes cambiar a este plan
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>