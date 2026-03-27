<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if($page->seo)
        <title>{{ $page->seo->meta_title ?: 'Esnova' }}</title>
        @if($page->seo->meta_description)
            <meta name="description" content="{{ $page->seo->meta_description }}">
        @endif
        @if($page->seo->meta_keywords)
            <meta name="keywords" content="{{ $page->seo->meta_keywords }}">
        @endif
        @if($page->seo->canonical_url)
            <link rel="canonical" href="{{ $page->seo->canonical_url }}">
        @endif
        <meta name="robots" content="{{ $page->seo->robots ?: 'index,follow' }}">

        {{-- Open Graph --}}
        @if($page->seo->og_title)
            <meta property="og:title" content="{{ $page->seo->og_title }}">
        @endif
        @if($page->seo->og_description)
            <meta property="og:description" content="{{ $page->seo->og_description }}">
        @endif
        @if($page->seo->og_image)
            <meta property="og:image" content="{{ Storage::url($page->seo->og_image) }}">
        @endif
        <meta property="og:type" content="{{ $page->seo->og_type ?: 'website' }}">
        @if($page->seo->og_url)
            <meta property="og:url" content="{{ $page->seo->og_url }}">
        @endif
        @if($page->seo->og_site_name)
            <meta property="og:site_name" content="{{ $page->seo->og_site_name }}">
        @endif

        {{-- Twitter Cards --}}
        <meta name="twitter:card" content="{{ $page->seo->twitter_card ?: 'summary_large_image' }}">
        @if($page->seo->twitter_title)
            <meta name="twitter:title" content="{{ $page->seo->twitter_title }}">
        @endif
        @if($page->seo->twitter_description)
            <meta name="twitter:description" content="{{ $page->seo->twitter_description }}">
        @endif
        @if($page->seo->twitter_image)
            <meta name="twitter:image" content="{{ Storage::url($page->seo->twitter_image) }}">
        @endif
        @if($page->seo->twitter_site)
            <meta name="twitter:site" content="{{ $page->seo->twitter_site }}">
        @endif
        @if($page->seo->twitter_creator)
            <meta name="twitter:creator" content="{{ $page->seo->twitter_creator }}">
        @endif
    @else
        <title>Esnova</title>
    @endif

    <link rel="icon" type="image/png" href="{{ asset($page->content['favicon'] ?? 'images/ico.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Modern Navigation Bar Styles */
        .modern-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 68px;
            background: linear-gradient(135deg, rgba(16, 16, 30, 0.97) 0%, rgba(30, 30, 60, 0.97) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
        }

        .modern-navbar.scrolled {
            height: 60px;
            background: linear-gradient(135deg, rgba(16, 16, 30, 0.99) 0%, rgba(30, 30, 60, 0.99) 100%);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-logo:hover {
            transform: translateY(-1px);
        }

        .logo-nav {
            height: 44px;
            width: auto;
            transition: all 0.3s ease;
        }

        .modern-navbar.scrolled .logo-nav {
            height: 40px;
        }

        .welcome-nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .welcome-nav-link {
            position: relative;
            padding: 8px 16px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
            border: none;
            background: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .welcome-nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .welcome-nav-link i {
            margin-right: 6px;
            font-size: 1rem;
        }

        .welcome-nav-link.active {
            color: #fff;
            background: rgba(255, 0, 200, 0.2);
        }

        .nav-separator {
            width: 1px;
            height: 24px;
            background: rgba(255, 255, 255, 0.15);
            margin: 0 0.25rem;
        }

        .welcome-nav-link.btn-register {
            background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .welcome-nav-link.btn-register:hover {
            box-shadow: 0 4px 15px rgba(255, 0, 200, 0.4);
            transform: translateY(-1px);
        }

        .welcome-nav-link.btn-login {
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .welcome-nav-link.btn-login:hover {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.08);
        }

        .logout-form {
            display: inline;
        }

        .welcome-nav-link.btn-logout {
            color: rgba(255, 130, 130, 0.9);
        }

        .welcome-nav-link.btn-logout:hover {
            color: #fff;
            background: rgba(220, 53, 69, 0.3);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s ease;
            padding: 0;
            flex-shrink: 0;
        }

        .mobile-menu-btn:hover,
        .mobile-menu-btn:focus {
            background: rgba(255, 0, 200, 0.15);
            border-color: rgba(255, 0, 200, 0.3);
        }

        .mobile-menu-btn .hamburger-icon {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 22px;
        }

        .mobile-menu-btn .hamburger-icon span {
            display: block;
            width: 100%;
            height: 2.5px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn.active .hamburger-icon span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-btn.active .hamburger-icon span:nth-child(2) {
            opacity: 0;
            width: 0;
        }

        .mobile-menu-btn.active .hamburger-icon span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            max-width: 85vw;
            height: 100vh;
            background: #fff;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 24px rgba(0, 0, 0, 0.15);
        }

        .mobile-menu-overlay.active .mobile-menu {
            transform: translateX(0);
        }

        .mobile-menu-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-logo {
            height: 36px;
            width: auto;
        }

        .close-mobile-menu {
            width: 36px;
            height: 36px;
            border: none;
            background: #f5f5f5;
            font-size: 1.2rem;
            color: #555;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .close-mobile-menu:hover {
            background: #ff00c8;
            color: #fff;
        }

        .mobile-menu-links {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            overflow-y: auto;
            flex: 1;
        }

        .mobile-menu-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            padding: 0.75rem 1rem 0.25rem;
            font-weight: 600;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }

        .mobile-nav-link:hover {
            background: #f5f0ff;
            color: #7000ff;
        }

        .mobile-nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            color: #888;
        }

        .mobile-nav-link:hover i {
            color: #7000ff;
        }

        .mobile-nav-link.register-mobile {
            background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%);
            color: white;
            margin-top: 0.5rem;
            justify-content: center;
            font-weight: 600;
        }

        .mobile-nav-link.register-mobile i {
            color: white;
        }

        .mobile-nav-link.register-mobile:hover {
            box-shadow: 0 4px 15px rgba(255, 0, 200, 0.3);
        }

        .mobile-nav-link.logout-mobile {
            color: #dc3545;
        }

        .mobile-nav-link.logout-mobile i {
            color: #dc3545;
        }

        .mobile-nav-link.logout-mobile:hover {
            background: #fef2f2;
        }

        .mobile-menu-divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.5rem 1rem;
        }

        /* Body padding for fixed navbar */
        body {
            padding-top: 68px;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .welcome-nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .nav-container {
                padding: 0 1rem;
            }

            .logo-nav {
                height: 36px;
            }

            body {
                padding-top: 60px;
            }

            .modern-navbar {
                height: 60px;
            }
        }

        /* Animation for scroll */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-navbar {
            animation: fadeInDown 0.6s ease-out;
        }
    </style>

