<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Pago - {{ $empresa->nombre }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3730a3;
            --secondary-color: #6366f1;
            --warning-color: #f59e0b;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pending-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
        }

        .pending-icon {
            width: 80px;
            height: 80px;
            background: var(--warning-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .pending-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .pending-message {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .reference-box {
            background: var(--bg-light);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .reference-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .reference-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            font-family: monospace;
        }

        .info-alert {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .info-alert i {
            color: var(--warning-color);
        }

        .loading-dots {
            display: inline-flex;
            gap: 0.25rem;
        }

        .loading-dot {
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .loading-dot:nth-child(1) { animation-delay: -0.32s; }
        .loading-dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }

        .action-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .pending-container {
                margin: 1rem;
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <!-- Pending Icon -->
        <div class="pending-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>

        <!-- Pending Message -->
        <h1 class="pending-title">Procesando tu pago</h1>
        <p class="pending-message">
            Tu transacción está siendo procesada. Esto puede tomar unos momentos.
            <span class="loading-dots">
                <span class="loading-dot"></span>
                <span class="loading-dot"></span>
                <span class="loading-dot"></span>
            </span>
        </p>

        <!-- Reference Number -->
        <div class="reference-box">
            <p class="reference-label">Referencia de transacción:</p>
            <p class="reference-number">{{ $transaccion->referencia_transaccion }}</p>
        </div>

        <!-- Info Alert -->
        <div class="info-alert">
            <p class="mb-2">
                <i class="bi bi-info-circle"></i>
                <strong>Importante:</strong>
            </p>
            <ul class="mb-0 ps-4">
                <li>No cierres esta ventana mientras se procesa el pago</li>
                <li>Recibirás un correo de confirmación cuando se complete</li>
                <li>Si el proceso toma más de 5 minutos, contacta a soporte</li>
            </ul>
        </div>

        <!-- Actions -->
        <p class="text-muted mb-2">
            ¿Necesitas ayuda? Contáctanos:
        </p>
        <p>
            @if($empresa->email)
                <a href="mailto:{{ $empresa->email }}" class="action-link">
                    {{ $empresa->email }}
                </a>
            @endif
            @if($empresa->telefono)
                | {{ $empresa->telefono }}
            @endif
        </p>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Verificar estado del pago cada 5 segundos
        let checkCount = 0;
        const maxChecks = 60; // 5 minutos máximo
        
        const checkPaymentStatus = setInterval(function() {
            checkCount++;
            
            // Aquí harías una llamada AJAX para verificar el estado
            // Por ahora solo simulamos
            
            if (checkCount >= maxChecks) {
                clearInterval(checkPaymentStatus);
                // Mostrar mensaje de timeout
                document.querySelector('.pending-title').textContent = 'El proceso está tomando más tiempo del esperado';
                document.querySelector('.pending-message').innerHTML = 'Por favor, verifica tu correo electrónico o contacta a soporte.';
                document.querySelector('.pending-icon').innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
                document.querySelector('.pending-icon').style.background = '#ef4444';
            }
        }, 5000);

        // Redirigir automáticamente cuando Wompi confirme el pago
        // Esto se haría mediante webhook o verificación periódica del estado
    </script>
</body>
</html>