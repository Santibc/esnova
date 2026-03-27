# ANÁLISIS COMPLETO DEL TEMPLATE LIMA - TIENDANUBE

## INFORMACIÓN GENERAL

**Fuente:** https://lima-theme.mitiendanube.com
**Plataforma:** Tiendanube (E-commerce Argentina)
**Framework CSS:** Bootstrap 4 Grid + CSS Custom
**Slider:** Swiper 4.4.2
**Tipografías:** Lexend Exa (títulos), Lexend (cuerpo)

---

## 1. VARIABLES CSS (ROOT)

```css
:root {
  /* Fuentes */
  --heading-font: "Lexend Exa", sans-serif;
  --body-font: "Lexend", sans-serif;

  /* Tamaños de títulos */
  --h1: 28px;
  --h1-huge: 40px;
  --h1-huge-md: 54px;
  --h2: 24px;
  --h3: 20px;
  --h4: 18px;
  --h5: 16px;
  --h6: 14px;

  /* Tamaños de fuente */
  --font-hugest: 28px;
  --font-huge: 24px;
  --font-largest: 20px;
  --font-large: 18px;
  --font-big: 16px;
  --font-base: 14px;
  --font-small: 12px;
  --font-smallest: 10px;

  /* Peso de títulos */
  --title-font-weight: 700;
}
```

---

## 2. ESTRUCTURA HTML COMPLETA DEL HOME

### 2.1 HEADER (Cabecera)

```html
<header class="head-main">
    <!-- Topbar (opcional) -->
    <div class="section-topbar">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <!-- Enlaces secundarios -->
                    <ul class="list list-inline">
                        <li class="secondary-menu-item">
                            <a href="/contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <!-- Login/Account -->
                    <div class="utilities-container">
                        <a href="/account/login/">
                            <svg class="icon-inline utilities-icon">...</svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-auto">
                <div class="logo-img-container">
                    <a href="/" aria-label="Lima Theme">
                        <img class="logo-img" src="..." alt="Lima Theme">
                    </a>
                </div>
            </div>

            <!-- Buscador (desktop) -->
            <div class="col d-none d-md-block">
                <form action="/buscar" method="get">
                    <div class="form-group">
                        <input type="search" name="q" class="search-input" placeholder="Buscar...">
                        <button type="submit" class="search-input-submit">
                            <svg class="icon-inline">...</svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Utilities (Carrito, etc) -->
            <div class="col-auto">
                <div class="utilities-container">
                    <!-- Cart -->
                    <a href="#" class="btn-utility">
                        <svg class="icon-inline cart-icon">...</svg>
                        <span class="badge">0</span>
                    </a>
                </div>
            </div>

            <!-- Hamburger Menu (mobile) -->
            <div class="col-auto d-md-none">
                <button class="js-modal-open" data-toggle="#nav-hamburger">
                    <svg class="icon-inline">...</svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Desktop -->
    <div class="container-fluid d-none d-md-block">
        <nav class="nav-desktop">
            <ul class="nav-desktop-list">
                <li class="nav-item">
                    <a href="/coleccion" class="nav-list-link">colección</a>
                </li>
                <li class="nav-item">
                    <a href="/beauty" class="nav-list-link">beauty</a>
                </li>
                <li class="nav-item">
                    <a href="/lifestyle" class="nav-list-link">lifestyle</a>
                </li>
                <li class="nav-item">
                    <a href="/bazar" class="nav-list-link">bazar</a>
                </li>
                <li class="nav-item">
                    <a href="/sale" class="nav-list-link">sale</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Header Banners (iconos con texto) -->
    <div class="container-fluid d-none d-md-block">
        <div class="head-banners row align-items-center justify-content-end">
            <div class="col col-xl-auto head-banner-item">
                <a href="/retiro">
                    <div class="row align-items-center">
                        <div class="col-auto pr-0">
                            <img class="head-banner-item-image" src="..." alt="nuestros locales">
                        </div>
                        <div class="col pl-2 ml-1">
                            <div class="head-banner-text">nuestros locales</div>
                            <div class="btn-link">opciones de retiro</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col col-xl-auto head-banner-item">
                <a href="/envio">
                    <div class="row align-items-center">
                        <div class="col-auto pr-0">
                            <img class="head-banner-item-image" src="..." alt="entregas a domicilio">
                        </div>
                        <div class="col pl-2 ml-1">
                            <div class="head-banner-text">entregas a domicilio</div>
                            <div class="btn-link">opciones de envío</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</header>
```

**Clases importantes del Header:**
- `.head-main` - Contenedor principal del header
- `.section-topbar` - Barra superior (opcional)
- `.logo-img-container` - Contenedor del logo (max-width: 320px)
- `.logo-img` - Logo (max-height: 45px mobile, 60px desktop)
- `.logo-img-small` - Variante pequeña del logo (max-height: 30px)
- `.logo-img-big` - Variante grande del logo (max-height: 80px)
- `.nav-desktop` - Navegación desktop
- `.nav-desktop-list` - Lista de navegación
- `.nav-item` - Item de navegación
- `.nav-list-link` - Enlace de navegación
- `.head-banners` - Contenedor de banners de header
- `.head-banner-item` - Item individual de banner
- `.head-banner-text` - Texto del banner (font-size: 10px)
- `.utilities-icon` - Icono de utilidad (font-size: 20px en topbar, 16px en main)
- `.search-input` - Input de búsqueda
- `.search-input-submit` - Botón de búsqueda

---

### 2.2 SLIDER PRINCIPAL (Hero Carousel)

```html
<section class="section-slider-home" data-store="home-slider">
    <div class="js-home-main-slider-container home-slider-container">
        <!-- Desktop Slider -->
        <div class="js-home-main-slider-visibility d-none d-md-block">
            <div class="section-slider">
                <div class="js-home-slider nube-slider-home swiper-container" data-animation="false">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide slide-container">
                            <a href="/inflables" aria-label="Carrusel 1">
                                <div class="slider-slide">
                                    <!-- Imagen del slider -->
                                    <img width="1440" height="550"
                                         class="js-slider-image slider-image swiper-lazy fade-in"
                                         alt="Carrusel 1"
                                         srcset="...">
                                    <div class="placeholder-fade"></div>

                                    <!-- Contenido sobre la imagen -->
                                    <div class="container position-relative">
                                        <div class="swiper-text swiper-light">
                                            <div class="h1-huge mb-3">team verano</div>
                                            <p class="mb-3 pl-1">inflables, toallas y mucho más</p>
                                            <div class="btn btn-default d-inline-block">¡a ver!</div>
                                        </div>
                                    </div>

                                    <!-- Overlay opcional -->
                                    <div class="swiper-overlay swiper-overlay-light"></div>
                                </div>
                            </a>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide slide-container">
                            <div class="slider-slide">
                                <img width="1440" height="550"
                                     class="js-slider-image slider-image swiper-lazy fade-in"
                                     alt="Carrusel 2"
                                     srcset="...">
                                <div class="placeholder-fade"></div>
                                <div class="container position-relative"></div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide slide-container">
                            <div class="slider-slide">
                                <img width="1440" height="550"
                                     class="js-slider-image slider-image swiper-lazy fade-in"
                                     alt="Carrusel 3"
                                     srcset="...">
                                <div class="placeholder-fade"></div>
                                <div class="container position-relative"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Controles del slider -->
                    <div class="container position-relative">
                        <div class="swiper-arrows d-none d-md-block">
                            <div class="js-swiper-home-prev swiper-button-prev d-inline-block svg-circle svg-circle-big svg-icon-invert">
                                <svg class="icon-inline icon-lg">...</svg>
                            </div>
                            <div class="js-swiper-home-next swiper-button-next d-inline-block svg-circle svg-circle-big svg-icon-invert ml-2">
                                <svg class="icon-inline icon-lg">...</svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Paginación (mobile) -->
            <div class="js-swiper-home-pagination swiper-pagination position-relative d-block d-md-none my-3"></div>
        </div>

        <!-- Mobile Slider (similar structure with mobile-specific images) -->
        <div class="js-home-mobile-slider-visibility d-md-none">
            <!-- Similar structure but with images sized for mobile (597x735) -->
        </div>
    </div>
</section>
```

**Clases importantes del Slider:**
- `.section-slider-home` - Contenedor principal del slider
- `.nube-slider-home` - Contenedor Swiper (height: 100%)
- `.slide-container` - Contenedor de cada slide
- `.slider-slide` - Slide individual (background-size: cover, background-position: center)
- `.slider-image` - Imagen del slide (object-fit: cover, z-index: 1)
- `.slider-image-animation` - Animación de zoom en slide activo
- `.swiper-text` - Contenedor de texto sobre imagen (position: absolute, width: 100%)
- `.swiper-text-center` - Texto centrado (position: absolute, top: 50%, left: 50%, transform: translate(-50%, -50%))
- `.swiper-light` - Texto claro (para fondos oscuros)
- `.swiper-overlay` - Overlay sobre imagen (position: absolute, z-index: 1, height: 50%)
- `.swiper-overlay-light` - Overlay claro
- `.swiper-arrows` - Contenedor de flechas (position: absolute, right: 15px, bottom: 60px)
- `.placeholder-fade` - Placeholder con animación de fade

