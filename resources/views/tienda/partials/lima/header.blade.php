<div class="js-overlay site-overlay" style="display: none;"></div>

<header class="js-head-main head-main position-sticky position-sticky-md transition-soft compress" data-store="head" style="top: -30px;">

    {{-- Topbar Desktop --}}
    <div class="js-topbar section-topbar container-fluid d-none d-md-block">
        <div class="row align-items-center justify-content-end">
            <div class="col">
                <ul class="list">
                    <li class="secondary-menu-item">
                        <a class="secondary-menu-link" href="#">quiénes somos</a>
                    </li>
                    <li class="secondary-menu-item">
                        <a class="secondary-menu-link" href="#">cómo comprar</a>
                    </li>
                    <li class="secondary-menu-item">
                        <a class="secondary-menu-link" href="#">cambios y devoluciones</a>
                    </li>
                    <li class="secondary-menu-item">
                        <a class="secondary-menu-link" href="#">preguntas frecuentes</a>
                    </li>
                    <li class="secondary-menu-item">
                        <a class="secondary-menu-link" href="#">contacto</a>
                    </li>
                </ul>
            </div>
            <div class="col-auto">
                <span class="utilities-container">
                    <a href="#">
                        <svg class="icon-inline utilities-icon mr-1" viewBox="0 0 512 512"><path d="M384.01,294.42H128.04c-49.4,0-89.59,40.19-89.59,89.59v102.39c0,7.17,5.63,12.8,12.8,12.8s12.8-5.63,12.8-12.8v-102.39c0-35.32,28.67-63.99,63.99-63.99h255.97c35.32,0,63.99,28.67,63.99,63.99v102.39c0,7.17,5.63,12.8,12.8,12.8s12.8-5.63,12.8-12.8v-102.39c0-49.4-40.19-89.59-89.59-89.59Z"></path><path d="M256.03,243.23c63.74,0,115.19-51.45,115.19-115.19S319.76,12.85,256.03,12.85s-115.19,51.45-115.19,115.19,51.45,115.19,115.19,115.19Zm0-204.78c49.4,0,89.59,40.19,89.59,89.59s-40.19,89.59-89.59,89.59-89.59-40.19-89.59-89.59,40.19-89.59,89.59-89.59Z"></path></svg>
                    </a>
                    <span class="utilities-text d-inline-block">
                        <a href="#" title="" class="mr-2">Iniciar sesión</a>
                        |<a href="#" title="" class="ml-2">Crear cuenta</a>
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="container-fluid">
        <div class="row align-items-center mb-1 mb-md-0">

            {{-- Mobile Menu Button --}}
            <div class="col-auto col-utility order-first pl-3 d-md-none">
                <span class="utilities-container">
                    <a href="#" class="js-modal-open utilities-item" data-toggle="#nav-hamburger" aria-label="Menú" data-component="menu-button">
                        <svg class="icon-inline utilities-icon align-bottom" viewBox="0 0 512 512"><path d="M25.6,76.8c0-14.14,11.46-25.6,25.6-25.6H460.8c14.14,0,25.6,11.46,25.6,25.6s-11.46,25.6-25.6,25.6H51.2c-14.14,0-25.6-11.46-25.6-25.6Zm0,179.2c0-14.14,11.46-25.6,25.6-25.6H460.8c14.14,0,25.6,11.46,25.6,25.6s-11.46,25.6-25.6,25.6H51.2c-14.14,0-25.6-11.46-25.6-25.6Zm0,179.2c0-14.14,11.46-25.6,25.6-25.6H460.8c14.14,0,25.6,11.46,25.6,25.6s-11.46,25.6-25.6,25.6H51.2c-14.14,0-25.6-11.46-25.6-25.6Z"></path></svg>
                    </a>
                </span>
            </div>

            {{-- Desktop Search --}}
            <div class="col-auto col-utility order-1 order-md-0 pr-md-0 pl-md-3 d-none d-md-inline-block ">
                <form class="js-search-container js-search-form search-container " action="/search/" method="get">
                    <div class="form-group m-0">
                        <input class="js-search-input form-control form-control-rounded search-input " autocomplete="off" type="search" name="q" placeholder="¿Qué estás buscando?" aria-label="¿Qué estás buscando?">
                        <button type="submit" class="search-input-submit" value="Buscar" aria-label="Buscar">
                            <svg class="icon-inline icon-lg svg-icon-text" viewBox="0 0 512 512"><path d="M496.37,453.94l-94.53-94.53c25.99-35.85,41.49-79.79,41.49-127.45,0-120.18-97.42-217.6-217.6-217.6S8.13,111.78,8.13,231.96s97.42,217.6,217.6,217.6c54.04,0,103.38-19.82,141.42-52.43l93.01,93.02c10,10,26.21,10,36.2,0,10-10,10-26.21,0-36.20Zm-270.64-55.59c-91.9,0-166.4-74.5-166.4-166.4S133.83,65.56,225.73,65.56s166.4,74.5,166.4,166.4-74.5,166.4-166.4,166.4Z"></path></svg>
                        </button>
                    </div>
                </form>
                <div class="js-search-suggest search-suggest w-md-100 mt-1 mt-md-2" style="display: none;"></div>
            </div>

            {{-- Logo --}}
            <div class="col text-center order-md-0 logo-md-offset text-md-center ">
                <div id="logo" class="logo-img-container ">
                    <a href="/" title="">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='60'%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial, sans-serif' font-size='24' fill='%23333'%3ELima Theme%3C/text%3E%3C/svg%3E" alt="Lima Theme" class="logo-img transition-soft " width="1150" height="544">
                    </a>
                    <h1 style="display: none;">Lima Theme</h1>
                </div>
            </div>

            {{-- Cart --}}
            <div class="col-auto col-utility-cart text-md-right col-utility order-2  pr-3 px-md-3 ">
                <span class="utilities-container">
                    <div id="ajax-cart" class="cart-summary" data-component="cart-button">
                        <a href="#" data-toggle="#modal-cart" data-modal-url="modal-fullscreen-cart" class="js-modal-open js-fullscreen-modal-open btn btn-medium btn-utility position-relative">
                            <svg class="icon-inline utilities-icon cart-icon mr-md-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M268.8,445.8c0,28.28-22.92,51.2-51.2,51.2s-51.2-22.92-51.2-51.2,22.92-51.2,51.2-51.2,51.2,22.92,51.2,51.2Zm230.4,0c0,28.28-22.92,51.2-51.2,51.2s-51.2-22.92-51.2-51.2,22.92-51.2,51.2-51.2,51.2,22.92,51.2,51.2ZM0,36.2C0,22.06,11.46,10.6,25.6,10.6H109.71c11.87,0,22.19,8.16,24.92,19.72l13.47,57.08h402.3c7.96,0,15.47,3.71,20.32,10.02,4.85,6.32,6.47,14.53,4.41,22.22l-58.51,217.6c-3.01,11.18-13.14,18.95-24.72,18.95H179.2c-11.87,0-22.19-8.16-24.92-19.72L89.45,61.8H25.6C11.46,61.8,0,50.34,0,36.2Zm160.19,102.4l39.27,166.4H472.26l44.75-166.4H160.19Z"></path></svg>
                            <span class="js-cart-widget-total cart-widget-total font-small mr-1 d-none " data-priceraw="0">$0,00</span>
                            <span class="d-none d-md-inline-block">(</span>
                            <span class="js-cart-widget-amount badge d-none d-md-inline-block">0</span>
                            <span class="d-none d-md-inline-block">)</span>
                        </a>
                    </div>
                </span>
            </div>
        </div>

        {{-- Cart Notification Desktop --}}
        <div class="d-none d-md-block">
            <div class="js-alert-added-to-cart notification-floating notification-cart-container notification-hidden " style="display: none;">
                <div class="notification notification-primary notification-cart position-relative">
                    <div class="js-cart-notification-close notification-close mr-2 mt-2">
                        <svg class="icon-inline icon-lg notification-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,274.92,72.67,458.25l-18.1-18.1L237.9,256.82,54.57,73.49l18.1-18.11L256,238.72,439.33,55.38l18.1,18.11L274.1,256.82,457.43,440.15l-18.1,18.1Z"></path></svg>
                    </div>
                    <div class="js-cart-notification-item row" data-store="cart-notification-item">
                        <div class="col-2 pr-0 notification-img">
                            <img src="" class="js-cart-notification-item-img img-fluid">
                        </div>
                        <div class="col-10 text-left">
                            <div class="mb-1 mr-4">
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
                            <strong>¡Agregado al carrito!</strong>
                        </div>
                    </div>
                    <div class="divider my-3"></div>
                    <div class="row text-primary h5 notification-cart-total mb-3">
                        <span class="col-auto text-left">
                            <strong>Total</strong>
                            (<span class="js-cart-widget-amount">0</span>
                            <span class="js-cart-counts-plural" style="display: none;">productos):</span>
                            <span class="js-cart-counts-singular" style="display: none;">producto):</span>
                        </span>
                        <strong class="js-cart-total col text-right">$0,00</strong>
                    </div>
                    <a href="#" data-toggle="#modal-cart" data-modal-url="modal-fullscreen-cart" class=" js-modal-open js-fullscreen-modal-open btn btn-primary btn-medium-md d-block">Ver carrito</a>
                </div>
            </div>
        </div>

        {{-- Mobile Search --}}
        <div class="js-big-search-mobile row no-gutters pb-3 d-block d-md-none">
            <form class="js-search-container js-search-form search-container " action="/search/" method="get">
                <div class="form-group m-0">
                    <input class="js-search-input form-control form-control-rounded search-input " autocomplete="off" type="search" name="q" placeholder="¿Qué estás buscando?" aria-label="¿Qué estás buscando?">
                    <button type="submit" class="search-input-submit" value="Buscar" aria-label="Buscar">
                        <svg class="icon-inline icon-lg svg-icon-text" viewBox="0 0 512 512"><path d="M496.37,453.94l-94.53-94.53c25.99-35.85,41.49-79.79,41.49-127.45,0-120.18-97.42-217.6-217.6-217.6S8.13,111.78,8.13,231.96s97.42,217.6,217.6,217.6c54.04,0,103.38-19.82,141.42-52.43l93.01,93.02c10,10,26.21,10,36.2,0,10-10,10-26.21,0-36.2Zm-270.64-55.59c-91.9,0-166.4-74.5-166.4-166.4S133.83,65.56,225.73,65.56s166.4,74.5,166.4,166.4-74.5,166.4-166.4,166.4Z"></path></svg>
                    </button>
                </div>
            </form>
            <div class="js-search-suggest search-suggest w-md-100 mt-1 mt-md-2" style="display: none;"></div>
        </div>

        {{-- Desktop Navigation Menu --}}
        <div class="js-menu-and-banners-row menu-and-banners-row row align-items-center d-none d-md-flex" style="visibility: visible;">
            <div class="js-desktop-nav-col col ">
                <div class="nav-desktop">
                    <ul class="js-nav-desktop-list nav-desktop-list" data-store="navigation" data-component="menu">
                        <span class="js-nav-desktop-list-arrow js-nav-desktop-list-arrow-left nav-desktop-list-arrow nav-desktop-list-arrow-left text-left disable" style="display: none">
                            <svg class="icon-inline icon-lg svg-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M241,451.75l-18.11,18.1L9.07,256,222.92,42.15,241,60.25,45.28,256Z"></path></svg>
                        </span>

                        <li class="nav-item-dropdown">
                            <a class="nav-list-link" href="#">colección</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-link" href="#">bolsos</a></li>
                                <li><a class="dropdown-link" href="#">bijou</a></li>
                                <li><a class="dropdown-link" href="#">accesorios</a></li>
                                <li><a class="dropdown-link" href="#">anteojos</a></li>
                                <li><a class="dropdown-link" href="#">medias</a></li>
                            </ul>
                        </li>

                        <li><a class="nav-list-link" href="#">beauty</a></li>

                        <li class="nav-item-dropdown">
                            <a class="nav-list-link" href="#">lifestyle</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-link" href="#">papelería</a></li>
                                <li><a class="dropdown-link" href="#">electrónica</a></li>
                            </ul>
                        </li>

                        <li class="nav-item-dropdown">
                            <a class="nav-list-link" href="#">bazar</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-link" href="#">drinkware</a></li>
                            </ul>
                        </li>

                        <li><a class="nav-list-link" href="#">sale</a></li>

                        <span class="js-nav-desktop-list-arrow js-nav-desktop-list-arrow-right nav-desktop-list-arrow nav-desktop-list-arrow-right text-right" style="display: none">
                            <svg class="icon-inline icon-lg svg-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M210.72,256,15,60.25l18.11-18.1L246.93,256,33.08,469.85,15,451.75Z"></path></svg>
                        </span>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</header>

