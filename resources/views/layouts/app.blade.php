<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/ico.png') }}"/>
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- CSS personalizado y Bootstrap --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            background-color: #f7f8fc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23e9eaf2' fill-opacity='0.4'%3E%3Cpath fill-rule='evenodd' d='M11 0l5 20H6l5-20zm42 31a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM0 72h40v4H0v-4zm0-8h31v4H0v-4zm20-16h20v4H20v-4zM0 56h40v4H0v-4zm0-8h20v4H0v-4zm0-8h11v4H0v-4zm0-8h40v4H0v-4zm0-8h20v4H0v-4zm0-8h31v4H0v-4zm0-8h40v4H0v-4zM40 0h40v4H40v-4zm0 8h31v4H40v-4zm0 8h20v4H40v-4zm0 8h11v4H40v-4zm0 8h40v4H40v-4zm0 8h20v4H40v-4zm0 8h31v4H40v-4zm0 8h40v4H40v-4zm0 8h11v4H40v-4zm0 8h20v4H40v-4zm0 8h31v4H40v-4zm0 8h40v4H40v-4z'/%3E%3C/g%3E%3C/svg%3E");
            font-family: 'Poppins', sans-serif;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        .sidebar {
            width: 250px;
            transition: all 0.3s ease;
            background: #ffffff !important;
            border-right: 1px solid #e9ecef;
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }

        .sidebar.collapsed {
            width: 70px;
            overflow-x: hidden !important;
        }

        .sidebar .nav-link span {
            transition: opacity 0.2s, width 0.2s;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Evitar que botones se desborden horizontalmente */
        .sidebar.collapsed .nav-link {
            justify-content: center !important;
            padding: 0.1rem !important;
            width: 100% !important;
            max-width: 45px !important;
            margin: 0.02rem auto !important;
            min-height: 25px !important;
            border-radius: 0.2rem !important;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0 !important;
            font-size: 0.9rem !important;
        }

        .sidebar.collapsed .nav-item {
            width: 100% !important;
            text-align: center !important;
        }

        /* Ocultar texto de "Salir" cuando está colapsado */
        .sidebar.collapsed .logout-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: opacity 0.2s, width 0.2s;
        }

        .sidebar.collapsed .btn-outline-danger {
            justify-content: center !important;
            padding: 0.1rem !important;
            max-width: 45px !important;
            min-height: 25px !important;
            margin: 0.02rem auto !important;
        }

        header {
            height: 64px;
            background: #ffffff;
            color: #212529;
            border-bottom: 1px solid #e9ecef;
            position: fixed;
            top: 0;
            right: 0;
            transition: left 0.3s ease;
            padding-right: 1rem;
        }

        header .text-muted {
            color: #6c757d !important;
        }

        header .fw-semibold {
            color: #212529 !important;
        }

        #toggleSidebar {
            color: #212529 !important;
        }

        #toggleSidebar:hover {
            background-color: rgba(0, 0, 0, 0.08) !important;
        }

        main {
            padding-top: 80px;
            transition: margin-left 0.3s ease;
        }

        #toggleSidebar {
            border: none !important;
            background-color: transparent;
        }

        #toggleSidebar:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .nav-link {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }

        .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .nav-link.active {
            background-color: rgba(0, 0, 0, 0.08) !important;
            color: #000 !important;
            font-weight: 600;
        }

        /* Header: info de usuario alineada a la derecha y con elipsis */
        .header-user-info {
            flex-grow: 1;
            justify-content: flex-end;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-user-info .text-end {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: calc(100% - 50px);
        }

        .header-user-info .text-end .fw-semibold,
        .header-user-info .text-end .text-muted {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .header-user-info .text-end {
                max-width: calc(100% - 50px);
            }
        }
    </style>
</head>
<body>

    {{-- Sidebar fijo --}}
    <div class="sidebar position-fixed top-0 start-0 bg-white border-end vh-100 d-flex flex-column" style="z-index: 1030;">
        @include('layouts.navigation-vertical')
    </div>

    {{-- Contenedor principal --}}
    <div>
        {{-- Header fijo --}}
        <header id="appHeader" class="position-fixed top-0 border-bottom d-flex justify-content-between align-items-center px-3" style="z-index: 1020;">
            <button id="toggleSidebar" class="btn btn-sm" title="Menú">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center gap-2 header-user-info">
                <div class="text-end">
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-muted small">{{ Auth::user()->email }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                     class="rounded-circle flex-shrink-0" width="40" height="40" alt="Avatar">
            </div>
        </header>

        {{-- Contenido --}}
        <main id="appMainContent">
            {{ $slot }}
        </main>
    </div>

    {{-- JS --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Lógica unificada del sidebar (sin variables duplicadas) --}}
    <script>
    (() => {
      const sidebar       = document.querySelector('.sidebar');
      const appHeader     = document.getElementById('appHeader');
      const appMainContent= document.getElementById('appMainContent');
      const toggleBtn     = document.getElementById('toggleSidebar');

      // Si por alguna razón no existen (otra plantilla), salimos sin romper nada
      if (!sidebar || !appHeader || !appMainContent || !toggleBtn) return;

      let isManuallyToggled = false;
      const TRANSITION_MS = 300;

      const updateLayout = () => {
        const sidebarWidth = sidebar.offsetWidth; // 70px o 250px
        appHeader.style.left = `${sidebarWidth}px`;
        appHeader.style.right = '0';
        appMainContent.style.marginLeft = `${sidebarWidth}px`;
      };

      const saveSidebarState = () => {
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ? 'true' : 'false');
      };

      const handleResponsive = () => {
        if (isManuallyToggled) return;
        if (window.innerWidth <= 768) {
          sidebar.classList.add('collapsed');
        } else {
          sidebar.classList.remove('collapsed');
        }
        setTimeout(updateLayout, TRANSITION_MS);
      };

      const restoreSidebarState = () => {
        const saved = localStorage.getItem('sidebarCollapsed');
        if (saved !== null) {
          isManuallyToggled = true;
          if (saved === 'true') sidebar.classList.add('collapsed');
          else sidebar.classList.remove('collapsed');
        } else {
          handleResponsive();
        }
        setTimeout(updateLayout, TRANSITION_MS);
      };

      document.addEventListener('DOMContentLoaded', () => {
        restoreSidebarState();

        // Inicializar tooltips del sidebar (si los hay)
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
      });

      // Toggle manual
      toggleBtn.addEventListener('click', () => {
        isManuallyToggled = true;
        sidebar.classList.toggle('collapsed');
        saveSidebarState();
        setTimeout(updateLayout, TRANSITION_MS);
      });

      // Redimensionamiento (debounce)
      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          handleResponsive();
          updateLayout();
        }, 250);
      });

      // Manejar submenús cuando el sidebar esté colapsado - mantener distribución vertical
      document.addEventListener('click', (e) => {
        if (!sidebar.classList.contains('collapsed')) return;
        const trigger = e.target.closest('[data-bs-toggle="collapse"]');
        if (!trigger) return;

        e.preventDefault();
        e.stopPropagation();

        // No expandir el sidebar, solo abrir el submenú en su lugar
        const targetSel = trigger.getAttribute('href') || trigger.dataset.bsTarget;
        if (targetSel) {
          const targetEl = document.querySelector(targetSel);
          if (targetEl) {
            new bootstrap.Collapse(targetEl, { toggle: true });
          }
        }
      });
    })();
    </script>

    {{-- Evitar scroll horizontal indeseado --}}
    <script>
      document.documentElement.style.overflowX = 'hidden';
      document.body.style.overflowX = 'hidden';
    </script>

    @stack('scripts')
</body>
</html>