**Dimensiones de imágenes:**
- Desktop: 1440x550 px
- Mobile: 597x735 px
- Formato: WebP con fallback

---

### 2.3 CATEGORÍAS DESTACADAS

```html
<section class="section-categories-home position-relative" data-store="home-categories-featured">
    <div class="container">
        <!-- Header de sección -->
        <div class="row mb-3 pb-2">
            <div class="col">
                <h2 class="h3 mb-0">categorías</h2>
            </div>
        </div>

        <!-- Grid/Slider de categorías -->
        <div class="row">
            <div class="col-12 pr-0 pr-md-3 mb-4">
                <div class="js-swiper-categories swiper-container">
                    <div class="swiper-wrapper">
                        <!-- Categoría 1 -->
                        <div class="swiper-slide w-auto text-center pr-3 pr-md-0">
                            <a href="/anteojos" class="js-home-category" aria-label="Categoría 1">
                                <div class="home-category">
                                    <div class="home-category-image">
                                        <img src="..." class="swiper-lazy" alt="Categoría 1">
                                        <div class="placeholder-fade"></div>
                                    </div>
                                    <div class="home-category-name">anteojos</div>
                                </div>
                            </a>
                        </div>

                        <!-- Categoría 2 -->
                        <div class="swiper-slide w-auto text-center pr-3 pr-md-0">
                            <a href="/bolsos" class="js-home-category" aria-label="Categoría 2">
                                <div class="home-category">
                                    <div class="home-category-image">
                                        <img src="..." class="swiper-lazy" alt="Categoría 2">
                                        <div class="placeholder-fade"></div>
                                    </div>
                                    <div class="home-category-name">bolsos</div>
                                </div>
                            </a>
                        </div>

                        <!-- Más categorías... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

**Clases importantes de Categorías:**
- `.section-categories-home` - Contenedor de sección (padding: 25px 0)
- `.home-category` - Card de categoría (width: 110px, border-radius: 6px, overflow: hidden)
- `.home-category-image` - Contenedor de imagen (position: relative, width: 100%, height: 85px)
- `.home-category-image img/svg` - Imagen de categoría (position: absolute, top: 50%, left: 50%, transform: translate(-50%, -50%), object-fit: cover)
- `.home-category-name` - Nombre de categoría (padding: 7px 0, font-size: var(--font-small), text-overflow: ellipsis, -webkit-line-clamp: 2)
- `.home-category-placeholder` - Placeholder (background: #f9f9f9)

**Dimensiones:**
- Card: 110px de ancho
- Imagen: 85px de alto
- Nombre: máximo 2 líneas con ellipsis

---

### 2.4 PRODUCTOS DESTACADOS (FEATURED PRODUCTS)

```html
<section class="js-section-products-featured section-featured-home section-featured-home-colors" data-store="home-products-featured">
    <div class="js-products-featured-container container">
        <!-- Header con título y controles -->
        <div class="js-products-featured-controls-container row mb-3 pb-2 align-items-center">
            <div class="col">
                <h2 class="js-products-featured-title h3 mb-1">ingresos</h2>
            </div>
            <div class="js-products-featured-link-container col-auto text-right">
                <a href="/drinkware" class="js-products-featured-link-link btn-link">Ver todos</a>
            </div>
            <div class="js-products-featured-controls col-auto text-right d-none d-md-block">
                <div class="js-swiper-featured-prev swiper-button-prev d-inline-block svg-circle svg-icon-text">
                    <svg class="icon-inline icon-lg">...</svg>
                </div>
                <div class="js-swiper-featured-next swiper-button-next d-inline-block svg-circle svg-icon-text ml-2">
                    <svg class="icon-inline icon-lg">...</svg>
                </div>
            </div>
        </div>

        <!-- Layout: Imagen grande + Slider de productos -->
        <div class="js-featured-products-row row no-gutters">
            <!-- Imagen destacada (lado izquierdo) -->
            <div class="js-products-featured-image-container col-12 col-md-8">
                <div class="js-products-featured-image featured-product-image featured-product-image-with-slider">
                    <img class="lazyload"
                         src="..."
                         data-srcset="..."
                         alt="">
                </div>
            </div>

            <!-- Productos en slider (lado derecho) -->
            <div class="js-products-featured-grid-container col-12 col-md-4 featured-product-container featured-product-image-with-slider">
                <div class="js-swiper-featured swiper-container">
                    <div class="js-products-featured-grid js-products-home-grid swiper-wrapper">

                        <!-- PRODUCTO 1 -->
                        <div class="swiper-slide">
                            <div class="js-item-product item-product js-item-slide p-0 col-grid"
                                 data-product-type="list"
                                 data-product-id="151548502">

                                <div class="js-item-info-container item m-0">
                                    <div class="js-product-container position-relative">

                                        <!-- IMAGEN DEL PRODUCTO -->
                                        <div class="js-product-item-image-container product-item-image-container item-image">
                                            <div style="padding-bottom: 155.62310030395%;" class="js-item-image-padding position-relative d-block">
                                                <a href="/productos/medias-batik/" title="medias Batik" aria-label="medias Batik">

                                                    <!-- Imagen principal -->
                                                    <img width="658" height="1024"
                                                         src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                         data-srcset="..."
                                                         class="lazyload js-product-item-image product-item-image img-absolute img-absolute-centered fade-in product-item-image-featured"
                                                         alt="medias Batik">

                                                    <!-- Imagen secundaria (hover) -->
                                                    <img width="658" height="1024"
                                                         src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                         data-srcset="..."
                                                         class="lazyload js-product-item-image product-item-image img-absolute img-absolute-centered fade-in product-item-image-secondary item-image-secondary"
                                                         alt="medias Batik">

                                                    <div class="placeholder-fade"></div>
                                                </a>

                                                <!-- LABELS (sobre la imagen) -->
                                                <div class="labels labels-absolute">
                                                    <!-- Label sin stock -->
                                                    <div class="js-stock-label label label-default" style="display:none;">
                                                        Sin stock
                                                    </div>

                                                    <!-- Label envío gratis -->
                                                    <div class="js-shipping-label label" style="display: none;">
                                                        <span class="label-shipping-icon">
                                                            <svg class="icon-inline icon-w-24 icon-lg">...</svg>
                                                        </span>
                                                        <span class="label-shipping-text">Gratis</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- INFORMACIÓN DEL PRODUCTO -->
                                        <div class="item-description">
                                            <a href="/productos/medias-batik/" title="medias Batik" class="item-link">

                                                <!-- LABELS (debajo de la imagen) -->
                                                <div class="labels">
                                                    <!-- Label de oferta -->
                                                    <div class="js-offer-label label label-accent">
                                                        <span class="label-offer-percentage">
                                                            -<span class="js-offer-percentage">22</span>%
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Nombre del producto -->
                                                <div class="js-item-name item-name mb-2">medias Batik</div>

                                                <!-- Precio -->
                                                <div class="item-price-container mb-1">
                                                    <span class="js-price-display item-price mr-1" data-product-price="97900">
                                                        $979,00
                                                    </span>
                                                    <span class="js-compare-price-display price-compare mt-1 mb-1 ml-0">
                                                        $1.260,00
                                                    </span>
                                                </div>
                                            </a>

                                            <!-- BOTÓN DE COMPRA -->
                                            <div class="item-actions mt-3">
                                                <form class="js-product-form d-block" method="post" action="/comprar/">
                                                    <input type="hidden" name="add_to_cart" value="151548502">
                                                    <input type="submit"
                                                           class="js-addtocart btn btn-secondary btn-medium w-100 py-2 cart"
                                                           value="Comprar">

                                                    <!-- Placeholder de carga -->
                                                    <div class="js-addtocart-placeholder btn btn-secondary btn-block btn-transition btn-medium mb-1 py-2 disabled" style="display: none;">
                                                        <div class="d-inline-block">
                                                            <span class="js-addtocart-text">Comprar</span>
                                                            <span class="js-addtocart-success transition-container">¡Listo!</span>
                                                            <div class="js-addtocart-adding transition-container transition-icon">
                                                                <svg class="icon-inline btn-icon icon-spin">...</svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MÁS PRODUCTOS... -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

