@extends('tienda.layout')

@section('title', $producto->nombre . ' - ' . $empresa->nombre)
@section('description', $producto->descripcion)
@section('body-class', 'product-details-page')

@push('styles')
<style>
  /* Estilos para botones de reaccion */
  .reaction-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 4px 4px 10px rgba(0,0,0,0.15), -2px -2px 6px rgba(255,255,255,0.9) !important;
  }
  .reaction-btn:active {
    transform: translateY(0) scale(0.98);
  }
  .reaction-btn.active {
    background: linear-gradient(145deg, #667eea, #764ba2) !important;
    color: white;
  }
  .reaction-btn.active .reaction-count {
    color: white !important;
  }

  /* Boton responder */
  .action-btn-reply:hover {
    background: #667eea !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
  }

  /* Botones de compartir en resenas */
  .share-buttons button:hover {
    transform: translateY(-3px) scale(1.1);
  }
  .share-buttons button:active {
    transform: translateY(0) scale(0.95);
  }

  /* Botones de compartir producto */
  .product-share button:hover {
    transform: translateY(-3px) scale(1.08);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
  }
  .product-share button:active {
    transform: translateY(0) scale(0.95);
  }

  /* Animacion suave para todos los botones */
  .reaction-btn,
  .action-btn-reply,
  .share-buttons button,
  .product-share button {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
  }

  /* Formulario de respuesta */
  .respuesta-form {
    animation: slideDown 0.3s ease-out;
  }
  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ===== MÓVILES - Galería compacta y sin zoom ===== */
  @media (max-width: 767.98px) {
    /* Page Title más compacto */
    .page-title {
      padding: 1rem 0 !important;
    }
    .page-title h1 {
      font-size: 1.1rem !important;
      margin-bottom: 0.5rem !important;
    }
    .page-title .breadcrumbs {
      font-size: 0.75rem;
    }
    
    /* Galería de producto compacta */
    .product-gallery {
      margin-bottom: 1rem;
    }
    .product-gallery .main-showcase {
      max-height: 250px !important;
      margin-bottom: 0.5rem;
    }
    .product-gallery .image-zoom-container {
      max-height: 250px !important;
      height: 250px !important;
    }
    .product-gallery .main-product-image,
    .product-gallery #main-product-image {
      max-height: 250px !important;
      height: 100% !important;
      width: 100% !important;
      object-fit: contain !important;
    }
    
    /* Thumbnails pequeños */
    .product-gallery .thumbnail-grid {
      gap: 0.35rem !important;
      margin-top: 0.5rem !important;
      justify-content: center;
    }
    .product-gallery .thumbnail-wrapper,
    .product-gallery .thumbnail-item {
      width: 45px !important;
      height: 45px !important;
      min-width: 45px !important;
      max-width: 45px !important;
    }
    .product-gallery .thumbnail-wrapper img {
      width: 100% !important;
      height: 100% !important;
      object-fit: cover !important;
    }
    
    /* Flechas de navegación más pequeñas */
    .product-gallery .image-navigation .nav-arrow,
    .product-gallery .image-nav-btn {
      width: 30px !important;
      height: 30px !important;
      font-size: 0.875rem !important;
    }
    
    /* Título del producto más pequeño */
    .product-details .product-name {
      font-size: 1.25rem !important;
      line-height: 1.3 !important;
      margin-bottom: 0.75rem !important;
    }
    
    /* Precio más compacto */
    .product-details .pricing-section {
      margin-bottom: 0.75rem !important;
    }
    .product-details .sale-price {
      font-size: 1.5rem !important;
    }
    .product-details .regular-price {
      font-size: 1rem !important;
    }
    
    /* Rating compacto */
    .product-rating-display {
      margin-top: 0.5rem !important;
    }
    .product-rating-display .bi {
      font-size: 0.95rem !important;
    }
    
    /* Stock info compacto */
    .availability-status {
      margin: 0.75rem 0 !important;
    }
    
    /* Variantes compactas */
    .variant-section {
      margin: 0.75rem 0 !important;
    }
    .variant-section .variant-label {
      font-size: 0.875rem !important;
      margin-bottom: 0.5rem !important;
    }
    .variant-section .variant-option {
      padding: 0.35rem 0.75rem !important;
      font-size: 0.8rem !important;
    }
    
    /* Botones de acción compactos */
    .purchase-section {
      margin-top: 1rem !important;
    }
    .purchase-section .action-buttons {
      gap: 0.5rem !important;
    }
    .purchase-section .primary-action,
    .purchase-section .secondary-action {
      padding: 0.6rem 1rem !important;
      font-size: 0.875rem !important;
    }
    
    /* Benefits más compactos */
    .benefits-list {
      margin-top: 1rem !important;
      gap: 0.5rem !important;
    }
    .benefits-list .benefit-item {
      padding: 0.5rem !important;
      font-size: 0.8rem !important;
    }
    
    /* Deshabilitar zoom completamente en móvil */
    .drift-zoom-pane,
    .drift-bounding-box,
    .drift-zoom-pane.drift-open {
      display: none !important;
      visibility: hidden !important;
      opacity: 0 !important;
    }
    
    /* Permitir scroll táctil normal */
    .drift-zoom,
    .image-zoom-container,
    .main-showcase {
      touch-action: pan-y pan-x !important;
      -webkit-overflow-scrolling: touch !important;
    }
  }

  /* Pantallas muy pequeñas */
  @media (max-width: 374px) {
    .product-gallery .image-zoom-container,
    .product-gallery .main-showcase {
      max-height: 200px !important;
      height: 200px !important;
      width: 100% !important;
    }
    .product-gallery .main-product-image {
      max-height: 200px !important;
    }
    .product-details .product-name {
      font-size: 1.1rem !important;
    }
    .product-details .sale-price {
      font-size: 1.3rem !important;
    }
    .product-gallery .thumbnail-wrapper,
    .product-gallery .thumbnail-item {
      width: 40px !important;
      height: 40px !important;
      min-width: 40px !important;
      max-width: 40px !important;
    }
  }

  /* ===== PAGINACIÓN ESTILIZADA ===== */
  .pagination-wrapper,
  .reviews-list + div {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
  }

  .pagination {
    display: flex !important;
    flex-direction: row !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 0.35rem !important;
    flex-wrap: wrap !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .pagination li {
    display: flex !important;
  }

  .pagination li a,
  .pagination li span {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 0.75rem !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    color: #495057 !important;
    background: #fff !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
  }

  .pagination li a:hover {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    border-color: transparent !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
  }

  .pagination li.active span,
  .pagination li.active a {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    border-color: transparent !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
  }

  .pagination li.disabled span,
  .pagination li.disabled a {
    background: #f8f9fa !important;
    color: #adb5bd !important;
    border-color: #e9ecef !important;
    cursor: not-allowed !important;
    pointer-events: none;
  }

  /* Ocultar texto y mostrar flechas */
  .pagination li:first-child a,
  .pagination li:first-child span,
  .pagination li:last-child a,
  .pagination li:last-child span {
    font-size: 0 !important;
    min-width: 42px !important;
  }

  .pagination li:first-child a::before,
  .pagination li:first-child span::before {
    content: "‹";
    font-size: 1.25rem !important;
    font-weight: 600;
  }

  .pagination li:last-child a::after,
  .pagination li:last-child span::after {
    content: "›";
    font-size: 1.25rem !important;
    font-weight: 600;
  }

  /* SVG icons si los hay */
  .pagination svg {
    width: 18px !important;
    height: 18px !important;
  }

  /* ===== PAGINACIÓN TAILWIND LARAVEL ===== */
  nav[role="navigation"] {
    display: flex !important;
    justify-content: center !important;
    width: 100%;
  }

  nav[role="navigation"] > div {
    display: flex !important;
    flex-direction: row !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 0.5rem !important;
    width: auto !important;
  }

  /* Ocultar texto "Showing X to Y" */
  nav[role="navigation"] p,
  nav[role="navigation"] > div > div:first-child {
    display: none !important;
  }

  /* Contenedor de botones */
  nav[role="navigation"] span.relative.z-0,
  nav[role="navigation"] .relative.z-0 {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 0.35rem !important;
    box-shadow: none !important;
    background: transparent !important;
    border-radius: 0 !important;
  }

  nav[role="navigation"] span.relative.z-0 > *,
  nav[role="navigation"] .relative.z-0 > * {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  nav[role="navigation"] a,
  nav[role="navigation"] span[aria-current="page"] > span,
  nav[role="navigation"] span.relative.z-0 > span:not([aria-current]) {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 38px !important;
    height: 38px !important;
    padding: 0 0.75rem !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    color: #495057 !important;
    background: #fff !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    -webkit-appearance: none !important;
  }

  nav[role="navigation"] a:hover {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    border-color: transparent !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
  }

  nav[role="navigation"] span[aria-current="page"] > span {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    border-color: transparent !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
  }

  nav[role="navigation"] span.relative.z-0 > span:not([aria-current]):not(:has(span)) {
    background: #f8f9fa !important;
    color: #adb5bd !important;
    border-color: #e9ecef !important;
    cursor: default;
  }

  /* Flechas Previous/Next - Ocultar texto, mostrar iconos */
  nav[role="navigation"] a[rel="prev"],
  nav[role="navigation"] a[rel="next"],
  nav[role="navigation"] span.relative.z-0 > span:first-child,
  nav[role="navigation"] span.relative.z-0 > span:last-child,
  nav[role="navigation"] span.relative.z-0 > a:first-child,
  nav[role="navigation"] span.relative.z-0 > a:last-child {
    font-size: 0 !important;
    min-width: 42px !important;
    padding: 0 !important;
  }

  nav[role="navigation"] a[rel="prev"]::before,
  nav[role="navigation"] span.relative.z-0 > span:first-child::before,
  nav[role="navigation"] span.relative.z-0 > a:first-child::before {
    content: "‹" !important;
    font-size: 1.4rem !important;
    font-weight: 600 !important;
    line-height: 1;
  }

  nav[role="navigation"] a[rel="next"]::after,
  nav[role="navigation"] span.relative.z-0 > span:last-child::after,
  nav[role="navigation"] span.relative.z-0 > a:last-child::after {
    content: "›" !important;
    font-size: 1.4rem !important;
    font-weight: 600 !important;
    line-height: 1;
  }

  /* Ocultar SVGs dentro de los botones prev/next */
  nav[role="navigation"] a[rel="prev"] svg,
  nav[role="navigation"] a[rel="next"] svg,
  nav[role="navigation"] span.relative.z-0 > span:first-child svg,
  nav[role="navigation"] span.relative.z-0 > span:last-child svg {
    display: none !important;
  }

  /* ===== PAGINACIÓN MÓVILES ===== */
  @media (max-width: 767.98px) {
    .pagination li a,
    .pagination li span,
    nav[role="navigation"] a,
    nav[role="navigation"] span[aria-current="page"] > span,
    nav[role="navigation"] span.relative.z-0 > span {
      min-width: 34px !important;
      height: 34px !important;
      padding: 0 0.5rem !important;
      font-size: 0.85rem !important;
      border-radius: 6px !important;
    }

    .pagination li:first-child a,
    .pagination li:first-child span,
    .pagination li:last-child a,
    .pagination li:last-child span,
    nav[role="navigation"] a[rel="prev"],
    nav[role="navigation"] a[rel="next"],
    nav[role="navigation"] span.relative.z-0 > span:first-child,
    nav[role="navigation"] span.relative.z-0 > span:last-child,
    nav[role="navigation"] span.relative.z-0 > a:first-child,
    nav[role="navigation"] span.relative.z-0 > a:last-child {
      min-width: 38px !important;
    }

    .pagination li:first-child a::before,
    .pagination li:first-child span::before,
    nav[role="navigation"] a[rel="prev"]::before,
    nav[role="navigation"] span.relative.z-0 > span:first-child::before,
    nav[role="navigation"] span.relative.z-0 > a:first-child::before {
      font-size: 1.2rem !important;
    }

    .pagination li:last-child a::after,
    .pagination li:last-child span::after,
    nav[role="navigation"] a[rel="next"]::after,
    nav[role="navigation"] span.relative.z-0 > span:last-child::after,
    nav[role="navigation"] span.relative.z-0 > a:last-child::after {
      font-size: 1.2rem !important;
    }
  }

  @media (max-width: 374px) {
    .pagination li a,
    .pagination li span,
    nav[role="navigation"] a,
    nav[role="navigation"] span[aria-current="page"] > span,
    nav[role="navigation"] span.relative.z-0 > span {
      min-width: 30px !important;
      height: 30px !important;
      font-size: 0.8rem !important;
      padding: 0 0.4rem !important;
    }
  }
  /* ===== OCULTAR TEXTO DE TRADUCCIÓN FALTANTE ===== */
nav[role="navigation"] > div > div.flex.justify-between,
nav[role="navigation"] > div > div.hidden,
nav[role="navigation"] > div > div:not(:last-child),
nav[role="navigation"] .flex.flex-1.justify-between,
nav[role="navigation"] > div > .flex:not(.relative) {
  display: none !important;
}

/* Solo mostrar el contenedor de los números */
nav[role="navigation"] > div > div:last-child,
nav[role="navigation"] > div > span.relative.z-0,
nav[role="navigation"] span.relative.z-0 {
  display: flex !important;
}

/* Ocultar cualquier texto suelto fuera de los botones */
nav[role="navigation"] > div {
  font-size: 0 !important;
}

nav[role="navigation"] > div > span.relative.z-0,
nav[role="navigation"] > div > span.relative.z-0 * {
  font-size: 0.9rem !important;
}

nav[role="navigation"] > div > span.relative.z-0 > span:first-child,
nav[role="navigation"] > div > span.relative.z-0 > span:last-child,
nav[role="navigation"] > div > span.relative.z-0 > a:first-child,
nav[role="navigation"] > div > span.relative.z-0 > a:last-child {
  font-size: 0 !important;
}
</style>
@endpush

@section('content')
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
@endphp

  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">{{ $producto->nombre }}</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('tienda.empresa') }}">Inicio</a></li>
            <li><a href="{{ route('tienda.empresa', ['categoria' =>$producto->categoria_id]) }}">{{ $producto->categoria->nombre }}</a></li>
            <li class="current">{{ $producto->nombre }}</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Product Details Section -->
    <section id="product-details" class="product-details section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">
          <!-- Product Gallery -->
          <div class="col-lg-7" data-aos="zoom-in" data-aos-delay="150">
            <div class="product-gallery">
              <div class="main-showcase">
                <div class="image-zoom-container">
                  <img src="{{ $producto->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}" 
                       alt="{{ $producto->nombre }}" 
                       class="img-fluid main-product-image drift-zoom" 
                       id="main-product-image" 
                       data-zoom="{{ $producto->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}">

                  @if($producto->imagenes->count() > 1)
                  <div class="image-navigation">
                    <button class="nav-arrow prev-image image-nav-btn prev-image" type="button" onclick="navigateImages(-1)">
                      <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="nav-arrow next-image image-nav-btn next-image" type="button" onclick="navigateImages(1)">
                      <i class="bi bi-chevron-right"></i>
                    </button>
                  </div>
                  @endif
                </div>
              </div>

              @if($producto->imagenes->count() > 0)
              <div class="thumbnail-grid">
                @foreach($producto->imagenes as $index => $imagen)
                <div class="thumbnail-wrapper thumbnail-item {{ $loop->first ? 'active' : '' }}" 
                     data-image="{{ $imagen->url }}"
                     onclick="changeMainImage('{{ $imagen->url }}', this)">
                  <img src="{{ $imagen->url }}" alt="{{ $producto->nombre }} - Vista {{ $loop->iteration }}" class="img-fluid">
                </div>
                @endforeach
              </div>
              @else
              {{-- Si no hay imágenes, mostrar una sola con placeholder --}}
              <div class="thumbnail-grid">
                <div class="thumbnail-wrapper thumbnail-item active" 
                     data-image="{{ asset('assets/img/product/placeholder.webp') }}">
                  <img src="{{ asset('assets/img/product/placeholder.webp') }}" alt="{{ $producto->nombre }}" class="img-fluid">
                </div>
              </div>
              @endif
            </div>
          </div>

          <!-- Product Details -->
          <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
            <div class="product-details">
{{--               <div class="product-badge-container">
                <span class="badge-category">{{ $producto->categoria->nombre }}</span>
                <div class="rating-group">
                  <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                  </div>
                  <span class="review-text">(127 reviews)</span>
                </div>
              </div> --}}

              <h1 class="product-name">{{ $producto->nombre }}</h1>

              <div class="pricing-section">
                @if($producto->precio_actual)
                  @if($descuentoActivo)
                    <div class="alert alert-success mb-3">
                      <i class="bi bi-tag-fill"></i> <strong>{{ $descuentoActivo->nombre }}</strong>
                      @if($descuentoActivo->descripcion)
                        <small class="d-block">{{ $descuentoActivo->descripcion }}</small>
                      @endif
                    </div>
                  @endif
                <div class="price-display">
                  @if($descuentoActivo)
                    <span class="regular-price">${{ number_format($producto->precio_actual, 0, ',', '.') }}</span>
                    <span class="sale-price">${{ number_format($precioConDescuento, 0, ',', '.') }}</span>
                  @else
                    <span class="sale-price">${{ number_format($producto->precio_actual, 0, ',', '.') }}</span>
                  @endif
                </div>
                @if($descuentoActivo && $montoDescuento > 0)
                <div class="savings-info">
                  <span class="save-amount">Ahorrás ${{ number_format($montoDescuento, 0, ',', '.') }}</span>
                  <span class="discount-percent">({{ $textoDescuento }})</span>
                </div>
                @endif
                @else
                <div class="price-display">
                  <span class="text-muted">Precio no disponible</span>
                </div>
                @endif
              </div>

              {{-- Calificación con estrellas --}}
              <div class="product-rating-display" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem;">
                <div class="stars">
                  @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($promedioCalificacion))
                      <i class="bi bi-star-fill" style="color: #ffc107; font-size: 1.1rem;"></i>
                    @elseif($i - 0.5 <= $promedioCalificacion)
                      <i class="bi bi-star-half" style="color: #ffc107; font-size: 1.1rem;"></i>
                    @else
                      <i class="bi bi-star" style="color: #ffc107; font-size: 1.1rem;"></i>
                    @endif
                  @endfor
                </div>
                @if($totalCalificaciones > 0)
                  <span style="font-weight: 600; color: #212529;">{{ number_format($promedioCalificacion, 1) }}</span>
                  <a href="#resenas" class="text-muted text-decoration-none" style="font-size: 0.9rem;">({{ $totalCalificaciones }} {{ $totalCalificaciones == 1 ? 'reseña' : 'reseñas' }})</a>
                @else
                  <span class="text-muted" style="font-size: 0.9rem;">Sin reseñas aún</span>
                @endif
              </div>

              {{-- Estado de disponibilidad --}}
              <div class="availability-status">
                @if($producto->tiene_variantes)
                  <div class="stock-indicator" id="stockInfo">
                    <i class="bi bi-info-circle"></i>
                    <span class="stock-text">Selecciona una opción para ver disponibilidad</span>
                  </div>
                @else
                  @php 
                    $stockInfo = $producto->getStockInfo();
                    $stockDisponible = $stockInfo['stock_disponible'];
                  @endphp
                  @if(!$stockInfo['controlar_stock'] || $stockInfo['permitir_venta_sin_stock'])
                    <div class="stock-indicator">
                      <i class="bi bi-check-circle-fill"></i>
                      <span class="stock-text">Disponible</span>
                    </div>
                  @elseif($stockInfo['controlar_stock'] && !$stockInfo['permitir_venta_sin_stock'])
                    @if($stockDisponible > 10)
                      <div class="stock-indicator">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="stock-text">Disponible</span>
                      </div>
                    @elseif($stockDisponible > 0)
                      <div class="stock-indicator">
                        <i class="bi bi-exclamation-circle-fill" style="color: #f59e0b;"></i>
                        <span class="stock-text">Limitado</span>
                      </div>
                      <div class="quantity-left">Solo {{ $stockDisponible }} unidades disponibles</div>
                    @else
                      <div class="stock-indicator">
                        <i class="bi bi-x-circle-fill" style="color: #ef4444;"></i>
                        <span class="stock-text">Sin Stock</span>
                      </div>
                    @endif
                  @endif
                @endif
              </div>

              <!-- Product Variants -->
              @if($producto->tiene_variantes && $producto->variantes->count() > 0)
                <div class="variant-section">
                  <div class="variant-selection">
                    <label class="variant-label">Variantes Disponibles:</label>
                    <div class="d-flex flex-wrap gap-2">
                      @foreach($producto->variantes as $variante)
                        @php
                          $varianteStockInfo = $producto->getStockInfo($variante->id);
                          $tieneStockDisponible = $varianteStockInfo['hay_stock'];
                          $nombreVariante = $variante->nombre_variante;
                        @endphp
                        <button class="btn btn-outline-secondary variant-option {{ !$tieneStockDisponible && $producto->controlar_stock && !$producto->permitir_venta_sin_stock ? 'disabled' : '' }}"
                                data-type="variante"
                                data-variante-id="{{ $variante->id }}"
                                data-talla="{{ $variante->talla }}"
                                data-color="{{ $variante->color }}"
                                data-value="{{ $nombreVariante }}"
                                data-stock-disponible="{{ $varianteStockInfo['stock_disponible'] }}"
                                data-puede-agregar-sin-stock="{{ $varianteStockInfo['puede_agregar_sin_stock'] ? 'true' : 'false' }}"
                                {{ (!$tieneStockDisponible && $producto->controlar_stock && !$producto->permitir_venta_sin_stock) ? 'disabled' : '' }}>
                          {{ $nombreVariante ?: 'Sin especificar' }}
                        </button>
                      @endforeach
                    </div>
                    <div class="selected-variant mt-2">Variante seleccionada: <span id="selectedVariant">-</span></div>
                  </div>
                </div>
              @endif

              <!-- Purchase Options -->
              <div class="purchase-section">
                <div class="quantity-control">
                  <label class="control-label">Cantidad:</label>
                  <div class="quantity-input-group">
                    <div class="quantity-selector">
                      <button class="quantity-btn decrease" type="button" onclick="updateQuantity(-1)">
                        <i class="bi bi-dash"></i>
                      </button>
                      <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="99">
                      <button class="quantity-btn increase" type="button" onclick="updateQuantity(1)">
                        <i class="bi bi-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="action-buttons">
                  <button class="btn primary-action" id="addToCartBtn"
                    @php $stockInfo = $producto->getStockInfo(); @endphp
                    @if($producto->tiene_variantes)
                      {{ !$producto->precio_actual ? 'disabled' : '' }}
                    @else
                      {{ (!$producto->precio_actual || (!$stockInfo['hay_stock'] && $stockInfo['stock_limitado'])) ? 'disabled' : '' }}
                    @endif>
                    <i class="bi bi-bag-plus"></i>
                    Agregar al Carrito
                  </button>
                  <button class="btn secondary-action" onclick="comprarAhora()">
                    <i class="bi bi-lightning"></i>
                    Comprar Ahora
                  </button>
                  <button class="btn icon-action" title="Agregar a favoritos">
                    <i class="bi bi-heart"></i>
                  </button>
                </div>
              </div>

              <!-- Benefits List -->
              @if($producto->info_envio || $producto->dias_devolucion || $producto->garantia)
              <div class="benefits-list">
                @if($producto->info_envio)
                <div class="benefit-item">
                  <i class="bi bi-truck"></i>
                  <span>{{ $producto->info_envio }}</span>
                </div>
                @endif
                
                @if($producto->dias_devolucion)
                <div class="benefit-item">
                  <i class="bi bi-arrow-clockwise"></i>
                  <span>{{ $producto->dias_devolucion }}</span>
                </div>
                @endif
                
                @if($producto->garantia)
                <div class="benefit-item">
                  <i class="bi bi-shield-check"></i>
                  <span>{{ $producto->garantia }}</span>
                </div>
                @endif
              </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Information Tabs -->
        <div class="row mt-5" data-aos="fade-up" data-aos-delay="300">
          <div class="col-12">
            <div class="info-tabs-container">
              <nav class="tabs-navigation nav">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ecommerce-product-details-5-overview" type="button">Descripción</button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ecommerce-product-details-5-technical" type="button">Detalles Técnicos</button>
               {{--  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ecommerce-product-details-5-customer-reviews" type="button">Reseñas (127)</button> --}}
              </nav>

              <div class="tab-content">
                <!-- Overview Tdab -->
                <div class="tab-pane fade show active" id="ecommerce-product-details-5-overview">
                  <div class="overview-content">
                    <div class="row g-4">
                      <div class="col-lg-8">
                        <div class="content-section">
                          <h3>Descripción del Producto</h3>
                          <p>{{ $producto->descripcion ?: 'No hay descripción disponible para este producto.' }}</p>

                          @if($producto->caracteristicas->count() > 0)
                          <h4>Características Principales</h4>
                          <div class="highlights-grid">
                            @foreach($producto->caracteristicas as $caracteristica)
                            <div class="highlight-card">
                              <i class="{{ $caracteristica->icono_clase }}"></i>
                              <h5>{{ $caracteristica->titulo }}</h5>
                              <p>{{ $caracteristica->descripcion }}</p>
                            </div>
                            @endforeach
                          </div>
                          @endif
                        </div>
                      </div>

                      <div class="col-lg-4">
                        <div class="package-contents">
                          <h4>Contenido del Paquete</h4>
                          <ul class="contents-list">
                            <li><i class="bi bi-check-circle"></i>{{ $producto->nombre }}</li>
                            <li><i class="bi bi-check-circle"></i>Empaque Premium</li>
                        {{--     <li><i class="bi bi-check-circle"></i>Instrucciones de Uso</li> --}}
                            <li><i class="bi bi-check-circle"></i>Garantía del Fabricante</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Technical Details Tab -->
                <div class="tab-pane fade" id="ecommerce-product-details-5-technical">
                  <div class="technical-content">
                    <div class="row g-4">
                      <div class="col-md-12">
                        @if($producto->caracteristicas->count() > 0)
                        <div class="tech-group">
                          <h4>Especificaciones del Producto</h4>
                          <div class="spec-table">
                            @foreach($producto->caracteristicas as $caracteristica)
                            <div class="spec-row">
                              <span class="spec-name">{{ $caracteristica->titulo }}</span>
                              <span class="spec-value">{{ $caracteristica->descripcion }}</span>
                            </div>
                            @endforeach
                          </div>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                          <p>No hay especificaciones técnicas disponibles para este producto.</p>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Reviews Tab -->
    {{--             <div class="tab-pane fade" id="ecommerce-product-details-5-customer-reviews">
                  <div class="reviews-content">
                    <div class="reviews-header">
                      <div class="rating-overview">
                        <div class="average-score">
                          <div class="score-display">4.6</div>
                          <div class="score-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                          </div>
                          <div class="total-reviews">127 reseñas de clientes</div>
                        </div>

                        <div class="rating-distribution">
                          <div class="rating-row">
                            <span class="stars-label">5★</span>
                            <div class="progress-container">
                              <div class="progress-fill" style="width: 68%;"></div>
                            </div>
                            <span class="count-label">86</span>
                          </div>
                          <div class="rating-row">
                            <span class="stars-label">4★</span>
                            <div class="progress-container">
                              <div class="progress-fill" style="width: 22%;"></div>
                            </div>
                            <span class="count-label">28</span>
                          </div>
                          <div class="rating-row">
                            <span class="stars-label">3★</span>
                            <div class="progress-container">
                              <div class="progress-fill" style="width: 6%;"></div>
                            </div>
                            <span class="count-label">8</span>
                          </div>
                          <div class="rating-row">
                            <span class="stars-label">2★</span>
                            <div class="progress-container">
                              <div class="progress-fill" style="width: 3%;"></div>
                            </div>
                            <span class="count-label">4</span>
                          </div>
                          <div class="rating-row">
                            <span class="stars-label">1★</span>
                            <div class="progress-container">
                              <div class="progress-fill" style="width: 1%;"></div>
                            </div>
                            <span class="count-label">1</span>
                          </div>
                        </div>
                      </div>

                      <div class="write-review-cta">
                        <h4>Comparte tu Experiencia</h4>
                        <p>Ayuda a otros a tomar decisiones informadas</p>
                        <button class="btn review-btn">Escribir Reseña</button>
                      </div>
                    </div>

                    <div class="customer-reviews-list">
                      <div class="review-card">
                        <div class="reviewer-profile">
                          <img src="{{ asset('assets/img/person/person-f-3.webp') }}" alt="Cliente" class="profile-pic">
                          <div class="profile-details">
                            <div class="customer-name">María González</div>
                            <div class="review-meta">
                              <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                              </div>
                              <span class="review-date">28 de Marzo, 2024</span>
                            </div>
                          </div>
                        </div>
                        <h5 class="review-headline">Excelente calidad y comodidad</h5>
                        <div class="review-text">
                          <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam. Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                        </div>
                        <div class="review-actions">
                          <button class="action-btn"><i class="bi bi-hand-thumbs-up"></i> Útil (12)</button>
                          <button class="action-btn"><i class="bi bi-chat-dots"></i> Responder</button>
                        </div>
                      </div>

                      <div class="review-card">
                        <div class="reviewer-profile">
                          <img src="{{ asset('assets/img/person/person-m-5.webp') }}" alt="Cliente" class="profile-pic">
                          <div class="profile-details">
                            <div class="customer-name">Carlos Rodríguez</div>
                            <div class="review-meta">
                              <div class="review-stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                              </div>
                              <span class="review-date">15 de Marzo, 2024</span>
                            </div>
                          </div>
                        </div>
                        <h5 class="review-headline">Buen producto, entrega rápida</h5>
                        <div class="review-text">
                          <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. En general satisfecho con la compra.</p>
                        </div>
                        <div class="review-actions">
                          <button class="action-btn"><i class="bi bi-hand-thumbs-up"></i> Útil (8)</button>
                          <button class="action-btn"><i class="bi bi-chat-dots"></i> Responder</button>
                        </div>
                      </div>

                      <div class="load-more-section">
                        <button class="btn load-more-reviews">Mostrar Más Reseñas</button>
                      </div>
                    </div>
                  </div>
                </div> --}}
              </div>
            </div>
          </div>
        </div>

        {{-- Sección de Reseñas - SIEMPRE VISIBLE --}}
        <div class="row mt-5" data-aos="fade-up" data-aos-delay="350" id="resenas">
          <div class="col-12">
            <div class="reviews-section" style="background: #f8f9fa; border-radius: 12px; padding: 2rem;">
              <h3 class="mb-4" style="font-weight: 600;">
                <i class="bi bi-star-fill text-warning me-2"></i>Reseñas de Clientes
              </h3>

              <div class="row g-4">
                {{-- Resumen de Calificaciones --}}
                <div class="col-lg-4">
                  <div class="rating-summary" style="background: white; border-radius: 10px; padding: 1.5rem; text-align: center;">
                    <div class="average-rating" style="font-size: 3.5rem; font-weight: 700; color: #212529;">
                      {{ number_format($promedioCalificacion ?? 0, 1) }}
                    </div>
                    <div class="stars-display mb-2">
                      @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($promedioCalificacion ?? 0))
                          <i class="bi bi-star-fill" style="color: #ffc107; font-size: 1.25rem;"></i>
                        @elseif($i - 0.5 <= ($promedioCalificacion ?? 0))
                          <i class="bi bi-star-half" style="color: #ffc107; font-size: 1.25rem;"></i>
                        @else
                          <i class="bi bi-star" style="color: #ffc107; font-size: 1.25rem;"></i>
                        @endif
                      @endfor
                    </div>
                    <div class="total-reviews" style="color: #6c757d;">
                      {{ $totalCalificaciones ?? 0 }} {{ ($totalCalificaciones ?? 0) == 1 ? 'reseña' : 'reseñas' }}
                    </div>

                    {{-- Distribución de Estrellas --}}
                    <div class="rating-distribution mt-4" style="text-align: left;">
                      @for($stars = 5; $stars >= 1; $stars--)
                        @php
                          $count = $distribucionCalificaciones[$stars] ?? 0;
                          $percentage = ($totalCalificaciones ?? 0) > 0 ? ($count / $totalCalificaciones) * 100 : 0;
                        @endphp
                        <div class="rating-bar d-flex align-items-center mb-2">
                          <span style="min-width: 30px; font-size: 0.875rem;">{{ $stars }}★</span>
                          <div class="progress flex-grow-1 mx-2" style="height: 8px; background: #e9ecef;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $percentage }}%; background: #ffc107;"
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                          <span style="min-width: 25px; font-size: 0.875rem; color: #6c757d;">{{ $count }}</span>
                        </div>
                      @endfor
                    </div>
                  </div>

                  {{-- Botón Escribir Reseña --}}
                  <div class="write-review-cta mt-3" style="background: white; border-radius: 10px; padding: 1.5rem; text-align: center;">
                    <h5 class="mb-2">¡Comparte tu experiencia!</h5>
                    <p class="text-muted small mb-3">Tu opinión ayuda a otros compradores</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalResena">
                      <i class="bi bi-pencil-square me-1"></i> Escribir Reseña
                    </button>
                  </div>
                </div>

                {{-- Lista de Reseñas --}}
                <div class="col-lg-8">
                  {{-- Botones compartir producto --}}
                  <div class="product-share mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 1.25rem 1.5rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                      <div class="d-flex align-items-center gap-2">
                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                          <i class="bi bi-share-fill" style="color: white; font-size: 1.1rem;"></i>
                        </div>
                        <div>
                          <span style="color: white; font-weight: 600; font-size: 1rem;">Comparte este producto</span>
                          <small style="color: rgba(255,255,255,0.8); display: block; font-size: 0.8rem;">Recomienda a tus amigos</small>
                        </div>
                      </div>
                      <div class="d-flex gap-2">
                        <button type="button"
                                onclick="compartirWhatsApp()"
                                style="width: 44px; height: 44px; border-radius: 12px; border: none; background: white; color: #25D366; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                                title="Compartir en WhatsApp">
                          <i class="bi bi-whatsapp" style="font-size: 1.3rem;"></i>
                        </button>
                        <button type="button"
                                onclick="compartirFacebook()"
                                style="width: 44px; height: 44px; border-radius: 12px; border: none; background: white; color: #1877F2; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                                title="Compartir en Facebook">
                          <i class="bi bi-facebook" style="font-size: 1.3rem;"></i>
                        </button>
                        <button type="button"
                                onclick="copiarEnlace()"
                                style="width: 44px; height: 44px; border-radius: 12px; border: none; background: white; color: #E1306C; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                                title="Copiar para Instagram">
                          <i class="bi bi-instagram" style="font-size: 1.3rem;"></i>
                        </button>
                        <button type="button"
                                onclick="copiarEnlace()"
                                style="width: 44px; height: 44px; border-radius: 12px; border: none; background: white; color: #000000; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                                title="Copiar para TikTok">
                          <i class="bi bi-tiktok" style="font-size: 1.3rem;"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  @if(($calificaciones ?? collect())->count() > 0)
                    <div class="reviews-list">
                      @foreach($calificaciones as $calificacion)
                        <div class="review-item" id="resena-{{ $calificacion->id }}" style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem;">
                          <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="reviewer-info">
                              <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="width: 40px; height: 40px; background: linear-gradient(135deg, #FF00C1, #0B00F9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                  {{ strtoupper(substr($calificacion->nombre_autor, 0, 1)) }}
                                </div>
                                <div>
                                  <div style="font-weight: 600;">{{ $calificacion->nombre_autor }}</div>
                                  <div class="d-flex align-items-center gap-2">
                                    <div class="stars-small">
                                      @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $calificacion->estrellas)
                                          <i class="bi bi-star-fill" style="color: #ffc107; font-size: 0.875rem;"></i>
                                        @else
                                          <i class="bi bi-star" style="color: #ffc107; font-size: 0.875rem;"></i>
                                        @endif
                                      @endfor
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <small class="text-muted">{{ $calificacion->created_at->diffForHumans() }}</small>
                          </div>

                          @if($calificacion->titulo)
                            <h6 class="mb-2" style="font-weight: 600;">{{ $calificacion->titulo }}</h6>
                          @endif

                          @if($calificacion->comentario)
                            <p class="mb-2" style="color: #495057;">{{ $calificacion->comentario }}</p>
                          @endif

                          {{-- Imagen de la reseña --}}
                          @if($calificacion->imagen)
                            <div class="review-image mb-3">
                              <img src="{{ asset($calificacion->imagen) }}"
                                   alt="Imagen de reseña"
                                   class="img-fluid rounded"
                                   style="max-height: 200px; cursor: pointer;"
                                   onclick="ampliarImagen('{{ asset($calificacion->imagen) }}')">
                            </div>
                          @endif

                          {{-- Reacciones --}}
                          <div class="review-reactions d-flex align-items-center gap-2 mb-3" style="flex-wrap: wrap;">
                            @php
                              $conteoReacciones = $calificacion->conteo_reacciones;
                              $emojis = ['hearts' => '😍', 'wink' => '😉', 'kiss' => '😘', 'thumbsup' => '👍'];
                            @endphp
                            @foreach($emojis as $key => $emoji)
                              <button type="button"
                                      class="reaction-btn"
                                      data-calificacion="{{ $calificacion->id }}"
                                      data-emoji="{{ $key }}"
                                      style="background: linear-gradient(145deg, #f8f9fa, #e9ecef); border: none; border-radius: 25px; padding: 0.4rem 1rem; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 2px 2px 5px rgba(0,0,0,0.1), -1px -1px 3px rgba(255,255,255,0.8);">
                                {{ $emoji }} <span class="reaction-count" style="font-size: 0.85rem; font-weight: 600; color: #495057;">{{ $conteoReacciones[$key] ?? 0 }}</span>
                              </button>
                            @endforeach
                          </div>

                          {{-- Acciones: Responder y Compartir --}}
                          <div class="review-actions d-flex align-items-center gap-2 pt-2" style="flex-wrap: wrap; border-top: 1px solid #eee;">
                            <button type="button"
                                    class="action-btn-reply"
                                    onclick="mostrarFormRespuesta({{ $calificacion->id }})"
                                    style="background: transparent; border: 2px solid #667eea; color: #667eea; border-radius: 25px; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                              <i class="bi bi-reply-fill"></i> Responder
                            </button>

                            {{-- Botones de compartir directos --}}
                            <div class="share-buttons d-flex align-items-center gap-1">
                              <button type="button"
                                      onclick="compartirResenaWhatsApp({{ $calificacion->id }})"
                                      style="width: 28px; height: 28px; border-radius: 50%; border: none; background: #25D366; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                                      title="WhatsApp">
                                <i class="bi bi-whatsapp" style="font-size: 0.75rem;"></i>
                              </button>
                              <button type="button"
                                      onclick="compartirResenaFacebook({{ $calificacion->id }})"
                                      style="width: 28px; height: 28px; border-radius: 50%; border: none; background: #1877F2; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                                      title="Facebook">
                                <i class="bi bi-facebook" style="font-size: 0.75rem;"></i>
                              </button>
                              <button type="button"
                                      onclick="copiarEnlaceResena({{ $calificacion->id }})"
                                      style="width: 28px; height: 28px; border-radius: 50%; border: none; background: #E1306C; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                                      title="Instagram">
                                <i class="bi bi-instagram" style="font-size: 0.75rem;"></i>
                              </button>
                              <button type="button"
                                      onclick="copiarEnlaceResena({{ $calificacion->id }})"
                                      style="width: 28px; height: 28px; border-radius: 50%; border: none; background: #000000; color: white; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                                      title="TikTok">
                                <i class="bi bi-tiktok" style="font-size: 0.75rem;"></i>
                              </button>
                            </div>
                          </div>

                          {{-- Formulario de respuesta (oculto) --}}
                          <div id="form-respuesta-{{ $calificacion->id }}" class="respuesta-form" style="display: none; background: #f8f9fa; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                            <form onsubmit="enviarRespuesta(event, {{ $calificacion->id }})">
                              <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" name="nombre" placeholder="Tu nombre *" required maxlength="100">
                              </div>
                              <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="comentario" rows="2" placeholder="Tu respuesta *" required maxlength="500"></textarea>
                              </div>
                              <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary">
                                  <i class="bi bi-send"></i> Enviar
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="ocultarFormRespuesta({{ $calificacion->id }})">
                                  Cancelar
                                </button>
                              </div>
                            </form>
                          </div>

                          {{-- Respuestas aprobadas --}}
                          @if($calificacion->respuestasAprobadas && $calificacion->respuestasAprobadas->count() > 0)
                            <div class="respuestas-list" style="border-left: 3px solid #dee2e6; padding-left: 1rem; margin-top: 1rem;">
                              @foreach($calificacion->respuestasAprobadas as $respuesta)
                                <div class="respuesta-item mb-2" style="background: #f8f9fa; border-radius: 8px; padding: 0.75rem;">
                                  <div class="d-flex align-items-center gap-2 mb-1">
                                    <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                      {{ strtoupper(substr($respuesta->nombre_autor, 0, 1)) }}
                                    </div>
                                    <strong class="small">{{ $respuesta->nombre_autor }}</strong>
                                    <small class="text-muted">{{ $respuesta->created_at->diffForHumans() }}</small>
                                  </div>
                                  <p class="mb-0 small" style="color: #495057;">{{ $respuesta->comentario }}</p>
                                </div>
                              @endforeach
                            </div>
                          @endif
                        </div>
                      @endforeach

                      {{-- Paginación --}}
                      @if($calificaciones->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                          {{ $calificaciones->links() }}
                        </div>
                      @endif
                    </div>
                  @else
                    <div class="no-reviews" style="background: white; border-radius: 10px; padding: 3rem; text-align: center;">
                      <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #dee2e6;"></i>
                      <h5 class="mt-3 mb-2">Aún no hay reseñas</h5>
                      <p class="text-muted mb-0">Sé el primero en compartir tu experiencia con este producto</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Productos relacionados --}}
        @if($relacionados->count() > 0)
        <div class="row mt-5" data-aos="fade-up" data-aos-delay="400">
          <div class="col-12">
            <h3 class="mb-4">Productos Relacionados</h3>
            <div class="row g-4">
              @foreach($relacionados as $relacionado)
              @php
                // Buscar descuentos activos para producto relacionado
                $descuentoRelacionado = null;
                $textoDescuentoRel = null;
                $precioActualRel = $relacionado->precio_actual;
                $precioConDescuentoRel = $precioActualRel;

                if (isset($descuentosActivos) && $precioActualRel) {
                    foreach ($descuentosActivos as $desc) {
                        $aplica = false;
                        if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito') {
                            $aplica = true;
                        } elseif ($desc->aplica_a === 'producto' && in_array($relacionado->id, $desc->productos_aplicables ?? [])) {
                            $aplica = true;
                        } elseif ($desc->aplica_a === 'categoria' && in_array($relacionado->categoria_id, $desc->categorias_aplicables ?? [])) {
                            $aplica = true;
                        }

                        if ($aplica) {
                            $descuentoRelacionado = $desc;
                            if ($desc->tipo === 'porcentaje') {
                                $montoDescRel = ($precioActualRel * $desc->valor) / 100;
                                $textoDescuentoRel = round($desc->valor) . '% OFF';
                            } else {
                                $montoDescRel = $desc->valor;
                                $textoDescuentoRel = '$' . number_format($desc->valor, 0, ',', '.') . ' OFF';
                            }
                            $precioConDescuentoRel = $precioActualRel - $montoDescRel;
                            break;
                        }
                    }
                }
              @endphp
              <div class="col-lg-3 col-md-6">
                <div class="product-card" style="height: 100%;">
                  <div class="product-image" style="position: relative; overflow: hidden; aspect-ratio: 1/1;">
                    @if($descuentoRelacionado)
                      <div class="product-badge sale">{{ $textoDescuentoRel }}</div>
                    @elseif($relacionado->stock_disponible <= 5 && $relacionado->stock_disponible > 0)
                      <div class="product-badge new">¡Últimas unidades!</div>
                    @elseif($relacionado->stock_disponible == 0 && !$relacionado->permitir_venta_sin_stock)
                      <div class="product-badge sale">Sin Stock</div>
                    @endif
                    <img src="{{ $relacionado->url_imagen_principal ?? asset('assets/img/product/placeholder.webp') }}"
                         alt="{{ $relacionado->nombre }}"
                         class="main-image img-fluid"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         loading="lazy">
                    @if($relacionado->imagenes->count() > 1)
                      <img src="{{ $relacionado->imagenes[1]->url }}"
                           class="hover-image img-fluid"
                           style="width: 100%; height: 100%; object-fit: cover;"
                           alt="{{ $relacionado->nombre }} - Vista 2">
                    @endif
                    <div class="product-overlay">
                      <div class="product-actions">
                        <a href="{{ route('tienda.producto', $relacionado->slug) }}"
                           class="action-btn" data-bs-toggle="tooltip" title="Ver Detalles">
                          <i class="bi bi-eye"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="product-details" style="padding: 1rem;">
                    <div class="product-category" style="font-size: 0.875rem; color: #6c757d; margin-bottom: 0.5rem;">
                      {{ $relacionado->categoria->nombre }}
                    </div>
                    <h4 class="product-title" style="font-size: 1rem; margin-bottom: 0.75rem; line-height: 1.4;">
                      <a href="{{ route('tienda.producto', $relacionado->slug) }}"
                         style="text-decoration: none; color: #212529;">
                        {{ Str::limit($relacionado->nombre, 50) }}
                      </a>
                    </h4>
                    <div class="product-meta">
                      @if($precioActualRel)
                        @if($descuentoRelacionado)
                          <div class="product-price" style="margin-bottom: 0.5rem;">
                            <span class="text-decoration-line-through text-muted me-2" style="font-size: 0.875rem;">
                              ${{ number_format($precioActualRel, 0, ',', '.') }}
                            </span>
                            <span class="text-danger fw-bold" style="font-size: 1.125rem;">
                              ${{ number_format($precioConDescuentoRel, 0, ',', '.') }}
                            </span>
                          </div>
                        @else
                          <div class="product-price" style="font-size: 1.125rem; font-weight: 600; color: #212529; margin-bottom: 0.5rem;">
                            ${{ number_format($precioActualRel, 0, ',', '.') }}
                          </div>
                        @endif
                      @else
                        <div class="product-price text-muted" style="font-size: 0.875rem;">Precio no disponible</div>
                      @endif
                      @if(($relacionado->total_calificaciones ?? 0) > 0)
                      <div class="product-rating" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                        @php $promedioRel = $relacionado->promedio_calificaciones ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                          @if($i <= round($promedioRel))
                            <i class="bi bi-star-fill" style="color: #ffc107;"></i>
                          @elseif($i - 0.5 <= $promedioRel)
                            <i class="bi bi-star-half" style="color: #ffc107;"></i>
                          @else
                            <i class="bi bi-star" style="color: #ffc107;"></i>
                          @endif
                        @endfor
                        {{ number_format($promedioRel, 1) }}
                        <span style="color: #6c757d;">({{ $relacionado->total_calificaciones }})</span>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

      </div>
    </section><!-- /Product Details Section -->

  </main>

  {{-- Modal para ampliar imagen de reseña --}}
  <div class="modal fade" id="modalImagenResena" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style="background: transparent; border: none;">
        <div class="modal-body text-center p-0">
          <img id="imagenAmpliada" src="" alt="Imagen ampliada" class="img-fluid rounded" style="max-height: 80vh;">
        </div>
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
      </div>
    </div>
  </div>

  {{-- Modal para escribir reseña --}}
  <div class="modal fade" id="modalResena" tabindex="-1" aria-labelledby="modalResenaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResenaLabel">
            <i class="bi bi-star-fill text-warning me-2"></i>Escribe tu Reseña
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formResena">
          <div class="modal-body">
            {{-- Nombre --}}
            <div class="mb-3">
              <label for="resena_nombre" class="form-label fw-bold">Tu nombre *</label>
              <input type="text" class="form-control" id="resena_nombre" name="nombre" required maxlength="100" placeholder="Escribe tu nombre">
            </div>

            {{-- Estrellas --}}
            <div class="mb-3">
              <label class="form-label fw-bold">¿Cómo calificarías este producto? *</label>
              <div class="star-rating-input" id="starRatingInput">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star fs-2" data-rating="{{ $i }}" style="cursor: pointer; color: #e5e7eb; transition: color 0.2s;"></i>
                @endfor
              </div>
              <input type="hidden" name="estrellas" id="resena_estrellas" required>
              <div class="invalid-feedback" id="estrellas-error">Por favor selecciona una calificación</div>
            </div>

            {{-- Título --}}
            <div class="mb-3">
              <label for="resena_titulo" class="form-label fw-bold">Título de tu reseña (opcional)</label>
              <input type="text" class="form-control" id="resena_titulo" name="titulo" maxlength="255" placeholder="Ej: Excelente producto, muy recomendado">
            </div>

            {{-- Comentario --}}
            <div class="mb-3">
              <label for="resena_comentario" class="form-label fw-bold">Tu opinión (opcional)</label>
              <textarea class="form-control" id="resena_comentario" name="comentario" rows="3" maxlength="1000" placeholder="Cuéntanos qué te pareció el producto..."></textarea>
              <div class="form-text">Máximo 1000 caracteres</div>
            </div>

            {{-- Imagen --}}
            <div class="mb-3">
              <label for="resena_imagen" class="form-label fw-bold">Agregar imagen (opcional)</label>
              <input type="file" class="form-control" id="resena_imagen" name="imagen" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
              <div class="form-text">Máximo 5MB. Formatos: JPG, PNG, GIF, WEBP</div>
              <div id="preview-imagen" class="mt-2 d-none">
                <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 150px; border-radius: 8px;">
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="quitarImagenPreview()">
                  <i class="bi bi-x"></i> Quitar
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btnEnviarResena" disabled>
              <span class="btn-text"><i class="bi bi-send me-1"></i> Enviar Reseña</span>
              <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-1"></span> Enviando...</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  // === Variantes disponibles del producto (JSON) ===
  const variantes = @json($producto->variantes);
  const tieneVariantes = {{ $producto->tiene_variantes ? 'true' : 'false' }};
  let selectedVariant = null;
  let selectedTalla = null;
  let selectedColor = null;
  let currentImageIndex = 0;
  const productImages = @json($producto->imagenes->pluck('url'));

  $(document).ready(function() {
    // Inicializar Drift zoom si está disponible
    if (typeof Drift !== 'undefined') {
      new Drift(document.querySelector('.drift-zoom'), {
        paneContainer: document.querySelector('.image-zoom-container'),
        inlinePane: true,
        inlineOffsetY: -85,
        containInline: true,
        hoverBoundingBox: true
      });
    }

    // Selección de variantes
    $('.variant-option:not(:disabled)').on('click', function() {
      const type = $(this).data('type');
      
      if (type === 'variante') {
        // Nueva lógica para variantes unificadas
        $('.variant-option[data-type="variante"]').removeClass('selected active');
        $(this).addClass('selected active');
        
        const varianteId = $(this).data('variante-id');
        const varianteNombre = $(this).data('value');
        selectedTalla = $(this).data('talla');
        selectedColor = $(this).data('color');
        
        // Encontrar la variante seleccionada
        selectedVariant = variantes.find(v => v.id == varianteId);
        
        $('#selectedVariant').text(varianteNombre);
        
        if (selectedVariant) {
          updateStockInfo(varianteId);
          // Solo habilitar si puede agregar al carrito
          const puedeAgregar = !!$(this).data('puede-agregar-sin-stock') || parseInt($(this).data('stock-disponible')) > 0;
          $('#addToCartBtn').prop('disabled', !puedeAgregar);
        }
      } else {
        // Lógica anterior para compatibilidad (si se necesita)
        const value = $(this).data('value');
        $(`.variant-option[data-type="${type}"]`).removeClass('selected active');
        $(this).addClass('selected active');

        if (type === 'talla') {
          selectedTalla = value;
        }
        if (type === 'color') {
          selectedColor = value;
          $('#selectedColor').text(value);
        }

        updateVariantAvailability();
        if (tieneVariantes) findSelectedVariant();
      }
    });

    // Agregar al carrito
    $('#addToCartBtn').on('click', function() {
      const btn = $(this);
      const quantity = parseInt($('#quantity').val());

      if (tieneVariantes && !selectedVariant) {
        showToast('error', 'Por favor selecciona todas las opciones del producto');
        return;
      }

      btn.prop('disabled', true);
      btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Agregando...');

      const data = {
        producto_id: {{ $producto->id }},
        cantidad: quantity
      };
      if (selectedVariant) data.variante_id = selectedVariant.id;

      $.ajax({
        url: "{{ route('tienda.carrito.agregar') }}",
        method: 'POST',
        data: data,
        success: function(response) {
          showToast('success', 'Producto agregado al carrito');
          if (response && typeof response.total_items !== 'undefined') {
            updateCartBadge(response.total_items);
          }
          btn.html('<i class="bi bi-check"></i> Agregado al Carrito');

          setTimeout(() => {
            btn.prop('disabled', false);
            btn.html('<i class="bi bi-bag-plus"></i> Agregar al Carrito');
          }, 2000);
        },
        error: function(xhr) {
          const error = xhr.responseJSON?.error || 'Error al agregar al carrito';
          showToast('error', error);
          btn.prop('disabled', false);
          btn.html('<i class="bi bi-bag-plus"></i> Agregar al Carrito');
        }
      });
    });

    // Cambiar imagen con thumbnails
    $('.thumbnail-item').on('click', function() {
      const index = $('.thumbnail-item').index(this);
      currentImageIndex = index;
      updateMainImage();
    });
  });

  // Cambiar imagen principal
  function changeMainImage(url, thumbnail) {
    $('#main-product-image').attr('src', url);
    $('#main-product-image').attr('data-zoom', url);
    $('.thumbnail-item').removeClass('active');
    $(thumbnail).addClass('active');
    
    // Re-inicializar Drift si existe
    if (typeof Drift !== 'undefined') {
      const oldDrift = document.querySelector('.drift-zoom').drift;
      if (oldDrift) oldDrift.destroy();
      
      new Drift(document.querySelector('.drift-zoom'), {
        paneContainer: document.querySelector('.image-zoom-container'),
        inlinePane: true,
        inlineOffsetY: -85,
        containInline: true,
        hoverBoundingBox: true
      });
    }
  }

  // Navegación de imágenes con flechas
  function navigateImages(direction) {
    const totalImages = productImages.length;
    currentImageIndex = (currentImageIndex + direction + totalImages) % totalImages;
    updateMainImage();
  }

  function updateMainImage() {
    const url = productImages[currentImageIndex];
    $('#main-product-image').attr('src', url);
    $('#main-product-image').attr('data-zoom', url);
    $('.thumbnail-item').removeClass('active');
    $('.thumbnail-item').eq(currentImageIndex).addClass('active');
  }

  // Actualizar cantidad
  function updateQuantity(change) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + change;

    if (value < 1) value = 1;

    // Verificar límite por stock usando el input max attribute
    const maxStock = parseInt(input.getAttribute('max'));
    if (maxStock && value > maxStock) {
      value = maxStock;
      showToast('error', `Solo hay ${maxStock} unidades disponibles`);
    }

    input.value = value;
  }

  // Actualizar disponibilidad de variantes (simplificada)
  function updateVariantAvailability() {
    // Con las variantes unificadas, esta función es menos necesaria
    // ya que el stock se maneja directamente en la renderización inicial
    if (!tieneVariantes) return;

    // Solo ejecutar si hay variantes separadas (compatibilidad)
    if ($('.variant-option[data-type="color"]').length > 0 || $('.variant-option[data-type="talla"]').length > 0) {
      // Filtra colores por talla
      if (selectedTalla) {
        $('.variant-option[data-type="color"]').each(function() {
          const color = $(this).data('value');
          const hayStock = variantes.some(v =>
            v.talla === selectedTalla &&
            v.color === color &&
            v.stock &&
            v.stock.stock_real > 0
          );
          $(this).prop('disabled', !hayStock).toggleClass('disabled', !hayStock);
        });
      }

      // Filtra tallas por color
      if (selectedColor) {
        $('.variant-option[data-type="talla"]').each(function() {
          const talla = $(this).data('value');
          const hayStock = variantes.some(v =>
            v.talla === talla &&
            v.color === selectedColor &&
            v.stock &&
            v.stock.stock_real > 0
          );
          $(this).prop('disabled', !hayStock).toggleClass('disabled', !hayStock);
        });
      }
    }
  }

  // Encuentra la variante seleccionada (simplificada para variantes unificadas)
  function findSelectedVariant() {
    // Esta función ahora es más simple porque las variantes se seleccionan directamente
    if (!selectedVariant) {
      $('#stockInfo').html('<i class="bi bi-info-circle"></i> <span class="stock-text">Selecciona una variante</span>');
      $('#addToCartBtn').prop('disabled', true);
      return;
    }

    updateStockInfo(selectedVariant);
    $('#addToCartBtn').prop('disabled', false);
  }

  // Actualizar información de stock
  function updateStockInfo(varianteId) {
    // Obtener información de stock vía AJAX
    $.ajax({
      url: "{{ route('tienda.stock.info') }}",
      method: 'POST',
      data: {
        producto_id: {{ $producto->id }},
        variante_id: varianteId
      },
      success: function(stockInfo) {
        const stock = stockInfo.stock_disponible || 0;
        let stockClass, stockText, stockIcon, quantityText = '';
        
        if (!stockInfo.controlar_stock || stockInfo.permitir_venta_sin_stock) {
          stockClass = 'stock-available';
          stockText = 'Disponible';
          stockIcon = 'check-circle-fill';
        } else if (stockInfo.controlar_stock && !stockInfo.permitir_venta_sin_stock) {
          if (stock > 10) {
            stockClass = 'stock-available';
            stockText = 'Disponible';
            stockIcon = 'check-circle-fill';
          } else if (stock > 0) {
            stockClass = 'stock-low';
            stockText = 'Limitado';
            stockIcon = 'exclamation-circle-fill';
            quantityText = `<div class="quantity-left">Solo ${stock} unidades disponibles</div>`;
          } else {
            stockClass = 'stock-out';
            stockText = 'Sin stock';
            stockIcon = 'x-circle-fill';
          }
        }

        $('#stockInfo').html(`
          <div class="stock-indicator">
            <i class="bi bi-${stockIcon}"></i>
            <span class="stock-text">${stockText}</span>
          </div>
          ${quantityText}
        `);
        
        // Actualizar límite de cantidad
        const quantityInput = document.getElementById('quantity');
        if (stockInfo.stock_limitado && stock > 0) {
          quantityInput.max = stock;
        } else {
          quantityInput.removeAttribute('max');
        }
      }
    });
  }

  // Comprar ahora
  function comprarAhora() {
    // Primero agregar al carrito
    const quantity = parseInt($('#quantity').val());

    if (tieneVariantes && !selectedVariant) {
      showToast('error', 'Por favor selecciona todas las opciones del producto');
      return;
    }

    const data = {
      producto_id: {{ $producto->id }},
      cantidad: quantity
    };
    if (selectedVariant) data.variante_id = selectedVariant.id;

    $.ajax({
      url: "{{ route('tienda.carrito.agregar') }}",
      method: 'POST',
      data: data,
      success: function(response) {
        // Redirigir al checkout
        window.location.href = "{{ route('tienda.checkout') }}";
      },
      error: function(xhr) {
        const error = xhr.responseJSON?.error || 'Error al procesar la compra';
        showToast('error', error);
      }
    });
  }

  // Toast notification
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

  // Actualiza el badge del carrito
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

  // ========== Sistema de Reseñas ==========
  $(document).ready(function() {
    const starRatingInput = $('#starRatingInput');
    const stars = starRatingInput.find('.bi');
    const inputEstrellas = $('#resena_estrellas');
    const btnEnviar = $('#btnEnviarResena');

    // Hover effect en estrellas
    stars.on('mouseenter', function() {
      const rating = $(this).data('rating');
      highlightStarsModal(rating);
    });

    stars.on('mouseleave', function() {
      const currentRating = inputEstrellas.val();
      if (currentRating) {
        highlightStarsModal(parseInt(currentRating));
      } else {
        clearStarsModal();
      }
    });

    // Click para seleccionar estrellas
    stars.on('click', function() {
      const rating = $(this).data('rating');
      inputEstrellas.val(rating);
      highlightStarsModal(rating);
      btnEnviar.prop('disabled', false);
      $('#estrellas-error').hide();
    });

    function highlightStarsModal(rating) {
      stars.each(function(index) {
        if (index < rating) {
          $(this).removeClass('bi-star').addClass('bi-star-fill').css('color', '#ffc107');
        } else {
          $(this).removeClass('bi-star-fill').addClass('bi-star').css('color', '#e5e7eb');
        }
      });
    }

    function clearStarsModal() {
      stars.removeClass('bi-star-fill').addClass('bi-star').css('color', '#e5e7eb');
    }

    // Envío del formulario de reseña
    $('#formResena').on('submit', function(e) {
      e.preventDefault();

      // Validar estrellas
      if (!inputEstrellas.val()) {
        $('#estrellas-error').show();
        return;
      }

      const btn = btnEnviar;
      btn.prop('disabled', true);
      btn.find('.btn-text').addClass('d-none');
      btn.find('.btn-loading').removeClass('d-none');

      // Usar FormData para poder enviar imagen
      const formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append('nombre', $('#resena_nombre').val());
      formData.append('estrellas', inputEstrellas.val());
      formData.append('titulo', $('#resena_titulo').val());
      formData.append('comentario', $('#resena_comentario').val());

      // Agregar imagen si existe
      const imagenInput = $('#resena_imagen')[0];
      if (imagenInput.files.length > 0) {
        formData.append('imagen', imagenInput.files[0]);
      }

      $.ajax({
        url: "{{ route('tienda.producto.resena', $producto->slug) }}",
        method: 'POST',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Resetear botón primero
          btn.find('.btn-loading').addClass('d-none');
          btn.find('.btn-text').removeClass('d-none');

          // Cerrar modal
          $('#modalResena').modal('hide');

          // Limpiar formulario
          $('#formResena')[0].reset();
          clearStarsModal();
          inputEstrellas.val('');
          btn.prop('disabled', true);

          // Mostrar mensaje de éxito después de cerrar el modal
          setTimeout(function() {
            Swal.fire({
              title: response.aprobada ? '¡Reseña Publicada!' : '¡Gracias por tu reseña!',
              text: response.message || 'Tu reseña será revisada antes de ser publicada',
              icon: 'success',
              confirmButtonText: 'Entendido',
              confirmButtonColor: '#FF00C1'
            }).then(() => {
              if (response.aprobada) {
                location.reload();
              }
            });
          }, 300);
        },
        error: function(xhr, status, error) {
          // Resetear botón
          btn.find('.btn-loading').addClass('d-none');
          btn.find('.btn-text').removeClass('d-none');
          btn.prop('disabled', false);

          let errorMsg = 'Error al enviar la reseña. Por favor intenta nuevamente.';

          if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
              errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
            } else if (xhr.responseJSON.message) {
              errorMsg = xhr.responseJSON.message;
            }
          } else if (status === 'timeout') {
            errorMsg = 'La solicitud tardó demasiado. Por favor intenta nuevamente.';
          } else if (status === 'error' && !xhr.status) {
            errorMsg = 'Error de conexión. Verifica tu internet e intenta nuevamente.';
          }

          console.error('Error enviando reseña:', status, error, xhr.responseText);

          Swal.fire({
            title: 'Error',
            text: errorMsg,
            icon: 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc3545'
          });
        }
      });
    });

    // Resetear formulario cuando se cierra el modal
    $('#modalResena').on('hidden.bs.modal', function() {
      $('#formResena')[0].reset();
      clearStarsModal();
      inputEstrellas.val('');
      // Resetear estado del botón completamente
      btnEnviar.find('.btn-loading').addClass('d-none');
      btnEnviar.find('.btn-text').removeClass('d-none');
      btnEnviar.prop('disabled', true);
      $('#estrellas-error').hide();
      // Limpiar preview de imagen
      quitarImagenPreview();
    });

    // Preview de imagen al seleccionar
    $('#resena_imagen').on('change', function() {
      const file = this.files[0];
      if (file) {
        // Validar tamaño (5MB)
        if (file.size > 5 * 1024 * 1024) {
          Swal.fire({
            icon: 'error',
            title: 'Imagen muy grande',
            text: 'La imagen no puede superar los 5MB'
          });
          this.value = '';
          return;
        }

        // Validar tipo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
          Swal.fire({
            icon: 'error',
            title: 'Formato no válido',
            text: 'Solo se permiten imágenes JPG, PNG, GIF o WEBP'
          });
          this.value = '';
          return;
        }

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#preview-img').attr('src', e.target.result);
          $('#preview-imagen').removeClass('d-none');
        };
        reader.readAsDataURL(file);
      }
    });

    // Click en botones de reacción
    $('.reaction-btn').on('click', function() {
      const btn = $(this);
      const calificacionId = btn.data('calificacion');
      const emoji = btn.data('emoji');

      // Efecto visual inmediato
      btn.css('transform', 'scale(0.9)');
      setTimeout(() => btn.css('transform', ''), 150);

      $.ajax({
        url: `/resenas/${calificacionId}/reaccion`,
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          emoji: emoji
        },
        success: function(response) {
          // Actualizar conteos
          btn.find('.reaction-count').text(response.conteos[emoji] || 0);

          // Toggle clase active con animación
          if (response.accion === 'agregada') {
            btn.addClass('active');
            // Efecto de "pop"
            btn.css('transform', 'scale(1.2)');
            setTimeout(() => btn.css('transform', ''), 200);
          } else {
            btn.removeClass('active');
          }
        },
        error: function(xhr) {
          console.error('Error al reaccionar:', xhr);
        }
      });
    });
  });

  // Quitar preview de imagen
  function quitarImagenPreview() {
    $('#resena_imagen').val('');
    $('#preview-imagen').addClass('d-none');
    $('#preview-img').attr('src', '');
  }

  // Ampliar imagen de reseña
  function ampliarImagen(url) {
    $('#imagenAmpliada').attr('src', url);
    new bootstrap.Modal(document.getElementById('modalImagenResena')).show();
  }

  // ========== Respuestas ==========
  function mostrarFormRespuesta(id) {
    $('#form-respuesta-' + id).slideDown();
  }

  function ocultarFormRespuesta(id) {
    $('#form-respuesta-' + id).slideUp();
  }

  function enviarRespuesta(event, calificacionId) {
    event.preventDefault();
    const form = event.target;
    const btn = $(form).find('button[type="submit"]');
    const originalText = btn.html();

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
      url: `/resenas/${calificacionId}/respuesta`,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        nombre: $(form).find('input[name="nombre"]').val(),
        comentario: $(form).find('textarea[name="comentario"]').val()
      },
      success: function(response) {
        ocultarFormRespuesta(calificacionId);
        form.reset();

        Swal.fire({
          icon: 'success',
          title: response.aprobada ? '¡Respuesta publicada!' : '¡Gracias!',
          text: response.message,
          confirmButtonColor: '#FF00C1'
        }).then(() => {
          if (response.aprobada) {
            location.reload();
          }
        });
      },
      error: function(xhr) {
        btn.prop('disabled', false).html(originalText);
        let msg = 'Error al enviar la respuesta';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: msg
        });
      }
    });
  }

  // ========== Compartir ==========
  const productoNombre = @json($producto->nombre);
  const productoUrl = window.location.href.split('#')[0];

  function compartirWhatsApp() {
    const texto = `¡Mira este producto! ${productoNombre}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(texto + ' ' + productoUrl)}`, '_blank');
  }

  function compartirFacebook() {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productoUrl)}`, '_blank');
  }

  function copiarEnlace() {
    navigator.clipboard.writeText(productoUrl).then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Enlace copiado',
        text: 'Pégalo en Instagram o TikTok',
        timer: 2000,
        showConfirmButton: false
      });
    });
  }

  function compartirResenaWhatsApp(resenaId) {
    const url = productoUrl + '#resena-' + resenaId;
    const texto = `¡Mira esta reseña de ${productoNombre}!`;
    window.open(`https://wa.me/?text=${encodeURIComponent(texto + ' ' + url)}`, '_blank');
  }

  function compartirResenaFacebook(resenaId) {
    const url = productoUrl + '#resena-' + resenaId;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
  }

  function copiarEnlaceResena(resenaId) {
    const url = productoUrl + '#resena-' + resenaId;
    navigator.clipboard.writeText(url).then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Enlace copiado',
        text: 'Pégalo en Instagram o TikTok',
        timer: 2000,
        showConfirmButton: false
      });
    });
  }
</script>
@endpush