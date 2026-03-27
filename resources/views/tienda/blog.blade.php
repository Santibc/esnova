@extends('tienda.layout')

@section('title', ($blogConfig->seo_title ?? null) ?: 'Blog - ' . $empresa->nombre)
@section('description', ($blogConfig->meta_description ?? null) ?: 'Blog de ' . $empresa->nombre . ' - Artículos, noticias y consejos')

@if($blogConfig)
@section('seo_extras')
    @if($blogConfig->seo_keywords)
        <meta name="keywords" content="{{ $blogConfig->seo_keywords }}">
    @endif
    @if($blogConfig->noindex || $blogConfig->nofollow)
        <meta name="robots" content="{{ $blogConfig->robots_meta }}">
    @endif
    @if($blogConfig->canonical_url)
        <link rel="canonical" href="{{ $blogConfig->canonical_url }}">
    @endif
    <meta property="og:title" content="{{ $blogConfig->seo_title ?: 'Blog - ' . $empresa->nombre }}">
    <meta property="og:description" content="{{ $blogConfig->meta_description ?: 'Blog de ' . $empresa->nombre }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/blog') }}">
    @if($blogConfig->og_image)
        <meta property="og:image" content="{{ asset($blogConfig->og_image) }}">
    @endif
@endsection
@endif