</head>

<body id="welcome-body">
    <!-- Navigation Bar -->
    <nav class="modern-navbar" id="modernNavbar">
        <div class="nav-container">
            <!-- Logo -->
            <a href="/" class="nav-logo">
                <img src="{{ asset($page->content['logo_principal'] ?? 'images/logo1.png') }}" alt="Esnova" class="logo-nav">
            </a>

            <!-- Desktop Navigation Links -->
            <div class="welcome-nav-links">
                <a href="/" class="welcome-nav-link active">
                    <i class="bi bi-house-door"></i>Inicio
                </a>
                <a href="{{ url('/catalogo') }}" class="welcome-nav-link">
                    <i class="bi bi-grid"></i>Catálogo
                </a>
                <a href="{{ route('tienda.blog') }}" class="welcome-nav-link">
                    <i class="bi bi-journal-richtext"></i>Blog
                </a>

                <div class="nav-separator"></div>

                @auth
                    <a href="{{ route('dashboard') }}" class="welcome-nav-link">
                        <i class="bi bi-person-circle"></i>Mi cuenta
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="welcome-nav-link btn-logout">
                            <i class="bi bi-box-arrow-right"></i>Salir
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="welcome-nav-link btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="welcome-nav-link btn-register">
                        <i class="bi bi-person-plus"></i>Registrarse
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Abrir menú">
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay">
            <div class="mobile-menu">
                <div class="mobile-menu-header">
                    <img src="{{ asset($page->content['logo_principal'] ?? 'images/logo1.png') }}" alt="Esnova" class="mobile-logo">
                    <button class="close-mobile-menu" id="closeMobileMenu" aria-label="Cerrar menú">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mobile-menu-links">
                    <span class="mobile-menu-label">Navegación</span>
                    <a href="/" class="mobile-nav-link">
                        <i class="bi bi-house-door"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="{{ url('/catalogo') }}" class="mobile-nav-link">
                        <i class="bi bi-grid"></i>
                        <span>Catálogo</span>
                    </a>
                    <a href="{{ route('tienda.blog') }}" class="mobile-nav-link">
                        <i class="bi bi-journal-richtext"></i>
                        <span>Blog</span>
                    </a>

                    <div class="mobile-menu-divider"></div>
                    <span class="mobile-menu-label">Cuenta</span>

                    @auth
                        <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                            <i class="bi bi-person-circle"></i>
                            <span>Mi cuenta</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mobile-nav-link logout-mobile">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar sesión</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mobile-nav-link">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Iniciar sesión</span>
                        </a>
                        <a href="{{ route('register') }}" class="mobile-nav-link register-mobile">
                            <i class="bi bi-person-plus"></i>
                            <span>Registrarse</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <header>

        <div class="hero">
            <div class="hero-text">
                <h1>{!! $page->content['hero_title'] ?? '¡Hola Colombia!<br>Tu Negocio, <span>Nuestra causa.</span>' !!}</h1>
                <p>{!! $page->content['hero_subtitle'] ?? 'Te abrimos la puerta a un ecosistema de <strong>crecimiento sin límites.</strong>' !!}</p>
                <ul class="benefits">
                    @if(isset($page->content['hero_benefits']))
                        @foreach(explode('|', $page->content['hero_benefits']) as $benefit)
                            <li>{{ $benefit }}</li>
                        @endforeach
                    @else
                        <li>🛒 Tienda Online al Instante</li>
                        <li>🚚 Pasarela de pago y Logística integrada</li>
                        <li>🎉 Festivales Exclusivos para nuestros miembros</li>
                    @endif
                </ul>
                <div class="buttons">
                    <a href="{{ url('/catalogo') }}" class="btn pink">{{ $page->content['hero_btn_primary'] ?? 'Así lo hacemos posible' }}</a>
                    <a href="{{ url('/catalogo') }}" class="btn outline">{{ $page->content['hero_btn_secondary'] ?? 'Regístrate ahora' }}</a>
                </div>
            </div>
        </div>
        <div class="wave-container">
            <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="white" fill-opacity="1"
                    d="M0,256L26.7,213.3C53.3,171,107,85,160,90.7C213.3,96,267,192,320,224C373.3,256,427,224,480,224C533.3,224,587,256,640,261.3C693.3,267,747,245,800,234.7C853.3,224,907,224,960,197.3C1013.3,171,1067,117,1120,122.7C1173.3,128,1227,192,1280,208C1333.3,224,1387,192,1413,176L1440,160L1440,320L1413.3,320C1386.7,320,1333,320,1280,320C1226.7,320,1173,320,1120,320C1066.7,320,1013,320,960,320C906.7,320,853,320,800,320C746.7,320,693,320,640,320C586.7,320,533,320,480,320C426.7,320,373,320,320,320C266.7,320,213,320,160,320C106.7,320,53,320,27,320L0,320Z">
                </path>

                <path fill="white" fill-opacity="0.6"
                    d="M0,160L34.3,181.3C68.6,203,137,245,206,250.7C274.3,256,343,224,411,192C480,160,549,128,617,133.3C685.7,139,754,181,823,213.3C891.4,245,960,267,1029,250.7C1097.1,235,1166,181,1234,160C1302.9,139,1371,149,1406,154.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
                <path fill="white" fill-opacity="0.3"
                    d="M0,64L18.5,90.7C36.9,117,74,171,111,213.3C147.7,256,185,288,222,266.7C258.5,245,295,171,332,154.7C369.2,139,406,181,443,186.7C480,192,517,160,554,144C590.8,128,628,128,665,138.7C701.5,149,738,171,775,165.3C812.3,160,849,128,886,144C923.1,160,960,224,997,250.7C1033.8,277,1071,267,1108,240C1144.6,213,1182,171,1218,144C1255.4,117,1292,107,1329,122.7C1366.2,139,1403,181,1422,202.7L1440,224L1440,320L1421.5,320C1403.1,320,1366,320,1329,320C1292.3,320,1255,320,1218,320C1181.5,320,1145,320,1108,320C1070.8,320,1034,320,997,320C960,320,923,320,886,320C849.2,320,812,320,775,320C738.5,320,702,320,665,320C627.7,320,591,320,554,320C516.9,320,480,320,443,320C406.2,320,369,320,332,320C295.4,320,258,320,222,320C184.6,320,148,320,111,320C73.8,320,37,320,18,320L0,320Z">
                </path>
                <path fill="white" fill-opacity="0.3"
                    d="M0,192L34.3,197.3C68.6,203,137,213,206,192C274.3,171,343,117,411,112C480,107,549,149,617,181.3C685.7,213,754,235,823,218.7C891.4,203,960,149,1029,106.7C1097.1,64,1166,32,1234,26.7C1302.9,21,1371,43,1406,53.3L1440,64L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>
    </header>

    <!-- 🎯 SVG SECTION -->


    <!-- 🎯 HERO SECTION -->
    <section class="seccion-oferta">
        <p class="subtitulo">{{ $page->content['offer_subtitle'] ?? '¡ACTIVA TU MARCA EN LO DIGITAL Y PRESENCIAL!' }}</p>
        <h2 class="titulo">{{ $page->content['offer_title'] ?? '¿Emprendes o lideras una fundación?' }}</h2>
        <p class="descripcion">
            {!! $page->content['offer_description'] ?? 'Con nosotros no solo accedes a una plataforma…<br />¡Abres la puerta a un <strong>ecosistema completo</strong> que se enfoca en atraer visitantes y potenciales clientes para ti.' !!}
        </p>

        <div class="tarjetas-contenedor">
            <!-- Tarjeta 1 -->
            <div class="tarjeta">
                <div class="icono"><i class="{{ $page->content['card1_icon'] ?? 'bi bi-laptop' }}"></i></div>
                <h3><a href="#">{{ $page->content['card1_title'] ?? 'Tu Tienda Online ¡Lista para Vender!' }}</a></h3>
                <p>{!! $page->content['card1_description'] ?? 'Lánzala en minutos y gestiona pagos seguros, envíos y estadísticas para el control total de tus ventas. 🧾' !!}</p>
            </div>

            <!-- Tarjeta 2 -->
            <div class="tarjeta">
                <div class="icono"><i class="{{ $page->content['card2_icon'] ?? 'fa-solid fa-handshake' }}"></i></div>
                <h3><a href="#">{{ $page->content['card2_title'] ?? 'Tu Marca Siempre Visible.' }}</a></h3>
                <p>{!! $page->content['card2_description'] ?? '¡No solo vendes, tu marca conecta! ❤️ Creamos contenido audiovisual en nuestras redes que cuenta la historia de algunos de nuestros miembros, impulsando su reconocimiento. <strong>Somos tu aliado para hacerte conocer.</strong>' !!}</p>
            </div>

            <!-- Tarjeta 3 -->
            <div class="tarjeta">
                <div class="icono"><i class="{{ $page->content['card3_icon'] ?? 'bi bi-star-fill' }}"></i></div>
                <h3><a href="#">{{ $page->content['card3_title'] ?? '¡Brilla en nuestros eventos exclusivos!' }}</a></h3>
                <p>{!! $page->content['card3_description'] ?? 'Lleva tu marca al siguiente nivel en festivales comerciales. Tu tienda digital y física se fusionan para que solo te preocupes por vender, nosotros nos encargamos del resto y, <strong>¡nosotros ponemos la infraestructura!</strong> 🏠' !!}</p>
            </div>
        </div>
    </section>





    <section class="hero2">
        <!-- Onda superior -->
        <svg class="wave wave-top" viewBox="0 0 1440 320">
            <path fill="#fff" fill-opacity="0.3"
                d="M0,128L60,154.7C120,181,240,235,360,240C480,245,600,203,720,176C840,149,960,139,1080,149.3C1200,160,1320,192,1380,208L1440,224L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="0.3"
                d="M0,256L24,234.7C48,213,96,171,144,154.7C192,139,240,149,288,154.7C336,160,384,160,432,170.7C480,181,528,203,576,192C624,181,672,139,720,122.7C768,107,816,117,864,133.3C912,149,960,171,1008,197.3C1056,224,1104,256,1152,240C1200,224,1248,160,1296,154.7C1344,149,1392,203,1416,229.3L1440,256L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="0.5"
                d="M0,224L26.7,186.7C53.3,149,107,75,160,69.3C213.3,64,267,128,320,128C373.3,128,427,64,480,64C533.3,64,587,128,640,133.3C693.3,139,747,85,800,80C853.3,75,907,117,960,117.3C1013.3,117,1067,75,1120,90.7C1173.3,107,1227,181,1280,208C1333.3,235,1387,213,1413,202.7L1440,192L1440,0L1413.3,0C1386.7,0,1333,0,1280,0C1226.7,0,1173,0,1120,0C1066.7,0,1013,0,960,0C906.7,0,853,0,800,0C746.7,0,693,0,640,0C586.7,0,533,0,480,0C426.7,0,373,0,320,0C266.7,0,213,0,160,0C106.7,0,53,0,27,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="1"
                d="M0,96L48,90.7C96,85,192,75,288,85.3C384,96,480,128,576,128C672,128,768,96,864,96C960,96,1056,128,1152,128C1248,128,1344,96,1392,80L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
            </path>
        </svg>

        <!-- Contenido -->
        <h2>{!! $page->content['stats_title'] ?? '¡Únete a la <strong>primera membresía</strong> en Colombia para Emprendedores y <strong>Fundaciones</strong>!' !!}</h2>
        <h1>{{ $page->content['stats_subtitle'] ?? 'Juntos, ya impactamos a más de:' }}</h1>
        <div class="contador"><span id="contador">0</span>K</div>
        <div class="subcontador">{{ $page->content['stats_label'] ?? 'VISITANTES TOTALES' }}</div>
        <script>
            const contador = document.getElementById('contador');
            const valorFinal = {{ $page->content['stats_count'] ?? 134 }};
            let actual = 0;
            const velocidad = 15;

            const animar = setInterval(() => {
                actual++;
                contador.textContent = actual;
                if (actual >= valorFinal) clearInterval(animar);
            }, velocidad);
        </script>

        <!-- Onda inferior -->

    </section>
    <svg class="wave-bottom" viewBox="0 0 1440 320">
        <path fill="#fff" fill-opacity="1"
            d="M0,192L48,202.7C96,213,192,235,288,224C384,213,480,171,576,176C672,181,768,235,864,240C960,245,1056,203,1152,186.7C1248,171,1344,181,1392,186.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
        </path>



        <path fill="#fff" fill-opacity="0.5"
            d="M0,32L30,64C60,96,120,160,180,176C240,192,300,160,360,149.3C420,139,480,149,540,165.3C600,181,660,203,720,192C780,181,840,139,900,128C960,117,1020,139,1080,165.3C1140,192,1200,224,1260,234.7C1320,245,1380,235,1410,229.3L1440,224L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z">
        </path>

        <path fill="#FFF" fill-opacity="0.5"
            d="M0,32L18.5,26.7C36.9,21,74,11,111,53.3C147.7,96,185,192,222,245.3C258.5,299,295,309,332,298.7C369.2,288,406,256,443,250.7C480,245,517,267,554,256C590.8,245,628,203,665,208C701.5,213,738,267,775,277.3C812.3,288,849,256,886,256C923.1,256,960,288,997,272C1033.8,256,1071,192,1108,181.3C1144.6,171,1182,213,1218,218.7C1255.4,224,1292,192,1329,165.3C1366.2,139,1403,117,1422,106.7L1440,96L1440,320L1421.5,320C1403.1,320,1366,320,1329,320C1292.3,320,1255,320,1218,320C1181.5,320,1145,320,1108,320C1070.8,320,1034,320,997,320C960,320,923,320,886,320C849.2,320,812,320,775,320C738.5,320,702,320,665,320C627.7,320,591,320,554,320C516.9,320,480,320,443,320C406.2,320,369,320,332,320C295.4,320,258,320,222,320C184.6,320,148,320,111,320C73.8,320,37,320,18,320L0,320Z">
        </path>
        <path fill="#fff" fill-opacity="0.3"
            d="M0,64L21.8,101.3C43.6,139,87,213,131,229.3C174.5,245,218,203,262,181.3C305.5,160,349,160,393,181.3C436.4,203,480,245,524,229.3C567.3,213,611,139,655,122.7C698.2,107,742,149,785,192C829.1,235,873,277,916,266.7C960,256,1004,192,1047,138.7C1090.9,85,1135,43,1178,69.3C1221.8,96,1265,192,1309,208C1352.7,224,1396,160,1418,128L1440,96L1440,320L1418.2,320C1396.4,320,1353,320,1309,320C1265.5,320,1222,320,1178,320C1134.5,320,1091,320,1047,320C1003.6,320,960,320,916,320C872.7,320,829,320,785,320C741.8,320,698,320,655,320C610.9,320,567,320,524,320C480,320,436,320,393,320C349.1,320,305,320,262,320C218.2,320,175,320,131,320C87.3,320,44,320,22,320L0,320Z">
        </path>

    </svg>
    <!--<img src="{{ asset('images/imagen1.png') }}" alt="imagen"> -->
    <section class="features-section">
        <div class="features-container">
            <div class="features-image">
                <img src="{{ asset($page->content['imagen_seccion_principal'] ?? 'images/imagen1.png') }}" alt="imagen">
            </div>

            <div class="features-text">
                <p class="subtitulo">{{ $page->content['features_subtitle'] ?? 'UN ECOSISTEMA COMPLETO Y LISTO PARA TI' }}</p>
                <h2>{{ $page->content['features_title'] ?? 'Sencillo, Rápido y Poderoso' }}</h2>
                <p class="intro">
                    {!! $page->content['features_intro'] ?? 'Tu tienda,<br />Tus eventos, tu momento<br />Inscríbete en la lista de espera 🚨' !!}
                </p>

                <div class="feature-item">
                    <div class="icon {{ $page->content['step1_color'] ?? 'pink' }}"><i class="{{ $page->content['step1_icon'] ?? 'bi bi-person-plus' }}"></i></div>
                    <div class="feature-content">
                        <h3>{{ $page->content['step1_title'] ?? 'Regístrate' }}</h3>
                        <p>{!! $page->content['step1_description'] ?? 'Da el primer paso para impulsar tu marca. Crea tu cuenta <strong>sin costo</strong> y configúrala en minutos.' !!}</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="icon {{ $page->content['step2_color'] ?? 'blue' }}"><i class="{{ $page->content['step2_icon'] ?? 'bi bi-check-lg' }}"></i></div>
                    <div class="feature-content">
                        <h3>{{ $page->content['step2_title'] ?? 'Activa' }}</h3>
                        <p>{!! $page->content['step2_description'] ?? 'Elige tu <strong>membresía</strong> activála ✅ y accede a múltiples beneficios. <strong>¡Así podrás enfocarte solo en vender!</strong>' !!}</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="icon {{ $page->content['step3_color'] ?? 'pink' }}"><i class="{{ $page->content['step3_icon'] ?? 'bi bi-calendar' }}"></i></div>
                    <div class="feature-content">
                        <h3>{{ $page->content['step3_title'] ?? 'Agéndate' }}</h3>
                        <p>{!! $page->content['step3_description'] ?? '<strong>Alquila tu espacio</strong> en nuestros festivales de comercio. ¡Tú solo vende, nosotros hacemos el resto!' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bt-section">
        <p class="bt-subtitle">{{ $page->content['bt_subtitle'] ?? 'Nacimos para transformar el emprendimiento y el impacto social en Colombia.' }}</p>
        <h2 class="bt-title">{{ $page->content['bt_title'] ?? '"Emprender no debe ser imposible, debe ser accesible."' }}</h2>

        <div class="bt-wrapper">
            <div class="bt-left">
                <div class="bt-icon">
                    <i class="bi bi-calendar2-event-fill"></i>
                </div>
                <div class="bt-text">
                    <p>
                        {!! $page->content['bt_description'] ?? '<strong>Better Together:</strong> <em>Tu ecosistema único</em> y accesible <em>en Colombia.</em> Con nuestra <strong>plataforma y eventos exclusivos</strong>, te damos las herramientas para que solo te enfoques en tu negocio y vender.<br>¡Únete y transforma tu estrategia!' !!}
                    </p>
                </div>
            </div>
            <div class="bt-right">
                <img src="{{ asset($page->content['imagen_better_together'] ?? 'images/imagen2.png') }}" alt="Personas emprendiendo">
            </div>
        </div>

        <p class="bt-footer-text">{{ $page->content['bt_footer'] ?? 'Somos una inversión para tu marca' }}</p>
    </section>

    <section class="btg-benefits-section">
        <div class="btg-benefits-content">
            <h4 class="btg-benefits-subtitle">{{ $page->content['access_subtitle'] ?? 'Acceso exclusivo por inscripción anticipada' }}</h4>
            <h2 class="btg-benefits-title">{{ $page->content['access_title'] ?? 'Todo lo que necesitas para crecer' }}</h2>
            <p class="btg-benefits-description">
                {!! $page->content['access_description'] ?? 'El acceso a Better Together es limitado en esta fase inicial.<br><strong>¡Solo quienes se registren en la lista de espera</strong> podrán ser parte de este grupo!<br><strong>No te quedes por fuera y asegura tu lugar ahora.</strong>' !!}
            </p>
        </div>
    </section>
    <div class="rectangulo-amarillo">
        <section class="benefits-container">
            <div class="benefit-box left-box hidden" data-animate="slideInLeft">
                <div class="horizontal-line"></div>
                <div class="benefit-item">
                    <i class="bi bi-shop"></i>
                    <p><strong>1. {{ $page->content['benefit1_title'] ?? 'Lanza tu e-commerce en minutos' }}:</strong> {{ $page->content['benefit1_description'] ?? 'gestiona tus pedidos, inventario y potencia tus ingresos' }}</p>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-cash"></i>
                    <p><strong>2. {{ $page->content['benefit2_title'] ?? 'Pagos rápidos y directos' }}:</strong> {{ $page->content['benefit2_description'] ?? 'Pagos ágiles y seguros usando nuestra plataforma en lo digital y en festivales.' }}</p>
                </div>
            </div>

            <div class="benefit-center">
                <img src="{{ asset($page->content['imagen_beneficios'] ?? 'images/imagen3.png') }}" alt="persona con bolsa y tablet">
                <button class="cta-button hidden" data-animate="slideInBottom">{{ $page->content['benefits_cta_text'] ?? '¡EMPIEZA AHORA!' }}</button>
            </div>

            <div class="benefit-box right-box animate-right hidden" data-animate="slideInRight">
                <div class="horizontal-line"></div>
                <div class="benefit-item">
                    <i class="bi bi-truck"></i>
                    <p><strong>3. {{ $page->content['benefit3_title'] ?? 'Logística Optimizada para ti' }}:</strong> {{ $page->content['benefit3_description'] ?? 'Cotiza y gestiona tus despachos con las transportadoras, ¡todo desde nuestra plataforma!' }}</p>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-card-text"></i>
                    <p><strong>4. {{ $page->content['benefit4_title'] ?? 'Presencia en eventos físicos' }}:</strong> {{ $page->content['benefit4_description'] ?? 'Participa en festivales de comercio con afluencia de público. Conecta con nuevos clientes e impulsa tus ventas.' }}</p>
                </div>
            </div>
            <script>
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const el = entry.target;
                            const anim = el.dataset.animate;
                            el.classList.remove('hidden');
                            el.classList.add(anim);
                            observer.unobserve(el); // para que solo se anime una vez
                        }
                    });
                }, {
                    threshold: 0.5
                });

                document.querySelectorAll('.hidden[data-animate]').forEach(el => {
                    observer.observe(el);
                });
            </script>
        </section>
    </div>

