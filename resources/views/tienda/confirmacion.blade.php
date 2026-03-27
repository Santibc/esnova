<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Compra Exitosa! - {{ $compra->empresa->nombre }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3730a3;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-primary);
            background: var(--bg-light);
        }

        .success-container {
            max-width: 800px;
            margin: 3rem auto;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
        }

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .success-message {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 3rem;
        }

        .order-details {
            background: var(--bg-light);
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .detail-value {
            color: var(--text-secondary);
        }

        .order-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .next-steps {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .next-steps-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #92400e;
        }

        .next-steps ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #92400e;
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
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-secondary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .success-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <!-- Success Message -->
            <h1 class="success-title">¡Gracias por tu compra!</h1>
            <p class="success-message">
                Tu pedido ha sido confirmado y pronto recibirás un correo electrónico con los detalles.
            </p>

            <!-- Order Details -->
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Número de orden:</span>
                    <span class="order-number">{{ $compra->numero_compra }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha:</span>
                    <span class="detail-value">{{ $compra->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total pagado:</span>
                    <span class="detail-value">${{ number_format($compra->total, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Método de pago:</span>
                    <span class="detail-value">{{ ucfirst($transaccion->metodo_pago ?? 'Wompi') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Estado del pago:</span>
                    <span class="detail-value">
                        <span class="badge bg-success">Aprobado</span>
                    </span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="order-details">
                <h5 class="mb-3">Información de envío</h5>
                <div class="detail-row">
                    <span class="detail-label">Nombre:</span>
                    <span class="detail-value">{{ $compra->nombre_cliente }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $compra->email_cliente }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Teléfono:</span>
                    <span class="detail-value">{{ $compra->telefono_cliente }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Dirección:</span>
                    <span class="detail-value">
                        {{ $compra->direccion_envio }}<br>
                        {{ $compra->ciudad->nombre }}, {{ $compra->ciudad->departamento->nombre }}
                    </span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h5 class="next-steps-title">¿Qué sigue?</h5>
                <ul>
                    <li>Recibirás un correo de confirmación en {{ $compra->email_cliente }}</li>
                    <li>Prepararemos tu pedido para enviarlo lo antes posible</li>
                    <li>Te notificaremos cuando tu pedido esté en camino</li>
                    <li>Podrás rastrear tu envío con el número de guía que te proporcionaremos</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('tienda.empresa', $compra->empresa->slug) }}" class="btn btn-primary-custom">
                    <i class="bi bi-shop"></i> Seguir Comprando
                </a>
                <button class="btn btn-secondary-custom" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir Recibo
                </button>
            </div>

            <!-- Contact Info -->
            <div class="mt-4 text-muted">
                <p class="mb-2">¿Tienes alguna pregunta sobre tu pedido?</p>
                <p class="mb-0">
                    Contáctanos: 
                    @if($compra->empresa->email)
                        <a href="mailto:{{ $compra->empresa->email }}">{{ $compra->empresa->email }}</a>
                    @endif
                    @if($compra->empresa->telefono)
                        | {{ $compra->empresa->telefono }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Opcional: Enviar evento de conversión a Google Analytics, Facebook Pixel, etc.
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase', {
                'transaction_id': '{{ $compra->numero_compra }}',
                'value': {{ $compra->total }},
                'currency': 'COP',
                'items': [
                    @foreach($compra->items as $item)
                    {
                        'item_id': '{{ $item->producto_id }}',
                        'item_name': '{{ $item->nombre_producto }}',
                        'quantity': {{ $item->cantidad }},
                        'price': {{ $item->precio_unitario }}
                    },
                    @endforeach
                ]
            });
        }
    </script>
</body>
</html>