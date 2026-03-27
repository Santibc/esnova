# SPORT TEMPLATE - GUIA RAPIDA DE REFERENCIA

## 1. ENLACES EXTERNOS

### Fuentes
```
Google Fonts: //fonts.googleapis.com/css?family=Big+Shoulders+Display:400,700|Chivo:400,700&display=swap
- Big Shoulders Display → Títulos (h1-h6)
- Chivo → Cuerpo de texto
```

### CSS
```
Crítico: dart-style-critical-b0a03eba075af1eb0c307ad1c3a2f41b.css
Asíncrono: dart-style-async-8f0a45a8e884c9144116ff6fcf196da8.css
CDN: //dcdn-us.mitiendanube.com/stores/003/928/529/themes/baires/
```

### JavaScript
```
Principal: dart-external-no-dependencies-2020a4748d2e0fc983451e7972c49502.js
Google reCAPTCHA: https://www.google.com/recaptcha/api.js
```

---

## 2. PALETA DE COLORES

| Variable | Valor | Uso |
|----------|-------|-----|
| --main-foreground | #000000 | Textos principales |
| --main-background | #FFFFFF | Fondos principales |
| --header-background | #FFFFFF | Fondo header |
| --header-foreground | #000000 | Texto header |
| --footer-background | #000000 | Fondo footer |
| --footer-foreground | #FFFFFF | Texto footer |
| --button-background | #000000 | Botones |
| --button-foreground | #FFFFFF | Texto botones |
| --success | #4bb98c | Éxito (verde) |
| --danger | #dd7774 | Error (rojo) |
| --warning | #dc8f38 | Advertencia (naranja) |

---

## 3. HEADER - ESTRUCTURA

```
┌─────────────────────────────────────────────────────────────────┐
│ MÓVIL (< 768px):                                                │
│ [☰ Menú] [       LOGO         ] [🔍] [🛒]                       │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ DESKTOP (>= 768px):                                             │
│ [🔍 Buscar] [      LOGO         ] [Login] [🛒]                  │
└─────────────────────────────────────────────────────────────────┘
```

**Componentes:**
- Logo: Centrado, webp optimizado
- Menú: Toggle #nav-hamburger (móvil)
- Búsqueda: Toggle #nav-search (desktop)
- Login: Link /account/login/
- Carrito: Toggle #modal-cart con badge

**Clases Clave:**
- position-sticky (fijo en scroll)
- head-logo-center (móvil)
- head-logo-md-center (desktop)

---

## 4. FOOTER - ESTRUCTURA

```
┌──────────────────────────────────────────────────────────────┐
│                    FOOTER STRUCTURE                          │
├──────────────────────────────────────────────────────────────┤
│ IZQUIERDA (Desktop)          │ DERECHA (Desktop)            │
│ - REPUESTOS                  │ [SUSCRIBITE]                 │
│ - INDUMENTARIA               │ Email: [________] [Enviar]   │
│ - CALZADO                    │ REDES: 📱 🔵 📺 🎵 🐦          │
│ - ACCESORIOS                 │ TEL: 4506-7890              │
│                              │ EMAIL: email@...             │
│                              │ DIR: Av. Córdoba 2334        │
├──────────────────────────────────────────────────────────────┤
│ [LOGO EMPRESA]                                               │
├──────────────────────────────────────────────────────────────┤
│ NOSOTROS | CÓMO COMPRAR | CONTACTO                          │
│ Copyright 2025 | Powered by TiendaNube                      │
└──────────────────────────────────────────────────────────────┘
```

**Newsletter:**
- Campo email con placeholder
- Honeypot: winnie-pooh (anti-spam)
- Action dinámica: /winnie-pooh

**Contacto:**
- Teléfono: 4506-7890
- Email: baires.skate@tiendanube.com
- Dirección: Av. Córdoba 2334

**Métodos de Pago:** 15+ (Visa, MC, Amex, Cabal, Tarjeta Naranja, etc.)
**Empresas de Envío:** 5+ (OCA, Correo, Branch, etc.)

---

## 5. SECCIONES DEL HOME (Orden)