**Clases importantes de Productos:**
- `.section-featured-home` - Contenedor de sección de productos destacados
- `.featured-product-image` - Imagen grande del lado izquierdo (height: 100%, border-radius: 6px 6px 0 0, overflow: hidden)
- `.featured-product-image-with-slider` - Variante con slider (border-radius: 6px 0 0 6px en desktop)
- `.featured-product-container` - Contenedor de productos (height: 100%, padding: 20px, border-radius: 0 0 6px 6px)
- `.item` / `.card` - Card de producto (margin-bottom: 20px, border-radius: 6px, overflow: hidden)
- `.item-image` - Contenedor de imagen de producto (position: relative, max-height: 1200px, overflow: hidden)
- `.item-image img` - Imagen de producto (height: 100%, max-height: 1200px, width: auto)
- `.img-absolute` - Imagen posicionada absoluta (position: absolute, left: 0, width: 100%, height: auto, vertical-align: middle, z-index: 1)
- `.img-absolute-centered` - Imagen centrada (left: 50%, transform: translateX(-50%))
- `.item-description` - Descripción del producto (padding: 15px 10px)
- `.item-name` - Nombre del producto (line-height: 1.1429em, text-overflow: ellipsis, overflow: hidden, -webkit-line-clamp: 2)
- `.item-price-container` - Contenedor de precio (font-size: var(--font-small))
- `.item-price` - Precio principal (font-size: var(--font-big))
- `.price-compare` - Precio comparativo (tachado)
- `.item-actions` - Contenedor de acciones (botones)
- `.labels` - Contenedor de etiquetas
- `.labels-absolute` - Etiquetas sobre la imagen (position: absolute)
- `.label` - Etiqueta individual
- `.label-accent` - Etiqueta de descuento
- `.label-default` - Etiqueta por defecto (sin stock)
- `.label-offer-percentage` - Porcentaje de descuento
- `.product-item-image-featured` - Imagen principal
- `.product-item-image-secondary` - Imagen secundaria (hover)
- `.fade-in` - Animación de fade in (opacity: 0, transition: opacity .2s)
- `.lazyload` / `.lazyloaded` - Lazy loading de imágenes

**Hover Effects:**
```css
.product-item-secondary-images-loaded:not(.product-item-secondary-images-disabled):hover .item-image-featured {
  opacity: 0;
  transition-delay: .05s;
}
.product-item-secondary-images-loaded:not(.product-item-secondary-images-disabled):hover .item-image-featured ~ .item-image-secondary {
  opacity: 1;
}
```

---

### 2.5 BANNERS PROMOCIONALES

```html
<section class="section-banners-home" data-store="home-banner-categories">
    <div class="js-banners js-banner-container container">
        <div class="row">
            <div class="js-banner-col col-12">
                <div class="js-banner-row banners-row row row-grid">

                    <!-- BANNER 1 -->
                    <div class="js-banner banner-container col-grid col-md-6">
                        <div class="js-textbanner textbanner text-center">
                            <a href="/accesorios" class="textbanner-link" aria-label="Carrusel 1">
                                <!-- Imagen del banner -->
                                <div class="js-textbanner-image-container textbanner-image p-0">
                                    <img width="930" height="464"
                                         data-src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                         data-sizes="auto"
                                         data-expand="-10"
                                         data-srcset="..."
                                         class="js-textbanner-image textbanner-image-effect img-fluid d-block w-100 lazyload fade-in"
                                         alt="">
                                    <div class="placeholder-fade"></div>

                                    <!-- Texto sobre la imagen -->
                                    <div class="js-textbanner-text textbanner-text over-image">
                                        <h3 class="h1 mb-0">a mimir</h3>
                                        <div class="textbanner-paragraph">todo para ir a la cama con onda</div>
                                        <div class="btn btn-secondary btn-medium d-inline-block mt-2">me interesa</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- BANNER 2 -->
                    <div class="js-banner banner-container col-grid col-md-6">
                        <div class="js-textbanner textbanner text-center">
                            <a href="/verano" class="textbanner-link" aria-label="Carrusel 2">
                                <div class="js-textbanner-image-container textbanner-image p-0">
                                    <img width="930" height="465"
                                         data-src="..."
                                         data-srcset="..."
                                         class="js-textbanner-image textbanner-image-effect img-fluid d-block w-100 lazyload fade-in"
                                         alt="">
                                    <div class="placeholder-fade"></div>
                                    <div class="js-textbanner-text textbanner-text over-image">
                                        <h3 class="h1 mb-0">vamos a la playa</h3>
                                        <div class="textbanner-paragraph">todo lo que necesitás para la arena y el sol</div>
                                        <div class="btn btn-secondary btn-medium d-inline-block mt-2">¡quiero!</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
```

**Clases importantes de Banners:**
- `.section-banners-home` - Contenedor de sección (padding: 50px 0)
- `.textbanner` - Card de banner (position: relative, margin-bottom: 15px, overflow: hidden)
- `.textbanner-no-border` - Sin borde (border: 0, border-radius: 0)
- `.textbanner-link` - Enlace del banner (display: block, width: 100%, height: 100%)
- `.textbanner-image` - Contenedor de imagen (position: relative, padding-top: 50%, overflow: hidden)
- `.textbanner-image-md` - Imagen para desktop (padding-top: 25%)
- `.textbanner-image-background` - Imagen de fondo (position: absolute, top: 0, width: 100%, height: 100%, object-fit: cover)
- `.textbanner-text` - Contenedor de texto (position: relative, padding: 20px 15px)
- `.textbanner-text.over-image` - Texto sobre imagen (position: absolute, bottom: 0, z-index: 9, width: 100%, border: 0)
- `.textbanner-paragraph` - Párrafo (margin: 10px 0, font-size: var(--font-small), overflow: hidden, text-overflow: ellipsis, -webkit-line-clamp: 3)
- `.textbanner-image-empty` - Placeholder cuando no hay imagen (background-image: url('data:image/svg+xml...'))

**Dimensiones sugeridas:**
- Banners: 930x464 px o 930x465 px
- Proporción: ~2:1

---

### 2.6 MENSAJE DE BIENVENIDA

```html
<section class="section-welcome-home" data-store="home-welcome-message">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-6 text-center">
                <h2 class="js-welcome-message-title h1">dale color a tu vida</h2>
                <p class="js-welcome-message-text">encontrá los objetos más divertidos</p>
            </div>
        </div>
    </div>
</section>
```

**Clases importantes:**
- `.section-welcome-home` - Contenedor de sección (padding: 70px 0)

---

### 2.7 PRODUCTO DESTACADO (SINGLE PRODUCT)

```html
<section id="single-product" class="js-product-container section-featured-home">
    <div class="container">
        <div class="row">
            <!-- IMÁGENES DEL PRODUCTO (Lado izquierdo) -->
            <div class="col-12 col-md-8">
                <div class="row">
                    <!-- Thumbnails (desktop) -->
                    <div class="col-12 col-md-2 d-none d-md-block order-last order-md-0 pr-0 pr-md-3">
                        <div class="js-swiper-product-thumbs swiper-product-thumb mb-3 swiper-container">
                            <div class="swiper-wrapper">
                                <!-- Thumbnail 1 -->
                                <div class="swiper-slide w-auto">
                                    <a href="#" class="js-product-thumb product-thumb d-block position-relative mr-3 mr-md-0 mb-md-3 selected"
                                       style="padding-bottom: 155.62310030395%;"
                                       data-thumb-loop="0">
                                        <img data-sizes="auto"
                                             src="..."
                                             data-srcset="..."
                                             class="img-absolute img-absolute-centered lazyload"
                                             alt="medias Girl Power">
                                    </a>
                                </div>
                                <!-- Más thumbnails... -->
                            </div>
                        </div>

                        <!-- Controles de thumbnails -->
                        <div class="js-swiper-product-thumb-controls text-center d-none d-md-block">
                            <div class="js-swiper-product-thumbs-prev swiper-button-prev swiper-product-thumb-control d-inline-block svg-circle svg-icon-text">
                                <svg class="icon-inline icon-lg">...</svg>
                            </div>
                            <div class="js-swiper-product-thumbs-next swiper-button-next swiper-product-thumb-control d-inline-block svg-circle svg-icon-text ml-2">
                                <svg class="icon-inline icon-lg">...</svg>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen principal -->
                    <div class="product-image-container col-12 col-md-10">
                        <div class="js-swiper-product swiper-container mb-3">
                            <!-- Labels flotantes -->
                            <div class="labels labels-product-slider labels-absolute">
                                <div class="js-stock-label label label-default" style="display:none;">
                                    Sin stock
                                </div>
                                <div class="js-shipping-label label" style="display: none;">
                                    <span class="label-shipping-icon">
                                        <svg class="icon-inline icon-w-24 icon-lg">...</svg>
                                    </span>
                                    <span class="label-shipping-text">Gratis</span>
                                </div>
                            </div>

                            <div class="swiper-wrapper">
                                <!-- Imagen 1 -->
                                <div class="swiper-slide js-product-slide slider-slide" data-image="421581390" data-image-position="0">
                                    <div class="js-product-slide-link d-block position-relative" style="padding-bottom: 155.62310030395%;">
                                        <img data-src="..."
                                             data-srcset="..."
                                             class="js-product-slide-img product-slider-image img-absolute img-absolute-centered lazyload"
                                             width="658"
                                             height="1024"
                                             alt="medias Girl Power">
                                    </div>
                                </div>
                                <!-- Más imágenes... -->
                            </div>
                        </div>

                        <!-- Paginación (mobile) -->
                        <div class="d-block d-md-none">
                            <div class="js-swiper-product-pagination swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN DEL PRODUCTO (Lado derecho) -->
            <div class="col pt-4">
                <!-- Labels -->
                <div class="labels labels-product-slider">
                    <div class="js-offer-label label label-accent">
                        <span class="label-offer-percentage">
                            -<span class="js-offer-percentage">22</span>%
                        </span>
                    </div>
                </div>

                <!-- Nombre del producto -->
                <h1 class="js-product-name mb-3">medias Girl Power</h1>

                <!-- Precio -->
                <div class="mb-3">
                    <span class="js-price-display price-big mr-2">$960,00</span>
                    <span class="js-compare-price-display price-compare">$1.200,00</span>
                </div>

                <!-- Descripción corta -->
                <div class="mb-4">
                    <p>Medias de algodón con diseño girl power.</p>
                </div>

                <!-- Variantes -->
                <div class="js-product-variants-container mb-4">
                    <div class="js-product-variants">
                        <!-- Color -->
                        <div class="js-product-variants-group">
                            <label class="form-label">Color</label>
                            <div class="js-product-variants-group-container">
                                <button class="btn-variant" data-option="0" data-value="Blanco">
                                    <span class="btn-variant-content" style="background: #FFFFFF; border: 1px solid #eee" data-name="Blanco"></span>
                                </button>
                                <button class="btn-variant" data-option="0" data-value="Negro">
                                    <span class="btn-variant-content" style="background: #000000; border: 1px solid #eee" data-name="Negro"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cantidad -->
                <div class="mb-4">
                    <label class="form-label">Cantidad</label>
                    <div class="js-quantity-container quantity-container">
                        <button class="js-quantity-down quantity-button">-</button>
                        <input type="number" class="js-quantity-input quantity-input" value="1" min="1">
                        <button class="js-quantity-up quantity-button">+</button>
                    </div>
                </div>

                <!-- Botón de compra -->
                <form method="post" action="/comprar/">
                    <input type="hidden" name="add_to_cart" value="151537120">
                    <input type="submit" class="js-addtocart btn btn-primary btn-block py-3" value="Comprar">
                </form>

                <!-- Información adicional -->
                <div class="mt-4">
                    <div class="accordion">
                        <!-- Descripción -->
                        <div class="accordion-item">
                            <button class="accordion-header">
                                <span>Descripción</span>
                                <svg class="icon-inline">...</svg>
                            </button>
                            <div class="accordion-content">
                                <p>Descripción completa del producto...</p>
                            </div>
                        </div>

                        <!-- Envío -->
                        <div class="accordion-item">
                            <button class="accordion-header">
                                <span>Envío</span>
                                <svg class="icon-inline">...</svg>
                            </button>
                            <div class="accordion-content">
                                <p>Información de envío...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
```

