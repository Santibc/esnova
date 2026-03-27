<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminContentManagerController extends Controller
{
    public function index()
    {
        $pages = Page::with('seo')->paginate(10);
        return view('admin.content-manager.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = Page::with('seo')->findOrFail($id);
        return view('admin.content-manager.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'content' => 'required|array',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'robots' => 'nullable|string',
            'og_title' => 'nullable|string|max:150',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'og_type' => 'nullable|string',
            'og_url' => 'nullable|url|max:500',
            'og_site_name' => 'nullable|string|max:100',
            'twitter_card' => 'nullable|string',
            'twitter_title' => 'nullable|string|max:150',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|image|max:2048',
            'twitter_site' => 'nullable|string|max:50',
            'twitter_creator' => 'nullable|string|max:50',
            'focus_keyword' => 'nullable|string|max:100',
            'breadcrumb_title' => 'nullable|string',
            'sitemap_include' => 'boolean',
            'sitemap_priority' => 'nullable|numeric|between:0.0,1.0',
            'sitemap_changefreq' => 'nullable|string',
            // Validaciones para las nuevas imágenes
            'logo_principal' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'imagen_seccion_principal' => 'nullable|image|max:3072',
            'imagen_better_together' => 'nullable|image|max:3072',
            'imagen_beneficios' => 'nullable|image|max:3072',
        ], [
            // Mensajes personalizados para imágenes
            'logo_principal.image' => 'El logo principal debe ser una imagen válida (JPG, PNG, etc.).',
            'logo_principal.max' => 'El logo principal no puede ser mayor a 2MB.',
            'favicon.image' => 'El favicon debe ser una imagen válida (JPG, PNG, etc.).',
            'favicon.max' => 'El favicon no puede ser mayor a 1MB.',
            'imagen_seccion_principal.image' => 'La imagen de la sección principal debe ser una imagen válida (JPG, PNG, etc.).',
            'imagen_seccion_principal.max' => 'La imagen de la sección principal no puede ser mayor a 3MB.',
            'imagen_better_together.image' => 'La imagen de Better Together debe ser una imagen válida (JPG, PNG, etc.).',
            'imagen_better_together.max' => 'La imagen de Better Together no puede ser mayor a 3MB.',
            'imagen_beneficios.image' => 'La imagen de beneficios debe ser una imagen válida (JPG, PNG, etc.).',
            'imagen_beneficios.max' => 'La imagen de beneficios no puede ser mayor a 3MB.',
            // Mensajes para imágenes SEO
            'og_image.image' => 'La imagen Open Graph debe ser una imagen válida (JPG, PNG, etc.).',
            'og_image.max' => 'La imagen Open Graph no puede ser mayor a 2MB.',
            'twitter_image.image' => 'La imagen de Twitter debe ser una imagen válida (JPG, PNG, etc.).',
            'twitter_image.max' => 'La imagen de Twitter no puede ser mayor a 2MB.',
        ]);

        $page = Page::findOrFail($id);

        // Procesar imágenes del contenido
        $content = $request->content;

        // Manejar imágenes de contenido (guardar en public directamente)
        $imageFields = [
            'logo_principal' => 'images/logos/',
            'favicon' => 'images/favicons/',
            'imagen_seccion_principal' => 'images/content/',
            'imagen_better_together' => 'images/content/',
            'imagen_beneficios' => 'images/content/',
        ];

        foreach ($imageFields as $fieldName => $directory) {
            if ($request->hasFile($fieldName)) {
                // Eliminar imagen anterior si existe
                if (isset($content[$fieldName]) && $content[$fieldName] && file_exists(public_path($content[$fieldName]))) {
                    unlink(public_path($content[$fieldName]));
                }

                // Subir nueva imagen
                $file = $request->file($fieldName);
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $directory . $filename;

                // Crear directorio si no existe
                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0755, true);
                }

                // Mover archivo
                $file->move(public_path($directory), $filename);

                // Guardar ruta en content
                $content[$fieldName] = $path;
            }
        }

        // Actualizar página
        $pageData = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $content,
            'is_active' => $request->boolean('is_active', true),
        ];

        $page->update($pageData);

        // Actualizar o crear SEO
        $seoData = $request->only([
            'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'robots',
            'og_title', 'og_description', 'og_type', 'og_url', 'og_site_name',
            'twitter_card', 'twitter_title', 'twitter_description', 'twitter_site', 'twitter_creator',
            'focus_keyword', 'breadcrumb_title', 'sitemap_changefreq'
        ]);

        $seoData['sitemap_include'] = $request->boolean('sitemap_include', true);
        $seoData['sitemap_priority'] = $request->sitemap_priority ?? 0.8;
        $seoData['is_active'] = $request->boolean('is_active', true);

        // Manejar imágenes
        if ($request->hasFile('og_image')) {
            if ($page->seo && $page->seo->og_image) {
                Storage::disk('public')->delete($page->seo->og_image);
            }
            $seoData['og_image'] = $request->file('og_image')->store('seo/og', 'public');
        }

        if ($request->hasFile('twitter_image')) {
            if ($page->seo && $page->seo->twitter_image) {
                Storage::disk('public')->delete($page->seo->twitter_image);
            }
            $seoData['twitter_image'] = $request->file('twitter_image')->store('seo/twitter', 'public');
        }

        if ($page->seo) {
            $page->seo->update($seoData);
        } else {
            $seoData['page_id'] = $page->id;
            Seo::create($seoData);
        }

        return redirect()->route('admin.content-manager.edit', $page->id)
                        ->with('success', 'Contenido actualizado correctamente');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ], [
            'image.required' => 'Debe seleccionar una imagen.',
            'image.image' => 'El archivo debe ser una imagen válida (JPG, PNG, etc.).',
            'image.max' => 'La imagen no puede ser mayor a 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('content/images', 'public');
            return response()->json([
                'success' => true,
                'url' => Storage::url($path),
                'path' => $path
            ]);
        }

        return response()->json(['success' => false], 400);
    }
}
