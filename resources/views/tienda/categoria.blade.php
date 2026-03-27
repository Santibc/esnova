@extends('tienda.layout')

@section('title', ($categoriaSeleccionada ? $categoriaSeleccionada->nombre . ' - ' : 'Categorías - ') . $empresa->nombre)
@section('nav-categorias', 'active')

@section('content')
<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">{{ $categoriaSeleccionada ? $categoriaSeleccionada->nombre : 'Todas las Categorías' }}</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="{{ route('tienda.empresa') }}">Inicio</a></li>
        @if($categoriaSeleccionada)
          <li><a href="{{ route('tienda.categorias') }}">Categorías</a></li>
          <li class="current">{{ $categoriaSeleccionada->nombre }}</li>
        @else
          <li class="current">Categorías</li>
        @endif
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

<div class="container">
  <div class="row">

    <div class="col-lg-4 sidebar">

      <div class="widgets-container">

        <!-- Product Categories Widget -->
        <div class="product-categories-widget widget-item">

          <h3 class="widget-title">Categorías</h3>

          <ul class="category-tree list-unstyled mb-0">
            @foreach($categorias as $categoria)
            <li class="category-item">
              <div class="d-flex justify-content-between align-items-center category-header">
                <a href="{{ route('tienda.categorias', ['categoria' => $categoria->id]) }}" 
                   class="category-link {{ request('categoria') == $categoria->id ? 'active' : '' }}">
                  {{ $categoria->nombre }}
                  <span class="category-count">({{ $categoria->productos_count ?? 0 }})</span>
                </a>
              </div>
            </li>
            @endforeach
            
            @if($categorias->count() > 0)
            <li class="category-item mt-3">
              <div class="d-flex justify-content-between align-items-center category-header">
                <a href="{{ route('tienda.categorias') }}"
                   class="category-link {{ !request('categoria') ? 'active' : '' }}">
                  Ver Todas
                </a>
              </div>
            </li>
            @endif
          </ul>

        </div><!--/Product Categories Widget -->

        <!-- Pricing Range Widget -->
        <div class="pricing-range-widget widget-item">

          <h3 class="widget-title">Rango de Precio</h3>

          <form id="priceRangeForm" method="GET" action="{{ route('tienda.categorias') }}">
            @foreach(request()->except(['precio_min', 'precio_max', 'precio_min_input', 'precio_max_input']) as $key => $value)
              @if($value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
              @endif
            @endforeach
            
            <div class="price-range-container">
              <div class="current-range mb-3">
                <span class="min-price">${{ number_format($precioMin, 0, ',', '.') }}</span>
                <span class="max-price float-end">${{ number_format($precioMax, 0, ',', '.') }}</span>
              </div>

              <div class="range-slider">
                <div class="slider-track"></div>
                <div class="slider-progress"></div>
                @php
                  $rango = $precioMax - $precioMin;
                  $step = $rango > 10000 ? 1000 : ($rango > 1000 ? 100 : ($rango > 100 ? 10 : 1));
                @endphp
                <input type="range" class="min-range" name="precio_min" 
                       min="{{ $precioMin }}" max="{{ $precioMax }}" 
                       value="{{ request('precio_min', $precioMin) }}" step="{{ $step }}">
                <input type="range" class="max-range" name="precio_max" 
                       min="{{ $precioMin }}" max="{{ $precioMax }}" 
                       value="{{ request('precio_max', $precioMax) }}" step="{{ $step }}">
              </div>

              <div class="price-inputs mt-3">
                <div class="row g-2">
                  <div class="col-6">
                    <div class="input-group input-group-sm">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control min-price-input" 
                             placeholder="Min" 
                             min="{{ $precioMin }}" max="{{ $precioMax }}" 
                             value="{{ request('precio_min', $precioMin) }}" step="{{ $step }}">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="input-group input-group-sm">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control max-price-input" 
                             placeholder="Max" 
                             min="{{ $precioMin }}" max="{{ $precioMax }}" 
                             value="{{ request('precio_max', $precioMax) }}" step="{{ $step }}">
                    </div>
                  </div>
                </div>
              </div>

              <div class="filter-actions mt-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">Aplicar Filtro</button>
              </div>
            </div>
          </form>

        </div><!--/Pricing Range Widget -->

      </div>

    </div>

    <div class="col-lg-8">

      <!-- Category Header Section -->
      <section id="category-header" class="category-header section">

        <div class="container" data-aos="fade-up">

          <!-- Filter and Sort Options -->
          <div class="filter-container mb-4" data-aos="fade-up" data-aos-delay="100">
            <form method="GET" action="{{ route('tienda.categorias') }}" id="filterForm">
              @foreach(request()->except(['buscar', 'orden', 'rango_precio', 'por_pagina']) as $key => $value)
                @if($value && !in_array($key, ['buscar', 'orden', 'rango_precio', 'por_pagina']))
                  <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
              @endforeach
              
              <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-4">
                  <div class="filter-item search-form">
                    <label for="productSearch" class="form-label">Buscar Productos</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="productSearch" 
                             name="buscar" value="{{ request('buscar') }}"
                             placeholder="Buscar productos..." aria-label="Buscar productos">
                      <button class="btn search-btn" type="submit">
                        <i class="bi bi-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                  <div class="filter-item">
                    <label for="priceRange" class="form-label">Rango de Precio</label>
                    <select class="form-select" id="priceRange" name="rango_precio" onchange="this.form.submit()">
                      <option value="" {{ !request('rango_precio') ? 'selected' : '' }}>Todos los Precios</option>
                      <option value="0-50000" {{ request('rango_precio') == '0-50000' ? 'selected' : '' }}>Menos de $50.000</option>
                      <option value="50000-100000" {{ request('rango_precio') == '50000-100000' ? 'selected' : '' }}>$50.000 a $100.000</option>
                      <option value="100000-200000" {{ request('rango_precio') == '100000-200000' ? 'selected' : '' }}>$100.000 a $200.000</option>
                      <option value="200000-500000" {{ request('rango_precio') == '200000-500000' ? 'selected' : '' }}>$200.000 a $500.000</option>
                      <option value="500000-0" {{ request('rango_precio') == '500000-0' ? 'selected' : '' }}>Más de $500.000</option>
                    </select>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                  <div class="filter-item">
                    <label for="sortBy" class="form-label">Ordenar Por</label>
                    <select class="form-select" id="sortBy" name="orden" onchange="this.form.submit()">
                      <option value="" {{ !request('orden') ? 'selected' : '' }}>Más Recientes</option>
                      <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                      <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                      <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                    </select>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                  <div class="filter-item">
                    <label class="form-label">Vista</label>
                    <div class="d-flex align-items-center">
                      <div class="view-options me-3">
                        <button type="button" class="btn view-btn active" data-view="grid" aria-label="Vista cuadrícula">
                          <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                        <button type="button" class="btn view-btn" data-view="list" aria-label="Vista lista">
                          <i class="bi bi-list-ul"></i>
                        </button>
                      </div>
                      <div class="items-per-page">
                        <select class="form-select" id="itemsPerPage" name="por_pagina" onchange="this.form.submit()">
                          <option value="12" {{ request('por_pagina', 12) == 12 ? 'selected' : '' }}>12 por página</option>
                          <option value="24" {{ request('por_pagina') == 24 ? 'selected' : '' }}>24 por página</option>
                          <option value="48" {{ request('por_pagina') == 48 ? 'selected' : '' }}>48 por página</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            @if(request()->anyFilled(['buscar', 'precio_min', 'precio_max', 'rango_precio']))
            <div class="row mt-3">
              <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                <div class="active-filters">
                  <span class="active-filter-label">Filtros Activos:</span>
                  <div class="filter-tags">
                    @if($categoriaSeleccionada)
                      <span class="filter-tag">
                        {{ $categoriaSeleccionada->nombre }}
                        <a href="{{ route('tienda.categorias', request()->except('categoria')) }}" class="filter-remove">
                          <i class="bi bi-x"></i>
                        </a>
                      </span>
                    @endif
                    
                    @if(request('buscar'))
                      <span class="filter-tag">
                        Búsqueda: {{ request('buscar') }}
                        <a href="{{ route('tienda.categorias', request()->except('buscar')) }}" class="filter-remove">
                          <i class="bi bi-x"></i>
                        </a>
                      </span>
                    @endif
                    
                    @if(request('precio_min') || request('precio_max'))
                      <span class="filter-tag">
                        Precio: ${{ number_format(request('precio_min', $precioMin), 0, ',', '.') }} - ${{ number_format(request('precio_max', $precioMax), 0, ',', '.') }}
                        <a href="{{ route('tienda.categorias', request()->except(['precio_min', 'precio_max', 'precio_min_input', 'precio_max_input'])) }}" class="filter-remove">
                          <i class="bi bi-x"></i>
                        </a>
                      </span>
                    @endif
                    
                    @if(request('rango_precio'))
                      @php
                        $rango = explode('-', request('rango_precio'));
                        $min = $rango[0];
                        $max = $rango[1] ?? 0;
                      @endphp
                      <span class="filter-tag">
                        @if($max == 0)
                          Más de ${{ number_format($min, 0, ',', '.') }}
                        @else
                          ${{ number_format($min, 0, ',', '.') }} - ${{ number_format($max, 0, ',', '.') }}
                        @endif
                        <a href="{{ route('tienda.categorias', request()->except('rango_precio')) }}" class="filter-remove">
                          <i class="bi bi-x"></i>
                        </a>
                      </span>
                    @endif
                    
                    <a href="{{ route('tienda.categorias') }}" class="clear-all-btn">Limpiar Todo</a>
                  </div>
                </div>
              </div>
            </div>
            @endif

          </div>

        </div>

      </section><!-- /Category Header Section -->

      <!-- Category Product List Section -->
      <section id="category-product-list" class="category-product-list section">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

          <!-- Vista Grid (por defecto) -->
          <div class="row g-4" id="productGrid">
            @forelse($productos as $index => $producto)
            @php
                // Buscar descuentos activos para este producto
                $descuentoActivo = null;
                $textoDescuento = null;
                $precioConDescuento = $producto->precio_actual;
                $montoDescuento = 0;

                if (isset($descuentosActivos)) {
                    foreach ($descuentosActivos as $desc) {
                        $aplica = false;

                        if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito') {
                            $aplica = true;
                        } elseif ($desc->aplica_a === 'producto' && in_array($producto->id, $desc->productos_aplicables ?? [])) {
                            $aplica = true;
                        } elseif ($desc->aplica_a === 'categoria' && in_array($producto->categoria_id, $desc->categorias_aplicables ?? [])) {
                            $aplica = true;
                        }

                        if ($aplica) {
                            $descuentoActivo = $desc;
                            if ($desc->tipo === 'porcentaje') {
                                $montoDescuento = ($producto->precio_actual * $desc->valor) / 100;
                                $textoDescuento = round($desc->valor) . '% OFF';
                            } else {
                                $montoDescuento = $desc->valor;
                                $textoDescuento = '$' . number_format($desc->valor, 0, ',', '.') . ' OFF';
                            }
                            $precioConDescuento = $producto->precio_actual - $montoDescuento;
                            break;
                        }
                    }
                }
                $stockInfo = $producto->getStockInfo();
            @endphp
            <!-- Product -->
            <div class="col-6 col-xl-4 product-item">
              <div class="product-card" data-aos="zoom-in" data-aos-delay="{{ $index * 50 }}">
                <div class="product-image">
                  <img src="{{ $producto->url_imagen_principal }}" class="main-image img-fluid" alt="{{ $producto->nombre }}">
                  @if($producto->imagenes->count() > 1)
                    <img src="{{ $producto->imagenes[1]->url }}" class="hover-image img-fluid" alt="{{ $producto->nombre }} - Vista 2">
                  @else
                    <img src="{{ $producto->url_imagen_principal }}" class="hover-image img-fluid" alt="{{ $producto->nombre }}">
                  @endif
                  <div class="product-overlay">
                    <div class="product-actions">
                      <a href="{{ route('tienda.producto', $producto->slug) }}"
                         class="action-btn" data-bs-toggle="tooltip" title="Ver Detalles">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if($producto->tiene_variantes)
                        <a href="{{ route('tienda.producto', $producto->slug) }}"
                           class="action-btn" data-bs-toggle="tooltip" title="Ver Opciones">
                          <i class="bi bi-cart-plus"></i>
                        </a>
                      @else
                        <button type="button" class="action-btn quick-add-btn"
                                data-producto-id="{{ $producto->id }}"
                                data-precio="{{ $producto->precio_actual }}"
                                data-bs-toggle="tooltip" title="Agregar al Carrito"
                                {{ (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado']) ? 'disabled' : '' }}>
                          <i class="bi bi-cart-plus"></i>
                        </button>
                      @endif
                    </div>
                  </div>
                  @if($descuentoActivo)
                    <div class="product-badge sale">{{ $textoDescuento }}</div>
                  @elseif($stockInfo['controlar_stock'] && !$stockInfo['permitir_venta_sin_stock'])
                    @if($stockInfo['stock_disponible'] <= 5 && $stockInfo['stock_disponible'] > 0)
                      <div class="product-badge new">¡Últimas unidades!</div>
                    @elseif($stockInfo['stock_disponible'] == 0)
                      <div class="product-badge sale">Sin Stock</div>
                    @endif
                  @endif
                </div>
                <div class="product-details">
                  <div class="product-category">{{ $producto->categoria->nombre }}</div>
                  <h4 class="product-title">
                    <a href="{{ route('tienda.producto', $producto->slug) }}">{{ $producto->nombre }}</a>
                  </h4>
                  <div class="product-meta">
                    @if($producto->precio_actual)
                      @if($descuentoActivo)
                        <div class="product-price">
                          <span class="text-decoration-line-through text-muted me-2">${{ number_format($producto->precio_actual, 0, ',', '.') }}</span>
                          <span class="text-danger fw-bold">${{ number_format($precioConDescuento, 0, ',', '.') }}</span>
                        </div>
                      @else
                        <div class="product-price">${{ number_format($producto->precio_actual, 0, ',', '.') }}</div>
                      @endif
                    @else
                      <div class="product-price text-muted">Precio no disponible</div>
                    @endif
                    @if(($producto->total_calificaciones ?? 0) > 0)
                    <div class="product-rating">
                      @php $promedio = $producto->promedio_calificaciones ?? 0; @endphp
                      @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($promedio))
                          <i class="bi bi-star-fill"></i>
                        @elseif($i - 0.5 <= $promedio)
                          <i class="bi bi-star-half"></i>
                        @else
                          <i class="bi bi-star"></i>
                        @endif
                      @endfor
                      {{ number_format($promedio, 1) }} <span>({{ $producto->total_calificaciones }})</span>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="col-12">
              <div class="alert alert-info text-center">
                <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                <p class="mb-0">No se encontraron productos con los filtros seleccionados.</p>
                <a href="{{ route('tienda.categorias') }}" class="btn btn-primary mt-3">
                  Ver todos los productos
                </a>
              </div>
            </div>
            @endforelse
          </div>

          <!-- Vista Lista (oculta por defecto) -->
          <div class="row d-none" id="productList">
            @forelse($productos as $producto)
            <div class="col-12 mb-4">
              <div class="product-list-item">
                <div class="row align-items-center">
                  <div class="col-md-3">
                    <a href="{{ route('tienda.producto', $producto->slug) }}">
                      <img src="{{ $producto->url_imagen_principal }}" class="img-fluid" alt="{{ $producto->nombre }}">
                    </a>
                  </div>
                  <div class="col-md-6">
                    <h4><a href="{{ route('tienda.producto', $producto->slug) }}">{{ $producto->nombre }}</a></h4>
                    <p class="text-muted mb-2">{{ $producto->categoria->nombre }}</p>
                    <p>{{ Str::limit($producto->descripcion, 150) }}</p>
                    @if(($producto->total_calificaciones ?? 0) > 0)
                    <div class="product-rating">
                      <div class="stars">
                        @php $promedio = $producto->promedio_calificaciones ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= round($promedio))
                            <i class="bi bi-star-fill"></i>
                          @elseif($i - 0.5 <= $promedio)
                            <i class="bi bi-star-half"></i>
                          @else
                            <i class="bi bi-star"></i>
                          @endif
                        @endfor
                      </div>
                      <span class="rating-count">({{ $producto->total_calificaciones }})</span>
                    </div>
                    @endif
                  </div>
                  <div class="col-md-3 text-md-end">
                    @if($producto->precio_actual)
                      <div class="product-price mb-2">${{ number_format($producto->precio_actual, 0, ',', '.') }}</div>
                    @else
                      <div class="product-price text-muted mb-2">Precio no disponible</div>
                    @endif
                    @if($producto->tiene_variantes)
                      <a href="{{ route('tienda.producto', $producto->slug) }}" class="btn btn-primary">
                        Ver Opciones
                      </a>
                    @else
                      @php $stockInfo = $producto->getStockInfo(); @endphp
                      <button class="btn btn-primary quick-add-btn" 
                              data-producto-id="{{ $producto->id }}"
                              data-precio="{{ $producto->precio_actual }}"
                              {{ (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado']) ? 'disabled' : '' }}>
                        {{ (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado']) ? 'Sin Stock' : 'Agregar al Carrito' }}
                      </button>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="col-12">
              <div class="alert alert-info text-center">
                <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                <p class="mb-0">No se encontraron productos con los filtros seleccionados.</p>
                <a href="{{ route('tienda.categorias') }}" class="btn btn-primary mt-3">
                  Ver todos los productos
                </a>
              </div>
            </div>
            @endforelse
          </div>

        </div>

      </section><!-- /Category Product List Section -->

      <!-- Category Pagination Section -->
      @if($productos->hasPages())
      <section id="category-pagination" class="category-pagination section">

        <div class="container">
          <nav class="d-flex justify-content-center" aria-label="Navegación de páginas">
            {{ $productos->withQueryString()->links('pagination::bootstrap-5') }}
          </nav>
        </div>

      </section><!-- /Category Pagination Section -->
      @endif

    </div>

  </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos adicionales para los filtros */
.filter-tag {
  display: inline-flex;
  align-items: center;
  background: #f0f0f0;
  padding: 5px 10px;
  border-radius: 20px;
  margin-right: 10px;
  margin-bottom: 5px;
  font-size: 14px;
}

.filter-remove {
  margin-left: 5px;
  color: #666;
  text-decoration: none;
}

.filter-remove:hover {
  color: #dc3545;
}

.clear-all-btn {
  display: inline-block;
  padding: 5px 15px;
  color: #dc3545;
  text-decoration: none;
  font-size: 14px;
}

.clear-all-btn:hover {
  text-decoration: underline;
}

.product-list-item {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.product-list-item:hover {
  transform: translateY(-5px);
}

/* Estilo para categoría activa */
.category-link.active {
  color: var(--accent-color);
  font-weight: 600;
}

/* Range slider personalizado */
.range-slider {
  position: relative;
  height: 5px;
  margin: 20px 0;
}

.slider-track {
  position: absolute;
  width: 100%;
  height: 5px;
  background: #ddd;
  border-radius: 5px;
}

.slider-progress {
  position: absolute;
  height: 5px;
  background: var(--accent-color);
  border-radius: 5px;
  left: 0;
  width: 100%;
}

.range-slider input[type="range"] {
  position: absolute;
  width: 100%;
  height: 5px;
  background: transparent;
  pointer-events: none;
  -webkit-appearance: none;
  z-index: 2;
}

.range-slider input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: var(--accent-color);
  pointer-events: auto;
  cursor: pointer;
  border: 2px solid #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.range-slider input[type="range"]::-moz-range-thumb {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: var(--accent-color);
  pointer-events: auto;
  cursor: pointer;
  border: 2px solid #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Fix para el segundo slider */
.range-slider .max-range {
  z-index: 3;
}

/* Botones de vista */
.view-btn {
  background: transparent;
  border: 1px solid #ddd;
  padding: 8px 12px;
  margin-right: 5px;
  border-radius: 4px;
  color: #666;
  transition: all 0.3s ease;
}

.view-btn:hover,
.view-btn.active {
  background: var(--accent-color, #007bff);
  border-color: var(--accent-color, #007bff);
  color: white;
}

.view-btn i {
  font-size: 16px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
  // Quick add to cart
  $('.quick-add-btn').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const btn = $(this);
    const productoId = btn.data('producto-id');
    const precio = btn.data('precio');
    
    if (!precio) {
      showToast('error', 'Este producto no tiene precio configurado');
      return;
    }
    
    const originalText = btn.html();
    btn.prop('disabled', true);
    btn.html('<span class="spinner-border spinner-border-sm"></span>');
    
    $.ajax({
      url: "{{ route('tienda.carrito.agregar') }}",
      method: 'POST',
      data: {
        producto_id: productoId,
        cantidad: 1
      },
      success: function(response) {
        showToast('success', 'Producto agregado al carrito');
        updateCartBadge(response.total_items);
        btn.html('<i class="bi bi-check"></i> Agregado');
        setTimeout(() => {
          btn.prop('disabled', false);
          btn.html(originalText);
        }, 1500);
      },
      error: function(xhr) {
        const error = xhr.responseJSON?.error || 'Error al agregar al carrito';
        showToast('error', error);
        btn.prop('disabled', false);
        btn.html(originalText);
      }
    });
  });

  // Cambiar vista grid/lista
  $('.view-btn').on('click', function() {
    const view = $(this).data('view');
    $('.view-btn').removeClass('active');
    $(this).addClass('active');
    
    if (view === 'list') {
      $('#productGrid').addClass('d-none');
      $('#productList').removeClass('d-none');
    } else {
      $('#productGrid').removeClass('d-none');
      $('#productList').addClass('d-none');
    }
  });

  // Range slider funcionalidad
  const minRange = $('.min-range');
  const maxRange = $('.max-range');
  const minInput = $('.min-price-input');
  const maxInput = $('.max-price-input');
  const progress = $('.slider-progress');
  const minPrice = $('.current-range .min-price');
  const maxPrice = $('.current-range .max-price');

  function updateSlider() {
    const min = parseInt(minRange.val());
    const max = parseInt(maxRange.val());
    const rangeMin = parseInt(minRange.attr('min'));
    const rangeMax = parseInt(minRange.attr('max'));
    
    // Calcular porcentajes basados en el rango real
    const minPercent = ((min - rangeMin) / (rangeMax - rangeMin)) * 100;
    const maxPercent = ((max - rangeMin) / (rangeMax - rangeMin)) * 100;
    
    progress.css({
      'left': minPercent + '%',
      'width': (maxPercent - minPercent) + '%'
    });
    
    minInput.val(min);
    maxInput.val(max);
    minPrice.text('$' + min.toLocaleString('es-CO'));
    maxPrice.text('$' + max.toLocaleString('es-CO'));
  }

  minRange.on('input', function() {
    const min = parseInt($(this).val());
    const max = parseInt(maxRange.val());
    if (min > max) {
      $(this).val(max);
    }
    updateSlider();
  });

  maxRange.on('input', function() {
    const min = parseInt(minRange.val());
    const max = parseInt($(this).val());
    if (max < min) {
      $(this).val(min);
    }
    updateSlider();
  });

  minInput.on('input', function() {
    const val = parseInt($(this).val()) || parseInt(minRange.attr('min'));
    minRange.val(val).trigger('input');
  });

  maxInput.on('input', function() {
    const val = parseInt($(this).val()) || parseInt(maxRange.attr('max'));
    maxRange.val(val).trigger('input');
  });

  // Sincronizar inputs al cambiar el range
  minRange.on('change', function() {
    $(this).attr('name', 'precio_min');
  });

  maxRange.on('change', function() {
    $(this).attr('name', 'precio_max');
  });

  // Inicializar slider
  updateSlider();

  // Show toast notification
  function showToast(type, message) {
    const toastEl = document.getElementById('cartToast');
    const toast = new bootstrap.Toast(toastEl);
    
    $('.toast-body').text(message);
    if (type === 'error') {
      $('.toast-header i').removeClass('text-success').addClass('text-danger');
      $('.toast-header i').removeClass('bi-check-circle-fill').addClass('bi-exclamation-circle-fill');
    } else {
      $('.toast-header i').removeClass('text-danger').addClass('text-success');
      $('.toast-header i').removeClass('bi-exclamation-circle-fill').addClass('bi-check-circle-fill');
    }
    
    toast.show();
  }

  // Update cart badge
  function updateCartBadge(count) {
    const cartBtn = $('#cart-header-btn');
    if (count > 0) {
      if (cartBtn.find('.cart-badge').length) {
        cartBtn.find('.cart-badge').text(count);
      } else {
        cartBtn.append('<span class="badge cart-badge">' + count + '</span>');
      }
    } else {
      cartBtn.find('.cart-badge').remove();
    }
  }

  // Inicializar tooltips si existen
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
});
</script>
@endpush