<!-- Add this HTML section after your benefits section -->
<section class="pricing-section">
  <div class="pricing-container">
    <div class="pricing-header">
      <p class="pricing-subtitle">ELIGE TU PLAN PERFECTO</p>
      <h2 class="pricing-title">Planes diseñados para cada etapa de tu crecimiento</h2>
      <p class="pricing-description">
        Desde emprendedores que inician hasta marcas consolidadas. 
        <strong>Todos incluyen tienda online y pasarela de pagos.</strong>
      </p>
    </div>

    <div class="plans-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
      @foreach($planes as $index => $plan)
      <div class="plan-card @if($index == 1) featured @endif" style="background: white; border-radius: 16px; padding: 25px 20px; border: 2px solid #eee; transition: all 0.3s ease; position: relative; overflow: hidden; min-height: 480px; display: flex; flex-direction: column;">
        @if($index == 1)
        <div style="content: 'MÁS POPULAR'; position: absolute; top: 15px; right: -30px; background: #ff00c8; color: white; padding: 4px 35px; font-size: 0.75rem; font-weight: 600; transform: rotate(45deg);">
          <span>Más Popular</span>
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
        
        @auth
          <a href="{{ route('membresias.index') }}" class="plan-cta" style="display: block; width: 100%; padding: 12px; background: #ff00c8; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; margin-top: 15px; text-decoration: none; text-align: center;">
            Ver Planes
          </a>
        @else
          <a href="{{ route('register') }}" class="plan-cta" style="display: block; width: 100%; padding: 12px; background: #ff00c8; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; margin-top: 15px; text-decoration: none; text-align: center;">
            {{ $plan->precio == 0 ? 'Empezar Gratis' : 'Elegir Plan' }}
          </a>
        @endauth
      </div>
      @endforeach
    </div>
  </div>