**Clases importantes del Producto:**
- `.swiper-product-thumb` - Contenedor de thumbnails
- `.product-thumb` - Thumbnail individual (d-block, position: relative)
- `.product-thumb.selected` - Thumbnail seleccionado
- `.product-image-container` - Contenedor de imagen principal
- `.product-slider-image` - Imagen principal (position: absolute, left: 50%, width: auto)
- `.labels-product-slider` - Labels del producto
- `.price-big` - Precio grande
- `.btn-variant` - Botón de variante
- `.btn-variant-content` - Contenido del botón de variante (puede tener color de fondo)
- `.quantity-container` - Contenedor de cantidad
- `.quantity-button` - Botón de cantidad (+/-)
- `.quantity-input` - Input de cantidad

---

### 2.8 FOOTER

```html
<footer class="footer">
    <div class="container">
        <!-- Newsletter -->
        <div class="row mb-5 pb-3">
            <div class="col-md-6 mx-auto text-center">
                <h3 class="h4 mb-3">¡Suscribite a nuestro newsletter!</h3>
                <p class="mb-4">Recibí novedades y promociones</p>
                <form action="/newsletter" method="post">
                    <div class="form-row">
                        <div class="col">
                            <input type="email" name="email" class="form-control" placeholder="Tu email" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Suscribirse</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Links -->
        <div class="row mb-4">
            <!-- Columna 1 -->
            <div class="col-6 col-md-3 mb-4">
                <h4 class="h6 mb-3">Categorías</h4>
                <ul class="list list-unstyled">
                    <li class="list-item mb-2">
                        <a href="/coleccion">Colección</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/beauty">Beauty</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/lifestyle">Lifestyle</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/bazar">Bazar</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 2 -->
            <div class="col-6 col-md-3 mb-4">
                <h4 class="h6 mb-3">Ayuda</h4>
                <ul class="list list-unstyled">
                    <li class="list-item mb-2">
                        <a href="/contacto">Contacto</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/preguntas-frecuentes">Preguntas frecuentes</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/cambios">Cambios y devoluciones</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/envios">Envíos</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 3 -->
            <div class="col-6 col-md-3 mb-4">
                <h4 class="h6 mb-3">Legal</h4>
                <ul class="list list-unstyled">
                    <li class="list-item mb-2">
                        <a href="/terminos">Términos y condiciones</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/privacidad">Política de privacidad</a>
                    </li>
                    <li class="list-item mb-2">
                        <a href="/botones-arrepentimiento">Botón de arrepentimiento</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 4 - Redes sociales -->
            <div class="col-6 col-md-3 mb-4">
                <h4 class="h6 mb-3">Seguinos</h4>
                <div class="social-links">
                    <a href="#" class="social-icon" target="_blank" rel="noopener">
                        <svg class="icon-inline icon-2x">...</svg>
                    </a>
                    <a href="#" class="social-icon" target="_blank" rel="noopener">
                        <svg class="icon-inline icon-2x">...</svg>
                    </a>
                    <a href="#" class="social-icon" target="_blank" rel="noopener">
                        <svg class="icon-inline icon-2x">...</svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col text-center">
                <div class="divider mb-4"></div>
                <p class="font-small">&copy; 2025 Lima Theme. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>
```

**Clases importantes del Footer:**
- `.footer` - Contenedor del footer
- `.social-icon` - Icono social (padding: 0 10px, margin-right: 15px)
- `.divider` - Línea divisoria

---

## 3. ESTILOS CSS COMPLETOS

### 3.1 RESET Y BASE

```css
body {
  margin: 0;
  font-size: var(--font-base);
}

a {
  text-decoration: none;
}

p {
  margin-top: 0;
  line-height: 1.4286em;
}
```

### 3.2 LAYOUTS Y WRAPPERS

```css
.container {
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

@media (min-width: 576px) {
  .container {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
  }
}

@media (min-width: 1400px) {
  .container {
    max-width: 1300px;
  }
}

.container-narrow {
  max-width: 680px;
}

.row-grid {
  margin-right: -10px;
  margin-left: -10px;
}

.col-grid {
  padding-right: 10px;
  padding-left: 10px;
}
```

### 3.3 PLACEHOLDERS Y LOADERS

```css
.placeholder-line-medium {
  height: 25px;
  border-radius: 6px;
}

.placeholder-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
}

.placeholder-full-height {
  position: relative;
  height: 100%;
}

.placeholder-shine {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0.5;
  -moz-animation: placeholder-shine 1.5s infinite;
  -webkit-animation: placeholder-shine 1.5s infinite;
  animation: placeholder-shine 1.5s infinite;
}

@keyframes placeholder-shine {
  0% {
    opacity: 0.1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 0.1;
  }
}

.placeholder-fade {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0.1;
  -moz-animation: placeholder-fade 1.5s infinite;
  -webkit-animation: placeholder-fade 1.5s infinite;
  animation: placeholder-fade 1.5s infinite;
}

@keyframes placeholder-fade {
  0% {
    opacity: 0.2;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 0.2;
  }
}

.blur-up {
  position: absolute;
  top: 0;
  -webkit-filter: blur(4px);
  filter: blur(4px);
  -moz-filter: blur(4px);
  -ms-filter: blur(4px);
  -o-filter: blur(4px);
  transition: opacity .2s, -webkit-filter .2s;
}

.blur-up-huge {
  -webkit-filter: blur(6px);
  filter: blur(6px);
  -moz-filter: blur(6px);
  -ms-filter: blur(6px);
  -o-filter: blur(6px);
  transition: filter .2s, -webkit-filter .2s;
}

.blur-up.lazyloaded,
.blur-up-huge.lazyloaded,
.blur-up.swiper-lazy-loaded,
.blur-up-huge.swiper-lazy-loaded {
  -webkit-filter: none;
  filter: none;
  -moz-filter: none;
  -ms-filter: none;
  -o-filter: none;
}

.fade-in {
  opacity: 0;
  transition: opacity .2s;
}

.fade-in.lazyloaded,
.fade-in.swiper-lazy-loaded {
  opacity: 1;
}

.spinner {
  position: relative;
  display: grid;
  place-items: center;
}

.spinner::before,
.spinner::after {
  content: '';
  box-sizing: border-box;
  position: absolute;
}

.spinner::before {
  width: 100%;
  height: 2px;
  border-radius: 10px;
  animation: spinner-line 0.8s cubic-bezier(0, 0, 0.03, 0.9) infinite;
}

@keyframes spinner-line {
  0%, 44%, 88.1%, 100% {
    transform-origin: left;
  }

  0%, 100%, 88% {
    transform: scaleX(0);
  }

  44.1%, 88% {
    transform-origin: right;
  }

  33%, 44% {
    transform: scaleX(1);
  }
}
```

