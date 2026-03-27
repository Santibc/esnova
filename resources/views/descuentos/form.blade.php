<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $descuento->nombre ?? '') }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                   value="{{ old('codigo', $descuento->codigo ?? '') }}"
                   placeholder="Dejar vacío para generar automático">
            <small class="text-muted">Dejar vacío para descuento automático (sin código)</small>
            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $descuento->descripcion ?? '') }}</textarea>
</div>

{{-- Sección: Ámbito de Aplicación --}}
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary bg-opacity-10">
        <h6 class="mb-0 text-primary"><i class="bi bi-bullseye"></i> Ámbito de Aplicación</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Aplicar a: *</label>
            <select name="aplica_a" id="aplica_a" class="form-select @error('aplica_a') is-invalid @enderror" required>
                <option value="orden" {{ old('aplica_a', $descuento->aplica_a ?? 'orden') == 'orden' ? 'selected' : '' }}>
                    Todo el Carrito/Orden
                </option>
                <option value="producto" {{ old('aplica_a', $descuento->aplica_a ?? '') == 'producto' ? 'selected' : '' }}>
                    Productos Específicos
                </option>
                <option value="categoria" {{ old('aplica_a', $descuento->aplica_a ?? '') == 'categoria' ? 'selected' : '' }}>
                    Categorías Específicas
                </option>
            </select>
            @error('aplica_a')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Selector de Productos --}}
        <div id="productos_section" class="mb-3" style="display: none;">
            <label class="form-label">Productos Aplicables</label>
            <select name="productos_aplicables[]" id="productos_aplicables" class="form-select select2-productos" multiple>
                @if(isset($productos))
                    @foreach($productos as $producto)
                        @php
                            $precio = $producto->precios->first() ? $producto->precios->first()->precio : 0;
                        @endphp
                        <option value="{{ $producto->id }}"
                            {{ in_array($producto->id, old('productos_aplicables', isset($descuento) && $descuento->productos_aplicables ? $descuento->productos_aplicables : [])) ? 'selected' : '' }}>
                            {{ $producto->nombre }} - ${{ number_format($precio, 0, ',', '.') }}
                        </option>
                    @endforeach
                @endif
            </select>
            <small class="text-muted">Usa el buscador para encontrar productos rápidamente</small>
        </div>

        {{-- Selector de Categorías --}}
        <div id="categorias_section" class="mb-3" style="display: none;">
            <label class="form-label">Categorías Aplicables</label>
            <select name="categorias_aplicables[]" id="categorias_aplicables" class="form-select select2-categorias" multiple>
                @if(isset($categorias))
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            {{ in_array($categoria->id, old('categorias_aplicables', isset($descuento) && $descuento->categorias_aplicables ? $descuento->categorias_aplicables : [])) ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
            <small class="text-muted">Usa el buscador para encontrar categorías rápidamente</small>
        </div>
    </div>
</div>

{{-- Sección: Tipo y Valor del Descuento --}}
<div class="card mb-4 border-success">
    <div class="card-header bg-success bg-opacity-10">
        <h6 class="mb-0 text-success"><i class="bi bi-percent"></i> Tipo y Valor del Descuento</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Tipo de Descuento *</label>
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        <option value="porcentaje" {{ old('tipo', $descuento->tipo ?? '') == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                        <option value="monto_fijo" {{ old('tipo', $descuento->tipo ?? '') == 'monto_fijo' ? 'selected' : '' }}>Monto Fijo ($)</option>
                    </select>
                    @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Valor *</label>
                    <input type="number" step="0.01" name="valor" class="form-control @error('valor') is-invalid @enderror"
                           value="{{ old('valor', $descuento->valor ?? '') }}" required>
                    @error('valor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Descuento Máximo</label>
                    <input type="number" step="0.01" name="descuento_maximo" class="form-control"
                           value="{{ old('descuento_maximo', $descuento->descuento_maximo ?? '') }}"
                           placeholder="Opcional para porcentajes">
                    <small class="text-muted">Tope máximo del descuento</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección: Requisitos --}}
<div class="card mb-4 border-warning">
    <div class="card-header bg-warning bg-opacity-10">
        <h6 class="mb-0 text-warning"><i class="bi bi-card-checklist"></i> Requisitos de Aplicación</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Monto Mínimo de Compra</label>
                    <input type="number" step="0.01" name="monto_minimo_compra" class="form-control"
                           value="{{ old('monto_minimo_compra', $descuento->monto_minimo_compra ?? '') }}"
                           placeholder="Ej: 50000">
                    <small class="text-muted">Compra mínima requerida</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Cantidad Mínima de Productos</label>
                    <input type="number" name="cantidad_minima_productos" class="form-control"
                           value="{{ old('cantidad_minima_productos', $descuento->cantidad_minima_productos ?? '') }}"
                           placeholder="Ej: 2">
                    <small class="text-muted">Cantidad mínima de productos en el carrito</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección: Límites de Uso --}}
<div class="card mb-4 border-info">
    <div class="card-header bg-info bg-opacity-10">
        <h6 class="mb-0 text-info"><i class="bi bi-clock-history"></i> Límites de Uso</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Límite de Usos Total</label>
                    <input type="number" name="limite_usos_total" class="form-control"
                           value="{{ old('limite_usos_total', $descuento->limite_usos_total ?? '') }}"
                           placeholder="Ej: 100">
                    <small class="text-muted">Total de veces que puede usarse</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Límite por Cliente</label>
                    <input type="number" name="limite_usos_por_cliente" class="form-control"
                           value="{{ old('limite_usos_por_cliente', $descuento->limite_usos_por_cliente ?? '') }}"
                           placeholder="Ej: 1">
                    <small class="text-muted">Usos permitidos por cliente</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Prioridad</label>
                    <input type="number" name="prioridad" class="form-control"
                           value="{{ old('prioridad', $descuento->prioridad ?? 0) }}"
                           placeholder="Ej: 1">
                    <small class="text-muted">Mayor número = mayor prioridad</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección: Vigencia --}}
