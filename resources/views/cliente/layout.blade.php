<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi Cuenta') - Esnova</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #FF00C1;
            --secondary-color: #0B00F9;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
        }

        .navbar-brand img {
            max-height: 40px;
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: white;
        }

        .page-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }

        .content-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #e600ae;
            border-color: #e600ae;
        }

        .badge-estado {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .badge-pendiente { background-color: #fef3c7; color: #92400e; }
        .badge-pagada { background-color: #d1fae5; color: #065f46; }
        .badge-enviada { background-color: #dbeafe; color: #1e40af; }
        .badge-entregada { background-color: #d1fae5; color: #065f46; }
        .badge-cancelada { background-color: #fee2e2; color: #991b1b; }

        .stars {
            color: #ffc107;
        }

        .stars .bi-star { color: #e5e7eb; }

        footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 0;
            margin-top: 3rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cliente.compras*') ? 'active' : '' }}" href="{{ route('cliente.compras') }}">
                            <i class="bi bi-bag"></i> Mis Compras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="bi bi-shop"></i> Volver a la Tienda
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @hasSection('header')
    <div class="page-header">
        <div class="container">
            @yield('header')
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Esnova. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