{{-- Pink Promotional Bar - RECONSTRUIDA DESDE CERO --}}
<div class="promotional-bar-simple" style="background-color: #f5d5e0; height: 35px; display: flex; align-items: center; justify-content: center; position: relative;">
    <div class="promotional-content" style="color: #000; font-size: 13px; font-weight: 600; text-transform: lowercase; text-align: center;">
        <span class="promo-message active" style="display: inline-block;">descuento 20%</span>
        <span class="promo-message" style="display: none;">envío gratis</span>
    </div>
    <button class="promo-prev" onclick="changePromo(-1)" style="position: absolute; left: 15px; background: none; border: none; cursor: pointer; color: #000; font-size: 18px;">&lt;</button>
    <button class="promo-next" onclick="changePromo(1)" style="position: absolute; right: 15px; background: none; border: none; cursor: pointer; color: #000; font-size: 18px;">&gt;</button>
</div>

<script>
let currentPromo = 0;
const promoMessages = document.querySelectorAll('.promo-message');

function changePromo(direction) {
    promoMessages[currentPromo].style.display = 'none';
    promoMessages[currentPromo].classList.remove('active');

    currentPromo = (currentPromo + direction + promoMessages.length) % promoMessages.length;

    promoMessages[currentPromo].style.display = 'inline-block';
    promoMessages[currentPromo].classList.add('active');
}

