<x-app-layout>
  <x-slot name="header">Mi Empresa</x-slot>

  {{-- Estilos específicos para esta vista (limitan imágenes y mejoran la composición) --}}
  <style>
    /* Portada con altura controlada */
    .cover-wrap { position: relative; height: 14rem; }
    @media (min-width: 768px) { .cover-wrap { height: 18rem; } }
    .cover-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; }
    .cover-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.55), rgba(0,0,0,0)); }

    /* Logo circular con sombra */
    .logo-ring { width: 7rem; height: 7rem; background: #fff; padding: .5rem; border-radius: 9999px; box-shadow: 0 10px 25px rgba(0,0,0,.15); }
    @media (min-width: 768px) { .logo-ring { width: 8rem; height: 8rem; } }
    .logo-img { width: 100%; height: 100%; border-radius: 9999px; object-fit: cover; object-position: center; }

    /* Cabecera (logo + identidad) responsive */
    .brand-header { position: relative; padding: 2rem 1.25rem 2rem 1.25rem; }
    @media (min-width: 768px){ .brand-header { padding: 2rem 2rem 2.5rem 2rem; } }

    .brand-logo {
      position: static;
      display: flex; justify-content: center; align-items: center;
      margin-top: -3rem; /* hace que el logo “muerda” la portada en móviles sin absolute */
    }
    @media (min-width: 768px){
      .brand-logo {
        position: absolute; top: -3.5rem; left: 1.25rem;
        margin-top: 0; justify-content: flex-start;
      }
    }

    .brand-text { padding-left: 0; text-align: center; }
    @media (min-width: 768px){
      .brand-text { padding-left: 9rem; text-align: left; }
    }

    /* Fila de encabezado: nombre a la izquierda y redes a la derecha en pantallas grandes */
    .brand-headline { display: grid; gap: .5rem; }
    @media (min-width: 768px){
      .brand-headline {
        grid-template-columns: 1fr auto;
        align-items: center;
      }
    }

    .muted { color: #6b7280; } /* text-gray-500 */
    .soft { color: #9ca3af; }  /* text-gray-400 */

    /* Mini cards para contacto y meta (fuera del padding-left del texto) */
    .brand-cards { padding-left: 0; }
    @media (min-width: 768px){
      .brand-cards { padding-left: 0; /* se mantiene sin 9rem */ }
    }
    .mini-card {
      background: #f9fafb; /* gray-50 */
      border: 1px solid #e5e7eb; /* gray-200 */
      border-radius: .75rem;
      padding: .9rem 1rem;
      height: 100%;
    }
    .mini-title {
      font-size: .8rem; letter-spacing: .04em; text-transform: uppercase;
      color: #6b7280; margin-bottom: .5rem;
    }
    .kv { display: flex; align-items: center; gap: .5rem; font-size: .925rem; color: #111827; } /* gray-900 */
    .kv + .kv { margin-top: .35rem; }

    /* Chips */
    .chip {
      display:inline-flex; align-items:center; gap:.35rem;
      font-size:.75rem; padding:.25rem .6rem; border-radius:9999px;
      background:#eef2ff; color:#3730a3; border: 1px solid #e0e7ff;
    }
    .chip.gray { background:#f3f4f6; color:#374151; border-color:#e5e7eb; }

    /* Imágenes del carrusel (se usan más abajo en la vista también) */
    .carousel-card img { width: 100%; height: 11rem; object-fit: cover; object-position: center; border-radius: .5rem; }
    @media (min-width: 768px) { .carousel-card img { height: 12rem; } }
    @media (min-width: 1024px) { .carousel-card img { height: 13rem; } }

    /* Tabla detalles carrusel responsive */
    .table-wrap { overflow-x: auto; }
    .badge-soft {
      display:inline-block; font-size:.75rem; padding:.2rem .5rem; border-radius:.5rem;
      background:#eef2ff; color:#3730a3;
    }
  </style>

  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Mensajes de éxito/error --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Mensaje de empresa inactiva --}}
      @if(!$empresa->activo)
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
              <strong>Su empresa se encuentra actualmente inactiva.</strong>
              <p class="mb-0">Si desea activarla, por favor comuníquese con el administrador de la aplicación.</p>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- ======= Card principal: Portada + Acciones + Estado ======= --}}
      <div class="bg-white shadow-sm rounded-4 overflow-hidden mb-6">
        <div class="cover-wrap bg-gradient-to-r from-blue-500 to-purple-600">
          @if(!empty($empresa->imagen_portada))
            <img
              src="{{ $empresa->imagen_portada_url }}"
              alt="Portada de {{ $empresa->nombre }}"
              class="cover-img"
              loading="lazy"
            >
          @endif

          <div class="cover-overlay"></div>

          {{-- Acciones --}}
          <div class="position-absolute top-0 end-0 p-3 d-flex gap-2">
            <a href="{{ route('empresa.form') }}" class="btn btn-light btn-sm">
              <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('empresa.preview') }}" target="_blank" class="btn btn-light btn-sm">
              <i class="bi bi-eye"></i> Ver Tienda
            </a>
          </div>

          {{-- Estado empresa --}}
          <div class="position-absolute top-0 start-0 p-3">
            @if($empresa->activo)
              <span class="badge bg-success">
                <i class="bi bi-check-circle"></i> Empresa Activa
              </span>
            @else
              <span class="badge bg-danger">
                <i class="bi bi-x-circle"></i> Empresa Inactiva
              </span>
            @endif
          </div>
        </div>

        {{-- Cabecera con logo + identidad (responsive) --}}
        <div class="brand-header">
          {{-- Logo (centrado en móvil, absoluto a la izquierda en md+) --}}
          <div class="brand-logo">
            <div class="logo-ring">
              <img
                src="{{ $empresa->logo_url }}"
                alt="Logo de {{ $empresa->nombre }}"
                class="logo-img"
                loading="lazy"
              >
            </div>
          </div>

          {{-- Texto (con padding-left en md+) --}}
          <div class="brand-text pt-3 pt-md-2">
            {{-- Fila: Nombre (izq) y redes (der) en pantallas grandes --}}
            <div class="brand-headline">
              <div>
                <h1 class="h3 mb-1 fw-bold">{{ $empresa->nombre }}</h1>
                <p class="mb-0 muted">{{ $empresa->descripcion ?: 'Sin descripción' }}</p>
              </div>

              {{-- Redes sociales a la derecha en md+ --}}
              <div class="d-flex align-items-center gap-3 justify-content-center justify-content-md-end mt-2 mt-md-0">
                @if($empresa->facebook_url)
                  <a href="{{ $empresa->facebook_url }}" class="text-decoration-none" target="_blank" aria-label="Facebook" title="Facebook">
                    <i class="bi bi-facebook fs-5 text-primary"></i>
                  </a>
                @endif
                @if($empresa->instagram_url)
                  <a href="{{ $empresa->instagram_url }}" class="text-decoration-none" target="_blank" aria-label="Instagram" title="Instagram">
                    <i class="bi bi-instagram fs-5" style="color:#d63384;"></i>
                  </a>
                @endif
                @if($empresa->twitter_url)
                  <a href="{{ $empresa->twitter_url }}" class="text-decoration-none" target="_blank" aria-label="Twitter/X" title="Twitter/X">
                    <i class="bi bi-twitter fs-5 text-info"></i>
                  </a>
                @endif
                @if($empresa->whatsapp)
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->whatsapp) }}"
                     class="text-decoration-none" target="_blank" aria-label="WhatsApp" title="WhatsApp">
                    <i class="bi bi-whatsapp fs-5" style="color:#25D366;"></i>
                  </a>
                @endif
              </div>
            </div>
          </div>

          {{-- Mini-cards fuera del padding-left (primero Información, luego Contacto) --}}
          <div class="brand-cards mt-3">
            <div class="row g-3">
              {{-- Información --}}
              <div class="col-12 col-md-6">
                <div class="mini-card">
                  <div class="mini-title">Información</div>
                  <div class="d-flex flex-wrap gap-2">
                    @if(!empty($empresa->slug))
                      <span class="chip gray"><i class="bi bi-link-45deg"></i> slug: {{ $empresa->slug }}</span>
                    @endif
                    <span class="chip">
                      <i class="bi bi-toggle-{{ $empresa->activo ? 'on' : 'off' }}"></i>
                      {{ $empresa->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                    @if(!empty($empresa->created_at))
                      <span class="chip gray"><i class="bi bi-clock-history"></i> creada: {{ $empresa->created_at->format('Y-m-d') }}</span>
                    @endif
                    @if(!empty($empresa->updated_at))
                      <span class="chip gray"><i class="bi bi-arrow-repeat"></i> actualizada: {{ $empresa->updated_at->format('Y-m-d') }}</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Contacto --}}
              <div class="col-12 col-md-6">
                <div class="mini-card">
                  <div class="mini-title">Contacto</div>

                  @if($empresa->email)
                    <div class="kv">
                      <i class="bi bi-envelope"></i>
                      <a href="mailto:{{ $empresa->email }}" class="text-decoration-none">{{ $empresa->email }}</a>
                    </div>
                  @endif

                  @if($empresa->telefono)
                    <div class="kv">
                      <i class="bi bi-telephone"></i>
                      <a href="tel:{{ preg_replace('/\s+/', '', $empresa->telefono) }}" class="text-decoration-none">
                        {{ $empresa->telefono }}
                      </a>
                    </div>
                  @endif

                  @if($empresa->direccion)
                    <div class="kv">
                      <i class="bi bi-geo-alt"></i>
                      <span>{{ $empresa->direccion }}</span>
                    </div>
                  @endif

                  @if(!$empresa->email && !$empresa->telefono && !$empresa->direccion)
                    <div class="text-muted">—</div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          {{-- /Mini-cards --}}
        </div> {{-- /brand-header --}}
      </div> {{-- /card principal --}}

      {{-- ======= Estadísticas ======= --}}


