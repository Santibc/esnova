<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo a Wompi...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        .loading-container {
            text-align: center;
        }
        .spinner {
            width: 50px;
            height: 50px;
            margin: 0 auto 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3730a3;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 {
            color: #333;
            font-weight: normal;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="loading-container">
        <div class="spinner"></div>
        <h2>Redirigiendo a la pasarela de pago...</h2>
        <p>Por favor espere un momento</p>
    </div>

    <form id="wompi-form" action="{{ $datosCheckout['action_url'] }}" method="GET" style="display: none;">
        <!-- Campos obligatorios -->
        <input type="hidden" name="public-key" value="{{ $datosCheckout['public_key'] }}">
        <input type="hidden" name="currency" value="{{ $datosCheckout['currency'] }}">
        <input type="hidden" name="amount-in-cents" value="{{ $datosCheckout['amount_in_cents'] }}">
        <input type="hidden" name="reference" value="{{ $datosCheckout['reference'] }}">
        <input type="hidden" name="signature:integrity" value="{{ $datosCheckout['signature_integrity'] }}">
        
        <!-- Campos opcionales -->
        <input type="hidden" name="redirect-url" value="{{ $datosCheckout['redirect_url'] }}">
        <input type="hidden" name="customer-data:email" value="{{ $datosCheckout['customer_email'] }}">
        <input type="hidden" name="customer-data:full-name" value="{{ $datosCheckout['customer_full_name'] }}">
        <input type="hidden" name="customer-data:phone-number" value="{{ $datosCheckout['customer_phone_number'] }}">
        <input type="hidden" name="shipping-address:address-line-1" value="{{ $datosCheckout['shipping_address'] }}">
        <input type="hidden" name="shipping-address:city" value="{{ $datosCheckout['shipping_city'] }}">
        <input type="hidden" name="shipping-address:phone-number" value="{{ $datosCheckout['shipping_phone_number'] }}">
        <input type="hidden" name="shipping-address:region" value="{{ $datosCheckout['shipping_region'] }}">
        <input type="hidden" name="shipping-address:country" value="CO">
    </form>

    <script>
        // Enviar formulario automáticamente después de cargar la página
        window.onload = function() {
            document.getElementById('wompi-form').submit();
        };
    </script>
</body>
</html>