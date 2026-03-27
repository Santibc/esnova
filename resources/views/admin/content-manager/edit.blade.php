<x-app-layout>
    <x-slot name="slot">
<div class="container-fluid">
    <form action="{{ route('admin.content-manager.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Información de la página -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Contenido de {{ $page->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Título de la página -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Título de la página</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title', $page->title) }}" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $page->description) }}</textarea>
                        </div>

                        <!-- Editor de contenido dinámico -->
                        <div class="mb-3">
                            <label class="form-label">Contenido de la página</label>
                            <div id="content-editor" class="border rounded p-3">
                                <!-- Aquí se cargará dinámicamente el contenido según la página -->
                                @if($page->name === 'welcome')
                                    @include('admin.content-manager.partials.welcome-editor')
                                @endif
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Página activa
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="col-lg-4">
                <!-- Meta Tags Básicos -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-search me-2"></i>
                            SEO Básico
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Título</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                   value="{{ old('meta_title', $page->seo->meta_title ?? '') }}" maxlength="150">
                            <small class="form-text text-muted">Máximo 150 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Descripción</label>
                            <textarea class="form-control" id="meta_description" name="meta_description"
                                      rows="3">{{ old('meta_description', $page->seo->meta_description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                   value="{{ old('meta_keywords', $page->seo->meta_keywords ?? '') }}">
                            <small class="form-text text-muted">Separadas por comas</small>
                        </div>

                        <div class="mb-3">
                            <label for="canonical_url" class="form-label">URL Canónica</label>
                            <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                                   value="{{ old('canonical_url', $page->seo->canonical_url ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="robots" class="form-label">Robots</label>
                            <select class="form-select" id="robots" name="robots">
                                <option value="index,follow" {{ old('robots', $page->seo->robots ?? 'index,follow') === 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                <option value="noindex,follow" {{ old('robots', $page->seo->robots ?? '') === 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                <option value="index,nofollow" {{ old('robots', $page->seo->robots ?? '') === 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                <option value="noindex,nofollow" {{ old('robots', $page->seo->robots ?? '') === 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Open Graph -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-facebook me-2"></i>
                            Open Graph (Facebook)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="og_title" class="form-label">OG Título</label>
                            <input type="text" class="form-control" id="og_title" name="og_title"
                                   value="{{ old('og_title', $page->seo->og_title ?? '') }}" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="og_description" class="form-label">OG Descripción</label>
                            <textarea class="form-control" id="og_description" name="og_description"
                                      rows="3">{{ old('og_description', $page->seo->og_description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="og_image" class="form-label">OG Imagen</label>
                            <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                            @if($page->seo && $page->seo->og_image)
                                <small class="form-text text-muted">
                                    <a href="{{ Storage::url($page->seo->og_image) }}" target="_blank">Ver imagen actual</a>
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="og_type" class="form-label">OG Tipo</label>
                            <select class="form-select" id="og_type" name="og_type">
                                <option value="website" {{ old('og_type', $page->seo->og_type ?? 'website') === 'website' ? 'selected' : '' }}>Website</option>
                                <option value="article" {{ old('og_type', $page->seo->og_type ?? '') === 'article' ? 'selected' : '' }}>Article</option>
                                <option value="product" {{ old('og_type', $page->seo->og_type ?? '') === 'product' ? 'selected' : '' }}>Product</option>
                                <option value="business.business" {{ old('og_type', $page->seo->og_type ?? '') === 'business.business' ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Twitter Cards -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-twitter me-2"></i>
                            Twitter Cards
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="twitter_card" class="form-label">Twitter Card</label>
                            <select class="form-select" id="twitter_card" name="twitter_card">
                                <option value="summary_large_image" {{ old('twitter_card', $page->seo->twitter_card ?? 'summary_large_image') === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                <option value="summary" {{ old('twitter_card', $page->seo->twitter_card ?? '') === 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="app" {{ old('twitter_card', $page->seo->twitter_card ?? '') === 'app' ? 'selected' : '' }}>App</option>
                                <option value="player" {{ old('twitter_card', $page->seo->twitter_card ?? '') === 'player' ? 'selected' : '' }}>Player</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="twitter_title" class="form-label">Twitter Título</label>
                            <input type="text" class="form-control" id="twitter_title" name="twitter_title"
                                   value="{{ old('twitter_title', $page->seo->twitter_title ?? '') }}" maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="twitter_description" class="form-label">Twitter Descripción</label>
                            <textarea class="form-control" id="twitter_description" name="twitter_description"
                                      rows="3">{{ old('twitter_description', $page->seo->twitter_description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="twitter_image" class="form-label">Twitter Imagen</label>
                            <input type="file" class="form-control" id="twitter_image" name="twitter_image" accept="image/*">
                            @if($page->seo && $page->seo->twitter_image)
                                <small class="form-text text-muted">
                                    <a href="{{ Storage::url($page->seo->twitter_image) }}" target="_blank">Ver imagen actual</a>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Botón de guardar -->
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.content-manager.index') }}" class="btn btn-outline-secondary mt-2 w-100">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%);
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0;
}

.form-control:focus {
    border-color: #ff00c8;
    box-shadow: 0 0 0 0.2rem rgba(255, 0, 200, 0.25);
}

.btn-success {
    background-color: #25d366;
    border-color: #25d366;
}

.btn-success:hover {
    background-color: #20b358;
    border-color: #20b358;
}

#content-editor {
    min-height: 400px;
    background-color: #f8f9fa;
}

.editor-section {
    margin-bottom: 2rem;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
}

.editor-section h6 {
    color: #ff00c8;
    margin-bottom: 1rem;
    font-weight: 600;
}

.section-divider {
    border: 1px dashed #dee2e6;
    margin: 1rem 0;
}
</style>

    </x-slot>
</x-app-layout>