### 3.4 ANIMACIONES

```css
.transition-up,
.fade-in-vertical {
  opacity: 0;
}

[data-transition="fade-in-up"] {
  transition: all 1s ease;
  opacity: 0;
  transform: translateY(30px);
}

[data-transition="fade-in-up"].is-inViewport,
.swiper-slide-duplicate [data-transition="fade-in-up"] {
  transition: all 1s ease;
  opacity: 1;
  transform: translateY(0px);
}
```

### 3.5 BOTONES

```css
.btn-whatsapp {
  position: fixed;
  bottom: 15px;
  right: 15px;
  z-index: 100;
  color: white;
  background-color: #4dc247;
  box-shadow: 0 0 6px rgba(0,0,0,0.3);
  border-radius: 50%;
}

.btn-whatsapp svg {
  width: 45px;
  height: 45px;
  padding: 10px;
  fill: white;
  vertical-align: middle;
}
```

### 3.6 ICONOS

```css
.icon-inline {
  display: inline-block;
  font-size: inherit;
  height: 1em;
  overflow: visible;
  vertical-align: -.125em;
}

.icon-xs {
  font-size: .75em;
}

.icon-md {
  font-size: .875em;
}

.icon-lg {
  font-size: 1.33333em;
  line-height: .75em;
  vertical-align: -.0667em;
}

.icon-2x {
  font-size: 2em;
}

.icon-2x-half {
  font-size: 2.5em;
}

.icon-3x {
  font-size: 3em;
}

.icon-4x {
  font-size: 4em;
}

.icon-5x {
  font-size: 5em;
}

.icon-6x {
  font-size: 6em;
}

.icon-7x {
  font-size: 7em;
}

.icon-8x {
  font-size: 8em;
}

.icon-9x {
  font-size: 9em;
}

.icon-w {
  text-align: center;
  width: 1.25em;
}

.icon-w-24 {
  width: 1.5em;
}

.icon-spin {
  -webkit-animation: icon-spin .5s infinite linear;
  animation: icon-spin .5s infinite linear;
}

@-webkit-keyframes icon-spin {
  0% {
    -webkit-transform: rotate(0);
    transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@keyframes icon-spin {
  0% {
    -webkit-transform: rotate(0);
    transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

.social-icon {
  padding: 0 10px;
  margin-right: 15px;
}
```

### 3.7 TÍTULOS Y TIPOGRAFÍA

```css
.h1-huge {
  font-size: var(--h1-huge);
  font-weight: var(--title-font-weight);
}

@media (min-width: 768px) {
  .h1-huge {
    font-size: var(--h1-huge-md);
  }
}

h1,
.h1 {
  font-size: var(--h1);
  font-weight: var(--title-font-weight);
}

h2,
.h2 {
  font-size: var(--h2);
  font-weight: var(--title-font-weight);
}

h3,
.h3 {
  font-size: var(--h3);
  font-weight: var(--title-font-weight);
}

h4,
.h4 {
  font-size: var(--h4);
  font-weight: var(--title-font-weight);
}

h5,
.h5 {
  font-size: var(--h5);
  font-weight: var(--title-font-weight);
}

h6,
.h6 {
  font-size: var(--h6);
  font-weight: var(--title-font-weight);
}

.breadcrumbs {
  display: inline-block;
  margin-bottom: 10px;
  font-size: var(--font-smallest);
  font-weight: 400;
}

.breadcrumbs .separator {
  margin: 3px;
  line-height: 14px;
  opacity: 0.6;
}

.breadcrumbs .crumb {
  opacity: 0.6;
}

.breadcrumbs .crumb.active {
  opacity: 1;
}

.font-hugest {
  font-size: var(--font-hugest) !important;
}

.font-huge {
  font-size: var(--font-huge) !important;
}

.font-largest {
  font-size: var(--font-largest);
}

.font-large {
  font-size: var(--font-large);
}

.font-big {
  font-size: var(--font-big);
}

.font-body {
  font-size: var(--font-base) !important;
}

.font-small {
  font-size: var(--font-small) !important;
}

.font-smallest {
  font-size: var(--font-smallest) !important;
}

.subtitle {
  font-size: var(--font-smallest);
  text-transform: uppercase;
  letter-spacing: 1px;
}
```

### 3.8 SLIDERS (SWIPER)

```css
.nube-slider-home {
  height: 100%;
}

.swiper-wrapper.disabled {
  transform: translate3d(0px, 0, 0) !important;
}

.slide-container {
  overflow: hidden;
}

.slider-slide {
  height: 100%;
  background-position: center;
  background-size: cover;
  overflow: hidden;
}

.slider-image {
  position: relative;
  z-index: 1;
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.swiper-slide-active .slider-image-animation {
  -webkit-animation: zoomin 20s ease forwards;
  -moz-animation: zoomin 20s ease forwards;
  animation: zoomin 20s ease forwards;
}

@-webkit-keyframes zoomin {
  0% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
  100% {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
}

@keyframes zoomin {
  0% {
    -webkit-transform: scale(1);
    transform: scale(1);
  }
  100% {
    -webkit-transform: scale(1.2);
    transform: scale(1.2);
  }
}

.swiper-button-next,
.swiper-button-prev {
  position: relative;
  top: initial;
  margin: 0;
  line-height: 28px;
}

.swiper-button-absolute {
  position: absolute;
  top: 50%;
  margin-top: -14px;
}

.swiper-button-absolute.swiper-button-next {
  right: 0;
}

.swiper-button-absolute.swiper-button-prev {
  left: 0;
}

.swiper-pagination {
  left: 50%;
  transform: translateX(-50%);
}

.swiper-pagination-bullet {
  margin: 0 4px;
  border-radius: 6px;
}

.swiper-pagination-bullet-active {
  width: 12px;
}

.swiper-text {
  position: absolute;
  z-index: 9;
  width: 100%;
}

.swiper-text-center {
  position: absolute;
  width: 92%;
  top: 50%;
  bottom: auto;
  left: 50%;
  padding: 0 25px;
  text-align: center;
  transform: translate(-50%, -50%);
}

@media (min-width: 768px) {
  .swiper-text {
    max-width: 800px;
  }
}

.swiper-arrows {
  position: absolute;
  right: 15px;
  bottom: 60px;
}

.swiper-overlay {
  position: absolute;
  z-index: 1;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 50%;
}

.swiper-button-next.swiper-button-disabled,
.swiper-button-prev.swiper-button-disabled {
  opacity: 0.2;
}
```

### 3.9 LISTAS

```css
.list {
  padding: 0;
  list-style-type: none;
}

.list .list-item {
  position: relative;
  margin-bottom: 10px;
  cursor: default;
}

.list-unstyled {
  padding: 0;
  margin: 0;
  list-style-type: none;
}

.list-inline li {
  display: inline-flex;
}
```

### 3.10 NOTIFICACIONES

```css
.notification {
  padding: 10px 15px;
  text-align: center;
}

.notification-floating {
  position: absolute;
  left: 0;
  z-index: 2000;
  width: 100%;
}

.notification-fixed {
  position: fixed;
  right: 0;
  left: initial;
  width: calc(100% - 20px);
  margin-top: -10px;
}

@media (min-width: 768px) {
  .notification-floating {
    margin-top: -10px;
  }

  .notification-fixed {
    right: 10px;
    width: 25%;
  }
}

.notification-floating .notification {
  margin: 15px;
  border-radius: 6px;
}

.notification-close {
  padding: 0 5px;
}

.notification-centered {
  display: inline-block;
  width: 100%;
  margin: 0 20px 20px 0;
  text-align: center;
}

.notification-left {
  display: inline-block;
  margin: 5px 0 20px 0;
  text-align: left;
  font-size: 12px;
}

.notification-fixed-bottom {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 999;
  width: 100%;
  font-size: 12px;
}

.notification-above {
  z-index: 9000;
}
```

### 3.11 BADGES

```css
.badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 0 5px;
  font-size: 12px;
  font-weight: bold;
  line-height: 14px;
  border-radius: 10px;
}
```

### 3.12 TABLAS

```css
.table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}

.table thead th {
  padding: 8px;
}

.table thead th:first-of-type {
  padding-left: 0;
}

.table td {
  padding: 8px;
  text-align: left;
}
```

### 3.13 TOOLTIPS

