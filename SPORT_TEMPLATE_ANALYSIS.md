# ANALISIS DETALLADO DEL TEMPLATE SPORT

## 1. CONTENIDO DEL HEAD

### 1.1 Meta Tags y Conectividad
```html
<meta http-equiv="origin-trial" content="A7vZI3v+Gz7JfuRolKNM4Aff6zaGuT7X0mf3wtoZTnKv6497cVMnhy03KDqX7kBz/q/iidW7srW31oQbBt4VhgoAAACUeyJvcmlnaW4iOiJodHRwczovL3d3dy5nb29nbGUuY29tOjQ0MyIsImZlYXR1cmUiOiJEaXNhYmxlVGhpcmRQYXJ0eVN0b3JhZ2VQYXJ0aXRpb25pbmczIiwiZXhwaXJ5IjoxNzU3OTgwODAwLCJpc1N1YmRvbWFpbiI6dHJ1ZSwiaXNUaGlyZFBhcnR5Ijp0cnVlfQ==">

<!-- Preconnect para optimizar recursos -->
<link rel="preconnect" href="https://dcdn-us.mitiendanube.com">
<link rel="dns-prefetch" href="https://dcdn-us.mitiendanube.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">

<!-- Charset y compatibilidad -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Información del sitio -->
<title>Tienda Online de baires-skate</title>
<meta name="description" content="Comprá productos de baires-skate por internet. Tenemos calzado, boards y más. Hacé tu pedido, pagalo online y recibilo donde quieras.">

<!-- Favicon -->
<link href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/common/logo-1030716766-1699451691-3002808fc68b37dab18ced73cda902611699451691.ico?0" class="js-favicon" rel="icon" type="image/x-icon">
<link href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/common/logo-1030716766-1699451691-3002808fc68b37dab18ced73cda902611699451691.ico?0" class="js-favicon" rel="shortcut icon" type="image/x-icon">

<!-- Canonical -->
<link rel="canonical" href="https://baires-theme.mitiendanube.com/">
```

### 1.2 Fuentes Importadas
**Google Fonts - Directa desde CDN:**
```
URL: //fonts.googleapis.com/css?family=Big+Shoulders+Display:400,700|Chivo:400,700&display=swap

Fuentes:
- Big Shoulders Display (400, 700) - Para títulos y headings
- Chivo (400, 700) - Para cuerpo de texto
```

### 1.3 CSS y JS Preload
```html
<!-- CSS Crítico (Inline para performance) -->
<link rel="preload" as="style" href="//fonts.googleapis.com/css?family=Big+Shoulders+Display:400,700|Chivo:400,700&amp;display=swap">

<!-- CSS Principal -->
<link rel="preload" href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/dart-style-critical-b0a03eba075af1eb0c307ad1c3a2f41b.css" as="style">

<!-- JavaScript externo (sin dependencias) -->
<link rel="preload" href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/dart-external-no-dependencies-2020a4748d2e0fc983451e7972c49502.js" as="script">

<!-- Preload de imagen principal del slider -->
<link rel="preload" as="image" href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/1-slide-1700243071328-4101729287-1622f39fe1e7e2fa40f729eb70d04a831700243089-480-0.webp?1759382424" imagesrcset="...">
```

### 1.4 CSS Sheets Activos
```html
<!-- CSS Crítico (estilos principales) -->
<link rel="stylesheet" type="text/css" href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/dart-style-critical-b0a03eba075af1eb0c307ad1c3a2f41b.css" media="all">

<!-- CSS Asíncrono (estilos adicionales) -->
<link rel="stylesheet" href="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/dart-style-async-8f0a45a8e884c9144116ff6fcf196da8.css" media="all" onload="this.media='all'">
```

### 1.5 Variables CSS Definidas en :root

