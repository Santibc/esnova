<!-- Footer -->
<footer class="footer-brasilia py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12 mb-4">
                <!-- Logo -->
                <div class="footer-logo mb-4">
                    <img src="{{ $empresa->logo_url ?? asset('images/default-logo.png') }}"
                         alt="{{ $empresa->nombre }}"
                         class="footer-logo-img"
                         style="max-width: 200px !important; max-height: 100px !important; width: auto !important; height: auto !important; object-fit: contain !important; filter: none !important;">
                </div>
                @if($empresa->descripcion)
                <p class="footer-description">{{ $empresa->descripcion }}</p>
                @endif
                <!-- Social Links -->
                <div class="footer-social mt-3">
                    @if($empresa->instagram_url)
                    <a href="{{ $empresa->instagram_url }}" target="_blank" class="footer-social-link"><svg class="icon-inline"><use xlink:href="#instagram"></use></svg></a>
                    @endif
                    @if($empresa->facebook_url)
                    <a href="{{ $empresa->facebook_url }}" target="_blank" class="footer-social-link"><svg class="icon-inline"><use xlink:href="#facebook-f"></use></svg></a>
                    @endif
                    @if($empresa->twitter_url)
                    <a href="{{ $empresa->twitter_url }}" target="_blank" class="footer-social-link"><svg class="icon-inline"><use xlink:href="#twitter"></use></svg></a>
                    @endif
                    @if($empresa->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->whatsapp) }}" target="_blank" class="footer-social-link"><svg class="icon-inline"><use xlink:href="#whatsapp"></use></svg></a>
                    @endif
                </div>
            </div>
            @if(isset($categorias) && $categorias && $categorias->count() > 0)
            <div class="col-md-4 col-6 mb-4">
                <h5 class="footer-title">Categorías</h5>
                <ul class="footer-links">
                    @foreach($categorias->take(5) as $categoria)
                    <li><a href="{{ route('tienda.categorias', ['categoria' => $categoria->id]) }}">{{ $categoria->nombre }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="col-md-4 col-6 mb-4">
                <h5 class="footer-title">Contacto</h5>
                <ul class="footer-links">
                    @if($empresa->telefono)
                    <li>
                        <svg class="icon-inline me-1"><use xlink:href="#phone"></use></svg>
                        <a href="tel:{{ $empresa->telefono }}">{{ $empresa->telefono }}</a>
                    </li>
                    @endif
                    @if($empresa->email)
                    <li>
                        <svg class="icon-inline me-1"><use xlink:href="#email"></use></svg>
                        <a href="mailto:{{ $empresa->email }}">{{ $empresa->email }}</a>
                    </li>
                    @endif
                    @if($empresa->direccion)
                    <li>
                        <svg class="icon-inline me-1"><use xlink:href="#store"></use></svg>
                        {{ $empresa->direccion }}
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <hr class="footer-divider my-4">
        <div class="row">
            <div class="col-md-12 text-md-center">
                <div class="footer-payments mb-3">
                    <img src="https://d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/visa@2x.png" alt="Visa" class="payment-logo">
                    <img src="https://d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mastercard@2x.png" alt="Mastercard" class="payment-logo">
                    <img src="https://d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/amex@2x.png" alt="Amex" class="payment-logo">
                    <img src="https://d26lpennugtm8s.cloudfront.net/assets/common/img/logos/payment/new_logos_payment/mercadopago@2x.png" alt="MercadoPago" class="payment-logo">
                </div>
            </div>
        </div>
        <hr class="footer-divider my-3">
        <div class="row">
            <div class="col-12 text-center">
                <p class="footer-copyright mb-0">
                    @if($empresa->planMembresia && $empresa->planMembresia->marca_de_agua)
                    <a href="https://Esnova.com.co" target="_blank" style="text-decoration: none; color: inherit;">
                        Página creada en Esnova
                    </a><br>
                    @endif
                    Copyright © {{ $empresa->nombre }} - {{ date('Y') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</footer>