```css
.tooltip {
  position: absolute;
  left: -170%;
  z-index: 9999;
  width: 340px;
  padding: 15px 5px;
  text-align: center;
  border-radius: 10px;
}

.tooltip-top {
  bottom: calc(100% + 20px);
}

.tooltip-bottom {
  top: 95%;
}

.tooltip-arrow {
  position: absolute;
  right: 30%;
  width: 0;
  height: 0;
  pointer-events: none;
}

.tooltip-bottom .tooltip-arrow {
  top: -8px;
  right: 15%;
}

.tooltip-top .tooltip-arrow {
  bottom: -8px;
  transform: rotate(180deg);
}
```

### 3.14 IMÁGENES

```css
.img-absolute {
  position: absolute;
  left: 0;
  width: 100%;
  height: auto;
  vertical-align: middle;
  text-indent: -9999px;
  z-index: 1;
}

.img-absolute-centered {
  left: 50%;
  transform: translateX(-50%) !important;
  -webkit-transform: translateX(-50%) !important;
  -ms-transform: translateX(-50%) !important;
}

.card-img {
  margin: 0 5px 5px 0;
  border: 1px solid #00000012;
}

.card-img-small {
  height: 25px;
}

.card-img-medium {
  height: 35px;
}

.card-img-big {
  height: 50px;
}

.card-img-square-container {
  position: relative;
  width: 100%;
  padding-top: 100%;
}

.card-img-square {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-img-pill {
  position: absolute;
  right: 5px;
  bottom: 5px;
  z-index: 999;
  padding: 4px 9px;
  font-size: 10px;
  border-radius: 15px;
}
```

### 3.15 FORMULARIOS

```css
.form-group {
  position: relative;
  width: 100%;
}

.radio-button {
  margin-bottom: 0;
  -webkit-tap-highlight-color: rgba(0,0,0,0);
  cursor: pointer;
}

.radio-button.disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.radio-button-container {
  display: inline-block;
  width: 100%;
}

.radio-button-content {
  position: relative;
  width: 100%;
  float: left;
  margin-bottom: 10px;
  padding: 15px 15px 15px 25px;
  border-radius: 6px;
}

.radio-button-icons-container {
  position: absolute;
  top: 15px;
  left: 10px;
}

.radio-button-icon {
  width: 16px;
  height: 16px;
  border-radius: 50%;
}

.radio-button input[type="radio"] {
  display: none;
}

.radio-button input[type="radio"] + .radio-button-content .unchecked {
  display: block;
}

.radio-button input[type="radio"] + .radio-button-content .checked {
  position: absolute;
  top: 8px;
  left: 8px;
  width: 0;
  height: 0;
  transform: translate(-50%, -50%);
  transition: all 0.2s;
}

.radio-button input[type="radio"]:checked + .radio-button-content .checked {
  width: 8px;
  height: 8px;
}

.form-select-icon {
  position: absolute;
  right: 12px;
  bottom: 10px;
  font-size: 18px;
  pointer-events: none;
}

.form-group-small .form-select-icon {
  bottom: 10px;
  right: 10px;
  font-size: 12px;
}

.form-row {
  width: auto;
  display: flex;
  flex-wrap: wrap;
  margin-right: -5px;
  margin-left: -5px;
  clear: both;
}

.form-row > .col,
.form-row > [class*=col-] {
  padding-right: 5px;
  padding-left: 5px;
}

.form-label {
  font-size: var(--font-small);
}

.form-label-divider {
  padding: 10px 0;
  margin-bottom: 10px;
}

.form-toggle-eye {
  position: absolute;
  bottom: 5%;
  right: 2px;
  display: inline-block;
  padding: 10px;
  background: none;
  border: 0;
}

.checkbox-container .checkbox {
  position: relative;
  display: block;
  margin-bottom: 15px;
  padding-left: 25px;
  font-weight: normal;
  text-decoration: none;
  transition: all 0.4s ease;
  cursor: pointer;
}

@media (min-width: 768px) {
  .checkbox-container .checkbox {
    margin-bottom: 20px;
  }
}

.checkbox-container .checkbox-icon {
  position: absolute;
  top: 1px;
  left: 0;
  display: block;
  width: 16px;
  height: 16px;
  border-radius: 100%;
}

.checkbox-container .checkbox-icon:after {
  position: absolute;
  top: 2px;
  left: 5px;
  display: none;
  width: 4px;
  height: 9px;
  content: '';
  transform: rotate(45deg);
}

.checkbox-container .checkbox-text.with-color {
  padding-right: 5px;
}

.checkbox-container .checkbox-color {
  display: inline-block;
  width: 10px;
  height: 10px;
  margin-top: 4px;
  vertical-align: top;
  border-radius: 50%;
}

.checkbox-container input {
  display: none;
}

.checkbox-container input:checked ~ .checkbox {
  opacity: 1;
}

.checkbox-container input:checked ~ .checkbox .checkbox-icon:after {
  display: block;
}
```

### 3.16 VIDEO

```css
.embed-responsive {
  position: relative;
  display: block;
  height: 0;
  padding: 0;
  overflow: hidden;
}

.embed-responsive.embed-responsive-16by9 {
  padding-bottom: 56.25%;
}

.embed-responsive.embed-responsive-1by1 {
  padding-bottom: 140%;
}

.embed-responsive .embed-responsive-item,
.embed-responsive embed,
.embed-responsive iframe,
.embed-responsive object,
.embed-responsive video {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}

.video-player {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.video-player-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 40px;
  height: 40px;
  margin: -20px 0 0 -20px;
  padding-left: 3px;
  font-size: 20px;
  line-height: 36px;
  text-align: center;
  border-radius: 100%;
  pointer-events: none;
}

.video-player-icon-small {
  width: 24px;
  height: 24px;
  margin: -12px 0 0 -12px;
  padding-left: 2px;
  font-size: 13px;
  line-height: 21px;
}

.video-image {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 100%;
  height: auto;
  transform: translate(-50%, -50%);
}
```

### 3.17 HEADER Y NAVEGACIÓN

```css
.head-main {
  top: 0;
  width: 100%;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  z-index: 1040;
}

.head-main .btn-utility {
  padding: 0;
}

.head-main .search-input {
  font-size: 12px;
}

.btn-utility .cart-icon {
  vertical-align: text-top;
}

.col-utility {
  padding-left: 5px;
}

/* Adbar & Topbar */

.section-adbar,
.section-topbar {
  z-index: 0;
  width: 100%;
  min-height: 30px;
  padding: 5px 15px;
}

.section-adbar {
  line-height: 20px;
}

.section-topbar .utilities-icon {
  font-size: 16px;
}

.section-topbar .list {
  margin: 0;
}

@keyframes marquee {
  0% {
    transform: translateX(0);
    -webkit-transform: translateX(0);
  }
  100% {
    transform: translateX(-100%);
    -webkit-transform: translateX(-100%);
  }
}

.secondary-menu-item {
  display: inline-block;
  margin-right: 30px;
  font-size: var(--font-small);
}

/* Navigation */

.menu-and-banners-row {
  visibility: hidden;
}

.nav-desktop {
  position: relative;
  width: 100%;
}

.nav-desktop-list {
  margin: 0;
  padding: 10px 0;
  list-style: none;
  white-space: nowrap !important;
}

.nav-desktop-list::-webkit-scrollbar {
  height: 0;
}

.nav-desktop-with-scroll {
  margin: 0 30px 0 40px;
  padding: 10px 0;
  overflow: scroll;
}

.nav-desktop-list-arrow {
  position: absolute;
  top: 0;
  width: 40px;
  height: 48px;
  cursor: pointer;
}

.nav-desktop-list-arrow-left {
  left: 0;
}

.nav-desktop-list-arrow-right {
  right: 0;
}

.nav-desktop-list-arrow.disable {
  opacity: 0.2;
  cursor: initial;
}

.nav-desktop-list-arrow .svg-circle {
  width: 25px;
  height: 25px;
  margin-top: calc(50% - 8px);
  padding: 5px;
}

.nav-item {
  display: inline-block;
  position: initial;
  padding: 0 10px;
  white-space: normal;
}

.nav-list-link {
  padding: 0 5px 0 0;
  line-height: 30px;
}

.desktop-dropdown {
  position: absolute;
  top: 100%;
  left: -15px;
  z-index: 9;
  width: calc(100% + 30px);
  overflow-y: auto;
}

.nav-categories {
  overflow-x: scroll;
}

.nav-list-mobile-categories {
  margin: 4px 0;
  padding: 0 15px;
  white-space: nowrap !important;
}

.utilities-icon {
  font-size: 20px;
}

.nav-dropdown-content {
  visibility: hidden;
  opacity: 0;
  transition: visibility 0s linear .3s, opacity .3s linear;
}

.head-banners {
  padding: 10px 0;
}

.head-banners .btn-link {
  font-size: 13px;
}

.head-banner-text {
  font-size: 10px;
}

.head-banner-item-image {
  width: 20px;
}

/* Logo */

.logo-text-container {
  display: inline-block;
  max-width: 450px;
  margin: 15px 0;
}

.logo-img-container {
  display: inline-block;
  width: 100%;
  max-width: 320px;
}

.logo-img,
.logo-text {
  width: auto;
  height: auto;
  margin: 12px 0;
  vertical-align: middle;
  max-height: 45px;
  max-width: 90%;
}

.logo-img-small,
header.head-main.compress .logo-img-small {
  max-height: 30px;
}

.logo-img-big {
  max-height: 80px;
}

@media (min-width: 768px) {
  .logo-img-container {
    max-width: 400px;
  }

  .logo-img,
  .logo-text {
    max-height: 60px;
    max-width: 80%;
    margin: 30px 0;
    padding-left: 0;
  }

  .logo-img-small,
  header.head-main.compress .logo-img-small {
    max-height: 40px;
  }

  .logo-img-big {
    max-height: 80px;
  }
}

/* Search */

.search-input-submit {
  position: absolute;
  top: 3px;
  right: 6px;
  padding: 7px 10px;
  background: none;
  border: 0;
}

.subutility-list {
  display: none;
}
```