**Colores Principales:**
```css
:root {
  --main-foreground: #000000;        /* Color de texto principal */
  --main-background: #FFFFFF;        /* Fondo principal */
  --accent-color: #000000;           /* Color de acentos */

  --button-background: #000000;      /* Botones - fondo */
  --button-foreground: #FFFFFF;      /* Botones - texto */

  --label-background: #000000;       /* Labels - fondo */
  --label-foreground: #ffffff;       /* Labels - texto */

  --adbar-background: #000000;       /* Barra de publicidad - fondo */
  --adbar-foreground: #FFFFFF;       /* Barra de publicidad - texto */

  --header-background: #FFFFFF;      /* Header - fondo */
  --header-foreground: #000000;      /* Header - texto */
  --header-transparent-foreground: ; /* Header - texto transparente (vacío) */

  --footer-background: #000000;      /* Footer - fondo */
  --footer-foreground: #FFFFFF;      /* Footer - texto */

  --newsletter-background: #FFFFFF;  /* Newsletter - fondo */
  --newsletter-foreground: #000000;  /* Newsletter - texto */

  --institutional-background: #FFFFFF;  /* Sección institucional - fondo */
  --banner-services-background: #FFFFFF; /* Banner servicios - fondo */
  --banner-services-foreground: #000000; /* Banner servicios - texto */
}
```

**Colores de Estado:**
```css
--success: #4bb98c;      /* Verde - éxito */
--danger: #dd7774;       /* Rojo - error */
--warning: #dc8f38;      /* Naranja - advertencia */
```

**Fuentes Tipográficas:**
```css
--heading-font: "Big Shoulders Display", sans-serif;  /* Títulos */
--body-font: "Chivo", sans-serif;                     /* Cuerpo texto */
--main-text-transform: uppercase;                     /* Textos en mayúscula */
```

**Espaciados (Gutter):**
```css
--gutter: 15px;                              /* Espaciado base */
--gutter-container: 15px;                    /* Espaciado contenedor móvil */
--gutter-container-md: 30px;                 /* Espaciado contenedor desktop */
--gutter-negative: calc(var(--gutter) * -1); /* Espaciado negativo */
--gutter-half: calc(var(--gutter) / 2);      /* Medio espaciado */
--gutter-double: calc(var(--gutter) * 2);    /* Doble espaciado */
```

**Tamaños de Fuentes:**
```css
/* Headings */
--h1: 28px;
--h2: 24px;
--h3: 20px;
--h4: 18px;
--h5: 16px;
--h6: 14px;
--h6-small: 12px;

/* Tamaños grandes */
--h1-huge: 100px;
--h1-huge-md: 125px;      /* Desktop */
--h2-huge: 70px;
--h3-huge: 50px;
--h4-huge: 40px;

/* Body text */
--font-huge: 32px;
--font-extra-largest: 28px;
--font-extra-large: 24px;
--font-largest: 20px;
--font-large: 18px;
--font-big: 16px;
--font-base: 14px;
--font-medium: 11px;
--font-small: 12px;
--font-smallest: 10px;
```

**Bordes y Radius:**
```css
--border-radius: 4px;
--border-radius-full: 100%;
--border-radius-half: 2px;
--border-radius-quarter: 1px;
--border-solid: 1px solid;
--border-dashed: 1px dashed;
```

**Distancias de Sección:**
```css
--section-distance: 30px;
--section-distance-huge: 60px;
--section-distance-huge-md: 90px;  /* Desktop */
--section-negative-distance: -30px;
```

**Sombras:**
```css
--shadow-distance: 0 0 5px;
```

