<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Rechazado - {{ $compra->empresa->nombre }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3730a3;
            --secondary-color: #6366f1;
            --error-color: #ef4444;
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

        .error-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: var(--error-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--error-color);
        }

        .error-message {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .error-details {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .error-details p {
            margin: 0.5rem 0;
            font-size: 0.875rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .error-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Error Icon -->
        <div class="error-icon">
            <i class="bi bi-x-lg"></i>
        </div>

        <!-- Error Message -->
        <h1 class="error-title">Pago Rechazado</h1>
        <p class="error-message">
            Lo sentimos, tu pago no pudo ser procesado exitosamente.
        </p>

        <!-- Error Details -->
        <div class="error-details">
            <p><strong>Orden:</strong> {{ $compra->numero_compra }}</p>
            <p><strong>Monto:</strong> ${{ number_format($compra->total, 0, ',', '.') }}</p>
            @if($transaccion->mensaje_error)
                <p><strong>Motivo:</strong> {{ $transaccion->mensaje_error }}</p>
            @endif
        </div>

        <!-- Suggestions -->
        <div class="alert alert-info text-start mb-4">
            <h6 class="alert-heading">Sugerencias:</h6>
            <ul class="mb-0">
                <li>Verifica que tu tarjeta tenga fondos suficientes</li>
                <li>Asegúrate de ingresar correctamente los datos de pago</li>
                <li>Contacta a tu banco si el problema persiste</li>
                <li>Intenta con otro medio de pago</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('tienda.checkout', $compra->empresa->slug) }}" class="btn-primary-custom">
                <i class="bi bi-arrow-clockwise"></i> Reintentar Pago
            </a>
            <a href="{{ route('tienda.empresa', $compra->empresa->slug) }}" class="btn-secondary-custom">
                <i class="bi bi-shop"></i> Volver a la Tienda
            </a>
        </div>

        <!-- Support Info -->
        <div class="mt-4 text-muted">
            <p class="mb-1">¿Necesitas ayuda?</p>
            <p class="mb-0">
                @if($compra->empresa->email)
                    <a href="mailto:{{ $compra->empresa->email }}">{{ $compra->empresa->email }}</a>
                @endif
                @if($compra->empresa->telefono)
                    | {{ $compra->empresa->telefono }}
                @endif
            </p>
        </div>
    </div>
</body>
</html>