### 3.18 PÁGINA HOME

```css
/* Section Title */

.section-title {
  padding: 40px 0;
}

/* Video */

.home-video-text {
  position: absolute;
  bottom: 40px;
  z-index: 999;
  width: 90%;
}

@media (min-width: 768px) {
  .home-video-text {
    bottom: 60px;
  }
}

.home-video-overlay:after {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100%;
  content: '';
}

.home-video-image {
  position: absolute;
  top: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.home-video .placeholder-shine {
  z-index: 9;
}

.home-video video {
  position: relative;
  object-fit: cover;
  object-position: 50% 20%;
  font-family: "object-fit: cover";
}

.home-video iframe {
  position: absolute;
  top: 0;
  left: -100%;
  width: 300%;
  max-width: none;
}

.home-video-hide-controls {
  position: absolute;
  top: 0;
  z-index: 99;
  width: 100%;
  height: 100%;
}

/* Main Categories */

.section-categories-home {
  padding: 25px 0;
}

.home-category {
  display: inline-block;
  width: 110px;
  border-radius: 6px;
  overflow: hidden;
}

.home-category-placeholder {
  background: #f9f9f9;
}

.home-category-image {
  position: relative;
  width: 100%;
  height: 85px;
  margin: 0 auto;
  text-align: center;
  overflow: hidden;
}

.home-category-image img,
.home-category-image svg {
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 9;
  width: 100%;
  height: 100%;
  transform: translate(-50%, -50%);
  object-fit: cover;
}

.home-category-name {
  padding: 7px 0;
  font-size: var(--font-small);
  text-overflow: ellipsis;
  overflow: hidden;
  -webkit-line-clamp: 2;
  display: -webkit-box;
  -webkit-box-orient: vertical;
}

/* Featured Products */

.featured-product-image {
  height: 100%;
  border-radius: 6px 6px 0 0;
  overflow: hidden;
}

.featured-product-image img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.featured-product-container {
  height: 100%;
  padding: 20px;
  border-radius: 0 0 6px 6px;
}

.featured-product-container.featured-product-image-with-slider {
  padding: 20px 0 20px 20px;
}

.featured-product-container .swiper-container {
  width: calc(100% + 15px);
  margin-right: -15px;
}

@media (min-width: 768px) {
  .featured-product-image.featured-product-image-with-slider {
    border-radius: 6px 0 0 6px;
  }

  .featured-product-container.featured-product-image-with-slider {
    padding: 20px;
    border-radius: 0 6px 6px 0;
  }

  .featured-product-container.featured-product-image-with-slider .swiper-container {
    width: 100%;
    margin: 0;
  }
}

/* Welcome Message */

.section-welcome-home {
  padding: 70px 0;
}
```

### 3.19 BANNERS

```css
/* Home Banners */

.section-banners-home {
  padding: 50px 0;
}

.textbanner {
  position: relative;
  margin-bottom: 15px;
  overflow: hidden;
}

.textbanner.textbanner-no-border {
  border: 0;
  border-radius: 0;
}

.textbanner-link {
  display: block;
  width: 100%;
  height: 100%;
}

.textbanner-image {
  position: relative;
  padding-top: 50%;
  overflow: hidden;
}

@media (min-width: 768px) {
  .textbanner-image-md {
    padding-top: 25%;
  }
}

.textbanner-text {
  position: relative;
  padding: 20px 15px;
}

.textbanner-text.over-image {
  position: absolute;
  bottom: 0;
  z-index: 9;
  width: 100%;
  border: 0;
}

.textbanner-paragraph {
  display: -webkit-box;
  margin: 10px 0;
  font-size: var(--font-small);
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}

.textbanner .textbanner-image.overlay.textbanner-image-empty {
  background-image: url("data:image/svg+xml;utf8,...");
  background-position: center;
}

.textbanner-image-background {
  position: absolute;
  top: 0;
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Informative Banners */

.section-informative-banners {
  position: relative;
  padding: 90px 0 110px 0;
}

.service-pagination {
  bottom: 40px;
  width: 100%;
}

.service-pagination .swiper-pagination-bullet {
  margin: 0 4px;
}
```

### 3.20 GRID DE PRODUCTOS

```css
/* Category Banner */

.category-banner {
  position: relative;
  height: 185px;
  overflow: hidden;
}

.category-banner-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.category-banner-info {
  padding: 15px;
}

/* Category Controls */

.category-controls-container {
  padding: 0;
}

.category-controls-sticky-detector {
  height: 1px;
}

.category-controls {
  position: sticky;
  top: 0;
  z-index: 100;
  padding: 10px 15px;
  transition: all 0.3s ease;
}

.category-controls .page-header {
  margin-bottom: 0;
}

/* Grid Item */

.item,
.card {
  margin-bottom: 20px;
  border-radius: 6px;
  overflow: hidden;
}

.item-horizontal,
.item-horizontal .item-image {
  max-height: 210px;
}

.item-description {
  padding: 15px 10px;
}

.item-image {
  position: relative;
  max-height: 1200px;
  overflow: hidden;
}

.item-image img {
  height: 100%;
  max-height: 1200px;
  width: auto;
}

.item-image-slide img {
  max-width: 100%;
  object-fit: contain;
  object-position: top;
}

.item-slider-pagination-container {
  position: absolute;
  bottom: 8px;
  left: 50%;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: start;
  width: 35px;
  height: 13px;
  overflow: hidden;
  transform: translateX(-50%);
}

.item-slider-pagination-container.two-bullets {
  width: 30px;
}

.item-slider-pagination {
  bottom: initial;
  left: initial;
  height: 5px;
  padding: 0;
  overflow: initial;
  transform: translateX(calc(-50% + 6px));
  white-space: nowrap;
}

.item-slider-pagination .swiper-pagination-bullet {
  position: relative;
  left: 20px;
  width: 5px;
  height: 5px;
  margin: 0 3px;
  transition: transform .2s, left .2s;
  transform: none;
}

.item-slider-pagination .swiper-pagination-bullet-active {
  width: 7px;
}

.item-more-images-message {
  position: absolute;
  z-index: 1;
  right: 10px;
  bottom: 0;
  font-size: 10px;
  opacity: 0;
  transition: all 0.2s ease;
}

.item-slider-controls-container {
  position: absolute;
  top: 50%;
  height: 25px;
  margin: -25px 10px 0 10px;
  opacity: 0;
  line-height: 24px;
  transition: all .2s ease;
  transform: translateY(5px);
}

.item-slider-controls-container.swiper-button-disabled {
  opacity: 0;
  cursor: auto;
}

.item-image:hover .item-slider-controls-container:not(.swiper-button-disabled) {
  opacity: 1;
  transform: translateY(0);
}

.item-horizontal .item-image img {
  width: 100%;
  height: 100%;
  max-height: 210px;
  object-fit: cover;
}

.item-horizontal .item-description {
  padding: 10px 30px 10px 15px;
}

.item-horizontal .item-name {
  -webkit-line-clamp: 1;
  font-size: 14px;
}

.item-horizontal .item-price {
  font-size: 16px;
}

.item-horizontal .item-price-container,
.item-horizontal .btn-small {
  font-size: 12px;
}

.item-horizontal .label {
  font-size: 10px;
}

.item-product-reduced .item-image {
  height: 130px;
}

.item-product-reduced .item-image img {
  width: 100%;
  height: 130px;
  object-fit: cover;
}

.item-image-primary {
  z-index: 2;
  opacity: 1;
}

.item-image .item-image-secondary {
  opacity: 0;
}

.item-image:not(.product-item-image-secondary).lazyloaded {
  z-index: 2;
  opacity: 1;
}

.item-image-secondary,
.item-image-secondary.fade-in.lazyloaded {
  display: none;
  opacity: 0;
}

.product-item-secondary-images-loaded:not(.product-item-secondary-images-disabled):hover .item-image-featured {
  opacity: 0;
  transition-delay: .05s;
}

.product-item-secondary-images-loaded:not(.product-item-secondary-images-disabled):hover .item-image-featured ~ .item-image-secondary {
  opacity: 1;
}

.item-thumbnail {
  display: block;
  width: 100%;
}

.item-name {
  line-height: 1.1429em;
  text-overflow: ellipsis;
  overflow: hidden;
  -webkit-line-clamp: 2;
  display: -webkit-box;
  -webkit-box-orient: vertical;
}

.item-price-container {
  font-size: var(--font-small);
}

.item-price {
  font-size: var(--font-big);
}

/* Labels */

.labels {
  /* Labels generales */
}

.labels-absolute {
  position: absolute;
  /* Labels sobre imagen */
}

.label {
  /* Estilo de label */
}

.label-accent {
  /* Label de descuento/oferta */
}

.label-default {
  /* Label por defecto */
}
```

