<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategoria;
use App\Models\BlogConfiguracion;
use App\Models\Producto;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    // ==================== BLOG POSTS ====================

    public function index(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar el blog.');
        }

        if ($request->ajax()) {
            $query = BlogPost::where('empresa_id', $empresa->id)
                            ->with('categoria')
                            ->select('blog_posts.*');

            return DataTables::of($query)
                ->addColumn('imagen_preview', function($post) {
                    if ($post->imagen_portada) {
                        $url = asset($post->imagen_portada);
                        return '<img src="'.$url.'" class="rounded" style="height: 40px; width: 40px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">Sin imagen</span>';
                })
                ->addColumn('categoria_nombre', function($post) {
                    return $post->categoria ? $post->categoria->nombre : '<span class="text-muted">Sin categoría</span>';
                })
                ->addColumn('estado', function($post) {
                    if (!$post->activo) {
                        return '<span class="badge bg-secondary">Inactivo</span>';
                    }
                    if ($post->publicado_en && $post->publicado_en->isPast()) {
                        return '<span class="badge bg-success">Publicado</span>';
                    }
                    if ($post->publicado_en && $post->publicado_en->isFuture()) {
                        return '<span class="badge bg-warning text-dark">Programado</span>';
                    }
                    return '<span class="badge bg-info">Borrador</span>';
                })
                ->addColumn('fecha_pub', function($post) {
                    return $post->publicado_en ? $post->publicado_en->format('d/m/Y H:i') : '-';
                })
                ->addColumn('action', function($post) {
                    $url = route('blog.form', $post->id);
                    $buttons = '<div class="d-flex justify-content-center gap-1">';
                    $buttons .= '<a href="'.$url.'" class="btn btn-outline-info btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>';

                    $iconEstado = $post->activo ? 'bi-toggle-on' : 'bi-toggle-off';
                    $colorEstado = $post->activo ? 'success' : 'danger';
                    $buttons .= '<button type="button" class="btn btn-outline-'.$colorEstado.' btn-sm" title="Cambiar Estado" onclick="cambiarEstado('.$post->id.')">';
                    $buttons .= '<i class="bi '.$iconEstado.'"></i>';
                    $buttons .= '</button>';

                    $buttons .= '<button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarPost('.$post->id.')">';
                    $buttons .= '<i class="bi bi-trash"></i>';
                    $buttons .= '</button>';

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'imagen_preview', 'categoria_nombre', 'estado'])
                ->make(true);
        }

        $estadisticas = [
            'total_posts' => BlogPost::where('empresa_id', $empresa->id)->count(),
            'publicados' => BlogPost::where('empresa_id', $empresa->id)->publicados()->count(),
            'borradores' => BlogPost::where('empresa_id', $empresa->id)
                ->where(function($q) {
                    $q->whereNull('publicado_en')->orWhere('publicado_en', '>', now());
                })->where('activo', true)->count(),
        ];

        return view('blog.index', compact('empresa', 'estadisticas'));
    }

    public function form(BlogPost $blogPost = null)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar el blog.');
        }

        if ($blogPost && $blogPost->exists && $blogPost->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este post.');
        }

        $blogPost = $blogPost ?? new BlogPost();

        $categorias = BlogCategoria::where('empresa_id', $empresa->id)
                                   ->activas()
                                   ->pluck('nombre', 'id');

        $productos = Producto::where('empresa_id', $empresa->id)
                            ->where('activo', true)
                            ->orderBy('nombre')
                            ->get()
                            ->mapWithKeys(fn($p) => [$p->id => $p->nombre . ' (' . $p->referencia . ')']);

        $otrosPosts = BlogPost::where('empresa_id', $empresa->id)
                              ->when($blogPost->exists, fn($q) => $q->where('id', '!=', $blogPost->id))
                              ->orderBy('titulo')
                              ->pluck('titulo', 'id');

        $relacionadosIds = $blogPost->exists ? $blogPost->relacionados->pluck('id')->toArray() : [];

        return view('blog.form', compact('blogPost', 'categorias', 'productos', 'otrosPosts', 'relacionadosIds', 'empresa'));
    }

    public function guardar(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar el blog.');
        }

        $blogPost = $request->id
                  ? BlogPost::findOrFail($request->id)
                  : new BlogPost();

        if ($blogPost->exists && $blogPost->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este post.');
        }

        $rules = [
            'titulo' => [
                'required', 'string', 'max:255',
                Rule::unique('blog_posts')
                    ->where('empresa_id', $empresa->id)
                    ->ignore($blogPost->id)
            ],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('blog_posts')
                    ->where('empresa_id', $empresa->id)
                    ->ignore($blogPost->id)
            ],
            'seo_title' => 'nullable|string|max:70',
            'blog_categoria_id' => 'nullable|exists:blog_categorias,id',
            'meta_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'canonical_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|string|max:500',
            'introduccion' => 'nullable|string',
            'contenido' => 'nullable|string',
            'producto_enlace_id' => 'nullable|exists:productos,id',
            'publicado_en' => 'nullable|date',
            'orden' => 'nullable|integer|min:0',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        $messages = [
            'imagen_portada.max' => 'La imagen de portada no debe superar los 5 MB.',
            'imagen_portada.image' => 'El archivo de portada debe ser una imagen.',
            'imagen_portada.mimes' => 'La imagen de portada debe ser jpeg, png, jpg, gif o webp.',
            'og_image.max' => 'La imagen OG no debe superar los 5 MB.',
            'og_image.image' => 'El archivo OG debe ser una imagen.',
            'og_image.mimes' => 'La imagen OG debe ser jpeg, png, jpg, gif o webp.',
            'titulo.required' => 'El título es obligatorio.',
            'titulo.unique' => 'Ya existe un post con ese título.',
            'slug.unique' => 'Ya existe un post con ese slug.',
        ];

        $data = $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            if (empty($data['slug'])) {
                unset($data['slug']);
            }

            $data['empresa_id'] = $empresa->id;
            $data['user_id'] = auth()->id();
            $data['activo'] = $request->has('activo') ? true : false;
            $data['orden'] = $data['orden'] ?? 0;
            $data['noindex'] = $request->has('noindex') ? true : false;
            $data['nofollow'] = $request->has('nofollow') ? true : false;

            // OG Image
            if ($request->hasFile('og_image')) {
                if ($blogPost->og_image && File::exists(public_path($blogPost->og_image))) {
                    File::delete(public_path($blogPost->og_image));
                }

                $directory = public_path('imagenes/blog/og');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $ogImg = $request->file('og_image');
                $ogFilename = time() . '_og_' . uniqid() . '_' . $ogImg->getClientOriginalName();
                $ogImg->move($directory, $ogFilename);
                $data['og_image'] = 'imagenes/blog/og/' . $ogFilename;
            }

            if ($request->input('eliminar_og_image') && $blogPost->og_image) {
                if (File::exists(public_path($blogPost->og_image))) {
                    File::delete(public_path($blogPost->og_image));
                }
                $data['og_image'] = null;
            }

            // Bloque de confianza (JSON)
            $bloqueConfianza = [];
            if ($request->has('confianza_icono')) {
                $iconos = $request->input('confianza_icono', []);
                $textos = $request->input('confianza_texto', []);
                foreach ($iconos as $i => $icono) {
                    if (!empty($textos[$i])) {
                        $bloqueConfianza[] = ['icono' => $icono, 'texto' => $textos[$i]];
                    }
                }
            }
            $data['bloque_confianza'] = !empty($bloqueConfianza) ? $bloqueConfianza : null;

            // FAQs (JSON)
            $faqs = [];
            if ($request->has('faq_pregunta')) {
                $preguntas = $request->input('faq_pregunta', []);
                $respuestas = $request->input('faq_respuesta', []);
                foreach ($preguntas as $i => $pregunta) {
                    if (!empty($pregunta) && !empty($respuestas[$i])) {
                        $faqs[] = ['pregunta' => $pregunta, 'respuesta' => $respuestas[$i]];
                    }
                }
            }
            $data['faqs'] = !empty($faqs) ? $faqs : null;

            // Imagen de portada
            if ($request->hasFile('imagen_portada')) {
                if ($blogPost->imagen_portada && File::exists(public_path($blogPost->imagen_portada))) {
                    File::delete(public_path($blogPost->imagen_portada));
                }

                $directory = public_path('imagenes/blog');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $imagen = $request->file('imagen_portada');
                $filename = time() . '_' . uniqid() . '_' . $imagen->getClientOriginalName();
                $imagen->move($directory, $filename);
                $data['imagen_portada'] = 'imagenes/blog/' . $filename;
            }

            if ($request->input('eliminar_imagen') && $blogPost->imagen_portada) {
                if (File::exists(public_path($blogPost->imagen_portada))) {
                    File::delete(public_path($blogPost->imagen_portada));
                }
                $data['imagen_portada'] = null;
            }

            $blogPost->fill($data)->save();

            // Sync posts relacionados
            $relacionados = $request->input('relacionados', []);
            $blogPost->relacionados()->sync($relacionados);

            DB::commit();

            return redirect()->route('blog.index')
                           ->with('success', 'Post guardado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($data['imagen_portada']) && File::exists(public_path($data['imagen_portada']))) {
                File::delete(public_path($data['imagen_portada']));
            }

            return back()->withInput()
                         ->with('error', 'Error al guardar el post: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Request $request, BlogPost $blogPost)
    {
        if ($blogPost->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $blogPost->activo = !$blogPost->activo;
        $blogPost->save();

        return response()->json([
            'success' => true,
            'activo' => $blogPost->activo,
            'mensaje' => $blogPost->activo ? 'Post activado' : 'Post desactivado'
        ]);
    }

    public function eliminar(Request $request, BlogPost $blogPost)
    {
        if ($blogPost->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $blogPost->delete();

            return response()->json([
                'success' => true,
                'mensaje' => 'Post eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el post'
            ], 500);
        }
    }

    // ==================== CATEGORÍAS DEL BLOG ====================

    public function categorias(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa primero.');
        }

        $categorias = BlogCategoria::where('empresa_id', $empresa->id)
                                   ->withCount('posts')
                                   ->orderBy('orden')
                                   ->get();

        return view('blog.categorias', compact('empresa', 'categorias'));
    }

    public function guardarCategoria(Request $request)
    {
        $empresa = auth()->user()->empresa;

        $categoria = $request->id
                   ? BlogCategoria::findOrFail($request->id)
                   : new BlogCategoria();

        if ($categoria->exists && $categoria->empresa_id !== $empresa->id) {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('blog_categorias')
                    ->where('empresa_id', $empresa->id)
                    ->ignore($categoria->id)
            ],
            'descripcion' => 'nullable|string|max:500',
            'orden' => 'nullable|integer|min:0',
        ]);

        $data['empresa_id'] = $empresa->id;
        $data['activo'] = $request->has('activo') ? true : ($categoria->exists ? $categoria->activo : true);
        $data['orden'] = $data['orden'] ?? 0;

        $categoria->fill($data)->save();

        return redirect()->route('blog.categorias')
                       ->with('success', 'Categoría guardada correctamente.');
    }

    public function eliminarCategoria(Request $request, BlogCategoria $blogCategoria)
    {
        if ($blogCategoria->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($blogCategoria->posts()->count() > 0) {
            return response()->json([
                'error' => 'No se puede eliminar la categoría porque tiene posts asociados'
            ], 400);
        }

        try {
            $blogCategoria->delete();
            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la categoría'], 500);
        }
    }

    // ==================== CONFIGURACIÓN DEL BLOG ====================

    public function configuracion()
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa primero.');
        }

        $config = BlogConfiguracion::firstOrNew(['empresa_id' => $empresa->id]);

        return view('blog.configuracion', compact('empresa', 'config'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa primero.');
        }

        $rules = [
            'banner_titulo' => 'nullable|string|max:255',
            'banner_subtitulo' => 'nullable|string|max:255',
            'banner_imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'seo_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        $messages = [
            'banner_imagen.max' => 'La imagen del banner no debe superar los 5 MB.',
            'banner_imagen.image' => 'El archivo del banner debe ser una imagen.',
            'og_image.max' => 'La imagen OG no debe superar los 5 MB.',
            'og_image.image' => 'El archivo OG debe ser una imagen.',
        ];

        $data = $request->validate($rules, $messages);

        $config = BlogConfiguracion::firstOrNew(['empresa_id' => $empresa->id]);
        $config->empresa_id = $empresa->id;

        // Banner imagen
        if ($request->hasFile('banner_imagen')) {
            if ($config->banner_imagen && File::exists(public_path($config->banner_imagen))) {
                File::delete(public_path($config->banner_imagen));
            }

            $directory = public_path('imagenes/blog');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $img = $request->file('banner_imagen');
            $filename = time() . '_banner_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move($directory, $filename);
            $data['banner_imagen'] = 'imagenes/blog/' . $filename;
        }

        if ($request->input('eliminar_banner_imagen') && $config->banner_imagen) {
            if (File::exists(public_path($config->banner_imagen))) {
                File::delete(public_path($config->banner_imagen));
            }
            $data['banner_imagen'] = null;
        }

        // OG Image
        if ($request->hasFile('og_image')) {
            if ($config->og_image && File::exists(public_path($config->og_image))) {
                File::delete(public_path($config->og_image));
            }

            $directory = public_path('imagenes/blog/og');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $ogImg = $request->file('og_image');
            $ogFilename = time() . '_og_' . uniqid() . '.' . $ogImg->getClientOriginalExtension();
            $ogImg->move($directory, $ogFilename);
            $data['og_image'] = 'imagenes/blog/og/' . $ogFilename;
        }

        if ($request->input('eliminar_og_image') && $config->og_image) {
            if (File::exists(public_path($config->og_image))) {
                File::delete(public_path($config->og_image));
            }
            $data['og_image'] = null;
        }

        $data['noindex'] = $request->has('noindex') ? true : false;
        $data['nofollow'] = $request->has('nofollow') ? true : false;

        $config->fill($data)->save();

        return redirect()->route('blog.configuracion')
                       ->with('success', 'Configuración del blog guardada correctamente.');
    }
}