**Opacidades:**
```css
/* Opacidades del foreground principal */
--main-foreground-opacity-03: #00000008;   /* 3% */
--main-foreground-opacity-05: #0000000D;   /* 5% */
--main-foreground-opacity-08: #00000014;   /* 8% */
--main-foreground-opacity-10: #0000001A;   /* 10% */
--main-foreground-opacity-20: #00000033;   /* 20% */
--main-foreground-opacity-30: #0000004D;   /* 30% */
--main-foreground-opacity-40: #00000066;   /* 40% */
--main-foreground-opacity-50: #00000080;   /* 50% */
--main-foreground-opacity-60: #00000099;   /* 60% */
--main-foreground-opacity-80: #000000CC;   /* 80% */
--main-foreground-opacity-90: #000000E6;   /* 90% */

/* Opacidades del background */
--main-background-opacity-30: #FFFFFF4D;   /* 30% */
--main-background-opacity-40: #FFFFFF66;   /* 40% */
--main-background-opacity-50: #FFFFFF80;   /* 50% */
--main-background-opacity-80: #FFFFFFCC;   /* 80% */
--main-background-opacity-90: #FFFFFFE6;   /* 90% */

/* Opacidades específicas de header y footer */
--header-foreground-opacity-10: #0000001A;
--header-foreground-opacity-20: #00000033;
--header-foreground-opacity-30: #0000004D;
--header-foreground-opacity-50: #00000080;
--footer-foreground-opacity-10: #FFFFFF1A;
--footer-foreground-opacity-20: #FFFFFF33;
--footer-foreground-opacity-50: #FFFFFF80;
--footer-foreground-opacity-60: #FFFFFF99;
--footer-foreground-opacity-80: #FFFFFFCC;
```

### 1.6 Open Graph Tags
```html
<meta property="og:site_name" content="baires-skate">
<meta property="og:type" content="website">
<meta property="og:title" content="Tienda Online de baires-skate">
<meta property="og:description" content="Comprá productos de baires-skate por internet. Tenemos calzado, boards y más. Hacé tu pedido, pagalo online y recibilo donde quieras.">
<meta property="og:url" content="https://baires-theme.mitiendanube.com">
<meta property="og:image" content="http://dcdn-us.mitiendanube.com/stores/003/928/529/themes/common/logo-669113612-1699451690-3acd50bab5b1a3093eaa9afbd2a2f5a81699451690.png?0">
<meta property="og:image:secure_url" content="https://dcdn-us.mitiendanube.com/stores/003/928/529/themes/common/logo-669113612-1699451690-3acd50bab5b1a3093eaa9afbd2a2f5a81699451690.png?0">
```

### 1.7 SEO y Privacidad
```html
<meta name="robots" content="noindex,nofollow">
<meta name="nuvempay-logo" content="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/nuvempago@2x.png">
```

---

## 2. ESTRUCTURA DEL HEADER

**Elemento raíz:**
```html
<header class="js-head-main head-main head-colors position-sticky position-sticky-md head-logo-center head-logo-md-center head-md-hamburger transition-soft" data-store="head" style="top: 0px;">
```

**Clases principales:**
- `js-head-main` - JavaScript hook
- `head-colors` - Aplicar colores configurados
- `position-sticky` - Header fijo en móvil
- `position-sticky-md` - Header fijo en desktop
- `head-logo-center` - Logo centrado en móvil
- `head-logo-md-center` - Logo centrado en desktop
- `head-md-hamburger` - Menú hamburguesa en desktop

### 2.1 Estructura Interna del Header