---

## 4. JAVASCRIPT / SWIPER INITIALIZATION

```javascript
// Slider principal (home)
var homeSlider = new Swiper('.js-home-slider', {
    loop: true,
    effect: 'fade',
    speed: 800,
    lazy: true,
    preloadImages: false,
    navigation: {
        nextEl: '.js-swiper-home-next',
        prevEl: '.js-swiper-home-prev',
    },
    pagination: {
        el: '.js-swiper-home-pagination',
        clickable: true,
    },
});

// Slider móvil (home)
var homeSliderMobile = new Swiper('.js-home-slider-mobile', {
    loop: true,
    effect: 'slide',
    speed: 800,
    lazy: true,
    preloadImages: false,
    navigation: {
        nextEl: '.js-swiper-home-next-mobile',
        prevEl: '.js-swiper-home-prev-mobile',
    },
    pagination: {
        el: '.js-swiper-home-pagination-mobile',
        clickable: true,
    },
});

// Slider de categorías
var categoriesSlider = new Swiper('.js-swiper-categories', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    freeMode: true,
});

// Slider de productos destacados
var featuredSlider = new Swiper('.js-swiper-featured', {
    slidesPerView: 2,
    spaceBetween: 20,
    navigation: {
        nextEl: '.js-swiper-featured-next',
        prevEl: '.js-swiper-featured-prev',
    },
});

// Slider de producto (thumbnails)
var productThumbs = new Swiper('.js-swiper-product-thumbs', {
    direction: 'vertical',
    slidesPerView: 4,
    spaceBetween: 10,
    navigation: {
        nextEl: '.js-swiper-product-thumbs-next',
        prevEl: '.js-swiper-product-thumbs-prev',
    },
});

// Slider de producto (principal)
var productSlider = new Swiper('.js-swiper-product', {
    effect: 'fade',
    pagination: {
        el: '.js-swiper-product-pagination',
        clickable: true,
    },
    thumbs: {
        swiper: productThumbs,
    },
});
```

---

## 5. COLORES Y PALETA (Por definir según configuración)

El template Lima utiliza variables CSS para colores que deben ser definidas. Ejemplos de colores encontrados:

- **WhatsApp Button:** `#4dc247`
- **Swiper Active Bullet:** `#007aff`
- **Border Card:** `#00000012` (12% negro)
- **Placeholder Background:** `#f9f9f9`
- **White:** `#FFFFFF`
- **Black:** `#000000`

**Colores de variantes de producto:**
- Azul: `#0000DC`
- Rosa: `#E998FF`
- Celeste: `#00BFFF`
- Amarillo: `#FFEE02`
- Fucsia: `#FF00FF`
- Naranja: `#FFA500`
- Negro: `#000000`
- Blanco: `#FFFFFF`

---

## 6. MEDIA QUERIES Y BREAKPOINTS

```css
/* Mobile first approach */

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) {
  .container {
    max-width: 540px;
  }
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) {
  .container {
    max-width: 720px;
  }

  .h1-huge {
    font-size: var(--h1-huge-md);
  }

  .logo-img,
  .logo-text {
    max-height: 60px;
  }
}

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

/* X-Large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
  }
}

/* XX-Large devices (larger desktops, 1400px and up) */
@media (min-width: 1400px) {
  .container {
    max-width: 1300px;
  }
}
```

---

## 7. ESTRUCTURA DE CLASES HELPERS

### 7.1 Margin y Padding

```css
/* Usar clases de Bootstrap 4 */
.m-0, .m-1, .m-2, .m-3, .m-4, .m-5
.mt-0, .mt-1, .mt-2, .mt-3, .mt-4, .mt-5
.mr-0, .mr-1, .mr-2, .mr-3, .mr-4, .mr-5
.mb-0, .mb-1, .mb-2, .mb-3, .mb-4, .mb-5
.ml-0, .ml-1, .ml-2, .ml-3, .ml-4, .ml-5
.mx-0, .mx-1, .mx-2, .mx-3, .mx-4, .mx-5
.my-0, .my-1, .my-2, .my-3, .my-4, .my-5

.p-0, .p-1, .p-2, .p-3, .p-4, .p-5
.pt-0, .pt-1, .pt-2, .pt-3, .pt-4, .pt-5
.pr-0, .pr-1, .pr-2, .pr-3, .pr-4, .pr-5
.pb-0, .pb-1, .pb-2, .pb-3, .pb-4, .pb-5
.pl-0, .pl-1, .pl-2, .pl-3, .pl-4, .pl-5
.px-0, .px-1, .px-2, .px-3, .px-4, .px-5
.py-0, .py-1, .py-2, .py-3, .py-4, .py-5
```

### 7.2 Text Alignment

```css
.text-left
.text-center
.text-right
.text-justify

/* Responsive */
.text-sm-left, .text-sm-center, .text-sm-right
.text-md-left, .text-md-center, .text-md-right
.text-lg-left, .text-lg-center, .text-lg-right
```

### 7.3 Display

```css
.d-none
.d-inline
.d-inline-block
.d-block
.d-flex

/* Responsive */
.d-sm-none, .d-sm-block, .d-sm-flex
.d-md-none, .d-md-block, .d-md-flex
.d-lg-none, .d-lg-block, .d-lg-flex
```

### 7.4 Position

```css
.position-relative
.position-absolute
.position-fixed
.position-sticky
```

### 7.5 Width

```css
.w-100 /* width: 100% */
.w-auto /* width: auto */
```

---

## 8. NOTAS IMPORTANTES PARA IMPLEMENTACIÓN

### 8.1 Lazy Loading

El template usa lazy loading en todas las imágenes con:
- Atributo `src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="` (placeholder transparente)
- Atributo `data-src` o `data-srcset` con la URL real
- Clase `.lazyload` o `.swiper-lazy`
- Se transforma a `.lazyloaded` cuando carga

### 8.2 Responsive Images

Las imágenes usan `srcset` con múltiples tamaños:
```html
data-srcset="imagen-240-0.webp 240w,
             imagen-320-0.webp 320w,
             imagen-480-0.webp 480w,
             imagen-640-0.webp 640w,
             imagen-1024-1024.webp 1024w,
             imagen-1920-1920.webp 1920w"
```

### 8.3 Formato WebP

Todas las imágenes están en formato WebP para optimización.

### 8.4 Placeholder con Blur

Las imágenes grandes usan un placeholder blur-up:
- Miniatura borrosa carga primero
- Se aplica filtro `blur(4px)` o `blur(6px)`
- Cuando carga la imagen real, se elimina el blur
- Mejora la percepción de velocidad

### 8.5 Grid Responsivo

El grid usa Bootstrap 4 con clases como:
- `.col-12` - Full width en mobile
- `.col-md-6` - 50% en tablets y desktop
- `.col-lg-4` - 33% en desktop grande
- `.col-xl-3` - 25% en desktop extra grande

### 8.6 Accesibilidad

- Todos los enlaces tienen `aria-label`
- Imágenes tienen atributo `alt` descriptivo
- SVG icons tienen títulos cuando es necesario
- Los controles de Swiper tienen clases descriptivas

---

## 9. RESUMEN DE SECCIONES Y ORDEN

**Orden de secciones en el Home:**

1. Header (Topbar + Logo + Nav + Banners)
2. Hero Slider (Desktop + Mobile)
3. Categorías Destacadas
4. Productos Destacados (Imagen + Slider)
5. Banners Promocionales (Grid 2 columnas)
6. Mensaje de Bienvenida
7. Producto Destacado Individual (con galería)
8. Más secciones de productos...
9. Footer

---

## 10. DEPENDENCIAS Y LIBRERÍAS

**CSS:**
- Bootstrap 4 Grid (minificado inline)
- Swiper 4.4.2 (minificado inline)
- CSS Custom (inline en `<style>`)

**JavaScript:**
- Swiper 4.4.2
- LazyLoad (para imágenes)
- Custom JS para interacciones

**Fuentes:**
- Google Fonts: Lexend Exa, Lexend
- Carga con `display=swap` para optimización

---

## FIN DEL ANÁLISIS

Este documento contiene toda la estructura HTML, estilos CSS completos, y la información necesaria para replicar el Template Lima en una aplicación Laravel con datos dinámicos de base de datos.