@push('styles')
<style>
    /* =========================================
       BLOG LISTING - Diseño moderno con tarjetas
       ========================================= */

    .blog-hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-size: cover;
        background-position: center;
        padding: 3.5rem 0 3rem;
        color: #fff;
        text-align: center;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }
    .blog-hero-section.has-image {
        padding: 4rem 0 3.5rem;
    }
    .blog-hero-section .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.45);
    }
    .blog-hero-section .container {
        position: relative;
        z-index: 2;
    }
    .blog-hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }
    .blog-hero-section p {
        font-size: 1.1rem;
        opacity: 0.85;
        max-width: 550px;
        margin: 0 auto;
    }

    /* Filter Bar */
    .blog-filter-bar {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 0;
        position: sticky;
        top: 60px;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .blog-filter-bar .filter-pills {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        scrollbar-width: none;
    }
    .blog-filter-bar .filter-pills::-webkit-scrollbar {
        display: none;
    }
    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        white-space: nowrap;
        border: 1.5px solid #e0e0e0;
        color: #555;
        background: #fff;
        transition: all 0.25s ease;
    }
    .filter-pill:hover {
        border-color: var(--accent-color, #764ba2);
        color: var(--accent-color, #764ba2);
        background: rgba(118, 75, 162, 0.05);
    }
    .filter-pill.active {
        background: var(--accent-color, #764ba2);
        color: #fff;
        border-color: var(--accent-color, #764ba2);
    }
    .filter-pill .pill-count {
        font-size: 0.7rem;
        background: rgba(0,0,0,0.08);
        padding: 0.1em 0.5em;
        border-radius: 50px;
        font-weight: 600;
    }
    .filter-pill.active .pill-count {
        background: rgba(255,255,255,0.25);
    }

    /* Featured Post (primer post grande) */
    .featured-post {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(0,0,0,0.1);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        margin-bottom: 2rem;
    }
    .featured-post:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 50px rgba(0,0,0,0.15);
    }
    .featured-post .featured-img {
        height: 420px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s ease;
    }
    .featured-post:hover .featured-img {
        transform: scale(1.03);
    }
    .featured-post .featured-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.85));
        padding: 3rem 2rem 2rem;
        color: #fff;
    }
    .featured-post .featured-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.35em 0.85em;
        border-radius: 50px;
        background: var(--accent-color, #e84545);
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        display: inline-block;
    }
    .featured-post .featured-title {
        font-size: 1.85rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 0.75rem;
    }
    .featured-post .featured-title a {
        color: #fff;
        text-decoration: none;
    }
    .featured-post .featured-title a:hover {
        text-decoration: underline;
    }
    .featured-post .featured-excerpt {
        font-size: 0.95rem;
        opacity: 0.85;
        line-height: 1.6;
        max-width: 600px;
        margin-bottom: 0.75rem;
    }
    .featured-post .featured-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        opacity: 0.7;
    }
    .featured-post .featured-meta i {
        margin-right: 0.2rem;
    }

    /* Blog Cards Grid */
    .blog-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 18px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        background: #fff;
    }
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
    }
    .blog-card .card-img-wrapper {
        position: relative;
        overflow: hidden;
    }
    .blog-card .card-img-top {
        height: 210px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .blog-card:hover .card-img-top {
        transform: scale(1.06);
    }
    .blog-card .badge-categoria {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.35em 0.75em;
        border-radius: 50px;
        background-color: var(--accent-color, #e84545);
        color: #fff;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
    }
    .blog-card .card-body {
        padding: 1.35rem;
        display: flex;
        flex-direction: column;
    }
    .blog-card .blog-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.78rem;
        color: #9ca3af;
        margin-bottom: 0.65rem;
    }
    .blog-card .blog-meta i {
        font-size: 0.7rem;
    }
    .blog-card .card-title {
        font-size: 1.05rem;
        font-weight: 650;
        line-height: 1.4;
        margin-bottom: 0.6rem;
    }
    .blog-card .card-title a {
        color: #1a202c;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .blog-card .card-title a:hover {
        color: var(--accent-color, #e84545);
    }
    .blog-card .card-text {
        color: #6b7280;
        font-size: 0.88rem;
        line-height: 1.6;
        flex-grow: 1;
    }
    .blog-card .btn-leer-mas {
        font-size: 0.83rem;
        font-weight: 600;
        color: var(--accent-color, #e84545);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: auto;
        padding-top: 0.75rem;
        transition: gap 0.25s ease;
    }
    .blog-card .btn-leer-mas:hover {
        gap: 0.65rem;
    }

    /* Empty state */
    .blog-empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
    }
    .blog-empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.25rem;
    }
    .blog-empty-state h5 {
        color: #4b5563;
        font-weight: 600;
        font-size: 1.2rem;
    }
    .blog-empty-state p {
        color: #9ca3af;
        font-size: 0.95rem;
    }

    /* Pagination */
    .blog-pagination .pagination {
        gap: 0.3rem;
    }
    .blog-pagination .page-link {
        border-radius: 10px;
        border: none;
        color: #4b5563;
        padding: 0.55rem 0.95rem;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .blog-pagination .page-link:hover {
        background-color: #f3f4f6;
    }
    .blog-pagination .page-item.active .page-link {
        background-color: var(--accent-color, #e84545);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .blog-hero-section h1 {
            font-size: 1.8rem;
        }
        .featured-post .featured-img {
            height: 280px;
        }
        .featured-post .featured-title {
            font-size: 1.3rem;
        }
        .featured-post .featured-excerpt {
            display: none;
        }
        .blog-card .card-img-top {
            height: 180px;
        }
    }
</style>
@endpush

@section('content')
<!-- Blog Hero -->
<div class="blog-hero-section {{ ($blogConfig->banner_imagen ?? null) ? 'has-image' : '' }}"
     @if($blogConfig->banner_imagen ?? null)
         style="background-image: url('{{ asset($blogConfig->banner_imagen) }}');"
     @endif>
    @if($blogConfig->banner_imagen ?? null)
        <div class="hero-overlay"></div>
    @endif
    <div class="container">
        <h1>{{ ($blogConfig->banner_titulo ?? null) ?: 'Blog' }}</h1>
        <p>{{ ($blogConfig->banner_subtitulo ?? null) ?: 'Noticias, consejos y artículos de interés' }}</p>
    </div>
</div>

<!-- Filter Bar: Categorías -->
<div class="blog-filter-bar">
    <div class="container">
        <div class="filter-pills">
            <a href="{{ route('tienda.blog') }}" class="filter-pill {{ !request('categoria') ? 'active' : '' }}">
                Todas <span class="pill-count">{{ $posts->total() }}</span>
            </a>
            @foreach($categoriasBlog as $cat)
                <a href="{{ route('tienda.blog', ['categoria' => $cat->slug]) }}"
                   class="filter-pill {{ request('categoria') == $cat->slug ? 'active' : '' }}">
                    {{ $cat->nombre }} <span class="pill-count">{{ $cat->posts_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Blog Content -->
<section class="py-5" style="background: #f9fafb;">
    <div class="container">
        @if($posts->count() > 0)

            @php $showFeatured = ($posts->currentPage() === 1 && $posts->count() > 2); @endphp

            {{-- Featured Post (solo si hay más de 2 posts en primera página) --}}
            @if($showFeatured)
                @php $featuredPost = $posts->first(); @endphp
                <div class="featured-post" data-aos="fade-up">
                    <img src="{{ $featuredPost->imagen_portada_url }}"
                         class="featured-img"
                         alt="{{ $featuredPost->titulo }}"
                         loading="lazy">
                    <div class="featured-overlay">
                        @if($featuredPost->categoria)
                            <span class="featured-badge">{{ $featuredPost->categoria->nombre }}</span>
                        @endif
                        <h2 class="featured-title">
                            <a href="{{ route('tienda.blog.post', $featuredPost->slug) }}">
                                {{ $featuredPost->titulo }}
                            </a>
                        </h2>
                        <p class="featured-excerpt">{{ $featuredPost->extracto }}</p>
                        <div class="featured-meta">
                            <span><i class="bi bi-calendar3"></i> {{ $featuredPost->publicado_en->format('d M, Y') }}</span>
                            @if($featuredPost->autor)
                                <span><i class="bi bi-person"></i> {{ $featuredPost->autor->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grid de Posts (tarjetas) --}}
            <div class="row g-4">
                @foreach($posts as $index => $post)
                    {{-- Saltar el primero solo si se mostró como featured --}}
                    @if($showFeatured && $index === 0)
                        @continue
                    @endif
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <div class="card blog-card">
                            <div class="card-img-wrapper">
                                @if($post->categoria)
                                    <span class="badge-categoria">{{ $post->categoria->nombre }}</span>
                                @endif
                                <a href="{{ route('tienda.blog.post', $post->slug) }}">
                                    <img src="{{ $post->imagen_portada_url }}"
                                         class="card-img-top"
                                         alt="{{ $post->titulo }}"
                                         loading="lazy">
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="blog-meta">
                                    <span>
                                        <i class="bi bi-calendar3"></i>
                                        {{ $post->publicado_en->format('d M, Y') }}
                                    </span>
                                    @if($post->autor)
                                        <span>
                                            <i class="bi bi-person"></i>
                                            {{ $post->autor->name }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="card-title">
                                    <a href="{{ route('tienda.blog.post', $post->slug) }}">
                                        {{ $post->titulo }}
                                    </a>
                                </h3>
                                <p class="card-text">{{ $post->extracto }}</p>
                                <a href="{{ route('tienda.blog.post', $post->slug) }}" class="btn-leer-mas">
                                    Leer más <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="blog-pagination d-flex justify-content-center mt-5">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @endif

        @else
            <div class="blog-empty-state" data-aos="fade-up">
                <i class="bi bi-journal-text d-block"></i>
                <h5>No hay publicaciones aún</h5>
                <p>Pronto compartiremos contenido interesante. ¡Vuelve a visitarnos!</p>
            </div>
        @endif
    </div>
</section>
@endsection