{{-- ======= Estadísticas ======= --}}
<div class="row g-3 mb-6">
  {{-- Total Productos --}}
  <div class="col-12 col-lg-4">
    <div class="bg-white rounded-lg shadow-sm p-6 h-100">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-sm text-gray-600 mb-1">Total Productos</p>
          <p class="h4 fw-bold text-gray-900 mb-1">{{ $estadisticas['total_productos'] }}</p>
          <p class="text-sm text-success mb-0">{{ $estadisticas['productos_activos'] }} activos</p>
        </div>
        <div class="text-primary">
          <i class="bi bi-box-seam fs-1"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Clientes --}}
  <div class="col-12 col-lg-4">
    <div class="bg-white rounded-lg shadow-sm p-6 h-100">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-sm text-gray-600 mb-1">Total Clientes</p>
          <p class="h4 fw-bold text-gray-900 mb-0">{{ $estadisticas['total_clientes'] }}</p>
        </div>
        <div class="text-success">
          <i class="bi bi-people fs-1"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Ventas del Mes --}}
  <div class="col-12 col-lg-4">
    <div class="bg-white rounded-lg shadow-sm p-6 h-100">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-sm text-gray-600 mb-1">Ventas del Mes</p>
          <p class="h4 fw-bold text-gray-900 mb-1">${{ number_format($estadisticas['ventas_mes'], 2) }}</p>
          <p class="text-sm text-primary mb-0">{{ $estadisticas['compras_mes'] }} órdenes</p>
        </div>
        <div class="text-purple-500">
          <i class="bi bi-currency-dollar fs-1"></i>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ======= Carrusel + Horario (col-12 en small, col-lg-6 en large) ======= --}}
