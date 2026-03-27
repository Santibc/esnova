@extends('tienda.layout')

@section('title', ($post->seo_title ?: $post->titulo) . ' - ' . $empresa->nombre)
@section('description', $post->meta_description ?? Str::limit(strip_tags($post->introduccion), 155))
@section('keywords', $post->seo_keywords ?? '')

@push('styles')
{{-- SEO Meta Tags --}}
<meta name="robots" content="{{ $post->robots_meta }}">
@if($post->canonical_url)
<link rel="canonical" href="{{ $post->canonical_url }}">
@else
<link rel="canonical" href="{{ route('tienda.blog.post', $post->slug) }}">
@endif

{{-- Open Graph --}}
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $post->seo_title ?: $post->titulo }}">
<meta property="og:description" content="{{ $post->meta_description ?? Str::limit(strip_tags($post->introduccion), 155) }}">
<meta property="og:url" content="{{ route('tienda.blog.post', $post->slug) }}">
@if($post->og_image)
<meta property="og:image" content="{{ asset($post->og_image) }}">
@elseif($post->imagen_portada)
<meta property="og:image" content="{{ asset($post->imagen_portada) }}">
@endif
<meta property="og:site_name" content="{{ $empresa->nombre }}">
@if($post->publicado_en)
<meta property="article:published_time" content="{{ $post->publicado_en->toIso8601String() }}">
@endif
@if($post->categoria)
<meta property="article:section" content="{{ $post->categoria->nombre }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->seo_title ?: $post->titulo }}">
<meta name="twitter:description" content="{{ $post->meta_description ?? Str::limit(strip_tags($post->introduccion), 155) }}">
@if($post->og_image)
<meta name="twitter:image" content="{{ asset($post->og_image) }}">
@elseif($post->imagen_portada)
<meta name="twitter:image" content="{{ asset($post->imagen_portada) }}">
@endif
@endpush