**Fila del Logo:**
```html
<div class="head-logo-row position-relative transition-soft">
  <div class="container-fluid">
    <div class="row no-gutters align-items-center">

      <!-- Menú Hamburguesa (Móvil) -->
      <div class="order-first col-3 order-md-first col-md-auto col-utility">
        <span class="utilities-container d-inline-block">
          <a href="#" class="js-modal-open utilities-item btn btn-utility pl-0 pl-md-0"
             data-toggle="#nav-hamburger" aria-label="Menú" data-component="menu-button">
            <span class="utilities-text">Menú</span>
          </a>
        </span>
      </div>

      <!-- Logo -->
      <div class="js-logo-container col text-center col-md order-md-1 text-md-center">
        <div id="logo" class="logo-img-container">
          <a href="https://baires-theme.mitiendanube.com" title="">
            <img src="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/common/logo-669113612-1699451690-3acd50bab5b1a3093eaa9afbd2a2f5a81699451690-480-0.webp"
                 alt="baires-skate" class="logo-img transition-soft logo-img-small" width="573" height="132">
          </a>
          <h1 style="display: none;">baires-skate</h1>
        </div>
      </div>

      <!-- Search Utility (Desktop) -->
      <div class="js-utility-col js-search-utility col-auto desktop-utility-col col-md-3 mr-md-3 col-utility order-md-0">
        <span class="d-none d-md-inline-block">
          <span class="utilities-container d-inline-block">
            <a href="#" class="js-search-button js-modal-open js-fullscreen-modal-open btn btn-utility utilities-item"
               data-modal-url="modal-fullscreen-search" data-toggle="#nav-search" aria-label="Buscador">
              <span class="utilities-text">Buscar</span>
            </a>
          </span>
        </span>
      </div>

      <!-- Login Utility (Desktop) -->
      <div class="js-utility-col col-utility desktop-utility-col text-right d-none d-md-inline-block order-md-1 col-md-3">
        <span class="utilities-container text-transform">
          <a href="/account/login/" class="btn btn-utility">
            <span class="utilities-text">Login</span>
          </a>
        </span>
      </div>

      <!-- Carrito -->
      <div class="js-utility-col col-auto col-utility desktop-utility-col order-last">
        <span class="utilities-container d-inline-block">
          <div id="ajax-cart" class="cart-summary" data-component="cart-button">
            <a href="#" data-toggle="#modal-cart" data-modal-url="modal-fullscreen-cart"
               class="js-modal-open js-fullscreen-modal-open btn btn-utility d-flex pr-0">
              <span class="utilities-text">Carrito</span>
              <span class="js-cart-widget-amount badge">0</span>
            </a>
          </div>
        </span>
      </div>

    </div>
  </div>
</div>
```

### 2.2 Componentes del Header

**Notificación de Carrito:**
```html
<div class="js-alert-added-to-cart notification-floating notification-cart-container notification-hidden notification-fixed position-absolute" style="display: none;">
  <div class="notification notification-primary notification-cart p-0">
    <div class="js-cart-notification-close notification-close">
      <svg class="icon-inline icon-2x notification-icon"><use xlink:href="#times"></use></svg>
    </div>
    <div class="js-cart-notification-item row no-gutters align-items-center" data-store="cart-notification-item">
      <div class="col-auto pr-0 notification-img">
        <img src="" class="js-cart-notification-item-img img-absolute-centered-vertically">
      </div>
      <div class="col text-left pl-3 pr-4">
        <div class="mb-1 mr-2 text-transform font-weight-bold">
          <span class="js-cart-notification-item-name"></span>
          <span class="js-cart-notification-item-variant-container" style="display: none;">
            (<span class="js-cart-notification-item-variant"></span>)
          </span>
        </div>
        <div class="mb-1">
          <span class="js-cart-notification-item-quantity"></span>
          <span> x </span>
          <span class="js-cart-notification-item-price"></span>
        </div>
      </div>
    </div>
  </div>
</div>
```

---

## 3. FOOTER - ESTRUCTURA COMPLETA