<div class="card mb-4 border-secondary">
    <div class="card-header bg-secondary bg-opacity-10">
        <h6 class="mb-0 text-secondary"><i class="bi bi-calendar-range"></i> Vigencia</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Fecha de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="form-control"
                           value="{{ old('fecha_inicio', isset($descuento) && $descuento->fecha_inicio ? $descuento->fecha_inicio->format('Y-m-d\TH:i') : '') }}">
                    <small class="text-muted">Dejar vacío para que aplique inmediatamente</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Fecha de Fin</label>
                    <input type="datetime-local" name="fecha_fin" class="form-control"
                           value="{{ old('fecha_fin', isset($descuento) && $descuento->fecha_fin ? $descuento->fecha_fin->format('Y-m-d\TH:i') : '') }}">
                    <small class="text-muted">Dejar vacío para que no expire</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sección: Opciones Adicionales --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-toggles"></i> Opciones Adicionales</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1"
                           {{ old('activo', $descuento->activo ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo">
                        <strong>Activo</strong>
                        <br><small class="text-muted">El descuento está disponible para uso</small>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="es_acumulable" id="es_acumulable" value="1"
                           {{ old('es_acumulable', $descuento->es_acumulable ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="es_acumulable">
                        <strong>Acumulable</strong>
                        <br><small class="text-muted">Se puede combinar con otros descuentos</small>
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="solo_primera_compra" id="solo_primera_compra" value="1"
                           {{ old('solo_primera_compra', $descuento->solo_primera_compra ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="solo_primera_compra">
                        <strong>Solo Primera Compra</strong>
                        <br><small class="text-muted">Solo para clientes nuevos</small>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--multiple {
        min-height: 38px !important;
        max-height: 300px;
        overflow-y: auto;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const aplicaASelect = document.getElementById('aplica_a');
    const productosSection = document.getElementById('productos_section');
    const categoriasSection = document.getElementById('categorias_section');

    // Inicializar Select2
    $('.select2-productos').select2({
        theme: 'bootstrap-5',
        placeholder: 'Selecciona productos',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron productos";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });

    $('.select2-categorias').select2({
        theme: 'bootstrap-5',
        placeholder: 'Selecciona categorías',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron categorías";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });

    function toggleSections() {
        const value = aplicaASelect.value;

        // Ocultar todas las secciones
        productosSection.style.display = 'none';
        categoriasSection.style.display = 'none';

        // Mostrar la sección correspondiente
        if (value === 'producto') {
            productosSection.style.display = 'block';
        } else if (value === 'categoria') {
            categoriasSection.style.display = 'block';
        }
    }

    // Inicializar al cargar
    toggleSections();

    // Escuchar cambios
    aplicaASelect.addEventListener('change', toggleSections);
});
</script>
@endpush