@push('styles')
<style>
    /* ============================================
       BLOG POST - Diseño moderno con hero image
       ============================================ */

    /* Hero Image */
    .blog-post-hero {
        position: relative;
        height: 450px;
        overflow: hidden;
        background: #1a202c;
    }
    .blog-post-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6;
    }
    .blog-post-hero .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 4rem 0 2.5rem;
    }
    .blog-post-hero .hero-category {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.35em 0.9em;
        border-radius: 50px;
        background: var(--accent-color, #e84545);
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 0.85rem;
    }
    .blog-post-hero h1 {
        font-size: 2.4rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 1rem;
        max-width: 780px;
    }
    .blog-post-hero .hero-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1.25rem;
        font-size: 0.88rem;
        color: rgba(255,255,255,0.75);
    }
    .blog-post-hero .hero-meta i {
        margin-right: 0.3rem;
    }

    /* Breadcrumb sobre el hero */
    .blog-breadcrumb {
        padding: 0.75rem 0;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .blog-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .blog-breadcrumb a:hover {
        color: var(--accent-color, #e84545);
    }
    .blog-breadcrumb .separator {
        color: #d1d5db;
        margin: 0 0.5rem;
    }
    .blog-breadcrumb .current {
        color: #374151;
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Article Container */
    .blog-article {
        max-width: 820px;
        margin: 0 auto;
    }

    /* YouTube embed */
    .blog-video-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 14px;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }
    .blog-video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
        border-radius: 14px;
    }

    /* Introduction */
    .blog-introduction {
        font-size: 1.2rem;
        line-height: 1.85;
        color: #4b5563;
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid var(--accent-color, #e84545);
    }

    /* Trust block */
    .trust-block {
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border-left: 4px solid #22c55e;
        margin-bottom: 2.5rem;
        box-shadow: 0 2px 12px rgba(34, 197, 94, 0.08);
    }
    .trust-block .card-body {
        padding: 1.5rem;
    }
    .trust-block-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.85rem 0;
    }
    .trust-block-item:not(:last-child) {
        border-bottom: 1px solid rgba(34, 197, 94, 0.12);
    }
    .trust-block-item .trust-icon {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #dcfce7;
        color: #16a34a;
        border-radius: 10px;
        font-size: 1rem;
    }
    .trust-block-item .trust-text {
        color: #374151;
        font-size: 0.95rem;
        line-height: 1.6;
        padding-top: 0.15rem;
    }

    /* Main Content (Quill HTML) */
    .blog-content {
        font-size: 1.08rem;
        line-height: 1.9;
        color: #374151;
        margin-bottom: 2.5rem;
    }
    .blog-content h2 {
        font-size: 1.65rem;
        font-weight: 700;
        color: #1a202c;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f3f4f6;
    }
    .blog-content h3 {
        font-size: 1.35rem;
        font-weight: 600;
        color: #1a202c;
        margin-top: 2rem;
        margin-bottom: 0.85rem;
    }
    .blog-content h4 {
        font-size: 1.15rem;
        font-weight: 600;
        color: #2d3748;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .blog-content p {
        margin-bottom: 1.35rem;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1.75rem 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .blog-content ul,
    .blog-content ol {
        margin-bottom: 1.35rem;
        padding-left: 1.5rem;
    }
    .blog-content li {
        margin-bottom: 0.5rem;
    }
    .blog-content blockquote {
        border-left: 4px solid var(--accent-color, #e84545);
        padding: 1.25rem 1.5rem;
        margin: 2rem 0;
        background-color: #f9fafb;
        border-radius: 0 10px 10px 0;
        font-style: italic;
        color: #4b5563;
        font-size: 1.1rem;
    }
    .blog-content a {
        color: var(--accent-color, #e84545);
        text-decoration: underline;
    }
    .blog-content pre {
        background-color: #1e293b;
        color: #e2e8f0;
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 1.75rem 0;
        font-size: 0.9rem;
    }
    .blog-content table {
        width: 100%;
        margin: 1.75rem 0;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }
    .blog-content table th,
    .blog-content table td {
        border: 1px solid #e5e7eb;
        padding: 0.85rem 1rem;
    }
    .blog-content table th {
        background-color: #f9fafb;
        font-weight: 600;
    }

    /* Product CTA Card */
    .product-cta-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2.5rem;
        transition: box-shadow 0.3s ease;
        background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
    }
    .product-cta-card:hover {
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
    }
    .product-cta-card .product-cta-img {
        height: 220px;
        object-fit: cover;
    }
    .product-cta-card .card-body {
        padding: 1.5rem;
    }
    .product-cta-card .product-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .product-cta-card .product-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }
    .product-cta-card .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--accent-color, #e84545);
        margin-bottom: 1rem;
    }

    /* FAQs */
    .blog-faqs {
        margin-bottom: 2.5rem;
    }
    .blog-faqs .section-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.25rem;
    }
    .blog-faqs .accordion-item {
        border: none;
        border-radius: 12px !important;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    .blog-faqs .accordion-button {
        font-weight: 600;
        font-size: 0.98rem;
        color: #1a202c;
        padding: 1.1rem 1.35rem;
        background-color: #fff;
        box-shadow: none;
    }
    .blog-faqs .accordion-button:not(.collapsed) {
        color: var(--accent-color, #e84545);
        background-color: #fef2f2;
    }
    .blog-faqs .accordion-button::after {
        flex-shrink: 0;
    }
    .blog-faqs .accordion-body {
        font-size: 0.95rem;
        line-height: 1.75;
        color: #4b5563;
        padding: 0.75rem 1.35rem 1.35rem;
    }

    /* Share Bar */
    .share-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.25rem 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 2.5rem;
    }
    .share-bar .share-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #374151;
    }
    .share-bar .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1.5px solid #e0e0e0;
        color: #6b7280;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.25s ease;
    }
    .share-bar .share-btn:hover {
        transform: translateY(-2px);
    }
    .share-bar .share-btn.facebook:hover {
        background: #1877F2;
        color: #fff;
        border-color: #1877F2;
    }
    .share-bar .share-btn.twitter:hover {
        background: #1DA1F2;
        color: #fff;
        border-color: #1DA1F2;
    }
    .share-bar .share-btn.whatsapp:hover {
        background: #25D366;
        color: #fff;
        border-color: #25D366;
    }
    .share-bar .share-btn.linkedin:hover {
        background: #0A66C2;
        color: #fff;
        border-color: #0A66C2;
    }

    /* Related Posts */
    .related-posts {
        padding-top: 2.5rem;
        margin-bottom: 2rem;
    }
    .related-posts .section-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.5rem;
    }
    .related-post-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 18px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        background: #fff;
    }
    .related-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .related-post-card .card-img-top {
        height: 190px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .related-post-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .related-post-card .card-img-wrapper {
        overflow: hidden;
    }
    .related-post-card .card-body {
        padding: 1.25rem;
    }
    .related-post-card .card-title {
        font-size: 1rem;
        font-weight: 650;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .related-post-card .card-title a {
        color: #1a202c;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .related-post-card .card-title a:hover {
        color: var(--accent-color, #e84545);
    }
    .related-post-card .card-text {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.55;
    }

    /* Product Carousel */
    .products-carousel-section {
        padding: 3rem 0 2rem;
        border-top: 1px solid #e9ecef;
        margin-top: 1rem;
    }
    .products-carousel-section .section-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.5rem;
    }
    .products-carousel-section .swiper {
        padding-bottom: 3rem;
    }
    .products-carousel-section .swiper-pagination-bullet-active {
        background: var(--accent-color, #e84545);
    }
    .product-carousel-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        height: 100%;
    }
    .product-carousel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    .product-carousel-card .product-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px;
        background: #f8f9fa;
    }
    .product-carousel-card .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .product-carousel-card:hover .product-img-wrapper img {
        transform: scale(1.06);
    }
    .product-carousel-card .card-body {
        padding: 1.1rem;
        text-align: center;
    }
    .product-carousel-card .product-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.4rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-carousel-card .product-name a {
        color: #1a202c;
        text-decoration: none;
        transition: color 0.2s;
    }
    .product-carousel-card .product-name a:hover {
        color: var(--accent-color, #e84545);
    }
    .product-carousel-card .product-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--accent-color, #e84545);
    }
    .product-carousel-card .btn-ver {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--accent-color, #e84545);
        text-decoration: none;
        margin-top: 0.5rem;
        transition: gap 0.2s;
    }
    .product-carousel-card .btn-ver:hover {
        gap: 0.55rem;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .blog-post-hero {
            height: 300px;
        }
        .blog-post-hero h1 {
            font-size: 1.5rem;
        }
        .blog-introduction {
            font-size: 1.05rem;
        }
        .blog-content {
            font-size: 1rem;
        }
        .product-cta-card .row {
            flex-direction: column;
        }
        .product-carousel-card .product-img-wrapper {
            height: 160px;
        }
    }
</style>
@endpush

@section('content')
{{-- Article Schema Markup (JSON-LD) --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->seo_title ?: $post->titulo,
    'description' => $post->meta_description ?? Str::limit(strip_tags($post->introduccion), 155),
    'image' => $post->og_image ? asset($post->og_image) : ($post->imagen_portada ? asset($post->imagen_portada) : null),
    'datePublished' => $post->publicado_en ? $post->publicado_en->toIso8601String() : $post->created_at->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Organization',
        'name' => $empresa->nombre,
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $empresa->nombre,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $empresa->logo_url,
        ],
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('tienda.blog.post', $post->slug),
    ],
] + ($post->seo_keywords ? ['keywords' => $post->seo_keywords] : []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- Hero Image --}}
<div class="blog-post-hero">
    <img src="{{ $post->imagen_portada_url }}" alt="{{ $post->titulo }}" loading="eager">
    <div class="hero-overlay">
        <div class="container">
            @if($post->categoria)
                <a href="{{ route('tienda.blog', ['categoria' => $post->categoria->slug]) }}" class="hero-category">
                    {{ $post->categoria->nombre }}
                </a>
            @endif
            <h1>{{ $post->titulo }}</h1>
            <div class="hero-meta">
                <span><i class="bi bi-calendar3"></i> {{ $post->publicado_en->format('d \d\e F, Y') }}</span>
                @if($post->autor)
                    <span><i class="bi bi-person"></i> {{ $post->autor->name }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div class="blog-breadcrumb">
    <div class="container">
        <a href="{{ route('tienda.empresa') }}">Inicio</a>
        <span class="separator"><i class="bi bi-chevron-right"></i></span>
        <a href="{{ route('tienda.blog') }}">Blog</a>
        <span class="separator"><i class="bi bi-chevron-right"></i></span>
        <span class="current">{{ Str::limit($post->titulo, 50) }}</span>
    </div>
</div>

<section class="py-5" style="background: #f9fafb;">
    <div class="container">
        <article class="blog-article">

            {{-- 1. YouTube Video --}}
            @if($post->youtube_embed_url)
                <div class="blog-video-wrapper" data-aos="fade-up">
                    <iframe src="{{ $post->youtube_embed_url }}"
                            title="{{ $post->titulo }}"
                            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"></iframe>
                </div>
            @endif

            {{-- 2. Introduction --}}
            @if($post->introduccion)
                <div class="blog-introduction" data-aos="fade-up">
                    {{ $post->introduccion }}
                </div>
            @endif

            {{-- 3. Trust Block --}}
            @if(!empty($post->bloque_confianza) && is_array($post->bloque_confianza) && count($post->bloque_confianza) > 0)
                <div class="card trust-block" data-aos="fade-up">
                    <div class="card-body">
                        @foreach($post->bloque_confianza as $item)
                            <div class="trust-block-item">
                                <div class="trust-icon">
                                    <i class="bi {{ $item['icono'] ?? 'bi-check-circle-fill' }}"></i>
                                </div>
                                <div class="trust-text">
                                    {{ $item['texto'] ?? '' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4. Main Content --}}
            <div class="blog-content" data-aos="fade-up">
                {!! $post->contenido !!}
            </div>

            {{-- 5. Product CTA --}}
            @if($post->productoEnlace)
                <div class="card product-cta-card" data-aos="fade-up">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4">
                            <img src="{{ $post->productoEnlace->url_imagen_principal }}"
                                 class="img-fluid product-cta-img w-100"
                                 alt="{{ $post->productoEnlace->nombre }}"
                                 loading="lazy">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="product-label">Producto recomendado</div>
                                <h4 class="product-name">{{ $post->productoEnlace->nombre }}</h4>
                                @if($post->productoEnlace->precio_actual)
                                    <div class="product-price">
                                        ${{ number_format($post->productoEnlace->precio_actual, 0, ',', '.') }}
                                    </div>
                                @endif
                                <a href="{{ route('tienda.producto', $post->productoEnlace->slug) }}"
                                   class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-cart3 me-1"></i> Ver producto
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 6. FAQs --}}
            @if(!empty($post->faqs) && is_array($post->faqs) && count($post->faqs) > 0)
                <div class="blog-faqs" data-aos="fade-up">
                    <h2 class="section-title"><i class="bi bi-question-circle me-2"></i>Preguntas Frecuentes</h2>
                    <div class="accordion" id="faqAccordion">
                        @foreach($post->faqs as $index => $faq)
                            <div class="accordion-item">
                                <h3 class="accordion-header" id="faqHeading{{ $index }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#faqCollapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-controls="faqCollapse{{ $index }}">
                                        {{ $faq['pregunta'] ?? '' }}
                                    </button>
                                </h3>
                                <div id="faqCollapse{{ $index }}"
                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                     aria-labelledby="faqHeading{{ $index }}"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['respuesta'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- FAQ Schema Markup (JSON-LD) --}}
                <script type="application/ld+json">
                {!! json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => collect($post->faqs)->map(function ($faq) {
                        return [
                            '@type' => 'Question',
                            'name' => $faq['pregunta'] ?? '',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => $faq['respuesta'] ?? '',
                            ],
                        ];
                    })->toArray(),
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
                </script>
            @endif

            {{-- 7. Share Bar --}}
            <div class="share-bar" data-aos="fade-up">
                <span class="share-label">Compartir:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('tienda.blog.post', $post->slug)) }}"
                   target="_blank" rel="noopener noreferrer" class="share-btn facebook" title="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('tienda.blog.post', $post->slug)) }}&text={{ urlencode($post->titulo) }}"
                   target="_blank" rel="noopener noreferrer" class="share-btn twitter" title="Twitter">
                    <i class="bi bi-twitter-x"></i>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->titulo . ' ' . route('tienda.blog.post', $post->slug)) }}"
                   target="_blank" rel="noopener noreferrer" class="share-btn whatsapp" title="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('tienda.blog.post', $post->slug)) }}"
                   target="_blank" rel="noopener noreferrer" class="share-btn linkedin" title="LinkedIn">
                    <i class="bi bi-linkedin"></i>
                </a>
            </div>

            {{-- 8. Related Posts --}}
            @if($post->relacionados && $post->relacionados->count() > 0)
                <div class="related-posts" data-aos="fade-up">
                    <h2 class="section-title">También te puede interesar</h2>
                    <div class="row g-4">
                        @foreach($post->relacionados->take(3) as $related)
                            <div class="col-md-4">
                                <div class="card related-post-card">
                                    <div class="card-img-wrapper">
                                        <a href="{{ route('tienda.blog.post', $related->slug) }}">
                                            <img src="{{ $related->imagen_portada_url }}"
                                                 class="card-img-top"
                                                 alt="{{ $related->titulo }}"
                                                 loading="lazy">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="{{ route('tienda.blog.post', $related->slug) }}">
                                                {{ $related->titulo }}
                                            </a>
                                        </h4>
                                        <p class="card-text">{{ $related->extracto }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </article>

        {{-- 9. Carrusel de Productos --}}
        @if(isset($productosCarrusel) && $productosCarrusel->count() > 0)
            <div class="products-carousel-section" data-aos="fade-up">
                <h2 class="section-title"><i class="bi bi-bag-heart me-2"></i>Nuestros Productos</h2>
                <div class="swiper product-swiper">
                    <div class="swiper-wrapper">
                        @foreach($productosCarrusel as $producto)
                            <div class="swiper-slide">
                                <div class="product-carousel-card">
                                    <div class="product-img-wrapper">
                                        <a href="{{ route('tienda.producto', $producto->slug) }}">
                                            <img src="{{ $producto->url_imagen_principal }}"
                                                 alt="{{ $producto->nombre }}"
                                                 loading="lazy">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="product-name">
                                            <a href="{{ route('tienda.producto', $producto->slug) }}">
                                                {{ $producto->nombre }}
                                            </a>
                                        </div>
                                        @if($producto->precio_actual)
                                            <div class="product-price">
                                                ${{ number_format($producto->precio_actual, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        <a href="{{ route('tienda.producto', $producto->slug) }}" class="btn-ver">
                                            Ver producto <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        @endif

    </div>
</section>

@push('scripts')
<script>
    // Inicializar carrusel de productos
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.product-swiper')) {
            new Swiper('.product-swiper', {
                slidesPerView: 2,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.product-swiper .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 24,
                    },
                },
            });
        }
    });
</script>
@endpush
@endsection