```
1. Banners de Categorías (Slider)
2. Mensaje Institucional
3. Anuncio Animado (fade-in)
4. Productos "LO NUEVO" (Carrusel 3 col)
5. Welcome Message (fade-in)
6. Video/Slider Principal (fade-in)
7. Marcas (Brands)
8. Producto Destacado (Detalles + Variantes)
9. Feed Instagram
10. Categorías Destacadas (fade-in)
11. Nuevos Productos (Carrusel)
12. Newsletter
13. Video Section (fade-in)
14. Banner Informativo de Servicios
```

---

## 6. COMPONENTES DE PRODUCTO

**Carrusel:**
- Swiper.js o slider nativo
- Móvil: 1 columna
- Desktop: 3 columnas (col-md-4)
- Espaciado: 15px entre items

**Datos en JSON (data-variants):**
```javascript
{
  product_id: 189951147,
  price_short: "$19.000,00",
  price_long: "$19.000,00 ARS",
  price_number: 19000,
  option0: "XS",         // Variante (talla, color, etc)
  option1: null,
  option2: null,
  image_url: "https://...",
  available: true,
  stock: null,
  installments_data: {
    "Mercado Pago": {
      "1": {installment_value: 19000, interest: 0},
      "3": {installment_value: 6333.33, interest: 0},
      "6": {installment_value: 3166.67, interest: 0},
      "12": {installment_value: 3380.42, interest: 1.135}
    }
  }
}
```

**Variantes:**
- Mostradas como dropdown (móvil) o botones (desktop)
- Clases: js-insta-variant btn btn-variant selected

**Precios:**
- Formato: $X.XXX,00 ARS (punto separador miles, coma decimales)
- Cuotas: Desde Mercado Pago, hasta 24 meses

---

## 7. TAMAÑOS DE FUENTES

| Variable | Móvil | Desktop |
|----------|-------|---------|
| h1 | 28px | - |
| h2 | 24px | - |
| h3 | 20px | - |
| h4 | 18px | - |
| h1-huge | 100px | - |
| h1-huge-md | - | 125px |
| h2-huge | 70px | - |
| h2-huge-md | - | 200px |
| h3-huge | 50px | - |
| h4-huge | 40px | - |
| font-base | 14px | - |
| font-big | 16px | - |
| font-small | 12px | - |

---

## 8. ESPACIADOS

```css
--gutter: 15px                          (Base móvil)
--gutter-container: 15px               (Contenedor móvil)
--gutter-container-md: 30px            (Contenedor desktop)
--section-distance: 30px               (Entre secciones)
--section-distance-huge: 60px          (Entre secciones grandes)
--section-distance-huge-md: 90px       (Desktop)
```

---

## 9. BREAKPOINTS RESPONSIVOS

```css
Móvil: < 768px
Desktop: >= 768px (col-md-*)

Clases Bootstrap:
- d-none d-md-inline-block  (Oculto móvil, visible desktop)
- d-md-none                 (Visible móvil, oculto desktop)
- col-12                    (100% ancho móvil)
- col-md-4                  (33% ancho desktop = 3 columnas)
```

---

## 10. ANIMACIONES

```css
Clases:
- is-inViewport           (Se activa al entrar en viewport)
- data-transition="fade-in"  (Fade in animation)
- transition-soft         (Transiciones suaves)
- transition-slide        (Slide animation para modales)
- section-home-text-animated  (Texto con animación)
- home-text-outline       (Texto con stroke/outline)
```

---

## 11. MODALES

| ID | Contenido | Abre desde |
|----|-----------|-----------|
| #nav-hamburger | Menú móvil | Botón Menú |
| #nav-search | Búsqueda fullscreen | Botón Buscar |
| #modal-cart | Carrito fullscreen | Botón Carrito |
| #js-cross-selling-modal | Venta cruzada | Sistema |
| #related-products-notification | Productos relacionados | Sistema |

---

## 12. DATOS DINÁMICOS DESDE CONTROLLER

```php
// Controller debe pasar:
$empresa            // Objeto Empresa
$productos          // Array de productos
$categorias         // Array de categorías
$banners            // Array de banners
$featured_products  // Productos destacados
$new_products       // Productos nuevos
$brands             // Array de marcas
$main_product       // Producto principal (single-product)
```

---

## 13. VARIABLES CSS IMPORTANTES