// Auto-rotate every 4 seconds
setInterval(() => changePromo(1), 4000);
</script>

{{-- Cookie Banner --}}
<div class="js-notification js-notification-cookie-banner notification notification-fixed-bottom notification-above notification-primary text-left" style="">
    <div class="container-fluid p-0">
        <div class="row justify-content-center align-items-center">
            <div class="col col-md-auto pr-0">
                Al navegar por este sitio <strong>aceptás el uso de cookies</strong> para agilizar tu experiencia de compra.
            </div>
            <div class="col-auto">
                <a href="#" class="js-notification-close js-acknowledge-cookies btn btn-secondary btn-small">Entendido</a>
            </div>
        </div>
    </div>
</div>

{{-- Cart Notification Mobile --}}
<div class="d-md-none">
    <div class="js-alert-added-to-cart notification-floating notification-cart-container notification-hidden notification-fixed" style="display: none;">
        <div class="notification notification-primary notification-cart position-relative">
            <div class="js-cart-notification-close notification-close mr-2 mt-2">
                <svg class="icon-inline icon-lg notification-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256,274.92,72.67,458.25l-18.1-18.1L237.9,256.82,54.57,73.49l18.1-18.11L256,238.72,439.33,55.38l18.1,18.11L274.1,256.82,457.43,440.15l-18.1,18.1Z"></path></svg>
            </div>
            <div class="js-cart-notification-item row" data-store="cart-notification-item">
                <div class="col-2 pr-0 notification-img">
                    <img src="" class="js-cart-notification-item-img img-fluid">
                </div>
                <div class="col-10 text-left">
                    <div class="mb-1 mr-4">
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
                    <strong>¡Agregado al carrito!</strong>
                </div>
            </div>
            <div class="divider my-3"></div>
            <div class="row text-primary h5 notification-cart-total mb-3">
                <span class="col-auto text-left">
                    <strong>Total</strong>
                    (<span class="js-cart-widget-amount">0</span>
                    <span class="js-cart-counts-plural" style="display: none;">productos):</span>
                    <span class="js-cart-counts-singular" style="display: none;">producto):</span>
                </span>
                <strong class="js-cart-total col text-right">$0,00</strong>
            </div>
            <a href="#" data-toggle="#modal-cart" data-modal-url="modal-fullscreen-cart" class=" js-modal-open js-fullscreen-modal-open btn btn-primary btn-medium-md d-block">Ver carrito</a>
        </div>
    </div>
</div>
