{{-- Cart Modal (Slide from Right) --}}
<div id="modal-cart" class="js-modal modal modal-cart modal-right transition-slide" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row w-100">
                    <div class="col">
                        <h5 class="modal-title">Tu Carrito</h5>
                    </div>
                    <div class="col-2 text-right">
                        <a href="#" class="js-modal-close modal-close" aria-label="Cerrar">
                            <svg class="icon-inline icon-lg" width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <div id="cart-items-container">
                    {{-- Cart items will be loaded here via JavaScript --}}
                    <div class="text-center py-5" id="empty-cart-message">
                        <svg class="icon-inline icon-3x mb-3 text-muted" width="48" height="48" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <p class="text-muted">Tu carrito está vacío</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="cart-summary-footer w-100">
                    <div class="row mb-3">
                        <div class="col">
                            <strong>Total:</strong>
                        </div>
                        <div class="col text-right">
                            <strong class="js-cart-total cart-total">$0.00</strong>
                        </div>
                    </div>
                    <a href="{{ route('tienda.carrito') }}" class="btn btn-primary btn-block">
                        Ver Carrito Completo
                    </a>
                    <a href="{{ route('tienda.checkout') }}" class="btn btn-secondary btn-block mt-2">
                        Finalizar Compra
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hamburger Menu (Slide from Left) --}}
<div id="nav-hamburger" class="js-modal modal modal-nav-hamburger modal-left transition-slide" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row w-100">
                    <div class="col">
                        <h5 class="modal-title">Menú</h5>
                    </div>
                    <div class="col-2 text-right">
                        <a href="#" class="js-modal-close modal-close" aria-label="Cerrar">
                            <svg class="icon-inline icon-lg" width="24" height="24" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <ul class="mobile-nav-list">
                    <li class="mobile-nav-item">
                        <a href="{{ route('tienda.empresa') }}" class="mobile-nav-link">
                            Inicio
                        </a>
                    </li>

                    @if(isset($categorias) && $categorias->count() > 0)
                        @foreach($categorias as $categoria)
                        <li class="mobile-nav-item {{ $categoria->subcategorias && $categoria->subcategorias->count() > 0 ? 'has-submenu' : '' }}">
                            <div class="mobile-nav-link-container">
                                <a href="{{ route('tienda.empresa', ['categoria' => $categoria->id]) }}" class="mobile-nav-link">
                                    {{ $categoria->nombre }}
                                </a>
                                @if($categoria->subcategorias && $categoria->subcategorias->count() > 0)
                                <button class="js-toggle-submenu mobile-nav-toggle" type="button">
                                    <svg class="icon-inline" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                                @endif
                            </div>

                            @if($categoria->subcategorias && $categoria->subcategorias->count() > 0)
                            <ul class="mobile-nav-submenu" style="display: none;">
                                @foreach($categoria->subcategorias as $subcategoria)
                                <li class="mobile-nav-item">
                                    <a href="{{ route('tienda.empresa', ['categoria' => $subcategoria->id]) }}" class="mobile-nav-link submenu-link">
                                        {{ $subcategoria->nombre }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    @endif

                    {{-- Account Links --}}
                    <li class="mobile-nav-item mobile-nav-divider mt-3 pt-3">
                        @auth
                        <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                            <svg class="icon-inline icon-md mr-2" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            Mi Cuenta
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="mobile-nav-link">
                            <svg class="icon-inline icon-md mr-2" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                            Iniciar Sesión
                        </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Modal Overlay --}}
<div class="js-modal-overlay modal-overlay" style="display: none;"></div>

{{-- WhatsApp Float Button --}}
@if(isset($empresa) && $empresa->whatsapp)
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->whatsapp) }}"
   class="btn-whatsapp"
   target="_blank"
   aria-label="WhatsApp">
    <svg width="45" height="45" viewBox="0 0 16 16" fill="white">
        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
    </svg>
</a>
@endif
