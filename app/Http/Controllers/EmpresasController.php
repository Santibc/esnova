<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\CarruselEmpresa;
use App\Models\BannerSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmpresasController extends Controller
{
    /**
     * Mostrar el perfil de la empresa o redirigir al formulario de creación
     */
    public function index()
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.crear')
                ->with('info', 'Primero debe crear su empresa para continuar.');
        }

        // Cargar imágenes del carrusel
        $empresa->load('carruselImagenesActivas');

        // Estadísticas básicas
        $estadisticas = [
            'total_productos'   => $empresa->productos()->count(),
            'productos_activos' => $empresa->productos()->where('activo', true)->count(),
            'total_compras'     => $empresa->compras()->count(),
            'compras_mes'       => $empresa->compras()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_clientes'    => $empresa->clientes()->count(),
            'ventas_mes'        => $empresa->compras()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('estado', 'pagada')
                ->sum('total'),
        ];

        return view('empresa.perfil', compact('empresa', 'estadisticas'));
    }

    /**
     * Mostrar formulario de creación/edición
     */
    public function form()
    {
        $empresa = auth()->user()->empresa;

        // Si ya tiene empresa, es edición
        if ($empresa) {
            $empresa->load('carruselImagenes');
        } else {
            $empresa = new Empresa();
        }

        return view('empresa.form', compact('empresa'));
    }

    /**
     * Guardar o actualizar la empresa
     * - Guarda archivos directamente en /public sin usar storage:link
     */
    public function guardar(Request $request)
    {
        $empresa = auth()->user()->empresa ?? new Empresa();

        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'slug'   => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('empresas')->ignore($empresa->id),
            ],
            'descripcion'    => ['nullable', 'string', 'max:500'],
            'email'          => ['nullable', 'email', 'max:255'],
            'telefono'       => ['nullable', 'string', 'max:255'],
            'direccion'      => ['nullable', 'string', 'max:255'],
            'instagram_url'  => ['nullable', 'url', 'max:255'],
            'facebook_url'   => ['nullable', 'url', 'max:255'],
            'tiktok_url'     => ['nullable', 'url', 'max:255'],
            'whatsapp'       => ['nullable', 'string', 'max:255'],
            'logo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'imagen_portada' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            // Horario de atención
            'horario_atencion'               => ['nullable', 'array'],
            'horario_atencion.*.apertura'    => ['nullable', 'date_format:H:i', 'exclude_if:horario_atencion.*.cerrado,1'],
            'horario_atencion.*.cierre'      => ['nullable', 'date_format:H:i', 'exclude_if:horario_atencion.*.cerrado,1', 'after:horario_atencion.*.apertura'],
            'horario_atencion.*.cerrado'     => ['nullable', 'boolean'],


            // Configuración de compras
            'monto_minimo_compra' => ['nullable', 'numeric', 'min:0'],

            // Carrusel
            'carrusel.*.imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'carrusel.*.titulo'      => ['nullable', 'string', 'max:255'],
            'carrusel.*.descripcion' => ['nullable', 'string'],
            'carrusel.*.link'        => ['nullable', 'url', 'max:255'],
            'carrusel.*.orden'       => ['nullable', 'integer'],
            'carrusel.*.fecha_inicio'=> ['nullable', 'date'],
            'carrusel.*.fecha_fin'   => ['nullable', 'date', 'after_or_equal:carrusel.*.fecha_inicio'],
        ];

        $messages = [
            'nombre.required'              => 'El nombre de la empresa es obligatorio.',
            'slug.unique'                  => 'Esta URL ya está en uso.',
            'email.email'                  => 'Ingrese un correo electrónico válido.',
            'logo.image'                   => 'El logo debe ser una imagen.',
            'logo.max'                     => 'El logo no debe superar 2MB.',
            'imagen_portada.max'           => 'La imagen de portada no debe superar 4MB.',
            'instagram_url.url'            => 'Ingrese una URL válida de Instagram.',
            'facebook_url.url'             => 'Ingrese una URL válida de Facebook.',
            'tiktok_url.url'               => 'Ingrese una URL válida de TikTok.',
            'carrusel.*.imagen.image'      => 'El archivo debe ser una imagen.',
            'carrusel.*.imagen.max'        => 'La imagen del carrusel no debe superar 4MB.',
            'carrusel.*.fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'horario_atencion.*.apertura.date_format' => 'Ingrese una hora de apertura válida (HH:MM).',
            'horario_atencion.*.cierre.date_format'   => 'Ingrese una hora de cierre válida (HH:MM).',
            'horario_atencion.*.cierre.after'         => 'La hora de cierre debe ser posterior a la hora de apertura.',
        ];

        $data = $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            // Generar slug si no se proporciona
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['nombre']);

                // Asegurar que sea único
                $count = Empresa::where('slug', 'like', $data['slug'] . '%')
                    ->when($empresa->id, fn($q) => $q->where('id', '!=', $empresa->id))
                    ->count();

                if ($count > 0) {
                    $data['slug'] = $data['slug'] . '-' . ($count + 1);
                }
            }

            // Procesar horario de atención (array a estructura guardable)
            if ($request->has('horario_atencion')) {
                $horario = [];
                foreach ($request->horario_atencion as $dia => $info) {
                    if (!isset($info['cerrado']) || !$info['cerrado']) {
                        $horario[$dia] = [
                            'apertura' => $info['apertura'] ?? '09:00',
                            'cierre'   => $info['cierre'] ?? '18:00',
                            'cerrado'  => false,
                        ];
                    } else {
                        $horario[$dia] = ['cerrado' => true];
                    }
                }
                $data['horario_atencion'] = $horario;
            }

            // Asignar usuario y plan por defecto si es nueva
            $esNueva = !$empresa->exists;
            if ($esNueva) {
                $data['usuario_id'] = auth()->id();
                $data['activo'] = true;

                // Asignar plan gratuito por defecto
                $planGratuito = \App\Models\PlanMembresia::where('precio', 0)->first();
                if ($planGratuito) {
                    $data['plan_membresia_id'] = $planGratuito->id;
                }

                // Asignar template por defecto (Clásico)
                $templateDefault = \App\Models\TemplateTienda::where('es_default', true)->first();
                if ($templateDefault) {
                    $data['template_tienda_id'] = $templateDefault->id;
                }
            }

            // No intentes guardar archivos en $data antes de moverlos
            unset($data['logo'], $data['imagen_portada'], $data['carrusel'], $data['carrusel_existente']);

            // Guardar/actualizar datos básicos primero para tener ID
            $empresa->fill($data)->save();

            // Rutas base en /public
            $baseDir = public_path('imagenes/empresas/' . $empresa->id);
            $logoDir = $baseDir . '/logo';
            $portadaDir = $baseDir . '/portada';
            $carruselDir = $baseDir . '/carrusel';

            // Crear directorios si no existen
            foreach ([$baseDir, $logoDir, $portadaDir, $carruselDir] as $dir) {
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                }
            }

            // ---- LOGO (mover a /public/imagenes/empresas/{id}/logo) ----
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe (intenta en public y en storage público)
                if (!empty($empresa->logo)) {
                    $posiblesRutas = [
                        public_path($empresa->logo),
                        storage_path('app/public/' . ltrim($empresa->logo, '/')),
                    ];
                    foreach ($posiblesRutas as $ruta) {
                        if (File::exists($ruta)) {
                            File::delete($ruta);
                        }
                    }
                }

                $logo = $request->file('logo');
                $logoFilename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $logo->getClientOriginalName());
                $logo->move($logoDir, $logoFilename);

                // Guardar ruta relativa a /public
                $empresa->logo = 'imagenes/empresas/' . $empresa->id . '/logo/' . $logoFilename;
                $empresa->save();
            }

            // ---- IMAGEN DE PORTADA (mover a /public/imagenes/empresas/{id}/portada) ----
            if ($request->hasFile('imagen_portada')) {
                if (!empty($empresa->imagen_portada)) {
                    $posiblesRutas = [
                        public_path($empresa->imagen_portada),
                        storage_path('app/public/' . ltrim($empresa->imagen_portada, '/')),
                    ];
                    foreach ($posiblesRutas as $ruta) {
                        if (File::exists($ruta)) {
                            File::delete($ruta);
                        }
                    }
                }

                $portada = $request->file('imagen_portada');
                $portadaFilename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $portada->getClientOriginalName());
                $portada->move($portadaDir, $portadaFilename);

                $empresa->imagen_portada = 'imagenes/empresas/' . $empresa->id . '/portada/' . $portadaFilename;
                $empresa->save();
            }

            // ---- IMÁGENES NUEVAS DEL CARRUSEL (mover a /public/imagenes/empresas/{id}/carrusel) ----
            if ($request->has('carrusel')) {
                foreach ($request->carrusel as $index => $carruselData) {
                    if (isset($carruselData['imagen']) && $carruselData['imagen']) {
                        $imagen = $carruselData['imagen'];

                        $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $imagen->getClientOriginalName());
                        $imagen->move($carruselDir, $filename);

                        $path = 'imagenes/empresas/' . $empresa->id . '/carrusel/' . $filename;

                        CarruselEmpresa::create([
                            'empresa_id'   => $empresa->id,
                            'imagen'       => $path,
                            'titulo'       => $carruselData['titulo'] ?? null,
                            'descripcion'  => $carruselData['descripcion'] ?? null,
                            'link'         => $carruselData['link'] ?? null,
                            'orden'        => $carruselData['orden'] ?? $index,
                            'fecha_inicio' => $carruselData['fecha_inicio'] ?? null,
                            'fecha_fin'    => $carruselData['fecha_fin'] ?? null,
                            'activo'       => true,
                        ]);
                    }
                }
            }

            // ---- ACTUALIZAR/ELIMINAR IMÁGENES EXISTENTES DEL CARRUSEL ----
            if ($request->has('carrusel_existente')) {
                foreach ($request->carrusel_existente as $id => $carruselData) {
                    $carruselImagen = CarruselEmpresa::find($id);

                    if ($carruselImagen && $carruselImagen->empresa_id == $empresa->id) {
                        if (isset($carruselData['eliminar']) && $carruselData['eliminar']) {
                            // Eliminar archivo físico
                            $posiblesRutas = [
                                public_path($carruselImagen->imagen),
                                storage_path('app/public/' . ltrim($carruselImagen->imagen, '/')),
                            ];
                            foreach ($posiblesRutas as $ruta) {
                                if (File::exists($ruta)) {
                                    File::delete($ruta);
                                }
                            }

                            $carruselImagen->delete();
                        } else {
                            // Nota: Si quisieras permitir reemplazar imagen aquí, podrías
                            // chequear $carruselData['imagen'] como UploadedFile y moverla.
                            $carruselImagen->update([
                                'titulo'       => $carruselData['titulo'] ?? $carruselImagen->titulo,
                                'descripcion'  => $carruselData['descripcion'] ?? $carruselImagen->descripcion,
                                'link'         => $carruselData['link'] ?? $carruselImagen->link,
                                'orden'        => $carruselData['orden'] ?? $carruselImagen->orden,
                                'fecha_inicio' => $carruselData['fecha_inicio'] ?? $carruselImagen->fecha_inicio,
                                'fecha_fin'    => $carruselData['fecha_fin'] ?? $carruselImagen->fecha_fin,
                                'activo'       => array_key_exists('activo', $carruselData) ? (bool)$carruselData['activo'] : $carruselImagen->activo,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            $mensaje = $esNueva
                ? '¡Empresa creada exitosamente! Ahora puede agregar productos.'
                : 'Empresa actualizada correctamente.';

            return redirect()->route('empresa.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al guardar la empresa: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de la empresa (activar/desactivar)
     */
    public function cambiarEstado(Request $request)
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return response()->json(['error' => 'No tiene empresa registrada'], 404);
        }

        $empresa->activo = !$empresa->activo;
        $empresa->save();

        return response()->json([
            'success' => true,
            'activo'  => $empresa->activo,
            'mensaje' => $empresa->activo ? 'Empresa activada' : 'Empresa desactivada',
        ]);
    }

    /**
     * Formulario de edición del banner promocional (carrusel de slides)
     */
    public function editarBanner()
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.crear')
                ->with('info', 'Primero debe crear su empresa para continuar.');
        }

        $empresa->load('bannerSlides');

        return view('empresa.banner', compact('empresa'));
    }

    /**
     * Guardar slides del banner promocional
     */
    public function guardarBanner(Request $request)
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.crear')
                ->with('error', 'No tiene empresa registrada.');
        }

        $rules = [
            // Nuevos slides
            'slides.*.imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'slides.*.titulo'      => ['nullable', 'string', 'max:255'],
            'slides.*.subtitulo'   => ['nullable', 'string', 'max:500'],
            'slides.*.btn1_texto'  => ['nullable', 'string', 'max:255'],
            'slides.*.btn1_link'   => ['nullable', 'string', 'max:255'],
            'slides.*.btn2_texto'  => ['nullable', 'string', 'max:255'],
            'slides.*.btn2_link'   => ['nullable', 'string', 'max:255'],
            'slides.*.orden'       => ['nullable', 'integer'],
            // Slides existentes
            'slides_existentes.*.titulo'      => ['nullable', 'string', 'max:255'],
            'slides_existentes.*.subtitulo'   => ['nullable', 'string', 'max:500'],
            'slides_existentes.*.btn1_texto'  => ['nullable', 'string', 'max:255'],
            'slides_existentes.*.btn1_link'   => ['nullable', 'string', 'max:255'],
            'slides_existentes.*.btn2_texto'  => ['nullable', 'string', 'max:255'],
            'slides_existentes.*.btn2_link'   => ['nullable', 'string', 'max:255'],
            'slides_existentes.*.orden'       => ['nullable', 'integer'],
            'slides_existentes.*.imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];

        $messages = [
            'slides.*.imagen.image'            => 'El archivo debe ser una imagen.',
            'slides.*.imagen.max'              => 'La imagen no debe superar 5MB.',
            'slides.*.imagen.mimes'            => 'La imagen debe ser JPG, PNG o WebP.',
            'slides_existentes.*.imagen.image' => 'El archivo debe ser una imagen.',
            'slides_existentes.*.imagen.max'   => 'La imagen no debe superar 5MB.',
            'slides_existentes.*.imagen.mimes' => 'La imagen debe ser JPG, PNG o WebP.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $bannerDir = public_path('imagenes/empresas/' . $empresa->id . '/banner');
            if (!File::exists($bannerDir)) {
                File::makeDirectory($bannerDir, 0755, true);
            }

            // ---- ACTUALIZAR / ELIMINAR SLIDES EXISTENTES ----
            if ($request->has('slides_existentes')) {
                foreach ($request->slides_existentes as $id => $slideData) {
                    $slide = BannerSlide::find($id);

                    if ($slide && $slide->empresa_id == $empresa->id) {
                        // Eliminar si marcado
                        if (isset($slideData['eliminar']) && $slideData['eliminar']) {
                            if (!empty($slide->imagen)) {
                                $rutaImagen = public_path($slide->imagen);
                                if (File::exists($rutaImagen)) {
                                    File::delete($rutaImagen);
                                }
                            }
                            $slide->delete();
                        } else {
                            // Actualizar campos
                            $updateData = [
                                'titulo'     => $slideData['titulo'] ?? $slide->titulo,
                                'subtitulo'  => $slideData['subtitulo'] ?? $slide->subtitulo,
                                'btn1_texto' => $slideData['btn1_texto'] ?? $slide->btn1_texto,
                                'btn1_link'  => $slideData['btn1_link'] ?? $slide->btn1_link,
                                'btn2_texto' => $slideData['btn2_texto'] ?? $slide->btn2_texto,
                                'btn2_link'  => $slideData['btn2_link'] ?? $slide->btn2_link,
                                'orden'      => $slideData['orden'] ?? $slide->orden,
                                'activo'     => isset($slideData['activo']) ? (bool)$slideData['activo'] : $slide->activo,
                            ];

                            // Reemplazar imagen si se subió una nueva
                            if (isset($slideData['imagen']) && $slideData['imagen']) {
                                // Eliminar imagen anterior
                                if (!empty($slide->imagen)) {
                                    $rutaAnterior = public_path($slide->imagen);
                                    if (File::exists($rutaAnterior)) {
                                        File::delete($rutaAnterior);
                                    }
                                }

                                $imagen = $slideData['imagen'];
                                $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $imagen->getClientOriginalName());
                                $imagen->move($bannerDir, $filename);
                                $updateData['imagen'] = 'imagenes/empresas/' . $empresa->id . '/banner/' . $filename;
                            }

                            $slide->update($updateData);
                        }
                    }
                }
            }

            // ---- CREAR NUEVOS SLIDES ----
            if ($request->has('slides')) {
                foreach ($request->slides as $index => $slideData) {
                    $imagenPath = null;

                    if (isset($slideData['imagen']) && $slideData['imagen']) {
                        $imagen = $slideData['imagen'];
                        $filename = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $imagen->getClientOriginalName());
                        $imagen->move($bannerDir, $filename);
                        $imagenPath = 'imagenes/empresas/' . $empresa->id . '/banner/' . $filename;
                    }

                    BannerSlide::create([
                        'empresa_id' => $empresa->id,
                        'titulo'     => $slideData['titulo'] ?? null,
                        'subtitulo'  => $slideData['subtitulo'] ?? null,
                        'imagen'     => $imagenPath,
                        'btn1_texto' => $slideData['btn1_texto'] ?? null,
                        'btn1_link'  => $slideData['btn1_link'] ?? null,
                        'btn2_texto' => $slideData['btn2_texto'] ?? null,
                        'btn2_link'  => $slideData['btn2_link'] ?? null,
                        'orden'      => $slideData['orden'] ?? $index,
                        'activo'     => true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('empresa.banner')->with('success', 'Banner actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al guardar el banner: ' . $e->getMessage());
        }
    }

    /**
     * Vista previa de la tienda
     */
    public function preview()
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.crear')
                ->with('error', 'Debe crear su empresa primero.');
        }

        // Redirigir a la raíz (single-tenant - no requiere slug)
        return redirect()
            ->route('tienda.empresa')
            ->with('info', 'Esta es la vista previa de su tienda.');
    }
}