</section>
    <section class="hero3">
        <svg class="wave-top2" viewBox="0 0 1440 320">
            <path fill="#fff" fill-opacity="0.3"
                d="M0,128L60,154.7C120,181,240,235,360,240C480,245,600,203,720,176C840,149,960,139,1080,149.3C1200,160,1320,192,1380,208L1440,224L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="0.3"
                d="M0,256L24,234.7C48,213,96,171,144,154.7C192,139,240,149,288,154.7C336,160,384,160,432,170.7C480,181,528,203,576,192C624,181,672,139,720,122.7C768,107,816,117,864,133.3C912,149,960,171,1008,197.3C1056,224,1104,256,1152,240C1200,224,1248,160,1296,154.7C1344,149,1392,203,1416,229.3L1440,256L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="0.5"
                d="M0,224L26.7,186.7C53.3,149,107,75,160,69.3C213.3,64,267,128,320,128C373.3,128,427,64,480,64C533.3,64,587,128,640,133.3C693.3,139,747,85,800,80C853.3,75,907,117,960,117.3C1013.3,117,1067,75,1120,90.7C1173.3,107,1227,181,1280,208C1333.3,235,1387,213,1413,202.7L1440,192L1440,0L1413.3,0C1386.7,0,1333,0,1280,0C1226.7,0,1173,0,1120,0C1066.7,0,1013,0,960,0C906.7,0,853,0,800,0C746.7,0,693,0,640,0C586.7,0,533,0,480,0C426.7,0,373,0,320,0C266.7,0,213,0,160,0C106.7,0,53,0,27,0L0,0Z">
            </path>
            <path fill="#fff" fill-opacity="1"
                d="M0,96L48,90.7C96,85,192,75,288,85.3C384,96,480,128,576,128C672,128,768,96,864,96C960,96,1056,128,1152,128C1248,128,1344,96,1392,80L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
            </path>
        </svg>
        <div class="hero-content3">
            <h1>{{ $page->content['cta_title'] ?? '¿Listo para llevar tu negocio al siguiente Nivel?' }}</h1>
            <p>
                {!! $page->content['cta_description'] ?? 'Forma parte de nuestra comunidad. <span class="highlight3">Regístrate en la lista de espera hoy y prepárate para crecer</span> con nosotros.' !!}
            </p>
        </div>
        <div class="form-container">
            @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('formulario.contacto') }}" method="POST">
                @csrf
                <label>Tu nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>

                <label>¿Eres un ...? *</label>
                <div class="radio-group">
                    <label><input type="radio" name="tipo" value="emprendimiento" {{ old('tipo') == 'emprendimiento' ? 'checked' : '' }} required> Emprendimiento</label>
                    <label><input type="radio" name="tipo" value="fundacion" {{ old('tipo') == 'fundacion' ? 'checked' : '' }} required> Fundación</label>
                </div>

                <label>¿Ya vendes en línea? *</label>
                <div class="radio-group">
                    <label><input type="radio" name="online" value="si" {{ old('online') == 'si' ? 'checked' : '' }} required> Sí</label>
                    <label><input type="radio" name="online" value="no" {{ old('online') == 'no' ? 'checked' : '' }} required> No</label>
                </div>

                <label>¿Has invertido en festivales anteriormente? *</label>
                <div class="radio-group">
                    <label><input type="radio" name="festival" value="si" {{ old('festival') == 'si' ? 'checked' : '' }} required> Sí</label>
                    <label><input type="radio" name="festival" value="no" {{ old('festival') == 'no' ? 'checked' : '' }} required> No</label>
                </div>

                <label>¿Cómo podemos encontrar tu negocio en redes sociales?</label>
                <input type="text" name="redes_sociales" value="{{ old('redes_sociales') }}">

                <label>Elige la red social</label>
                <select name="red_social">
                    <option value="">Selecciona una opción</option>
                    <option value="facebook" {{ old('red_social') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="instagram" {{ old('red_social') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="tiktok" {{ old('red_social') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                </select>

                <label>¿Te gustaría participar en eventos? *</label>
                <select name="participar_eventos" required>
                    <option value="">Selecciona una opción</option>
                    <option value="no_interesado" {{ old('participar_eventos') == 'no_interesado' ? 'selected' : '' }}>No estoy interesado</option>
                    <option value="si_claro" {{ old('participar_eventos') == 'si_claro' ? 'selected' : '' }}>Sí, claro</option>
                    <option value="depende_evento" {{ old('participar_eventos') == 'depende_evento' ? 'selected' : '' }}>Depende del evento</option>
                </select>

                <label>Un email para ponernos en contacto *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>

                <label>Tu número WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}">

                <label>Algo que quieras decirnos</label>
                <textarea name="mensaje_adicional" rows="4">{{ old('mensaje_adicional') }}</textarea>

                <button class="button2" type="submit">{{ $page->content['cta_button_text'] ?? 'Enviar' }}</button>
            </form>
        </div>
        <section class="socials">
            <p>{{ $page->content['social_text'] ?? 'Síguenos en nuestras redes sociales' }}</p>
            <div class="icon-container">
                <a href="{{ $page->content['facebook_url'] ?? '#' }}" class="social-icon"><i class="bi bi-facebook"></i></a>
                <a href="{{ $page->content['tiktok_url'] ?? '#' }}" class="social-icon"><i class="bi bi-tiktok"></i></a>
                <a href="{{ $page->content['instagram_url'] ?? '#' }}" class="social-icon"><i class="bi bi-instagram"></i></a>
                <a href="{{ $page->content['linkedin_url'] ?? '#' }}" class="social-icon"><i class="bi bi-linkedin"></i></a>
            </div>
        </section>
    </section>


    <footer class="footer-personalizado">
        <hr class="linea-gris">
        <p class="texto-gris">{{ $page->content['footer_rights'] ?? '© Esnova.COM.CO - TODOS LOS DERECHOS RESERVADOS' }}</p>
        <p class="texto-negro">{{ $page->content['footer_slogan'] ?? 'TECNOLOGÍA ÚTIL, CERCANA Y SIN COMPLICACIONES.' }}</p>
    </footer>

    <a href="{{ $page->content['whatsapp_url'] ?? 'https://wa.me/#' }}" class="whatsapp-button" target="_blank">
        <i class="bi bi-whatsapp"></i> {{ $page->content['whatsapp_text'] ?? 'Contáctanos vía Whatsapp' }}
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('modernNavbar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const closeMobileMenu = document.getElementById('closeMobileMenu');

            // Scroll effect
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        navbar.classList.toggle('scrolled', window.scrollY > 20);
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            // Mobile menu
            function toggleMobileMenu(show) {
                mobileMenuOverlay.classList.toggle('active', show);
                mobileMenuBtn.classList.toggle('active', show);
                document.body.style.overflow = show ? 'hidden' : '';
            }

            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMobileMenu(true);
            });

            closeMobileMenu.addEventListener('click', function() {
                toggleMobileMenu(false);
            });

            mobileMenuOverlay.addEventListener('click', function(e) {
                if (e.target === mobileMenuOverlay) toggleMobileMenu(false);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') toggleMobileMenu(false);
            });

            document.querySelectorAll('.mobile-nav-link:not(.logout-mobile)').forEach(function(link) {
                link.addEventListener('click', function() { toggleMobileMenu(false); });
            });

            // Close on resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991) toggleMobileMenu(false);
            });
        });
    </script>
</body>

</html>
