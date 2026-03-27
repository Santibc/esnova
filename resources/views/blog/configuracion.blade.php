<x-app-layout>
  <x-slot name="header">Configuración del Blog</x-slot>

  <div class="container py-4">
    {{-- Botón volver --}}
    <div class="mb-3">
      <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver al Blog
      </a>
    </div>

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

    {{-- Errores de validación --}}
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>Por favor corrija los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="POST" action="{{ route('blog.configuracion.guardar') }}" enctype="multipart/form-data">
      @csrf

      {{-- ============================================ --}}
      {{-- TABS: Banner / SEO                           --}}
      {{-- ============================================ --}}
      <ul class="nav nav-tabs nav-fill mb-4" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-semibold" id="banner-tab" data-bs-toggle="tab" data-bs-target="#tab-banner" type="button" role="tab">
            <i class="bi bi-image me-1"></i> Banner del Blog
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="seo-tab" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button" role="tab">
            <i class="bi bi-search me-1"></i> SEO
          </button>
        </li>
      </ul>

      <div class="tab-content" id="configTabsContent">

        {{-- ============================================ --}}
        {{-- TAB 1: BANNER DEL BLOG                      --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade show active" id="tab-banner" role="tabpanel">

          {{-- Card: Texto del Banner --}}
          <div class="card shadow mb-4 border-start border-4 border-primary">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-fonts me-2 text-primary"></i>Texto del Banner</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="banner_titulo" class="form-label fw-semibold">Título del Banner</label>
                  <input type="text" class="form-control" id="banner_titulo" name="banner_titulo"
                         value="{{ old('banner_titulo', $config->banner_titulo) }}"
                         placeholder="Ej: Blog" maxlength="255">
                  <div class="form-text">Texto principal que aparece en el banner del blog. Por defecto: "Blog"</div>
                </div>
                <div class="col-md-6">
                  <label for="banner_subtitulo" class="form-label fw-semibold">Subtítulo del Banner</label>
                  <input type="text" class="form-control" id="banner_subtitulo" name="banner_subtitulo"
                         value="{{ old('banner_subtitulo', $config->banner_subtitulo) }}"
                         placeholder="Ej: Noticias, consejos y artículos de interés" maxlength="255">
                  <div class="form-text">Texto secundario debajo del título</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card: Imagen del Banner --}}
          <div class="card shadow mb-4 border-start border-4 border-info">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-card-image me-2 text-info"></i>Imagen del Banner</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="banner_imagen" class="form-label fw-semibold">Imagen de Fondo</label>
                  <input type="file" class="form-control" id="banner_imagen" name="banner_imagen" accept="image/*">
                  <div class="form-text">Imagen de fondo para el banner. Tamaño recomendado: 1920x400px. Máx: 5 MB.</div>
                </div>
                <div class="col-md-4">
                  @if($config->banner_imagen)
                    <label class="form-label fw-semibold">Imagen Actual</label>
                    <div class="position-relative d-inline-block">
                      <img src="{{ asset($config->banner_imagen) }}" class="img-thumbnail rounded" style="max-height: 120px; object-fit: cover;">
                      <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="eliminar_banner_imagen" name="eliminar_banner_imagen" value="1">
                        <label class="form-check-label text-danger small" for="eliminar_banner_imagen">
                          <i class="bi bi-trash"></i> Eliminar imagen
                        </label>
                      </div>
                    </div>
                  @else
                    <label class="form-label fw-semibold">Vista Previa</label>
                    <div class="text-muted small">
                      <i class="bi bi-info-circle"></i> Sin imagen. Se usará el gradiente por defecto.
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Preview del Banner --}}
          <div class="card shadow mb-4 border-start border-4 border-success">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-eye me-2 text-success"></i>Vista Previa del Banner</h6>
            </div>
            <div class="card-body p-0">
              <div id="banner-preview" style="
                background: {{ $config->banner_imagen ? 'url(' . asset($config->banner_imagen) . ') center/cover no-repeat' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};
                padding: 3rem 2rem;
                text-align: center;
                color: #fff;
                border-radius: 0 0 0.375rem 0.375rem;
                position: relative;
                overflow: hidden;
              ">
                <div style="position: relative; z-index: 2;">
                  <h2 id="preview-titulo" style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                    {{ $config->banner_titulo ?: 'Blog' }}
                  </h2>
                  <p id="preview-subtitulo" style="font-size: 1rem; opacity: 0.85; margin: 0;">
                    {{ $config->banner_subtitulo ?: 'Noticias, consejos y artículos de interés' }}
                  </p>
                </div>
                <div id="preview-overlay" style="
                  position: absolute; top: 0; left: 0; right: 0; bottom: 0;
                  background: rgba(0,0,0,0.4);
                  display: {{ $config->banner_imagen ? 'block' : 'none' }};
                "></div>
              </div>
            </div>
          </div>

        </div>

        {{-- ============================================ --}}
        {{-- TAB 2: SEO                                   --}}
        {{-- ============================================ --}}
        <div class="tab-pane fade" id="tab-seo" role="tabpanel">

          {{-- Card: Meta Tags --}}
          <div class="card shadow mb-4 border-start border-4 border-warning">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-tags me-2 text-warning"></i>Meta Tags</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-12">
                  <label for="seo_title" class="form-label fw-semibold">SEO Title</label>
                  <input type="text" class="form-control" id="seo_title" name="seo_title"
                         value="{{ old('seo_title', $config->seo_title) }}"
                         placeholder="Ej: Blog - Tu Empresa | Artículos y Consejos" maxlength="70">
                  <div class="d-flex justify-content-between">
                    <small class="text-muted">Título que aparece en los resultados de Google</small>
                    <small class="text-muted"><span id="seo-title-count">{{ strlen(old('seo_title', $config->seo_title ?? '')) }}</span>/70</small>
                  </div>
                </div>
                <div class="col-12">
                  <label for="meta_description" class="form-label fw-semibold">Meta Description</label>
                  <textarea class="form-control" id="meta_description" name="meta_description"
                            rows="3" maxlength="300"
                            placeholder="Descripción que aparece en los resultados de búsqueda...">{{ old('meta_description', $config->meta_description) }}</textarea>
                  <div class="d-flex justify-content-between">
                    <small class="text-muted">Recomendado: 150-160 caracteres</small>
                    <small class="text-muted"><span id="meta-desc-count">{{ strlen(old('meta_description', $config->meta_description ?? '')) }}</span>/300</small>
                  </div>
                </div>
                <div class="col-12">
                  <label for="seo_keywords" class="form-label fw-semibold">Keywords</label>
                  <input type="text" class="form-control" id="seo_keywords" name="seo_keywords"
                         value="{{ old('seo_keywords', $config->seo_keywords) }}"
                         placeholder="blog, artículos, noticias, consejos" maxlength="500">
                  <div class="form-text">Palabras clave separadas por comas</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card: Redes Sociales (Open Graph) --}}
          <div class="card shadow mb-4 border-start border-4 border-primary">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-share me-2 text-primary"></i>Redes Sociales (Open Graph)</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="og_image" class="form-label fw-semibold">Imagen OG (para compartir en redes)</label>
                  <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                  <div class="form-text">Imagen que aparece al compartir en Facebook, Twitter, etc. Recomendado: 1200x630px. Máx: 5 MB.</div>
                </div>
                <div class="col-md-4">
                  @if($config->og_image)
                    <label class="form-label fw-semibold">OG Actual</label>
                    <div>
                      <img src="{{ asset($config->og_image) }}" class="img-thumbnail rounded" style="max-height: 100px; object-fit: cover;">
                      <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="eliminar_og_image" name="eliminar_og_image" value="1">
                        <label class="form-check-label text-danger small" for="eliminar_og_image">
                          <i class="bi bi-trash"></i> Eliminar
                        </label>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Card: URL Canónica --}}
          <div class="card shadow mb-4 border-start border-4 border-secondary">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-link-45deg me-2 text-secondary"></i>Configuración Avanzada</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="canonical_url" class="form-label fw-semibold">URL Canónica</label>
                  <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                         value="{{ old('canonical_url', $config->canonical_url) }}"
                         placeholder="https://ejemplo.com/blog">
                  <div class="form-text">Deje vacío para usar la URL por defecto</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Indexación</label>
                  <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="noindex" name="noindex" value="1"
                           {{ old('noindex', $config->noindex) ? 'checked' : '' }}>
                    <label class="form-check-label" for="noindex">NoIndex</label>
                    <div class="form-text">Evita que Google indexe esta página</div>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="nofollow" name="nofollow" value="1"
                           {{ old('nofollow', $config->nofollow) ? 'checked' : '' }}>
                    <label class="form-check-label" for="nofollow">NoFollow</label>
                    <div class="form-text">Evita que Google siga los links</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Vista previa en Google --}}
          <div class="card shadow mb-4 border-start border-4 border-success">
            <div class="card-header bg-white">
              <h6 class="mb-0"><i class="bi bi-google me-2 text-success"></i>Vista Previa en Google</h6>
            </div>
            <div class="card-body">
              <div style="max-width: 600px; font-family: Arial, sans-serif;">
                <div style="font-size: 20px; color: #1a0dab; line-height: 1.3; margin-bottom: 3px;">
                  <span id="google-preview-title">{{ $config->seo_title ?: 'Blog - ' . $empresa->nombre }}</span>
                </div>
                <div style="font-size: 14px; color: #006621; margin-bottom: 3px;">
                  {{ url('/blog') }}
                </div>
                <div style="font-size: 13px; color: #545454; line-height: 1.4;">
                  <span id="google-preview-desc">{{ $config->meta_description ?: 'Blog de ' . $empresa->nombre . ' - Artículos, noticias y consejos' }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Botones --}}
      <div class="d-flex gap-2 mb-4">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg me-1"></i> Guardar Configuración
        </button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">
          <i class="bi bi-x-lg me-1"></i> Cancelar
        </a>
      </div>

    </form>
  </div>

  @push('scripts')
  <script>
    // Preview en tiempo real del banner
    const tituloInput = document.getElementById('banner_titulo');
    const subtituloInput = document.getElementById('banner_subtitulo');
    const previewTitulo = document.getElementById('preview-titulo');
    const previewSubtitulo = document.getElementById('preview-subtitulo');

    tituloInput.addEventListener('input', () => {
      previewTitulo.textContent = tituloInput.value || 'Blog';
    });

    subtituloInput.addEventListener('input', () => {
      previewSubtitulo.textContent = subtituloInput.value || 'Noticias, consejos y artículos de interés';
    });

    // Preview de imagen del banner
    document.getElementById('banner_imagen').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          const bannerPreview = document.getElementById('banner-preview');
          bannerPreview.style.background = `url(${ev.target.result}) center/cover no-repeat`;
          document.getElementById('preview-overlay').style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });

    // Contadores de caracteres SEO
    document.getElementById('seo_title').addEventListener('input', function() {
      document.getElementById('seo-title-count').textContent = this.value.length;
      document.getElementById('google-preview-title').textContent = this.value || 'Blog - {{ $empresa->nombre }}';
    });

    document.getElementById('meta_description').addEventListener('input', function() {
      document.getElementById('meta-desc-count').textContent = this.value.length;
      document.getElementById('google-preview-desc').textContent = this.value || 'Blog de {{ $empresa->nombre }} - Artículos, noticias y consejos';
    });
  </script>
  @endpush
</x-app-layout>