```html
<footer class="js-footer js-hide-footer-while-scrolling footer container-fluid footer-colors display-when-content-ready" data-store="footer">
  <div class="row">

    <!-- Sección 1: Categorías -->
    <div class="col-md-6 mb-4 pb-2 mb-md-5 pb-md-0 pb-md-0 pr-md-5">
      <ul class="list pr-md-5">
        <li class="footer-menu-item">
          <a class="h4-huge h2-huge-md footer-menu-link" href="https://baires-theme.mitiendanube.com/repuestos/">REPUESTOS</a>
        </li>
        <li class="footer-menu-item">
          <a class="h4-huge h2-huge-md footer-menu-link" href="https://baires-theme.mitiendanube.com/indumentaria/">INDUMENTARIA</a>
        </li>
        <li class="footer-menu-item">
          <a class="h4-huge h2-huge-md footer-menu-link" href="https://baires-theme.mitiendanube.com/zapatillas/">CALZADO</a>
        </li>
        <li class="footer-menu-item">
          <a class="h4-huge h2-huge-md footer-menu-link" href="https://baires-theme.mitiendanube.com/accesorios/">ACCESORIOS</a>
        </li>
      </ul>
    </div>

    <!-- Sección 2: Newsletter y Redes Sociales -->
    <div class="col-md-6 mb-4 pb-2 mb-md-5 pb-md-0 pr-0 pl-md-5 pr-md-3">
      <div class="js-footer-col-sticky col-sticky-md" style="top: 129px;">

        <!-- Newsletter -->
        <div class="js-newsletter newsletter mb-4 pb-3 pr-3 pr-md-0">
          <div class="h4-huge h2-huge-md">SUSCRIBITE</div>
          <form method="post" action="/winnie-pooh" onsubmit="this.setAttribute('action', '');" data-store="newsletter-form">
            <div class="newsletter-form input-append">
              <div class="form-group mb-0">
                <input type="email" id="email" class="form-control form-control-line"
                       autocorrect="off" autocapitalize="off" name="email" placeholder="Email" aria-label="Email">
              </div>
              <div class="winnie-pooh" style="display: none;">
                <label for="winnie-pooh-newsletter">No completar este campo</label>
                <input id="winnie-pooh-newsletter" type="text" name="winnie-pooh">
              </div>
              <input type="hidden" name="name" value="Sin nombre">
              <input type="hidden" name="message" value="Pedido de inscripción a newsletter">
              <input type="hidden" name="type" value="newsletter">
              <input type="submit" name="contact" class="btn btn-link form-control-btn form-control-line-btn" value="Enviar">
            </div>
          </form>
        </div>

        <!-- Redes Sociales -->
        <div class="list-horizontal mb-4 pb-md-3 pb-1">
          <a class="btn-link text-transform text-capitalize mr-3" href="https://instagram.com/baires.sk8" target="_blank">instagram</a>
          <a class="btn-link text-transform text-capitalize mr-3" href="https://facebook.com/tiendanube" target="_blank">facebook</a>
          <a class="btn-link text-transform text-capitalize mr-3" href="https://youtube.com/tiendanube" target="_blank">youtube</a>
          <a class="btn-link text-transform text-capitalize mr-3" href="https://www.tiktok.com/@tiendanube" target="_blank">tiktok</a>
          <a class="btn-link text-transform text-capitalize mr-3" href="https://www.twitter.com/tiendanube" target="_blank">twitter</a>
        </div>

        <div class="divider d-md-none mb-4 pb-1 mr-3 mr-md-0"></div>

        <!-- Información de Contacto -->
        <div class="mb-4 pb-3 pr-3 pr-md-0">
          <div class="contact-info row no-gutters">
            <div class="col-md-auto">
              <div class="contact-info-item mb-3 d-md-inline-block mr-md-3">
                <div class="font-small text-transform mb-1 opacity-80">Teléfono</div>
                <a href="tel:4506-7890" class="btn-link text-transform">4506-7890</a>
              </div>
            </div>
            <div class="col-md-auto">
              <div class="contact-info-item mb-3 d-md-inline-block mr-md-3">
                <div class="font-small text-transform mb-1 opacity-80">Email</div>
                <a href="mailto:baires.skate@tiendanube.com" class="btn-link text-transform">baires.skate@tiendanube.com</a>
              </div>
            </div>
            <div class="col-md-auto">
              <div class="text-transform mb-3 contact-info-item mb-3 d-md-inline-block mr-md-3">
                <div class="font-small text-transform mb-1 opacity-80">Dirección</div>
                Av. Córdoba 2334
              </div>
            </div>
          </div>
        </div>

        <!-- Logos de Pagos y Envíos -->
        <div class="footer-payments-shipping-logos pr-3 pr-md-0">
          <!-- Métodos de Pago: Visa, Mastercard, Amex, Diners, Cabal, etc. -->
          <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png" alt="visa" width="40" height="25">
          <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png" alt="mastercard" width="40" height="25">
          <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png" alt="amex" width="40" height="25">
          <!-- ... más métodos de pago ... -->

          <!-- Empresas de Envío: OCA, Correo, Branch, etc. -->
          <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/shipping/ar/oca@2x.png" alt="ar_oca" width="40" height="25">
          <img src="//d26lpennugtm8s.cloudfront.net/assets/common/img/logos/shipping/api/6053@2x.png" alt="api_6053" width="40" height="25">
          <!-- ... más empresas de envío ... -->
        </div>

      </div>
    </div>

  </div>

  <!-- Sección Inferior: Logo, Links, Copyright y Powered By -->
  <div class="mb-4 pb-2 mb-md-5 pb-md-0">
    <img src="//dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/img-1523049344-1699465097-cea144f7b801fec4c98ab20834e1fa331699465097.png" alt="baires-skate" title="baires-skate" class="img-fluid">
  </div>

  <!-- Links Institucionales -->
  <div class="mb-4 pb-2 mb-md-5 pb-md-0">
    <ul class="list d-flex flex-wrap justify-content-md-between">
      <li class="mr-2 mr-md-3 mb-2 mb-md-3">
        <a class="btn-link text-transform font-small font-md-body" href="https://baires-theme.mitiendanube.com/quienes-somos/">NOSOTROS</a>
      </li>
      <li class="mr-2 mr-md-3 mb-2 mb-md-3">
        <a class="btn-link text-transform font-small font-md-body" href="https://baires-theme.mitiendanube.com/como-comprar/">CÓMO COMPRAR</a>
      </li>
      <li class="mr-2 mr-md-3 mb-2 mb-md-3">
        <a class="btn-link text-transform font-small font-md-body" href="https://baires-theme.mitiendanube.com/quienes-somos/">CONTACTO</a>
      </li>
    </ul>
  </div>

  <!-- Powered By y Copyright -->
  <div class="text-left text-md-center">
    <div class="powered-by-logo">
      <a target="_blank" title="Tiendanube" rel="nofollow" href="https://www.tiendanube.com?utm_source=store&utm_medium=referral&utm_campaign=footerSlogan">
        <svg title="Tiendanube" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 120" id="svg_brand">
          <!-- SVG path para logo de Tiendanube -->
        </svg>
      </a>
    </div>

    <div class="my-3 my-md-2 font-small">
      Copyright baires-skate - 2025. Todos los derechos reservados.
    </div>

    <!-- Defensa del Consumidor -->
    <div class="claim-link font-small">
      <span class="d-inline-block mb-1">Defensa de las y los consumidores. Para reclamos</span>
      <a class="btn-link font-small" href="https://www.argentina.gob.ar/produccion/defensadelconsumidor/formulario" target="_blank">ingresá acá.</a>
      <span class="mx-1">/</span>
      <a class="btn-link font-small" href="/contacto/?order_cancellation_without_id=true">Botón de arrepentimiento</a>
    </div>
  </div>

</footer>
```

