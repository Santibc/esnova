<header class="js-head-main head-main head-colors position-sticky position-fixed-md transition-soft" data-store="head" data-header-md-fixed="true" style="top: 0px;">

    <!-- Adbar Primary (Gris Oscuro) -->
    <div class="js-adbar js-adbar-primary adbar-primary adbar adbar-colors adbar-with-messages" data-active="true" data-messages="1" data-animated="false">
        <div class="js-adbar-content js-swiper-adbar-primary swiper-container text-center container">
            <div class="js-adbar-messages-container js-adbar-primary-messages-container swiper-wrapper adbar-text-container align-items-center">
                <span class="js-adbar-message-container js-adbar-primary-message-container adbar-message swiper-slide slide-container">
                    ¿Necesitas ayuda? Llámanos: {{ $empresa->telefono ?? '+1 (234) 567-890' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Adbar Secondary (Verde - Animated) - Esnova Watermark or Language/Currency -->
    @if($empresa->planMembresia && $empresa->planMembresia->marca_de_agua)
    <div class="js-adbar js-adbar-secondary adbar-secondary adbar adbar-animated adbar-colors adbar-with-messages" data-active="true" data-messages="20" data-animated="true">
        <div class="js-adbar-content js-swiper-adbar-secondary adbar-content-animated">
            <div class="js-adbar-messages-container js-adbar-secondary-messages-container swiper-wrapper adbar-text-container align-items-center" style="gap: 120px !important;">
                @for($i = 0; $i < 20; $i++)
                <span class="js-adbar-message-container js-adbar-secondary-message-container adbar-message swiper-slide" style="flex: 0 0 auto !important; width: auto !important;">
                    <a href="https://Esnova.com.co" target="_blank" style="color: inherit; text-decoration: none; white-space: nowrap; display: inline-block; padding: 0 10px;">
                        Página creada en Esnova
                    </a>
                </span>
                @endfor
            </div>
        </div>
    </div>
    @else
    <div class="js-adbar js-adbar-secondary adbar-secondary adbar adbar-colors adbar-with-messages" data-active="true" data-messages="1" data-animated="false">
        <div class="js-adbar-content js-swiper-adbar-secondary swiper-container text-center container">
            <div class="js-adbar-messages-container js-adbar-secondary-messages-container swiper-wrapper adbar-text-container align-items-center">
                <span class="js-adbar-message-container js-adbar-secondary-message-container adbar-message swiper-slide slide-container">
                    ES / COP
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Navigation -->
    <div class="js-head-row head-row container logo-center logo-md-left" style="padding-top: 10px !important; padding-bottom: 10px !important;">

        <!-- Mobile Menu Button -->
        <div class="menu-container d-md-none">
            <button class="js-modal-open-private header-utility" data-target="#nav-hamburger" aria-label="Menú">
                <svg class="icon-inline utility-icon icon-lg"><use xlink:href="#bars"></use></svg>
            </button>
        </div>

        <!-- Logo -->
        <div class="js-logo-container logo-container" style="max-width: 120px !important; width: 120px !important;">
            <div id="logo" class="logo-img-container" style="max-height: 50px !important;">
                <a href="{{ route('tienda.empresa') }}" title="{{ $empresa->nombre }}">
                    <img src="{{ $empresa->logo_url ?? asset('images/default-logo.png') }}"
                         alt="{{ $empresa->nombre }}"
                         class="logo-img transition-soft"
                         style="max-height: 50px !important; height: auto !important; width: auto !important; max-width: 120px !important; object-fit: contain !important;">
                </a>
                <h1 style="display: none;">{{ $empresa->nombre }}</h1>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-container" style="flex: 1; display: flex; justify-content: center; max-width: 500px; margin: 0 auto;">
            <form class="js-search-form search-form" action="/search/" method="get">
                <div class="form-group position-relative m-0">
                    <input class="js-search-input form-control search-input"
                           autocomplete="off"
                           type="search"
                           name="q"
                           placeholder="¿Qué estás buscando?"
                           aria-label="¿Qué estás buscando?">
                    <button type="submit" class="js-search-input-submit search-btn search-submit-btn svg-icon-mask" value="Buscar" aria-label="Buscar">
                    </button>
                    <a href="#" class="js-empty-search search-btn search-empty-btn svg-icon-mask" style="display: none;">
                    </a>
                </div>
            </form>
            <div class="js-search-form-suggestions search-suggestions" style="display: none;"></div>
        </div>

        <!-- Utilities -->
        <div class="utilities-container">
            <!-- Cart -->
            <span id="ajax-cart" data-component="cart-button">
                <a href="{{ route('tienda.carrito') }}" class="header-utility">
                    <span class="js-header-utility-icon header-icon-big utility-icon-md-colors">
                        <svg class="icon-inline utility-icon icon-lg"><use xlink:href="#bag"></use></svg>
                        <span class="js-cart-widget-amount badge d-md-none">{{ $carrito->total_items ?? 0 }}</span>
                    </span>
                    <div class="js-header-utility-text js-header-utility-text-cart utility-text d-none d-md-grid">
                        <div class="font-weight-bold d-flex">
                            <span class="mr-1">Carrito</span>
                            <span>(<span class="js-cart-widget-amount">{{ $carrito->total_items ?? 0 }}</span>)</span>
                        </div>
                        <div class="js-cart-widget-total" data-priceraw="{{ $carrito->total ?? 0 }}">${{ number_format($carrito->total ?? 0, 0, ',', '.') }}</div>
                    </div>
                </a>
            </span>
        </div>
    </div>

    <!-- Desktop Navigation Menu -->
    <div class="js-head-row-nav head-row-nav d-none d-md-block">
        <nav class="js-desktop-nav desktop-nav container">
            <div class="nav-list-container">
                <ul class="nav-list nav-list-left">
                    <li class="nav-list-item">
                        <a href="{{ route('tienda.empresa') }}" class="nav-list-link">Inicio</a>
                    </li>
                    @if(isset($categorias) && $categorias && $categorias->count() > 0)
                    <li class="nav-list-item nav-dropdown-parent">
                        <a href="#" class="nav-list-link">Categorías</a>
                        <div class="nav-dropdown-menu">
                            <div class="nav-dropdown-content">
                                @php
                                    $totalCategorias = $categorias->count();
                                    $mitad = ceil($totalCategorias / 2);
                                    $primeraColumna = $categorias->take($mitad);
                                    $segundaColumna = $categorias->slice($mitad);
                                @endphp
                                <div class="nav-dropdown-column">
                                    @foreach($primeraColumna as $categoria)
                                    <a href="{{ route('tienda.empresa', ['categoria' => $categoria->id]) }}" class="nav-dropdown-item">{{ $categoria->nombre }}</a>
                                    @endforeach
                                </div>
                                @if($segundaColumna->count() > 0)
                                <div class="nav-dropdown-column">
                                    @foreach($segundaColumna as $categoria)
                                    <a href="{{ route('tienda.empresa', ['categoria' => $categoria->id]) }}" class="nav-dropdown-item">{{ $categoria->nombre }}</a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>
