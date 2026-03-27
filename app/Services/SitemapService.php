<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\BlogPost;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Regenerar el sitemap.xml completo
     */
    public static function generar()
    {
        try {
            $empresa = Empresa::where('activo', true)->orderBy('id')->first();

            if (!$empresa) {
                return false;
            }

            // Usar el dominio real de la petición (web) o APP_URL como fallback (CLI)
            if (app()->runningInConsole()) {
                $baseUrl = rtrim(config('app.url'), '/');
            } else {
                $baseUrl = rtrim(url('/'), '/');
            }
            $urls = [];

            // Página principal
            $urls[] = [
                'loc' => $baseUrl . '/',
                'changefreq' => 'daily',
                'priority' => '1.0',
                'lastmod' => now()->toDateString(),
            ];

            // Catálogo
            $urls[] = [
                'loc' => $baseUrl . '/catalogo',
                'changefreq' => 'daily',
                'priority' => '0.8',
                'lastmod' => now()->toDateString(),
            ];

            // Blog index
            $urls[] = [
                'loc' => $baseUrl . '/blog',
                'changefreq' => 'daily',
                'priority' => '0.8',
                'lastmod' => now()->toDateString(),
            ];

            // Productos activos
            $productos = Producto::where('empresa_id', $empresa->id)
                ->where('activo', true)
                ->orderBy('nombre')
                ->get();

            foreach ($productos as $producto) {
                $urls[] = [
                    'loc' => $baseUrl . '/producto/' . Str::slug($producto->nombre),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'lastmod' => $producto->updated_at ? $producto->updated_at->toDateString() : now()->toDateString(),
                ];
            }

            // Blog posts publicados
            $posts = BlogPost::where('empresa_id', $empresa->id)
                ->where('activo', true)
                ->whereNotNull('publicado_en')
                ->where('publicado_en', '<=', now())
                ->where(function ($q) {
                    $q->where('noindex', false)->orWhereNull('noindex');
                })
                ->orderBy('publicado_en', 'desc')
                ->get();

            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $baseUrl . '/blog/' . $post->slug,
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                    'lastmod' => $post->updated_at ? $post->updated_at->toDateString() : $post->publicado_en->toDateString(),
                ];
            }

            // Generar XML
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($urls as $url) {
                $xml .= '  <url>' . "\n";
                $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
                if (!empty($url['lastmod'])) {
                    $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
                }
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
                $xml .= '  </url>' . "\n";
            }

            $xml .= '</urlset>' . "\n";

            // Escribir archivo
            File::put(public_path('sitemap.xml'), $xml);

            return true;
        } catch (\Exception $e) {
            \Log::error('Error al generar sitemap: ' . $e->getMessage());
            return false;
        }
    }
}