---

## 4. SECCIONES PRINCIPALES DEL HOME (Orden de Aparición)

### 4.1 Modales (Inicialmente Ocultos)
- `#js-cross-selling-modal` - Modal de venta cruzada
- `#nav-search` - Modal de búsqueda fullscreen
- `#nav-hamburger` - Menú hamburguesa (móvil)
- `#modal-cart` - Carrito fullscreen
- `#error-ajax-stock` - Error de stock
- `#related-products-notification` - Notificación de productos relacionados

### 4.2 Secciones del Contenido Principal (Orden)

**1. Banners de Categorías (Línea 579)**
```
data-store: home-banner-categories
Clase: section-home section-banners-home position-relative overflow-none
Contenido: Banners/slider de categorías principales
```

**2. Mensaje Institucional (Línea 659)**
```
data-store: home-institutional-message
Clase: section-home overflow-none
Contenido: Mensaje de bienvenida/información
```

**3. Anuncio Animado (Línea 678)**
```
data-store: home-announcement-message
Clase: section-home section-home-text-animated home-text-outline
Características: Fade-in animation, texto con outline
```

**4. Productos Featured - "LO NUEVO" (Línea 826)**
```
data-store: home-products-featured
Clase: section-home section-featured-home section-featured-products-home
Contenido: Carrusel de productos nuevos (3 columnas en desktop, 1 en móvil)
Título: "LO NUEVO" (clase h3-huge h2-huge-md)
```

