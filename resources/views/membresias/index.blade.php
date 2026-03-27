<x-app-layout>
  <x-slot name="header">Planes de Membresía</x-slot>

<style>
.plan-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  border-color: #ff00c8 !important;
}

.plan-cta:hover {
  background: #0047ff !important;
  transform: translateY(-1px);
}

.plan-card {
  height: 100% !important;
}

.col-lg-3 {
  display: flex;
  align-items: stretch;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4">
                <h1 class="page-title">Planes de Membresía</h1>
                <p class="text-muted">Elige el plan que mejor se adapte a tu negocio</p>
            </div>

            @if($membresiaActiva)
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>Plan actual: {{ $membresiaActiva->plan->nombre }}</strong>
                        @if($membresiaActiva->fecha_fin)
                        <br><small>Válido hasta: {{ $membresiaActiva->fecha_fin->format('d/m/Y') }} ({{ $membresiaActiva->diasRestantes() }} días restantes)</small>
                        @else
                        <br><small class="text-success">Membresía ilimitada</small>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Estado actual de la empresa -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-1">{{ $empresa->productos()->count() }}/{{ $empresa->limite_productos }}</h3>
                            <p class="text-muted mb-0">Productos creados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h3 class="mb-1">{{ $empresa->planMembresia->porcentaje_comision ?? 0 }}% + ${{ number_format($empresa->planMembresia->comision_fija ?? 0, 0) }}</h3>
                            <p class="text-muted mb-0">Comisión actual</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            @if($empresa->planMembresia)
                            <h3 class="mb-1 text-{{ $empresa->planMembresia->esGratuito() ? 'warning' : 'success' }}">
                                {{ $empresa->planMembresia->nombre }}
                            </h3>
                            @else
                            <h3 class="mb-1 text-danger">Sin Plan</h3>
                            @endif
                            <p class="text-muted mb-0">Tu plan actual</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planes disponibles -->
            <div class="row">
                @foreach($planes as $plan)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="plan-card {{ $empresa->plan_membresia_id == $plan->id ? 'featured' : '' }}" style="background: white; border-radius: 16px; padding: 25px 20px; border: 2px solid #eee; transition: all 0.3s ease; position: relative; overflow: hidden; min-height: 480px; display: flex; flex-direction: column;">
                        @if($empresa->plan_membresia_id == $plan->id)
                        <div style="content: 'PLAN ACTUAL'; position: absolute; top: 15px; right: -30px; background: #ff00c8; color: white; padding: 4px 35px; font-size: 0.75rem; font-weight: 600; transform: rotate(45deg);">
                            <span>Plan Actual</span>
                        </div>
                        @endif
                        
                        <h3 class="plan-name" style="font-size: 1.4rem; font-weight: 700; color: #1c1c1c; margin-bottom: 8px;">{{ $plan->nombre }}</h3>
                        <div class="plan-price {{ $plan->precio == 0 ? 'free' : '' }}" style="font-size: 2rem; font-weight: 800; color: {{ $plan->precio == 0 ? '#25D366' : '#ff00c8' }}; margin-bottom: 4px;">
                            @if($plan->precio == 0)
                            GRATIS
                            @else
                            ${{ number_format($plan->precio, 0) }}<small style="font-size: 0.8rem; font-weight: 400; color: #666;">/mes</small>
                            @endif
                        </div>
                        @if($plan->limite_transacciones)
                        <p class="plan-limit" style="font-size: 0.85rem; color: #666; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">Hasta {{ $plan->limite_transacciones }} transacciones/mes</p>
                        @endif
                        
                        <ul class="plan-features" style="list-style: none; margin-bottom: auto; flex-grow: 1;">
                            @if($plan->caracteristicas)
                                @foreach($plan->caracteristicas as $caracteristica)
                                <li style="padding: 8px 0; color: #444; font-size: 0.85rem; display: flex; align-items: flex-start; gap: 8px;">
                                    <i class="bi bi-check-circle-fill" style="color: #25D366; font-size: 1rem; flex-shrink: 0; margin-top: 2px;"></i>
                                    <span>{{ $caracteristica }}</span>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                        
                        <div class="commission-box" style="background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%); color: white; padding: 12px; border-radius: 10px; text-align: center; margin-top: 15px;">
                            <strong style="display: block; font-size: 1.1rem; margin-bottom: 3px;">{{ $plan->porcentaje_comision }}% + ${{ number_format($plan->comision_fija, 0) }}</strong>
                            <span class="commission-label" style="font-size: 0.75rem; opacity: 0.9;">Por transacción exitosa</span>
                        </div>
                        
                        @if($empresa->plan_membresia_id == $plan->id)
                            <button class="plan-cta" style="display: block; width: 100%; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: not-allowed; margin-top: 15px;" disabled>
                                <i class="bi bi-check"></i> Plan Actual
                            </button>
                        @else
                            <a href="{{ route('membresias.show', $plan->slug) }}" class="plan-cta" style="display: block; width: 100%; padding: 12px; background: #ff00c8; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; margin-top: 15px; text-decoration: none; text-align: center;">
                                Cambiar a este Plan
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

</x-app-layout>