<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada - {{ $empresa->nombre }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #3730a3;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .store-header {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .logo-container {
            max-width: 150px;
        }

        .confirmation-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            padding: 3rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success-color), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .order-number {
            background: var(--bg-light);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            margin: 1.5rem 0;
        }

        .order-number-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            font-family: monospace;
        }

        .info-box {
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .info-box.warning {
            background: #fef3c7;
            border: 1px solid #fcd34d;
        }

        .info-box.info {
            background: #dbeafe;
            border: 1px solid #93c5fd;
        }

        .info-box h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box ol {
            margin: 0;
            padding-left: 1.25rem;
        }

        .info-box ol li {
            margin-bottom: 0.5rem;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-1px);
        }

        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .steps-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-left: 0;
        }

        .step-number {
            width: 28px;
            height: 28px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
            margin-right: 0.75rem;
        }

        .email-highlight {
            background: #fef3c7;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="store-header">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <a href="{{ route('tienda.empresa') }}" class="d-inline-block">
                        <div class="logo-container mx-auto">
                            <img src="{{ $empresa->logo_url }}" alt="{{ $empresa->nombre }}" class="img-fluid">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex align-items-center py-5">
        <div class="container">
            <div class="confirmation-card">
                <!-- Success Icon -->
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <!-- Title -->
                <h1 class="text-center mb-3" style="color: var(--text-primary);">
                    Solicitud Enviada
                </h1>
                <p class="text-center text-secondary mb-4">
                    Tu solicitud de pago ha sido recibida y está pendiente de revisión.
                </p>

                <!-- Order Number -->
                <div class="order-number">
                    <small class="text-secondary d-block mb-1">Número de Orden</small>
                    <div class="order-number-value">{{ $compra->numero_compra }}</div>
                </div>

                <!-- Status Info -->
                <div class="info-box warning">
                    <h5>
                        <i class="bi bi-hourglass-split" style="color: #d97706;"></i>
                        Próximos Pasos
                    </h5>
                    <ol>
                        <li>Nuestro equipo revisará la información que enviaste</li>
                        <li>Recibirás un correo electrónico cuando se apruebe tu pago</li>
                        <li>Una vez aprobado, procesaremos tu pedido</li>
                    </ol>
                </div>

                <!-- How to track -->
                <div class="info-box info">
                    <h5>
                        <i class="bi bi-eye" style="color: #2563eb;"></i>
                        Seguimiento de tu Compra
                    </h5>
                    <p class="mb-3">Para ver el estado de tu compra en cualquier momento:</p>
                    <ul class="steps-list">
                        <li>
                            <span class="step-number">1</span>
                            <span>Regístrate en nuestra tienda con el correo: <span class="email-highlight">{{ $email }}</span></span>
                        </li>
                        <li>
                            <span class="step-number">2</span>
                            <span>Inicia sesión y ve a <strong>"Mis Compras"</strong></span>
                        </li>
                        <li>
                            <span class="step-number">3</span>
                            <span>Podrás ver el estado actualizado de tu pedido</span>
                        </li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('tienda.empresa') }}" class="btn btn-primary-custom">
                        <i class="bi bi-shop me-2"></i>
                        Volver a la Tienda
                    </a>
                </div>

                @guest
                <div class="text-center mt-3">
                    <a href="{{ route('register.cliente') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        <i class="bi bi-person-plus me-1"></i>
                        Crear cuenta ahora
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-secondary" style="background: white; border-top: 1px solid #e5e7eb;">
        <div class="container">
            <small>&copy; {{ date('Y') }} {{ $empresa->nombre }}. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