**5. Welcome Message (Línea 2010)**
```
data-store: home-welcome-message
Clase: section-home section-home-text-animated
Características: Texto animado con fade-in
```

**6. Video/Slider (Línea 2105)**
```
data-store: home-slider
Clase: section-slider-home section-home-color
Características: Video o slider principal con fade-in animation
```

**7. Marcas/Brands (Línea 2169)**
```
data-store: home-brands
Clase: section-home section-brands-home overflow-none
Contenido: Logos de marcas destacadas
```

**8. Producto Principal (Línea 2758)**
```
Clase: js-product-container section-home
ID: single-product
Contenido: Producto destacado con variantes, imágenes y detalles de pago
```

**9. Feed de Instagram (Línea 3095)**
```
data-store: home-instagram-feed
Clase: section-home section-instafeed-home overflow-none py-2 py-md-4
Contenido: Integración de feed de Instagram
```

**10. Categorías Destacadas (Línea 3117)**
```
data-store: home-categories-featured
Clase: section-home section-categories-home position-relative overflow-none
Características: Fade-in animation
```

**11. Nuevos Productos (Línea 3819)**
```
data-store: home-products-new
Clase: section-home section-featured-home section-new-products-home
Contenido: Carrusel de productos nuevos
```

**12. Newsletter (Línea 4732)**
```
data-store: home-newsletter
Clase: section-newsletter-home position-relative overflow-none
Contenido: Formulario de suscripción a newsletter
```

**13. Video Section (Línea 4768)**
```
data-store: home-video
Clase: js-section-video section-video-home position-relative
Características: Fade-in animation
Contenido: Video incrustado
```

**14. Banner de Servicios/Informativo (Línea 4792)**
```
data-store: banner-services
Clase: section-home section-informative-banners
Contenido: Banners informativos (envíos, garantía, atención, etc.)
```

---

## 5. SCRIPTS Y RECURSOS (Antes de </body>)

### 5.1 Archivos JavaScript Principales
```html
<!-- Nombre de archivo no especificado explícitamente pero lista CDN de MiTiendaNube: -->
- dart-external-no-dependencies-2020a4748d2e0fc983451e7972c49502.js
- Otros scripts de MiTiendaNube
```

### 5.2 Google reCAPTCHA
```html
<script src="https://www.google.com/recaptcha/api.js"></script>

<!-- Badge de reCAPTCHA (invisible) -->
<div class="grecaptcha-badge" data-style="bottomright">
  <div class="grecaptcha-logo">
    <iframe title="reCAPTCHA" ...></iframe>
  </div>
</div>
```

### 5.3 SVG Sprite (Iconografía)
- Contiene iconos como `#times` para cerrar modales
- Se referencia con `<use xlink:href="#times"></use>`

---

## 6. CLASES Y COMPONENTES CLAVE

### 6.1 Utilidades de Bootstrap
- `container-fluid` - Contenedor 100% ancho
- `row` - Fila de grid
- `col-*`, `col-md-*` - Columnas responsivas
- `d-none`, `d-md-inline-block` - Display responsivo
- `mb-*`, `pb-*`, `mr-*` - Márgenes y padding
- `text-center`, `text-left`, `text-right` - Alineación de texto
- `no-gutters` - Sin espaciado entre columnas