<div class="row g-4 mb-6">

  @if($empresa->carruselImagenesActivas->count() > 0)
    <div class="col-12 col-lg-6">
      <div class="bg-white rounded-4 shadow-sm p-6 h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h3 class="h5 fw-semibold mb-0">Carrusel de Imágenes (Activas)</h3>
          <span class="badge-soft">{{ $empresa->carruselImagenesActivas->count() }} imágenes</span>
        </div>
        <p class="soft mb-4">Las imágenes se limitan en altura para mantener la estética.</p>

        {{-- Grid visual --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          @foreach($empresa->carruselImagenesActivas as $imagen)
            <div class="carousel-card position-relative group">
              <img
                src="{{ $imagen->imagen_url }}"
                alt="{{ $imagen->titulo ?: ('Imagen ' . $loop->iteration) }}"
                loading="lazy"
                style="width:100%;height:12rem;object-fit:cover;border-radius:.5rem;"
              >
              <div class="position-absolute top-0 start-0 w-100 h-100 rounded"
                   style="background:rgba(0,0,0,.45);opacity:0;transition:opacity .2s;display:flex;align-items:center;justify-content:center;"
                   onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                <div class="text-white text-center px-2">
                  @if($imagen->titulo)
                    <p class="fw-semibold mb-1">{{ $imagen->titulo }}</p>
                  @endif
                  <p class="mb-0 small">Orden: {{ $imagen->orden }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- Tabla detalle --}}
        <div class="table-responsive mt-4">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Preview</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Link</th>
                <th>Orden</th>
                <th>Activo</th>
                <th>Vigencia</th>
                <th>Archivo</th>
                <th>Creado</th>
                <th>Actualizado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($empresa->carruselImagenesActivas as $imagen)
                <tr>
                  <td>{{ $imagen->id }}</td>
                  <td><img src="{{ $imagen->imagen_url }}" alt="img {{ $imagen->id }}" style="width:70px;height:45px;object-fit:cover;border-radius:.35rem;"></td>
                  <td>{{ $imagen->titulo ?: '—' }}</td>
                  <td class="soft" style="max-width:260px;"><div class="truncate-2">{{ $imagen->descripcion ?: '—' }}</div></td>
                  <td>
                    @if(!empty($imagen->link))
                      <a href="{{ $imagen->link }}" target="_blank" class="text-decoration-none">
                        {{ \Illuminate\Support\Str::limit($imagen->link, 28) }}
                      </a>
                    @else
                      —
                    @endif
                  </td>
                  <td>{{ $imagen->orden }}</td>
                  <td>
                    @if(isset($imagen->activo))
                      <span class="badge text-bg-{{ $imagen->activo ? 'success' : 'secondary' }}">{{ $imagen->activo ? 'Sí' : 'No' }}</span>
                    @else
                      —
                    @endif
                  </td>
                  <td class="soft">
                    @php
                      $ini = !empty($imagen->fecha_inicio) ? \Illuminate\Support\Carbon::parse($imagen->fecha_inicio)->format('Y-m-d') : null;
                      $fin = !empty($imagen->fecha_fin) ? \Illuminate\Support\Carbon::parse($imagen->fecha_fin)->format('Y-m-d') : null;
                    @endphp
                    {{ $ini ? $ini : '—' }} @if($ini || $fin) – @endif {{ $fin ? $fin : '—' }}
                  </td>
                  <td class="soft">{{ $imagen->imagen ?? '—' }}</td>
                  <td class="soft">{{ !empty($imagen->created_at) ? $imagen->created_at->format('Y-m-d') : '—' }}</td>
                  <td class="soft">{{ !empty($imagen->updated_at) ? $imagen->updated_at->format('Y-m-d') : '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  @endif

  @if($empresa->horario_atencion)
    <div class="col-12 col-lg-6">
      <div class="bg-white rounded-4 shadow-sm p-6 h-100">
        <h3 class="h5 fw-semibold mb-4">Horario de Atención</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          @php
            $dias = ['lunes' => 'Lunes','martes' => 'Martes','miercoles' => 'Miércoles','jueves' => 'Jueves','viernes' => 'Viernes','sabado' => 'Sábado','domingo' => 'Domingo'];
          @endphp
          @foreach($dias as $key => $dia)
            @if(isset($empresa->horario_atencion[$key]))
              <div class="d-flex justify-content-between align-items-center p-3 bg-gray-50 rounded">
                <span class="fw-medium">{{ $dia }}</span>
                @if($empresa->horario_atencion[$key]['cerrado'] ?? false)
                  <span class="text-danger">Cerrado</span>
                @else
                  <span class="text-gray-600">
                    {{ $empresa->horario_atencion[$key]['apertura'] ?? '09:00' }} -
                    {{ $empresa->horario_atencion[$key]['cierre'] ?? '18:00' }}
                  </span>
                @endif
              </div>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  @endif

</div>


    </div>
  </div>

</x-app-layout>
