<x-app-layout>
  <x-slot name="header">Redirigiendo a Wompi</x-slot>

@push('styles')
<style>
body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .loading-container {
            text-align: center;
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3730a3;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
</style>
@endpush

<div class="loading-container">
        <div class="spinner"></div>
        <h2>Procesando tu pago</h2>
        <p>Serás redirigido a Wompi en unos segundos...</p>
    </div>

    <form id="wompi-form" action="{{ $datosCheckout['action_url'] }}" method="GET" style="display: none;">
        <input type="hidden" name="public-key" value="{{ $datosCheckout['public_key'] }}">
        <input type="hidden" name="currency" value="{{ $datosCheckout['currency'] }}">
        <input type="hidden" name="amount-in-cents" value="{{ $datosCheckout['amount_in_cents'] }}">
        <input type="hidden" name="reference" value="{{ $datosCheckout['reference'] }}">
        <input type="hidden" name="signature:integrity" value="{{ $datosCheckout['signature_integrity'] }}">
        <input type="hidden" name="redirect-url" value="{{ $datosCheckout['redirect_url'] }}">
        <input type="hidden" name="customer-email" value="{{ $datosCheckout['customer_email'] }}">
        <input type="hidden" name="customer-full-name" value="{{ $datosCheckout['customer_full_name'] }}">
        <input type="hidden" name="customer-data:description" value="{{ $datosCheckout['description'] }}">
    </form>

    

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('wompi-form').submit();
        });
</script>
@endpush

</x-app-layout>