### 6.2 Clases Personalizadas
- `js-*` - JavaScript hooks (js-modal-open, js-search-button, etc.)
- `section-home` - Sección del home
- `position-sticky` - Header fijo
- `transition-soft` - Transiciones suaves
- `utilities-container` - Contenedor de utilidades (búsqueda, login, carrito)
- `cart-summary` - Resumen del carrito
- `notification-*` - Clases para notificaciones

### 6.3 Data Attributes
- `data-store` - Para tracking/analytics
- `data-toggle` - Modal a abrir
- `data-modal-url` - URL del contenido modal
- `data-component` - Identificador de componente
- `data-variants` - JSON de variantes de producto

---

## 7. ANÁLISIS DE PERFORMANCE

### 7.1 Optimizaciones Implementadas
1. **Preload/Preconnect**: Links de fuentes y CDN para agilizar carga
2. **CSS Crítico Separado**: CSS crítico inline, CSS async con onload
3. **Lazy Loading**: Uso de `lazyloaded` class en imágenes
4. **Image Optimization**: Responsive images con `imagesrcset`
5. **Modal Deferred**: Modales ocultos hasta necesarios

### 7.2 Recursos Externos
- **CDN de Fuentes**: Google Fonts
- **CDN de Assets**: dcdn-us.mitiendanube.com
- **CDN de Pagos/Envíos**: d26lpennugtm8s.cloudfront.net
- **Google reCAPTCHA**: De Google

---

## 8. ESTRUCTURA RESPONSIVA

### Mobile (< 768px)
- Logo centrado
- Menú hamburguesa activo
- Búsqueda y login ocultos
- 1 columna de productos
- Footer con columnas apiladas

### Desktop (>= 768px)
- Logo centrado/izquierda
- Búsqueda y login visibles
- Hamburguesa oculto
- 3-4 columnas de productos
- Footer con 2 columnas lado a lado

---

## 9. MAPEO DE DATOS DINÁMICOS

### 9.1 Variantes de Producto
Almacenadas en JSON en `data-variants`:
```json
{
  "product_id": 189951147,
  "price_short": "$19.000,00",
  "price_long": "$19.000,00 ARS",
  "price_number": 19000,
  "option0": "XS",  // Talla/variante
  "image_url": "...",
  "installments_data": { /* Cuotas disponibles */ }
}
```

### 9.2 Moneda
- Se define en: `<div id="store-curr" class="hidden">ARS</div>`
- Peso Argentino (ARS)

### 9.3 Idioma
- HTML lang="es"
- Títulos en español
- Moneda: ARS (Pesos Argentinos)

---

## 10. CONFIGURACIÓN PARA BLADE (sport_layout.blade.php y sport_index.blade.php)

### Estructura Recomendada
```
@extends('tienda.sport_layout')

@section('content')
  <!-- Contenido específico del home aquí -->
@endsection

@push('styles')
  <!-- Estilos adicionales -->
@endpush

@push('scripts')
  <!-- Scripts adicionales -->
@endpush
```

### Variables Esperadas desde Controller
```php
$empresa        // Objeto de empresa/tienda
$productos      // Array de productos
$categorias     // Array de categorías
$template       // Configuración del template
$banners        // Array de banners
```

---

## 11. NOTAS IMPORTANTES

1. **Moneda y Números**: Usa formato de Argentina (puntos como separadores de miles)
2. **Idioma**: Español latinoamericano (Argentina específicamente)
3. **Tipografía**: Big Shoulders Display para títulos, Chivo para cuerpo
4. **Colores**: Paleta en blanco y negro con posibilidad de personalización
5. **Animaciones**: Fade-in y transiciones suaves
6. **Accesibilidad**: Usa aria-label en botones, estructura semántica correcta
7. **SEO**: Robots noindex/nofollow (sitio de prueba)
8. **Forms**: Incluye honeypot (winnie-pooh) para evitar spam
9. **Métodos de Pago**: Integración con múltiples gateways (Mercado Pago, etc.)
10. **Empresas de Envío**: OCA, Correo Argentino, etc.

