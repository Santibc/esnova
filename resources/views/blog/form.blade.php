<x-app-layout>
  <x-slot name="header">
    {{ $blogPost->exists ? 'Editar' : 'Nuevo' }} Post
  </x-slot>

  <div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
        <li class="breadcrumb-item active">{{ $blogPost->exists ? 'Editar' : 'Nuevo' }} Post</li>
      </ol>
    </nav>

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

    <form method="POST" action="{{ route('blog.guardar') }}" enctype="multipart/form-data" id="blogPostForm">
      @csrf
      <input type="hidden" name="id" value="{{ old('id', $blogPost->id) }}">

      {{-- ============================================ --}}
      {{-- TABS: Edición del Post / SEO                 --}}
      {{-- ============================================ --}}
      <ul class="nav nav-tabs nav-fill mb-4" id="blogTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold fs-5" id="tab-post" data-bs-toggle="tab"
                  data-bs-target="#panel-post" type="button" role="tab"
                  aria-controls="panel-post" aria-selected="true">
            <i class="bi bi-pencil-square me-2"></i>Edición del Post
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold fs-5" id="tab-seo" data-bs-toggle="tab"
                  data-bs-target="#panel-seo" type="button" role="tab"
                  aria-controls="panel-seo" aria-selected="false">
            <i class="bi bi-search me-2"></i>SEO
          </button>
        </li>
      </ul>

      <div class="tab-content" id="blogTabsContent">

        {{-- ======================================================== --}}
        {{-- TAB 1: EDICIÓN DEL POST                                   --}}
        {{-- ======================================================== --}}
        <div class="tab-pane fade show active" id="panel-post" role="tabpanel" aria-labelledby="tab-post">

          {{-- Card 1: Información Principal --}}
          <div class="card shadow mb-4 border-primary">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información Principal</h5>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- Título --}}
                <div class="col-md-8 mb-3">
                  <label class="form-label">Título <span class="text-danger">*</span></label>
                  <input name="titulo" id="titulo" type="text"
                         class="form-control @error('titulo') is-invalid @enderror"
                         value="{{ old('titulo', $blogPost->titulo) }}"
                         placeholder="Título del post"
                         required>
                  @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Slug --}}
                <div class="col-md-4 mb-3">
                  <label class="form-label">Slug (URL amigable)</label>
                  <div class="input-group">
                    <input name="slug" id="slug" type="text"
                           class="form-control @error('slug') is-invalid @enderror"
                           value="{{ old('slug', $blogPost->slug) }}"
                           placeholder="se-genera-automaticamente"
                           readonly>
                    <button type="button" class="btn btn-outline-secondary" id="btnEditSlug" title="Editar slug manualmente">
                      <i class="bi bi-pencil"></i>
                    </button>
                  </div>
                  @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Categoría --}}
                <div class="col-md-4 mb-3">
                  <label class="form-label">Categoría</label>
                  <select name="blog_categoria_id" class="form-select @error('blog_categoria_id') is-invalid @enderror">
                    <option value="">-- Seleccionar --</option>
                    @foreach($categorias as $id => $nombre)
                      <option value="{{ $id }}"
                        {{ old('blog_categoria_id', $blogPost->blog_categoria_id) == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                      </option>
                    @endforeach
                  </select>
                  @error('blog_categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Imagen de Portada --}}
                <div class="col-md-8 mb-3">
                  <label class="form-label">Imagen de Portada</label>

                  @if($blogPost->imagen_portada)
                    <div class="mb-2" id="imagen-actual">
                      <img src="{{ asset($blogPost->imagen_portada) }}"
                           alt="Portada actual"
                           class="img-thumbnail"
                           style="max-height: 200px; max-width: 300px;">
                      <div class="mt-2">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen" value="1">
                          <label class="form-check-label text-danger" for="eliminar_imagen">
                            <i class="bi bi-trash"></i> Eliminar imagen actual
                          </label>
                        </div>
                      </div>
                    </div>
                  @endif

                  <input type="file" name="imagen_portada" id="imagen_portada"
                         class="form-control @error('imagen_portada') is-invalid @enderror"
                         accept="image/*">
                  @error('imagen_portada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <small class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB</small>

                  <div id="preview-container" class="mt-2" style="display: none;">
                    <img id="preview-imagen" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card 2: Video YouTube --}}
          <div class="card shadow mb-4 border-danger">
            <div class="card-header bg-danger text-white">
              <h5 class="mb-0"><i class="bi bi-youtube"></i> Video YouTube</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">URL de YouTube</label>
                  <input name="youtube_url" id="youtube_url" type="text"
                         class="form-control @error('youtube_url') is-invalid @enderror"
                         value="{{ old('youtube_url', $blogPost->youtube_url) }}"
                         placeholder="https://www.youtube.com/watch?v=...">
                  @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Vista previa</label>
                  <div id="youtube-preview">
                    <div class="text-muted text-center py-4 border rounded" id="youtube-placeholder">
                      <i class="bi bi-youtube fs-1 d-block mb-2 opacity-50"></i>
                      <p class="mb-0">Ingrese una URL de YouTube para ver la vista previa</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card 3: Contenido --}}
          <div class="card shadow mb-4 border-success">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-file-earmark-richtext"></i> Contenido</h5>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- Introducción --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Introducción</label>
                  <textarea name="introduccion" rows="4"
                            class="form-control @error('introduccion') is-invalid @enderror"
                            placeholder="Breve introducción del post...">{{ old('introduccion', $blogPost->introduccion) }}</textarea>
                  @error('introduccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Contenido (Quill Editor) --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Contenido del Post</label>
                  <div id="quill-editor"></div>
                  <textarea name="contenido" id="contenido" style="display:none;">{{ old('contenido', $blogPost->contenido) }}</textarea>
                  @error('contenido') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Card 4: Bloque de Confianza --}}
          <div class="card shadow mb-4 border-warning">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
              <h5 class="mb-0"><i class="bi bi-shield-check"></i> Bloque de Confianza</h5>
              <button type="button" class="btn btn-sm btn-outline-dark" id="addConfianza">
                <i class="bi bi-plus-circle"></i> Agregar fila
              </button>
            </div>
            <div class="card-body">
              <div id="confianzaContainer">
                @if($blogPost->exists && is_array($blogPost->bloque_confianza) && count($blogPost->bloque_confianza) > 0)
                  @foreach($blogPost->bloque_confianza as $index => $item)
                    <div class="confianza-row mb-3">
                      <div class="row align-items-end">
                        <div class="col-md-2">
                          <label class="form-label">Icono</label>
                          <select name="confianza_icono[]" class="form-select">
                            <option value="&#10004;" {{ ($item['icono'] ?? '') == '✔' ? 'selected' : '' }}>&#10004;</option>
                            <option value="&#9733;" {{ ($item['icono'] ?? '') == '★' ? 'selected' : '' }}>&#9733;</option>
                            <option value="&#10003;" {{ ($item['icono'] ?? '') == '✓' ? 'selected' : '' }}>&#10003;</option>
                            <option value="&#9679;" {{ ($item['icono'] ?? '') == '●' ? 'selected' : '' }}>&#9679;</option>
                            <option value="&#9656;" {{ ($item['icono'] ?? '') == '▸' ? 'selected' : '' }}>&#9656;</option>
                            <option value="&#10007;" {{ ($item['icono'] ?? '') == '✗' ? 'selected' : '' }}>&#10007;</option>
                          </select>
                        </div>
                        <div class="col-md-8">
                          <label class="form-label">Texto</label>
                          <input type="text" name="confianza_texto[]" class="form-control"
                                 value="{{ $item['texto'] ?? '' }}"
                                 placeholder="Ej: Garantía de satisfacción">
                        </div>
                        <div class="col-md-2">
                          <button type="button" class="btn btn-danger btn-sm w-100 removeConfianza">
                            <i class="bi bi-trash"></i> Eliminar
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>

              <div id="noConfianzaMsg" class="text-center text-muted py-3"
                   style="{{ $blogPost->exists && is_array($blogPost->bloque_confianza) && count($blogPost->bloque_confianza) > 0 ? 'display:none;' : '' }}">
                <i class="bi bi-shield-check fs-3 d-block mb-2 opacity-50"></i>
                <p class="mb-0">No hay elementos de confianza. Haga clic en "Agregar fila" para comenzar.</p>
              </div>
            </div>
          </div>

          {{-- Card 5: Enlace a Producto --}}
          <div class="card shadow mb-4 border-info">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0"><i class="bi bi-link-45deg"></i> Enlace a Producto</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Producto relacionado</label>
                  <select name="producto_enlace_id" class="form-select @error('producto_enlace_id') is-invalid @enderror">
                    <option value="">Sin enlace</option>
                    @foreach($productos as $id => $nombre)
                      <option value="{{ $id }}"
                        {{ old('producto_enlace_id', $blogPost->producto_enlace_id) == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                      </option>
                    @endforeach
                  </select>
                  @error('producto_enlace_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Card 6: Preguntas Frecuentes --}}
          <div class="card shadow mb-4 border-primary">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0"><i class="bi bi-question-circle"></i> Preguntas Frecuentes</h5>
              <button type="button" class="btn btn-sm btn-outline-light" id="addFaq">
                <i class="bi bi-plus-circle"></i> Agregar pregunta
              </button>
            </div>
            <div class="card-body">
              <div id="faqContainer">
                @if($blogPost->exists && is_array($blogPost->faqs) && count($blogPost->faqs) > 0)
                  @foreach($blogPost->faqs as $index => $faq)
                    <div class="faq-row mb-3 p-3 border rounded bg-light">
                      <div class="row">
                        <div class="col-md-10 mb-2">
                          <label class="form-label">Pregunta</label>
                          <input type="text" name="faq_pregunta[]" class="form-control"
                                 value="{{ $faq['pregunta'] ?? '' }}"
                                 placeholder="Ej: ¿Cuánto tiempo tarda el envío?">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                          <button type="button" class="btn btn-danger btn-sm w-100 removeFaq">
                            <i class="bi bi-trash"></i> Eliminar
                          </button>
                        </div>
                        <div class="col-md-12">
                          <label class="form-label">Respuesta</label>
                          <textarea name="faq_respuesta[]" rows="2" class="form-control"
                                    placeholder="Respuesta a la pregunta...">{{ $faq['respuesta'] ?? '' }}</textarea>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>

              <div id="noFaqMsg" class="text-center text-muted py-3"
                   style="{{ $blogPost->exists && is_array($blogPost->faqs) && count($blogPost->faqs) > 0 ? 'display:none;' : '' }}">
                <i class="bi bi-question-circle fs-3 d-block mb-2 opacity-50"></i>
                <p class="mb-0">No hay preguntas frecuentes. Haga clic en "Agregar pregunta" para comenzar.</p>
              </div>
            </div>
          </div>

          {{-- Card 7: Posts Relacionados --}}
          <div class="card shadow mb-4 border-secondary">
            <div class="card-header bg-secondary text-white">
              <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Posts Relacionados</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-8 mb-3">
                  <label class="form-label">Seleccionar posts relacionados</label>
                  <select name="relacionados[]" class="form-select" multiple size="6">
                    @foreach($otrosPosts as $id => $titulo)
                      <option value="{{ $id }}"
                        {{ in_array($id, old('relacionados', $relacionadosIds ?? [])) ? 'selected' : '' }}>
                        {{ $titulo }}
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted">Mantenga Ctrl/Cmd presionado para seleccionar varios posts.</small>
                </div>
              </div>
            </div>
          </div>

          {{-- Card 8: Publicación --}}
          <div class="card shadow mb-4 border-dark">
            <div class="card-header bg-dark text-white">
              <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Publicación</h5>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- Activo --}}
                <div class="col-md-3 mb-3">
                  <label class="form-label">Estado</label>
                  <div class="form-check mt-2">
                    <input type="hidden" name="activo" value="0">
                    <input class="form-check-input" type="checkbox"
                           name="activo" id="activo"
                           value="1"
                           {{ old('activo', $blogPost->activo ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo">Publicado</label>
                  </div>
                </div>

                {{-- Fecha de publicación --}}
                <div class="col-md-5 mb-3">
                  <label class="form-label">Fecha de Publicación</label>
                  <input name="publicado_en" type="datetime-local"
                         class="form-control @error('publicado_en') is-invalid @enderror"
                         value="{{ old('publicado_en', $blogPost->publicado_en ? \Carbon\Carbon::parse($blogPost->publicado_en)->format('Y-m-d\TH:i') : '') }}">
                  @error('publicado_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Orden --}}
                <div class="col-md-4 mb-3">
                  <label class="form-label">Orden</label>
                  <input name="orden" type="number" min="0"
                         class="form-control @error('orden') is-invalid @enderror"
                         value="{{ old('orden', $blogPost->orden ?? 0) }}">
                  @error('orden') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

        </div>{{-- /panel-post --}}

        {{-- ======================================================== --}}
        {{-- TAB 2: SEO                                                --}}
        {{-- ======================================================== --}}
        <div class="tab-pane fade" id="panel-seo" role="tabpanel" aria-labelledby="tab-seo">

          {{-- Card SEO 1: Meta Tags principales --}}
          <div class="card shadow mb-4 border-success">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-tags"></i> Meta Tags</h5>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- SEO Title --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Título SEO <small class="text-muted">(Title tag - lo que aparece en la pestaña del navegador y Google)</small></label>
                  <input name="seo_title" id="seo_title" type="text"
                         class="form-control @error('seo_title') is-invalid @enderror"
                         value="{{ old('seo_title', $blogPost->seo_title) }}"
                         maxlength="70"
                         placeholder="Título personalizado para Google (máx 70 caracteres)">
                  <div class="d-flex justify-content-between">
                    <small class="text-muted">Si se deja vacío se usará el título del post.</small>
                    <small class="text-muted"><span id="seo_title_counter">0</span>/70</small>
                  </div>
                  @error('seo_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Meta Description --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Meta Descripción <small class="text-muted">(el texto que aparece debajo del título en Google)</small></label>
                  <textarea name="meta_description" id="meta_description" rows="3"
                            class="form-control @error('meta_description') is-invalid @enderror"
                            maxlength="300"
                            placeholder="Descripción para SEO (máximo 300 caracteres)">{{ old('meta_description', $blogPost->meta_description) }}</textarea>
                  <div class="d-flex justify-content-between">
                    <small class="text-muted">Recomendado: entre 120 y 160 caracteres.</small>
                    <small class="text-muted"><span id="meta_counter">0</span>/300</small>
                  </div>
                  @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- SEO Keywords --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Palabras clave <small class="text-muted">(Keywords)</small></label>
                  <input name="seo_keywords" type="text"
                         class="form-control @error('seo_keywords') is-invalid @enderror"
                         value="{{ old('seo_keywords', $blogPost->seo_keywords) }}"
                         maxlength="500"
                         placeholder="keyword1, keyword2, keyword3...">
                  <small class="text-muted">Separadas por comas.</small>
                  @error('seo_keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Card SEO 2: Redes Sociales (Open Graph) --}}
          <div class="card shadow mb-4 border-primary">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-share"></i> Redes Sociales (Open Graph / Twitter)</h5>
            </div>
            <div class="card-body">
              <div class="alert alert-light border mb-3">
                <i class="bi bi-info-circle text-primary"></i>
                <small>Estos valores controlan cómo se ve el post cuando se comparte en Facebook, Twitter, WhatsApp, etc. Si no los configura, se usarán el título, descripción e imagen de portada del post.</small>
              </div>

              <div class="row">
                {{-- OG Image --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Imagen para Redes Sociales</label>

                  @if($blogPost->og_image)
                    <div class="mb-2" id="og-imagen-actual">
                      <img src="{{ asset($blogPost->og_image) }}"
                           alt="OG Image actual"
                           class="img-thumbnail"
                           style="max-height: 150px; max-width: 300px;">
                      <div class="mt-2">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="eliminar_og_image" id="eliminar_og_image" value="1">
                          <label class="form-check-label text-danger" for="eliminar_og_image">
                            <i class="bi bi-trash"></i> Eliminar imagen OG
                          </label>
                        </div>
                      </div>
                    </div>
                  @endif

                  <input type="file" name="og_image" id="og_image_file"
                         class="form-control @error('og_image') is-invalid @enderror"
                         accept="image/*">
                  @error('og_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <small class="text-muted">Recomendado: 1200x630px. Si no se sube, se usará la imagen de portada.</small>

                  <div id="og-preview-container" class="mt-2" style="display: none;">
                    <img id="og-preview-imagen" src="#" alt="Preview OG" class="img-thumbnail" style="max-height: 150px;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card SEO 3: Configuración avanzada --}}
          <div class="card shadow mb-4 border-dark">
            <div class="card-header bg-dark text-white">
              <h5 class="mb-0"><i class="bi bi-gear"></i> Configuración Avanzada</h5>
            </div>
            <div class="card-body">
              <div class="row">
                {{-- Canonical URL --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">URL Canónica <small class="text-muted">(Canonical)</small></label>
                  <input name="canonical_url" type="url"
                         class="form-control @error('canonical_url') is-invalid @enderror"
                         value="{{ old('canonical_url', $blogPost->canonical_url) }}"
                         placeholder="https://ejemplo.com/blog/mi-articulo">
                  <small class="text-muted">Dejar vacío para usar la URL actual del post. Útil para evitar contenido duplicado.</small>
                  @error('canonical_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Robots --}}
                <div class="col-md-12 mb-3">
                  <label class="form-label">Directivas de Robots</label>
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox"
                           name="noindex" id="noindex"
                           value="1"
                           {{ old('noindex', $blogPost->noindex ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="noindex">
                      <strong>noindex</strong> — No indexar este post en buscadores (Google no lo mostrará en resultados)
                    </label>
                  </div>
                  <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox"
                           name="nofollow" id="nofollow"
                           value="1"
                           {{ old('nofollow', $blogPost->nofollow ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="nofollow">
                      <strong>nofollow</strong> — No seguir los enlaces de este post
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Card SEO 4: Vista previa en Google --}}
          <div class="card shadow mb-4 border-success">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-google"></i> Vista Previa en Google</h5>
            </div>
            <div class="card-body">
              <div class="border rounded p-4 bg-white" id="seoPreview">
                <div style="font-size: 20px; color: #1a0dab; line-height: 1.3; font-family: arial, sans-serif;" id="previewTitle">
                  {{ $blogPost->seo_title ?: ($blogPost->titulo ?: 'Título del post') }}
                </div>
                <div style="font-size: 14px; color: #006621; margin: 4px 0; font-family: arial, sans-serif;" id="previewUrl">
                  {{ url('/blog') }}/{{ $blogPost->slug ?: 'slug-del-post' }}
                </div>
                <div style="font-size: 14px; color: #545454; line-height: 1.5; font-family: arial, sans-serif;" id="previewDesc">
                  {{ Str::limit($blogPost->meta_description ?: 'La meta descripción del post aparecerá aquí. Escriba una descripción atractiva para mejorar el CTR en Google.', 160) }}
                </div>
              </div>
              <small class="text-muted mt-2 d-block">Esta es una aproximación de cómo se vería en los resultados de Google. Se actualiza en tiempo real.</small>
            </div>
          </div>

        </div>{{-- /panel-seo --}}

      </div>{{-- /tab-content --}}

      {{-- Botones de acción (siempre visibles) --}}
      <div class="d-flex justify-content-between mb-5">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-save"></i> Guardar
        </button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary btn-lg">
          <i class="bi bi-x-circle"></i> Cancelar
        </a>
      </div>
    </form>
  </div>

  @push('styles')
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <style>
    #quill-editor {
      background: #fff;
      height: 500px;
      overflow-y: auto;
    }
    #quill-editor .ql-editor {
      min-height: 100%;
      font-size: 1rem;
    }
    .ql-toolbar.ql-snow {
      border-radius: 0.375rem 0.375rem 0 0;
      background: #f8f9fa;
      border-color: #dee2e6;
    }
    .ql-container.ql-snow {
      border-radius: 0 0 0.375rem 0.375rem;
      border-color: #dee2e6;
    }
    .ql-editor h1 { font-size: 2rem; font-weight: 700; }
    .ql-editor h2 { font-size: 1.65rem; font-weight: 700; }
    .ql-editor h3 { font-size: 1.35rem; font-weight: 600; }
    .ql-editor h4 { font-size: 1.15rem; font-weight: 600; }
    .ql-editor h5 { font-size: 1rem; font-weight: 600; }
    .ql-editor h6 { font-size: 0.9rem; font-weight: 600; }
    .confianza-row, .faq-row {
      transition: background-color 0.2s;
    }
    .confianza-row:hover, .faq-row:hover {
      background-color: #f8f9fa;
    }

    /* Tabs styling */
    #blogTabs .nav-link {
      color: #6c757d;
      border: none;
      border-bottom: 3px solid transparent;
      padding: 0.75rem 1.5rem;
      transition: all 0.2s;
    }
    #blogTabs .nav-link:hover {
      color: #333;
      border-bottom-color: #dee2e6;
    }
    #blogTabs .nav-link.active {
      color: #0d6efd;
      border-bottom-color: #0d6efd;
      background: transparent;
    }
  </style>
  @endpush

  @push('scripts')
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
  <script>
  $(document).ready(function() {

    // =============================================
    // 1. Auto-Slug desde Título
    // =============================================
    let slugManual = {{ $blogPost->exists ? 'true' : 'false' }};

    $('#titulo').on('input', function() {
      if (slugManual && $('#slug').val()) return;

      let slug = $(this).val()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-+|-+$/g, '');

      $('#slug').val(slug);
    });

    // Botón para desbloquear la edición del slug
    $('#btnEditSlug').on('click', function() {
      const slugInput = $('#slug');
      if (slugInput.prop('readonly')) {
        slugInput.prop('readonly', false);
        slugManual = true;
        $(this).html('<i class="bi bi-lock"></i>').attr('title', 'Bloquear slug');
      } else {
        slugInput.prop('readonly', true);
        $(this).html('<i class="bi bi-pencil"></i>').attr('title', 'Editar slug manualmente');
      }
    });

    // =============================================
    // 2. Contador de caracteres para meta_description
    // =============================================
    function updateMetaCounter() {
      const len = $('#meta_description').val().length;
      $('#meta_counter').text(len);
    }
    $('#meta_description').on('input', updateMetaCounter);
    updateMetaCounter();

    // =============================================
    // 3. Preview de imagen de portada
    // =============================================
    $('#imagen_portada').on('change', function(e) {
      const file = e.target.files[0];
      const previewContainer = $('#preview-container');
      const previewImagen = $('#preview-imagen');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImagen.attr('src', e.target.result);
          previewContainer.show();
        };
        reader.readAsDataURL(file);
      } else {
        previewContainer.hide();
      }
    });

    // =============================================
    // 4. YouTube Preview
    // =============================================
    function extractYouTubeId(url) {
      if (!url) return null;
      const regExp = /^.*(?:youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
      const match = url.match(regExp);
      return (match && match[1].length === 11) ? match[1] : null;
    }

    function updateYouTubePreview() {
      const url = $('#youtube_url').val();
      const videoId = extractYouTubeId(url);
      const previewDiv = $('#youtube-preview');

      if (videoId) {
        previewDiv.html(
          '<div class="ratio ratio-16x9">' +
          '<iframe src="https://www.youtube.com/embed/' + videoId + '" ' +
          'frameborder="0" allowfullscreen></iframe>' +
          '</div>'
        );
      } else {
        previewDiv.html(
          '<div class="text-muted text-center py-4 border rounded" id="youtube-placeholder">' +
          '<i class="bi bi-youtube fs-1 d-block mb-2 opacity-50"></i>' +
          '<p class="mb-0">Ingrese una URL de YouTube para ver la vista previa</p>' +
          '</div>'
        );
      }
    }

    $('#youtube_url').on('input change', updateYouTubePreview);
    updateYouTubePreview();

    // =============================================
    // 5. Quill Editor
    // =============================================
    const quill = new Quill('#quill-editor', {
      theme: 'snow',
      placeholder: 'Escriba el contenido del post aquí...',
      modules: {
        toolbar: [
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'font': [] }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ 'color': [] }, { 'background': [] }],
          [{ 'script': 'sub'}, { 'script': 'super' }],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'indent': '-1'}, { 'indent': '+1' }],
          [{ 'direction': 'rtl' }],
          [{ 'align': [] }],
          ['blockquote', 'code-block'],
          ['link', 'image', 'video'],
          ['clean']
        ]
      }
    });

    const existingContent = $('#contenido').val();
    if (existingContent) {
      quill.root.innerHTML = existingContent;
    }

    $('#blogPostForm').on('submit', function() {
      const htmlContent = quill.root.innerHTML;
      if (htmlContent === '<p><br></p>') {
        $('#contenido').val('');
      } else {
        $('#contenido').val(htmlContent);
      }
    });

    // =============================================
    // 6. Bloque de Confianza (filas dinámicas)
    // =============================================
    function confianzaRowTemplate() {
      return `
        <div class="confianza-row mb-3">
          <div class="row align-items-end">
            <div class="col-md-2">
              <label class="form-label">Icono</label>
              <select name="confianza_icono[]" class="form-select">
                <option value="&#10004;">&#10004;</option>
                <option value="&#9733;">&#9733;</option>
                <option value="&#10003;">&#10003;</option>
                <option value="&#9679;">&#9679;</option>
                <option value="&#9656;">&#9656;</option>
                <option value="&#10007;">&#10007;</option>
              </select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Texto</label>
              <input type="text" name="confianza_texto[]" class="form-control"
                     placeholder="Ej: Garantía de satisfacción">
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-danger btn-sm w-100 removeConfianza">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </div>
          </div>
        </div>
      `;
    }

    $('#addConfianza').on('click', function() {
      $('#confianzaContainer').append(confianzaRowTemplate());
      $('#noConfianzaMsg').hide();
    });

    $(document).on('click', '.removeConfianza', function() {
      $(this).closest('.confianza-row').remove();
      if ($('.confianza-row').length === 0) {
        $('#noConfianzaMsg').show();
      }
    });

    // =============================================
    // 7. Preguntas Frecuentes (bloques dinámicos)
    // =============================================
    function faqRowTemplate() {
      return `
        <div class="faq-row mb-3 p-3 border rounded bg-light">
          <div class="row">
            <div class="col-md-10 mb-2">
              <label class="form-label">Pregunta</label>
              <input type="text" name="faq_pregunta[]" class="form-control"
                     placeholder="Ej: ¿Cuánto tiempo tarda el envío?">
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
              <button type="button" class="btn btn-danger btn-sm w-100 removeFaq">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </div>
            <div class="col-md-12">
              <label class="form-label">Respuesta</label>
              <textarea name="faq_respuesta[]" rows="2" class="form-control"
                        placeholder="Respuesta a la pregunta..."></textarea>
            </div>
          </div>
        </div>
      `;
    }

    $('#addFaq').on('click', function() {
      $('#faqContainer').append(faqRowTemplate());
      $('#noFaqMsg').hide();
    });

    $(document).on('click', '.removeFaq', function() {
      $(this).closest('.faq-row').remove();
      if ($('.faq-row').length === 0) {
        $('#noFaqMsg').show();
      }
    });

    // =============================================
    // 8. SEO Preview en tiempo real
    // =============================================
    function updateSeoTitleCounter() {
      const len = ($('#seo_title').val() || '').length;
      $('#seo_title_counter').text(len);
    }
    $('#seo_title').on('input', updateSeoTitleCounter);
    updateSeoTitleCounter();

    function updateSeoPreview() {
      const seoTitle = $('#seo_title').val() || $('#titulo').val() || 'Título del post';
      const slug = $('#slug').val() || 'slug-del-post';
      const metaDesc = $('#meta_description').val() || 'La meta descripción del post aparecerá aquí. Escriba una descripción atractiva para mejorar el CTR en Google.';

      $('#previewTitle').text(seoTitle.substring(0, 70));
      $('#previewUrl').text('{{ url("/blog") }}/' + slug);
      $('#previewDesc').text(metaDesc.substring(0, 160));
    }

    $('#seo_title, #titulo, #slug, #meta_description').on('input', updateSeoPreview);
    updateSeoPreview();

    // OG Image preview
    $('#og_image_file').on('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $('#og-preview-imagen').attr('src', e.target.result);
          $('#og-preview-container').show();
        };
        reader.readAsDataURL(file);
      } else {
        $('#og-preview-container').hide();
      }
    });

  });
  </script>
  @endpush
</x-app-layout>