```css
/* Moneda */
--moneda: ARS;
--moneda-symbol: "$";

/* Formato números */
--numero-separador-miles: ".";
--numero-separador-decimales: ",";

/* Transformación texto */
--main-text-transform: uppercase;

/* Tipografía */
--heading-font: "Big Shoulders Display", sans-serif;
--body-font: "Chivo", sans-serif;
--title-font-weight: bold;
```

---

## 14. ACCESIBILIDAD

```html
<!-- Aria labels necesarios -->
<a data-toggle="#nav-hamburger" aria-label="Menú">
<a data-toggle="#nav-search" aria-label="Buscador">
<a href="..." aria-label="instagram baires-skate">

<!-- H1 invisible pero presente -->
<h1 style="display: none;">baires-skate</h1>
```

---

## 15. SEO

```html
<title>Tienda Online de baires-skate</title>
<meta name="description" content="Comprá productos...">
<link rel="canonical" href="https://baires-theme.mitiendanube.com/">

<!-- Open Graph -->
<meta property="og:title" content="Tienda Online...">
<meta property="og:image" content="...">
<meta property="og:url" content="...">
```

---

## 16. FORMULARIOS

**Newsletter:**
```html
<form method="post" action="/winnie-pooh" onsubmit="this.setAttribute('action', '');>
  <input type="email" name="email" placeholder="Email">
  <input type="hidden" name="type" value="newsletter">
  <input type="hidden" name="message" value="Pedido de inscripción a newsletter">
  <input type="text" name="winnie-pooh" style="display: none;"> <!-- Honeypot -->
  <input type="submit" value="Enviar">
</form>
```

---

## 17. RUTAS IMPORTANTES

```
/account/login/          → Login
/envio/                  → Cálculo de envío
/winnie-pooh             → Newsletter endpoint
/contacto/               → Contacto
/quienes-somos/          → About
/como-comprar/           → How to buy
/repuestos/              → Categoría
/indumentaria/           → Categoría
/zapatillas/             → Categoría
/accesorios/             → Categoría
```

---

## 18. ESTRUCTURA ARCHIVOS BLADE

```
resources/views/tienda/
├── sport_layout.blade.php          (Layout base)
├── sport_index.blade.php           (Home)
├── sport_categoria.blade.php       (Categoría
├── sport_producto.blade.php        (Detalle producto)
└── partials/sport/
    ├── header.blade.php
    ├── footer.blade.php
    ├── product-card.blade.php
    ├── slider.blade.php
    └── newsletter.blade.php
```

---

## 19. BD - CONFIGURACION

```php
TemplateTienda::create([
    'codigo' => 'sport',
    'nombre' => 'Sport Template',
    'descripcion' => 'Template para tienda de artículos deportivos',
    'vista_index' => 'tienda.sport_index',
    'vista_categoria' => 'tienda.sport_categoria',
    'vista_producto' => 'tienda.sport_producto',
    'layout' => 'tienda.sport_layout',
    'preview_image' => 'images/templates/sport-preview.jpg',
    'activo' => true,
    'es_default' => false,
    'configuracion' => json_encode([
        'color_primario' => '#000000',
        'color_secundario' => '#FFFFFF',
        'mostrar_banners' => true,
        'mostrar_instagram' => true,
        'mostrar_video' => true,
    ]),
]);
```

---

## 20. CHECKLIST IMPLEMENTACION

- [ ] Crear sport_layout.blade.php con CSS variables
- [ ] Crear sport_index.blade.php con 14 secciones
- [ ] Crear sport_categoria.blade.php con carrusel
- [ ] Crear sport_producto.blade.php con variantes
- [ ] Crear partials (header, footer, product-card, etc)
- [ ] Añadir sport-theme.css personalizado
- [ ] Añadir sport-theme.js (modales, carrusel, etc)
- [ ] Insertar TemplateTienda en BD
- [ ] Crear TemplateStrategyInterface implementation
- [ ] Registrar strategy en TemplateResolver
- [ ] Crear SportTemplateStrategy
- [ ] Crear migration y seeder si necesario
- [ ] Probar responsive (móvil, tablet, desktop)
- [ ] Probar animaciones y transiciones
- [ ] Probar formularios (newsletter, contacto)

