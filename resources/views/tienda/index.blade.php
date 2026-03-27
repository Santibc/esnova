@extends('tienda.layout')

@section('title', $empresa->nombre . ' - Tienda Online')
@section('nav-inicio', 'active')

@section('content')
   @if($empresa->carruselImagenesActivas->count() > 0)
    <section id="portada" class="section p-0">
      <div id="heroCarousel" 
           class="carousel slide carousel-fade hero-carousel"
           data-bs-ride="carousel" 
           data-bs-interval="4500" 
           data-bs-pause="hover">

        @if($empresa->carruselImagenesActivas->count() > 1)
          <div class="carousel-indicators">
            @foreach($empresa->carruselImagenesActivas as $index => $img)
              <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" 
                      class="{{ $index === 0 ? 'active' : '' }}"
                      aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                      aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
          </div>
        @endif

        <div class="carousel-inner">
          @foreach($empresa->carruselImagenesActivas as $index => $imagen)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" 
                 style="background-image: url('{{ $imagen->imagen_url }}');">
              <div class="hero-overlay"></div>

              <div class="container h-100">
                <div class="d-flex align-items-center justify-content-center h-100">
                  <div class="hero-caption text-center shadow rounded-4 p-4 p-md-5">
                    @if($imagen->titulo)
                      <h2 class="fw-bold mb-2 hero-title">{{ $imagen->titulo }}</h2>
                    @endif
                    @if($imagen->descripcion)
                      <p class="mb-3 hero-desc">{{ $imagen->descripcion }}</p>
                    @endif
                    @if($imagen->link)
                      <a href="{{ $imagen->link }}" class="btn btn-primary btn-lg">Ver más</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        @if($empresa->carruselImagenesActivas->count() > 1)
          <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        @endif
      </div>
    </section>
    @endif

    <!-- Banner Promocional - Carrusel -->
    @if($empresa->bannerSlidesActivos->count() > 0)
    <section class="promo-banner section" data-aos="fade-up">
      <div id="promoBannerCarousel" class="carousel slide carousel-fade"
           data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover">

        @if($empresa->bannerSlidesActivos->count() > 1)
          <div class="carousel-indicators promo-indicators">
            @foreach($empresa->bannerSlidesActivos as $idx => $s)
              <button type="button" data-bs-target="#promoBannerCarousel" data-bs-slide-to="{{ $idx }}"
                      class="{{ $idx === 0 ? 'active' : '' }}"
                      aria-current="{{ $idx === 0 ? 'true' : 'false' }}"
                      aria-label="Slide {{ $idx + 1 }}"></button>
            @endforeach
          </div>
        @endif

        <div class="carousel-inner">
          @foreach($empresa->bannerSlidesActivos as $idx => $slide)
            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
              <div class="promo-banner-bg" style="background-image: url('{{ $slide->imagen_url }}');">
                <div class="promo-banner-overlay"></div>
                <div class="container position-relative" style="z-index: 2;">
                  <div class="row align-items-center min-vh-25">
                    <div class="col-lg-7 text-white py-5">
                      <span class="promo-badge">
                        <i class="bi bi-stars me-1"></i> {{ $empresa->nombre }}
                      </span>
                      <h2 class="promo-title">
                        {{ $slide->titulo ?? 'Descubre lo mejor para ti' }}
                      </h2>
                      <p class="promo-desc">
                        {{ $slide->subtitulo ?? $empresa->descripcion ?? 'Explora nuestra colección exclusiva de productos seleccionados con la mejor calidad y los mejores precios.' }}
                      </p>
                      <div class="promo-actions">
                        <a href="{{ $slide->btn1_link ?? '#productos' }}" class="promo-btn-primary">
                          <i class="bi bi-bag-check me-2"></i>{{ $slide->btn1_texto ?? 'Ver Productos' }}
                        </a>
                        <a href="{{ $slide->btn2_link ?? '#categorias' }}" class="promo-btn-secondary">
                          {{ $slide->btn2_texto ?? 'Explorar Categorías' }} <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        @if($empresa->bannerSlidesActivos->count() > 1)
          <button class="carousel-control-prev" type="button" data-bs-target="#promoBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#promoBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        @endif
      </div>
    </section>
    @else
    {{-- Fallback: banner por defecto si no hay slides --}}
    <section class="promo-banner section" data-aos="fade-up">
      <div class="promo-banner-bg" style="background-image: url('{{ $empresa->imagen_portada_url }}');">
        <div class="promo-banner-overlay"></div>
        <div class="container position-relative" style="z-index: 2;">
          <div class="row align-items-center min-vh-25">
            <div class="col-lg-7 text-white py-5">
              <span class="promo-badge">
                <i class="bi bi-stars me-1"></i> {{ $empresa->nombre }}
              </span>
              <h2 class="promo-title">Descubre lo mejor para ti</h2>
              <p class="promo-desc">
                {{ $empresa->descripcion ?? 'Explora nuestra colección exclusiva de productos seleccionados con la mejor calidad y los mejores precios.' }}
              </p>
              <div class="promo-actions">
                <a href="#productos" class="promo-btn-primary">
                  <i class="bi bi-bag-check me-2"></i>Ver Productos
                </a>
                <a href="#categorias" class="promo-btn-secondary">
                  Explorar Categorías <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

    <section id="hero" class="hero section">
      <div class="hero-container">
        <div class="hero-content">
          <div class="content-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h1 class="hero-title">{{ $empresa->nombre }}</h1>
            <p class="hero-description">{{ $empresa->descripcion ?? 'Tu tienda online de confianza.' }}</p>
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
              <a href="#productos" class="btn-primary">Comprar ahora</a>
              <a href="#categorias" class="btn-secondary">Categorías</a>
            </div>
            <div class="features-list" data-aos="fade-up" data-aos-delay="300">
              <div class="feature-item">
                <i class="bi bi-truck"></i>
                <span>Envío</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-award"></i>
                <span>Certificado</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-headset"></i>
                <span>Llamanos</span>
              </div>
            </div>
          </div>
        </div>

        {{-- HERO VISUALS: primeros 3 productos si existen --}}
        @if(($productos->count() ?? 0) > 0)
          @php
            // Si $productos es un paginator, obtén la colección; si no, úsala tal cual
            $base = method_exists($productos, 'getCollection') ? $productos->getCollection() : $productos;
            $destacados = $base->take(3)->values();
          @endphp

          <div class="hero-visuals">
            <div class="product-showcase" data-aos="fade-left" data-aos-delay="200">
              {{-- Producto destacado (el primero) --}}
              @if(isset($destacados[0]))
                @php
                    // Buscar descuentos para producto destacado 0
                    $descHero0 = null;
                    $precioHero0 = $destacados[0]->precio_actual;
                    $precioDescHero0 = $precioHero0;

                    if (isset($descuentosActivos)) {
                        foreach ($descuentosActivos as $desc) {
                            if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito' ||
                                ($desc->aplica_a === 'producto' && in_array($destacados[0]->id, $desc->productos_aplicables ?? [])) ||
                                ($desc->aplica_a === 'categoria' && in_array($destacados[0]->categoria_id, $desc->categorias_aplicables ?? []))) {
                                $descHero0 = $desc;
                                if ($desc->tipo === 'porcentaje') {
                                    $precioDescHero0 = $precioHero0 - (($precioHero0 * $desc->valor) / 100);
                                } else {
                                    $precioDescHero0 = $precioHero0 - $desc->valor;
                                }
                                break;
                            }
                        }
                    }
                @endphp
                <div class="product-card featured">
                  <a href="{{ route('tienda.producto', $destacados[0]->slug) }}">
                    <img
                      src="{{ $destacados[0]->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}"
                      alt="{{ $destacados[0]->nombre }}"
                      class="img-fluid">
                  </a>
                  @if($descHero0)
                    <div class="product-badge sale-badge">
                      {{ $descHero0->tipo === 'porcentaje' ? round($descHero0->valor) . '% OFF' : '$' . number_format($descHero0->valor, 0, ',', '.') . ' OFF' }}
                    </div>
                  @else
                    <div class="product-badge">Destacado</div>
                  @endif
                  <div class="product-info">
                    <h4>
                      <a href="{{ route('tienda.producto', $destacados[0]->slug) }}">
                        {{ $destacados[0]->nombre }}
                      </a>
                    </h4>
                    <div class="price">
                      @if($destacados[0]->precio_actual)
                        @if($descHero0)
                          <span class="regular-price text-decoration-line-through text-muted me-2">
                            ${{ number_format($precioHero0, 0, ',', '.') }}
                          </span>
                          <span class="sale-price text-danger fw-bold">
                            ${{ number_format($precioDescHero0, 0, ',', '.') }}
                          </span>
                        @else
                          <span class="sale-price">
                            ${{ number_format($destacados[0]->precio_actual, 0, ',', '.') }}
                          </span>
                        @endif
                      @else
                        <span class="text-muted">Precio no disponible</span>
                      @endif
                    </div>
                  </div>
                </div>
              @endif

              {{-- Grid de 2 minis (segundo y tercero si existen) --}}
              <div class="product-grid">
                @if(isset($destacados[1]))
                  @php
                    // Buscar descuentos para producto destacado 1
                    $descHero1 = null;
                    $precioHero1 = $destacados[1]->precio_actual;
                    $precioDescHero1 = $precioHero1;

                    if (isset($descuentosActivos) && $precioHero1) {
                        foreach ($descuentosActivos as $desc) {
                            if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito' ||
                                ($desc->aplica_a === 'producto' && in_array($destacados[1]->id, $desc->productos_aplicables ?? [])) ||
                                ($desc->aplica_a === 'categoria' && in_array($destacados[1]->categoria_id, $desc->categorias_aplicables ?? []))) {
                                $descHero1 = $desc;
                                if ($desc->tipo === 'porcentaje') {
                                    $precioDescHero1 = $precioHero1 - (($precioHero1 * $desc->valor) / 100);
                                } else {
                                    $precioDescHero1 = $precioHero1 - $desc->valor;
                                }
                                break;
                            }
                        }
                    }
                  @endphp
                  <div class="product-mini" data-aos="zoom-in" data-aos-delay="400">
                    <a href="{{ route('tienda.producto', $destacados[1]->slug) }}">
                      <img
                        src="{{ $destacados[1]->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}"
                        alt="{{ $destacados[1]->nombre }}"
                        class="img-fluid">
                    </a>
                    @if($destacados[1]->precio_actual)
                      <span class="mini-price">
                        @if($descHero1)
                          <span class="text-decoration-line-through text-muted d-block" style="font-size: 0.85em;">
                            ${{ number_format($precioHero1, 0, ',', '.') }}
                          </span>
                          <span class="text-danger fw-bold">
                            ${{ number_format($precioDescHero1, 0, ',', '.') }}
                          </span>
                        @else
                          ${{ number_format($destacados[1]->precio_actual, 0, ',', '.') }}
                        @endif
                      </span>
                    @endif
                  </div>
                @endif

                @if(isset($destacados[2]))
                  @php
                    // Buscar descuentos para producto destacado 2
                    $descHero2 = null;
                    $precioHero2 = $destacados[2]->precio_actual;
                    $precioDescHero2 = $precioHero2;

                    if (isset($descuentosActivos) && $precioHero2) {
                        foreach ($descuentosActivos as $desc) {
                            if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito' ||
                                ($desc->aplica_a === 'producto' && in_array($destacados[2]->id, $desc->productos_aplicables ?? [])) ||
                                ($desc->aplica_a === 'categoria' && in_array($destacados[2]->categoria_id, $desc->categorias_aplicables ?? []))) {
                                $descHero2 = $desc;
                                if ($desc->tipo === 'porcentaje') {
                                    $precioDescHero2 = $precioHero2 - (($precioHero2 * $desc->valor) / 100);
                                } else {
                                    $precioDescHero2 = $precioHero2 - $desc->valor;
                                }
                                break;
                            }
                        }
                    }
                  @endphp
                  <div class="product-mini" data-aos="zoom-in" data-aos-delay="500">
                    <a href="{{ route('tienda.producto', $destacados[2]->slug) }}">
                      <img
                        src="{{ $destacados[2]->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}"
                        alt="{{ $destacados[2]->nombre }}"
                        class="img-fluid">
                    </a>
                    @if($destacados[2]->precio_actual)
                      <span class="mini-price">
                        @if($descHero2)
                          <span class="text-decoration-line-through text-muted d-block" style="font-size: 0.85em;">
                            ${{ number_format($precioHero2, 0, ',', '.') }}
                          </span>
                          <span class="text-danger fw-bold">
                            ${{ number_format($precioDescHero2, 0, ',', '.') }}
                          </span>
                        @else
                          ${{ number_format($destacados[2]->precio_actual, 0, ',', '.') }}
                        @endif
                      </span>
                    @endif
                  </div>
                @endif
              </div>
            </div>

            {{-- Iconos flotantes (solo si hay productos) --}}
            <div class="floating-elements">
              <div class="floating-icon cart" data-aos="fade-up" data-aos-delay="600">
                <i class="bi bi-cart3"></i>
                <span class="notification-dot">3</span>
              </div>
              <div class="floating-icon wishlist" data-aos="fade-up" data-aos-delay="700">
                <i class="bi bi-heart"></i>
              </div>
              <div class="floating-icon search" data-aos="fade-up" data-aos-delay="800">
                <i class="bi bi-search"></i>
              </div>
            </div>
          </div>
        @endif
      </div>
    </section><!-- /Hero Section -->

    <!-- Promo Cards Section - Categorías -->
    <section id="categorias" class="promo-cards section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        @php
          // Ordenamos por 'orden' y tomamos máximo 5 para este bloque
          $cats = $categorias->sortBy('orden')->values()->take(5);
          $featured = $cats->first();
          $rest = $cats->slice(1);

          // Clases de color como el template original
          $colorClasses = ['cat-men','cat-kids','cat-cosmetics','cat-accessories'];
        @endphp

        @if($cats->isEmpty())
          <div class="row">
            <div class="col-12 text-center">
              <p>No hay categorías disponibles en este momento.</p>
            </div>
          </div>
        @else
          <div class="row gy-4">

            {{-- Columna izquierda: categoría destacada --}}
            <div class="col-lg-6">
              <div class="category-featured" data-aos="fade-right" data-aos-delay="200">
                @if($featured && $featured->imagen)
                  <div class="category-image">
                    <img
                      src="{{ asset($featured->imagen) }}"
                      alt="{{ $featured->nombre }}"
                      class="img-fluid"
                      loading="lazy">
                  </div>
                @endif
                <div class="category-content {{ !($featured && $featured->imagen) ? 'no-image' : '' }}">
                  <span class="category-tag">Destacado</span>
                  <h2>{{ $featured->nombre }}</h2>
                  <p>{{ $featured->descripcion ?? 'Descubre nuestra selección de productos en esta categoría.' }}</p>
                  <a href="{{ route('tienda.categorias', ['categoria' => $featured->id]) }}" class="btn-shop">
                    Explorar Categoría <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>

            {{-- Columna derecha: hasta 4 categorías en grid 2x2 --}}
            <div class="col-lg-6">
              <div class="row gy-4">
                @foreach($rest as $i => $categoria)
                  @php
                    $catColor = $colorClasses[$i % count($colorClasses)];
                    $delay = 300 + ($i * 100);
                  @endphp
                  <div class="col-xl-6">
                    <div class="category-card {{ $catColor }} {{ !$categoria->imagen ? 'no-image' : '' }}" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                      @if($categoria->imagen)
                        <div class="category-image">
                          <img
                            src="{{ asset($categoria->imagen) }}"
                            alt="{{ $categoria->nombre }}"
                            class="img-fluid"
                            loading="lazy">
                        </div>
                      @endif
                      <div class="category-content">
                        <h4>{{ $categoria->nombre }}</h4>
                        <p>{{ $categoria->productos_count ?? 0 }} productos</p>
                        <a href="{{ route('tienda.categorias', ['categoria' => $categoria->id]) }}" class="card-link">
                          Ver Productos <i class="bi bi-arrow-right"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        @endif
      </div>
    </section>

    <!-- Products Section -->
    <section id="productos" class="best-sellers section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Productos</h2>
        <p>Explora nuestra selección de productos de alta calidad</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-5">

          @forelse($productos as $producto)
          @php
              // Buscar descuentos activos para este producto
              $descuentoProducto = null;
              $textoDescuentoProducto = null;
              $precioActualProducto = $producto->precio_actual;
              $precioConDescuentoProducto = $precioActualProducto;
              $montoDescuentoProducto = 0;

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
                          $descuentoProducto = $desc;
                          if ($desc->tipo === 'porcentaje') {
                              $montoDescuentoProducto = ($precioActualProducto * $desc->valor) / 100;
                              $textoDescuentoProducto = round($desc->valor) . '% OFF';
                          } else {
                              $montoDescuentoProducto = $desc->valor;
                              $textoDescuentoProducto = '$' . number_format($desc->valor, 0, ',', '.') . ' OFF';
                          }
                          $precioConDescuentoProducto = $precioActualProducto - $montoDescuentoProducto;
                          break;
                      }
                  }
              }
              $stockInfo = $producto->getStockInfo();
          @endphp
          <div class="col-lg-3 col-md-6">
            <div class="product-item product-card-clickable" data-href="{{ route('tienda.producto', $producto->slug) }}" style="cursor: pointer;">
              <div class="product-image">
                @if($descuentoProducto)
                  <div class="product-badge sale-badge">{{ $textoDescuentoProducto }}</div>
                @elseif($stockInfo['controlar_stock'] && !$stockInfo['permitir_venta_sin_stock'])
                  @if($stockInfo['stock_disponible'] <= 5 && $stockInfo['stock_disponible'] > 0)
                    <div class="product-badge">¡Últimas unidades!</div>
                  @elseif($stockInfo['stock_disponible'] == 0)
                    <div class="product-badge sale-badge">Sin Stock</div>
                  @endif
                @endif
                <img src="{{ $producto->url_imagen_principal }}" alt="{{ $producto->nombre }}" class="img-fluid" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn" onclick="event.stopPropagation();">
                    <i class="bi bi-heart"></i>
                  </button>
                  <button class="action-btn compare-btn" onclick="event.stopPropagation();">
                    <i class="bi bi-arrow-left-right"></i>
                  </button>
                  <a href="{{ route('tienda.producto', $producto->slug) }}" class="action-btn quickview-btn" onclick="event.stopPropagation();">
                    <i class="bi bi-zoom-in"></i>
                  </a>
                </div>
                @if($producto->tiene_variantes)
                  <a href="{{ route('tienda.producto', $producto->slug) }}" class="cart-btn" onclick="event.stopPropagation();">Ver Opciones</a>
                @else
                  <button class="cart-btn quick-add-btn"
                          data-producto-id="{{ $producto->id }}"
                          data-precio="{{ $producto->precio_actual }}"
                          onclick="event.stopPropagation();"
                          {{ (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado']) ? 'disabled' : '' }}>
                    {{ (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado']) ? 'Sin Stock' : 'Agregar al Carrito' }}
                  </button>
                @endif
              </div>
              <div class="product-info">
                <div class="product-category">{{ $producto->categoria->nombre }}</div>
                <h4 class="product-name">
                  <a href="{{ route('tienda.producto', $producto->slug) }}" onclick="event.stopPropagation();">{{ $producto->nombre }}</a>
                </h4>
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
                @if($producto->precio_actual)
                  @if($descuentoProducto)
                    <div class="product-price">
                      <span class="text-decoration-line-through text-muted me-2">${{ number_format($precioActualProducto, 0, ',', '.') }}</span>
                      <span class="text-danger fw-bold">${{ number_format($precioConDescuentoProducto, 0, ',', '.') }}</span>
                    </div>
                  @else
                    <div class="product-price">${{ number_format($producto->precio_actual, 0, ',', '.') }}</div>
                  @endif
                @else
                  <div class="product-price text-muted">Precio no disponible</div>
                @endif
              </div>
            </div>
          </div>
          @empty
          <div class="col-12">
            <div class="alert alert-info text-center">
              <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
              <p class="mb-0">No se encontraron productos.</p>
            </div>
          </div>
          @endforelse

        </div>

        <!-- Pagination -->
        @if($productos->hasPages())
        <div class="mt-5 d-flex justify-content-center">
          {{ $productos->links('pagination::bootstrap-5') }}
        </div>
        @endif

      </div>

    </section>

    <!-- Ofertas Section - Productos con Descuentos -->
    @if(isset($productosConDescuento) && $productosConDescuento->count() > 0)
    <section id="ofertas" class="best-sellers section bg-light">
      <div class="container section-title" data-aos="fade-up">
        <h2>Ofertas Especiales</h2>
        <p>Aprovecha nuestros productos con descuento</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-5">
          @foreach($productosConDescuento as $oferta)
          @php
              $descuento = $oferta->descuento_info;
              $precioOriginal = $oferta->precio_actual ? $oferta->precio_actual->precio : 0;
              $montoDescuento = 0;
              $precioConDescuento = $precioOriginal;

              if ($descuento) {
                  if ($descuento->tipo === 'porcentaje') {
                      $montoDescuento = ($precioOriginal * $descuento->valor) / 100;
                      $textoDescuento = round($descuento->valor) . '% OFF';
                  } else {
                      $montoDescuento = $descuento->valor;
                      $textoDescuento = '$' . number_format($descuento->valor, 0, ',', '.') . ' OFF';
                  }
                  $precioConDescuento = $precioOriginal - $montoDescuento;
              }
          @endphp
          <div class="col-lg-3 col-md-6">
            <div class="product-item">
              <div class="product-image">
                @if($descuento)
                  <div class="product-badge sale-badge">{{ $textoDescuento }}</div>
                @endif
                <img src="{{ $oferta->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}"
                     alt="{{ $oferta->nombre }}"
                     class="img-fluid"
                     loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                  </button>
                  <a href="{{ route('tienda.producto', $oferta->slug) }}" class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                  </a>
                </div>
                <a href="{{ route('tienda.producto', $oferta->slug) }}" class="cart-btn">Ver Oferta</a>
              </div>
              <div class="product-info">
                <div class="product-category">{{ $oferta->categoria->nombre ?? 'Producto' }}</div>
                <h4 class="product-name">
                  <a href="{{ route('tienda.producto', $oferta->slug) }}">{{ $oferta->nombre }}</a>
                </h4>
                @if($oferta->precio_actual)
                  <div class="product-price">
                    @if($montoDescuento > 0)
                      <span class="text-decoration-line-through text-muted me-2">${{ number_format($precioOriginal, 0, ',', '.') }}</span>
                      <span class="text-danger fw-bold">${{ number_format($precioConDescuento, 0, ',', '.') }}</span>
                    @else
                      ${{ number_format($precioOriginal, 0, ',', '.') }}
                    @endif
                  </div>
                @else
                  <div class="product-price text-muted">Precio no disponible</div>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

@endsection

@push('styles')
<style>
/* ==========================================
   Banner Promocional
   ========================================== */
.promo-banner {
  padding: 0;
  margin: 0;
}
.promo-banner-bg {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
  min-height: 340px;
  display: flex;
  align-items: center;
}
.promo-banner-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(135deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.35) 60%, rgba(0,0,0,0.15) 100%);
}
.promo-badge {
  display: inline-block;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.25);
  color: #fff;
  font-size: 0.8rem;
  font-weight: 600;
  padding: 0.4em 1.1em;
  border-radius: 50px;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  margin-bottom: 1rem;
}
.promo-title {
  font-size: 2.6rem;
  font-weight: 800;
  line-height: 1.15;
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
  text-shadow: 0 2px 20px rgba(0,0,0,0.3);
}
.promo-desc {
  font-size: 1.1rem;
  opacity: 0.9;
  line-height: 1.7;
  max-width: 520px;
  margin-bottom: 1.75rem;
  text-shadow: 0 1px 8px rgba(0,0,0,0.2);
}
.promo-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}
.promo-btn-primary {
  display: inline-flex;
  align-items: center;
  padding: 0.75rem 1.75rem;
  background: #fff;
  color: #1a202c;
  font-weight: 700;
  font-size: 0.95rem;
  border-radius: 50px;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.promo-btn-primary:hover {
  background: var(--accent-color, #e84545);
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
}
.promo-btn-secondary {
  display: inline-flex;
  align-items: center;
  padding: 0.75rem 1.75rem;
  background: transparent;
  color: #fff;
  font-weight: 600;
  font-size: 0.95rem;
  border-radius: 50px;
  border: 2px solid rgba(255,255,255,0.5);
  text-decoration: none;
  transition: all 0.3s ease;
}
.promo-btn-secondary:hover {
  background: rgba(255,255,255,0.15);
  border-color: #fff;
  color: #fff;
  transform: translateY(-2px);
}

@media (max-width: 991.98px) {
  .promo-banner-bg {
    min-height: 300px;
  }
  .promo-title {
    font-size: 2rem;
  }
}
@media (max-width: 767.98px) {
  .promo-banner-bg {
    min-height: 280px;
  }
  .promo-title {
    font-size: 1.6rem;
  }
  .promo-desc {
    font-size: 0.95rem;
  }
  .promo-actions {
    flex-direction: column;
    gap: 0.75rem;
  }
  .promo-btn-primary,
  .promo-btn-secondary {
    justify-content: center;
    text-align: center;
  }
}

/* Carousel controls para banner */
#promoBannerCarousel .carousel-control-prev,
#promoBannerCarousel .carousel-control-next {
  width: 5%;
  opacity: 0;
  transition: opacity 0.3s ease;
}
#promoBannerCarousel:hover .carousel-control-prev,
#promoBannerCarousel:hover .carousel-control-next {
  opacity: 1;
}
.promo-indicators {
  bottom: 15px;
}
.promo-indicators button {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin: 0 4px;
  opacity: 0.6;
}
.promo-indicators button.active {
  opacity: 1;
  background-color: #fff;
}

/* Ajustes para las imágenes de categorías */
.promo-cards .category-featured {
  min-height: 400px;
}

.promo-cards .category-featured .category-image {
  position: absolute;
  top: 0;
  right: 0;
  width: 55%;
  height: 100%;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.promo-cards .category-featured .category-image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: center;
  transition: transform 0.6s ease;
  padding: 20px;
}

.promo-cards .category-featured .category-content.no-image {
  max-width: 100%;
  padding: 50px 60px;
  text-align: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8f5ff 0%, #f0ebff 100%);
}

.promo-cards .category-card {
  height: 200px;
}

.promo-cards .category-card .category-image {
  position: absolute;
  top: 0;
  right: 0;
  width: 45%;
  height: 100%;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.promo-cards .category-card .category-image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 15px;
  transition: transform 0.6s ease;
}

.promo-cards .category-card.no-image {
  text-align: center;
}

.promo-cards .category-card.no-image .category-content {
  width: 100%;
  text-align: center;
  padding: 30px 20px;
}

.promo-cards .category-featured:hover .category-image img,
.promo-cards .category-card:hover .category-image img {
  transform: scale(1.05);
}

@media (max-width: 991.98px) {
  .promo-cards .category-featured {
    height: 380px;
  }
  
  .promo-cards .category-featured .category-image img {
    padding: 15px;
  }
}

@media (max-width: 767.98px) {
  .promo-cards .category-featured {
    height: auto;
    min-height: 300px;
  }

  .promo-cards .category-featured .category-image {
    position: relative;
    width: 100%;
    height: 200px;
    padding: 20px;
  }

  .promo-cards .category-featured .category-content {
    max-width: 100%;
    padding: 30px;
  }

  .promo-cards .category-card {
    height: 180px;
  }

  .promo-cards .category-card .category-image {
    width: 40%;
  }

  .promo-cards .category-card .category-image img {
    padding: 10px;
  }

  .promo-cards .category-card .category-content {
    width: 60%;
    padding: 20px;
  }
}

@media (max-width: 575.98px) {
  .promo-cards .category-card {
    height: 160px;
  }

  .promo-cards .category-card .category-content {
    width: 65%;
  }
}
</style>
@endpush

@push('scripts')
<script>
  $(document).ready(function() {
    // Make entire product card clickable
    $('.product-card-clickable').on('click', function(e) {
      // Only navigate if the click wasn't on an interactive element
      if (!$(e.target).closest('button, a, .cart-btn, .action-btn').length) {
        window.location.href = $(this).data('href');
      }
    });

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
            btn.html('Agregar al Carrito');
          }, 1500);
        },
        error: function(xhr) {
          const error = xhr.responseJSON?.error || 'Error al agregar al carrito';
          showToast('error', error);
          btn.prop('disabled', false);
          btn.html('Agregar al Carrito');
        }
      });
    });

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
  });
</script>
@endpush