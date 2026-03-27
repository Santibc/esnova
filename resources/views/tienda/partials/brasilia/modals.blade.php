<!-- Mobile Navigation Modal -->
<div class="modal fade" id="nav-hamburger" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <nav class="mobile-nav">
                    <ul class="mobile-nav-list">
                        <li class="mobile-nav-item"><a href="{{ route('tienda.empresa') }}" class="mobile-nav-link">Inicio</a></li>
                        @if(isset($categorias) && $categorias && $categorias->count() > 0)
                        <li class="mobile-nav-item">
                            <a href="#" class="mobile-nav-link">Categorías</a>
                            <ul class="mobile-nav-sublist">
                                @foreach($categorias as $categoria)
                                <li class="mobile-nav-subitem">
                                    <a href="{{ route('tienda.empresa', ['categoria' => $categoria->id]) }}" class="mobile-nav-link">{{ $categoria->nombre }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
