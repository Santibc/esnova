<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\PrecioProducto;
use App\Models\ActualizacionPrecio;
use App\Imports\PreciosImport;
use App\Models\ListaPrecio;
use App\Models\VarianteProducto;
use App\Models\StockProducto;
use App\Models\MovimientoStock;
use App\Models\CaracteristicaProducto;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa;

class ProductosController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                        ->with('warning', 'Debe crear su empresa antes de gestionar productos.');
        }
        
        if ($request->ajax()) {
            $query = Producto::with(['categoria', 'imagenPrincipal', 'stockPrincipal'])
                            ->where('empresa_id', $empresa->id)
                            ->noEliminados()
                            ->select('productos.*');

            return DataTables::of($query)
                ->addColumn('categoria', fn($p) => $p->categoria?->nombre)
                ->addColumn('imagen', function($p) {
                    $url = $p->imagenPrincipal 
                        ? asset($p->imagenPrincipal->ruta_imagen)
                        : asset('images/no-image.png');
                    return '<img src="'.$url.'" class="img-thumbnail" style="width:50px;">';
                })
                ->addColumn('stock', function($p) {
                    if (!$p->controlar_stock) {
                        return '<span class="badge bg-secondary">No controlado</span>';
                    }
                    
                    $stockDisponible = $p->stock_disponible;
                    $badge = 'success';
                    
                    if ($stockDisponible <= 0) {
                        $badge = 'danger';
                    } elseif ($p->tiene_stock_bajo) {
                        $badge = 'warning';
                    }
                    
                    return '<span class="badge bg-'.$badge.'">' . $stockDisponible . '</span>';
                })
                ->addColumn('variantes', fn($p) => $p->tiene_variantes ? 'Sí' : 'No')
                ->addColumn('info_envio', fn($p) => $p->info_envio ? '<span title="'.e($p->info_envio).'">'.e(\Illuminate\Support\Str::limit($p->info_envio, 30)).'</span>' : '-')
                ->addColumn('dias_devolucion', fn($p) => $p->dias_devolucion ? '<span title="'.e($p->dias_devolucion).'">'.e(\Illuminate\Support\Str::limit($p->dias_devolucion, 30)).'</span>' : '-')
                ->addColumn('garantia', fn($p) => $p->garantia ? '<span title="'.e($p->garantia).'">'.e(\Illuminate\Support\Str::limit($p->garantia, 30)).'</span>' : '-')
                ->addColumn('activo', fn($p) => $p->activo ? 'Sí' : 'No')
                ->addColumn('descuentos', function($p) use ($empresa) {
                    // Buscar descuentos activos
                    $descuentos = \App\Models\Descuento::porEmpresa($empresa->id)
                        ->activos()
                        ->vigentes()
                        ->get()
                        ->filter(function($desc) use ($p) {
                            // Descuentos que aplican a toda la orden
                            if ($desc->aplica_a === 'orden' || $desc->aplica_a === 'carrito') {
                                return true;
                            }

                            // Descuentos de producto específico
                            if ($desc->aplica_a === 'producto') {
                                return in_array($p->id, $desc->productos_aplicables ?? []);
                            }

                            // Descuentos de categoría
                            if ($desc->aplica_a === 'categoria' && $p->categoria_id) {
                                return in_array($p->categoria_id, $desc->categorias_aplicables ?? []);
                            }

                            return false;
                        });

                    if ($descuentos->isEmpty()) {
                        return '<span class="text-muted">-</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-1">';
                    foreach ($descuentos as $desc) {
                        $valor = $desc->tipo === 'porcentaje'
                            ? $desc->valor . '%'
                            : '$' . number_format($desc->valor, 0, ',', '.');

                        $badge = $desc->codigo ? 'bg-primary' : 'bg-success';
                        $tipo = $desc->codigo ? 'Código: ' . $desc->codigo : 'Automático';

                        $editUrl = route('descuentos.edit', $desc->id);

                        $html .= '<div class="d-flex align-items-center gap-1">';
                        $html .= '<span class="badge ' . $badge . ' text-white" style="font-size:0.75rem;">';
                        $html .= '<i class="bi bi-tag-fill"></i> ' . $valor;
                        $html .= '</span>';
                        $html .= '<a href="' . $editUrl . '" class="btn btn-outline-secondary btn-sm" style="padding:0.1rem 0.3rem;font-size:0.7rem;" title="' . $desc->nombre . ' - ' . $tipo . '">';
                        $html .= '<i class="bi bi-pencil"></i>';
                        $html .= '</a>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('action', function($p) {
                    $url = route('productos.form', $p->id);

                    $buttons = '<div class="d-flex justify-content-center gap-1">';
                    $buttons .= '<a href="'.$url.'" class="btn btn-outline-info btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>';

                    if ($p->tiene_variantes) {
                        $buttons .= '<button type="button" class="btn btn-outline-secondary btn-sm" title="Ver Variantes" onclick="verVariantes('.$p->id.')"><i class="bi bi-list-ul"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-outline-primary btn-sm" title="Ver Imágenes" onclick="verImagenes('.$p->id.')"><i class="bi bi-image"></i></button>';
                    $buttons .= '<button type="button" class="btn btn-outline-success btn-sm" title="Ver Precios" onclick="verPrecios('.$p->id.')"><i class="bi bi-currency-dollar"></i></button>';

                    if ($p->controlar_stock) {
                        $buttons .= '<button type="button" class="btn btn-outline-warning btn-sm" title="Ver Stock" onclick="verStock('.$p->id.')"><i class="bi bi-box-seam"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-outline-dark btn-sm" title="Ver Logs" onclick="verLogs('.$p->id.')"><i class="bi bi-clock-history"></i></button>';
                    $buttons .= '<button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarProducto('.$p->id.', \''.addslashes($p->nombre).'\')"><i class="bi bi-trash"></i></button>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['imagen', 'stock', 'info_envio', 'dias_devolucion', 'garantia', 'descuentos', 'action'])
                ->make(true);
        }

        return view('productos.productos_index');
    }

    public function form(Producto $producto = null)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                        ->with('warning', 'Debe crear su empresa antes de gestionar productos.');
        }

        // Si es edición, verificar que el producto pertenezca a la empresa
        if ($producto && $producto->exists && $producto->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este producto.');
        }
        
        $producto = $producto ?? new Producto();
        $categorias = Categoria::where('empresa_id', $empresa->id)
                     ->activas()
                     ->pluck('nombre', 'id');
                     
        if ($categorias->isEmpty()) {
            return redirect()->route('categorias.form')
                           ->with('warning', 'Debe crear al menos una categoría antes de crear productos.');
        }
        
        $listas = ListaPrecio::activas()->get();
        
        // Cargar stock si el producto existe
        $stocks = [];
        if ($producto->exists) {
            if ($producto->tiene_variantes) {
                $stocks = $producto->stock()->with('variante')->get();
            } else {
                $stock = $producto->stockPrincipal;
                if ($stock) {
                    $stocks = [$stock];
                }
            }
        }

        // Cargar características existentes
        $caracteristicas = collect();
        if ($producto->exists) {
            $caracteristicas = $producto->caracteristicas()->orderBy('orden')->get();
        }

        // Iconos disponibles para el selector
        $iconosDisponibles = [
            'bi-star' => 'Estrella',
            'bi-star-fill' => 'Estrella Llena',
            'bi-box' => 'Caja',
            'bi-box-seam' => 'Caja Sellada',
            'bi-truck' => 'Envío',
            'bi-shield-check' => 'Garantía',
            'bi-award' => 'Premio',
            'bi-gem' => 'Calidad',
            'bi-lightning' => 'Rápido',
            'bi-heart' => 'Favorito',
            'bi-check-circle' => 'Verificado',
            'bi-tools' => 'Herramientas',
            'bi-gear' => 'Configuración',
            'bi-palette' => 'Diseño',
            'bi-rulers' => 'Medidas',
            'bi-thermometer' => 'Temperatura',
            'bi-droplet' => 'Resistente Agua',
            'bi-sun' => 'UV Protección',
            'bi-battery-full' => 'Batería',
            'bi-wifi' => 'Conectividad',
            'bi-clock' => 'Tiempo',
            'bi-calendar-check' => 'Disponibilidad',
            'bi-recycle' => 'Ecológico',
            'bi-leaf' => 'Natural',
            'bi-hand-thumbs-up' => 'Recomendado',
            'bi-tag' => 'Etiqueta',
            'bi-percent' => 'Descuento',
        ];

        return view('productos.productos_form', compact('producto', 'categorias', 'listas', 'stocks', 'caracteristicas', 'iconosDisponibles'));
    }

    public function guardar(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                        ->with('warning', 'Debe crear su empresa antes de gestionar productos.');
        }
        
        $producto = $request->id
                ? Producto::findOrFail($request->id)
                : new Producto();
                
        // Si es edición, verificar que el producto pertenezca a la empresa
        if ($producto->exists && $producto->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este producto.');
        }

        $rules = [
            'referencia' => [
                'required','string','max:255',
                Rule::unique('productos', 'referencia')
                    ->where(fn($q) => $q->where('empresa_id', $empresa->id))
                    ->ignore($producto->id),
            ],
            'nombre' => ['required','string','max:255'],
            'descripcion' => ['nullable','string'],
            'unidad_venta' => ['required','string','max:100'],
            'unidad_empaque' => ['required','string','max:100'],
            'extension' => ['nullable','string','max:100'],
            'categoria_id' => ['required','exists:categorias,id'],
            'controlar_stock' => ['boolean'],
            'permitir_venta_sin_stock' => ['boolean'],
            'info_envio' => ['nullable','string','max:255'],
            'dias_devolucion' => ['nullable','string','max:255'],
            'garantia' => ['nullable','string','max:255'],
            'orden' => ['nullable','integer','min:0'],
            'variantes.*.talla' => ['nullable','string','max:50'],
            'variantes.*.color' => ['nullable','string','max:50'],
            'variantes.*.sku'   => ['nullable','string','max:255','distinct'],
            'variantes.*.stock_inicial' => ['nullable','integer','min:0'],
            'variantes.*.stock_minimo'  => ['nullable','integer','min:0'],
            'variantes.*.stock_maximo'  => ['nullable','integer','min:0'],
            'variantes.*.ubicacion'     => ['nullable','string','max:255'],
            'precios.*' => ['nullable','numeric','min:0'],
            'stock_inicial' => ['nullable','integer','min:0'],
            'stock_minimo'  => ['nullable','integer','min:0'],
            'stock_maximo'  => ['nullable','integer','min:0'],
            'ubicacion_stock' => ['nullable','string','max:255'],
            'caracteristicas.*.icono' => ['nullable','string','max:50'],
            'caracteristicas.*.titulo' => ['nullable','string','max:100'],
            'caracteristicas.*.descripcion' => ['nullable','string','max:500'],
        ];

        $messages = [
            'required' => 'Este campo es obligatorio.',
            'max' => 'No debe superar los :max caracteres.',
            'unique' => 'Ya existe un producto con esta referencia.',
            'exists' => 'La categoría seleccionada no es válida.',
            'precios.*.numeric' => 'El precio debe ser un número.',
            'precios.*.min' => 'El precio no puede ser negativo.',
            'stock_inicial.integer' => 'El stock debe ser un número entero.',
            'stock_inicial.min' => 'El stock no puede ser negativo.',
        ];

        $data = $request->validate($rules, $messages);
        
        DB::beginTransaction();
        
        try {
            // Guardar datos básicos del producto
            $data['tiene_variantes'] = $request->input('tiene_variantes', 0) == 1;
            $data['controlar_stock'] = $request->input('controlar_stock', 1) == 1;
            $data['permitir_venta_sin_stock'] = $request->input('permitir_venta_sin_stock', 0) == 1;
            $data['activo'] = true;
            $data['empresa_id'] = $empresa->id;

            $esNuevo = !$producto->exists;

            // Snapshot para auditoría
            $camposAuditar = ['referencia','nombre','descripcion','unidad_venta','unidad_empaque',
                'extension','categoria_id','activo','tiene_variantes','controlar_stock',
                'permitir_venta_sin_stock','info_envio','dias_devolucion','garantia','orden'];
            $oldValues = $esNuevo ? [] : $producto->only($camposAuditar);
            $oldCategoriaName = !$esNuevo && $producto->categoria ? $producto->categoria->nombre : null;

            $producto->fill($data)->save();

            // Registrar logs de campos básicos
            if ($esNuevo) {
                $this->registrarLog($producto->id, 'Producto creado: ' . $producto->nombre);
            } else {
                foreach ($camposAuditar as $campo) {
                    $viejo = $oldValues[$campo] ?? null;
                    $nuevo = $producto->$campo;
                    if ((string)$viejo !== (string)$nuevo) {
                        if ($campo === 'categoria_id') {
                            $nuevaCat = Categoria::find($nuevo);
                            $this->registrarLog($producto->id, 'Categoría modificada', $oldCategoriaName ?? (string)$viejo, $nuevaCat->nombre ?? (string)$nuevo);
                        } else {
                            $this->registrarLog($producto->id, "Campo '{$campo}' modificado", (string)$viejo, (string)$nuevo);
                        }
                    }
                }
            }
            
            // Guardar variantes
            if ($producto->tiene_variantes && $request->has('variantes')) {
                // Snapshot variantes para auditoría
                $oldVariantesCount = $producto->variantes()->count();
                $oldVariantesList = $producto->variantes()->get()->map(fn($v) => $v->sku . ' (' . trim(($v->talla ?? '') . '/' . ($v->color ?? ''), '/') . ')')->implode(', ');

                if ($request->id) {
                    $variantesIds = $producto->variantes()->pluck('id');
                    StockProducto::whereIn('variante_producto_id', $variantesIds)
                                 ->where('producto_id', $producto->id)
                                 ->delete();
                    $producto->variantes()->delete();
                }
                
                foreach ($request->variantes as $index => $varianteData) {
                    if (!empty($varianteData['talla']) || !empty($varianteData['color']) || !empty($varianteData['sku'])) {
                        $sku = $varianteData['sku'];
                        if (empty($sku)) {
                            $sku = $producto->referencia;
                            if (!empty($varianteData['talla'])) {
                                $sku .= '-' . strtoupper(str_replace(' ', '', $varianteData['talla']));
                            }
                            if (!empty($varianteData['color'])) {
                                $sku .= '-' . strtoupper(str_replace(' ', '', $varianteData['color']));
                            }
                            if (empty($varianteData['talla']) && empty($varianteData['color'])) {
                                $count = $producto->variantes()->count() + 1;
                                $sku .= '-VAR' . $count;
                            }
                        }
                        
                        $variante = $producto->variantes()->create([
                            'talla' => $varianteData['talla'],
                            'color' => $varianteData['color'],
                            'sku' => $sku,
                            'activo' => true
                        ]);
                        
                        if ($producto->controlar_stock) {
                            $stockInicial = $varianteData['stock_inicial'] ?? 0;
                            $stock = StockProducto::create([
                                'producto_id' => $producto->id,
                                'variante_producto_id' => $variante->id,
                                'cantidad_disponible' => $stockInicial,
                                'cantidad_reservada' => 0,
                                'stock_minimo' => $varianteData['stock_minimo'] ?? 0,
                                'stock_maximo' => $varianteData['stock_maximo'] ?? null,
                                'ubicacion' => $varianteData['ubicacion'] ?? null,
                                'alerta_stock_bajo' => true
                            ]);
                            
                            if ($stockInicial > 0) {
                                MovimientoStock::create([
                                    'producto_id' => $producto->id,
                                    'variante_producto_id' => $variante->id,
                                    'tipo_movimiento' => 'entrada',
                                    'cantidad' => $stockInicial,
                                    'stock_anterior' => 0,
                                    'stock_nuevo' => $stockInicial,
                                    'origen' => 'ajuste_inventario',
                                    'motivo' => 'Stock inicial',
                                    'usuario_id' => auth()->id() ?? 1
                                ]);
                            }
                        }
                    }
                }

                // Log de variantes
                $newVariantesCount = $producto->variantes()->count();
                $newVariantesList = $producto->variantes()->get()->map(fn($v) => $v->sku . ' (' . trim(($v->talla ?? '') . '/' . ($v->color ?? ''), '/') . ')')->implode(', ');
                if ($oldVariantesCount !== $newVariantesCount || $oldVariantesList !== $newVariantesList) {
                    $this->registrarLog($producto->id, "Variantes actualizadas ({$oldVariantesCount} → {$newVariantesCount})", $oldVariantesList ?: null, $newVariantesList ?: null);
                }
            } else if ($producto->controlar_stock && !$producto->tiene_variantes) {
                $stockInicial = $request->input('stock_inicial', 0);
                
                $stock = StockProducto::firstOrNew([
                    'producto_id' => $producto->id,
                    'variante_producto_id' => null
                ]);
                
                if (!$stock->exists || ($esNuevo && $stockInicial > 0)) {
                    $stockAnterior = $stock->cantidad_disponible ?? 0;
                    
                    $stock->fill([
                        'cantidad_disponible' => $esNuevo ? $stockInicial : $stock->cantidad_disponible,
                        'cantidad_reservada' => $stock->cantidad_reservada ?? 0,
                        'stock_minimo' => $request->input('stock_minimo', 0),
                        'stock_maximo' => $request->input('stock_maximo'),
                        'ubicacion' => $request->input('ubicacion_stock'),
                        'alerta_stock_bajo' => true
                    ])->save();
                    
                    if ($esNuevo && $stockInicial > 0) {
                        MovimientoStock::create([
                            'producto_id' => $producto->id,
                            'variante_producto_id' => null,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $stockInicial,
                            'stock_anterior' => 0,
                            'stock_nuevo' => $stockInicial,
                            'origen' => 'ajuste_inventario',
                            'motivo' => 'Stock inicial',
                            'usuario_id' => auth()->id() ?? 1
                        ]);
                    }
                } else {
                    $stock->update([
                        'stock_minimo' => $request->input('stock_minimo', 0),
                        'stock_maximo' => $request->input('stock_maximo'),
                        'ubicacion' => $request->input('ubicacion_stock')
                    ]);
                }
            }
            
            // Guardar imágenes nuevas
            if ($request->hasFile('imagenes')) {
                $directory = public_path('imagenes/productos/' . $producto->id);
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                
                $orden = $producto->imagenes()->max('orden') ?? 0;
                $imagenPrincipalNueva = $request->input('imagen_principal_nueva', 0);
                
                foreach ($request->file('imagenes') as $index => $imagen) {
                    $filename = time() . '_' . uniqid() . '_' . $imagen->getClientOriginalName();
                    $imagen->move($directory, $filename);
                    $path = 'imagenes/productos/' . $producto->id . '/' . $filename;
                    
                    $orden++;
                    $producto->imagenes()->create([
                        'ruta_imagen' => $path,
                        'texto_alternativo' => $producto->nombre,
                        'es_principal' => $index == $imagenPrincipalNueva,
                        'orden' => $orden
                    ]);

                    $this->registrarLog($producto->id, 'Imagen agregada: ' . $imagen->getClientOriginalName(), null, $path);
                }
            }
            
            // Actualizar imagen principal existente
            if ($request->has('imagen_principal_existente')) {
                $producto->imagenes()->update(['es_principal' => false]);
                $producto->imagenes()
                        ->where('id', $request->imagen_principal_existente)
                        ->update(['es_principal' => true]);

                $imgPrincipal = ImagenProducto::find($request->imagen_principal_existente);
                $this->registrarLog($producto->id, 'Imagen principal cambiada', null, $imgPrincipal ? basename($imgPrincipal->ruta_imagen) : 'ID: ' . $request->imagen_principal_existente);
            }
            
            // Eliminar imágenes marcadas
            if ($request->has('eliminar_imagenes')) {
                foreach ($request->eliminar_imagenes as $imagenId) {
                    $imagen = ImagenProducto::find($imagenId);
                    if ($imagen && $imagen->producto_id == $producto->id) {
                        $rutaImagen = $imagen->ruta_imagen;
                        $filePath = public_path($rutaImagen);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                        $imagen->delete();

                        $this->registrarLog($producto->id, 'Imagen eliminada: ' . basename($rutaImagen), $rutaImagen, null);
                    }
                }
            }
            
            // Guardar precios
            if ($request->has('precios')) {
                $oldPrecios = $producto->precios()->pluck('precio', 'lista_precio_id');

                foreach ($request->precios as $listaId => $precio) {
                    $precioAnterior = $oldPrecios->get($listaId);
                    $listaNombre = ListaPrecio::find($listaId)?->nombre ?? "Lista #{$listaId}";

                    if (!empty($precio)) {
                        $producto->precios()->updateOrCreate(
                            ['lista_precio_id' => $listaId],
                            ['precio' => $precio, 'activo' => true]
                        );

                        if ($precioAnterior === null) {
                            $this->registrarLog($producto->id, "Precio agregado ({$listaNombre})", null, (string)$precio);
                        } elseif ((float)$precioAnterior !== (float)$precio) {
                            $this->registrarLog($producto->id, "Precio modificado ({$listaNombre})", (string)$precioAnterior, (string)$precio);
                        }
                    } else {
                        $producto->precios()
                                ->where('lista_precio_id', $listaId)
                                ->delete();

                        if ($precioAnterior !== null) {
                            $this->registrarLog($producto->id, "Precio eliminado ({$listaNombre})", (string)$precioAnterior, null);
                        }
                    }
                }
            }

            // Guardar/Actualizar/Eliminar Características
            $oldCaractImages = $producto->caracteristicas()->pluck('imagen')->filter()->toArray();
            $oldCaractTitulos = $producto->caracteristicas()->pluck('titulo')->filter()->toArray();

            if ($request->has('caracteristicas')) {
                $producto->caracteristicas()->delete();

                $orden = 0;
                $newImages = [];
                $caractDir = public_path("imagenes/productos/{$producto->id}/caracteristicas");

                foreach ($request->caracteristicas as $index => $caracteristicaData) {
                    if (empty($caracteristicaData['titulo']) && empty($caracteristicaData['descripcion'])) {
                        continue;
                    }
                    $orden++;

                    $imagenPath = $caracteristicaData['imagen_actual'] ?? null;

                    // Nueva imagen subida
                    if ($request->hasFile("caracteristicas.{$index}.imagen")) {
                        if ($imagenPath && File::exists(public_path($imagenPath))) {
                            File::delete(public_path($imagenPath));
                        }
                        if (!File::exists($caractDir)) {
                            File::makeDirectory($caractDir, 0755, true);
                        }
                        $file = $request->file("caracteristicas.{$index}.imagen");
                        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $file->move($caractDir, $filename);
                        $imagenPath = "imagenes/productos/{$producto->id}/caracteristicas/{$filename}";
                    }

                    // Eliminar imagen si se marcó checkbox
                    if (!empty($caracteristicaData['eliminar_imagen'])) {
                        if ($imagenPath && File::exists(public_path($imagenPath))) {
                            File::delete(public_path($imagenPath));
                        }
                        $imagenPath = null;
                    }

                    if ($imagenPath) {
                        $newImages[] = $imagenPath;
                    }

                    $producto->caracteristicas()->create([
                        'icono' => $caracteristicaData['icono'] ?? 'bi-star',
                        'imagen' => $imagenPath,
                        'titulo' => $caracteristicaData['titulo'],
                        'descripcion' => $caracteristicaData['descripcion'],
                        'orden' => $orden
                    ]);
                }

                // Limpiar imágenes huérfanas
                foreach ($oldCaractImages as $oldImg) {
                    if (!in_array($oldImg, $newImages) && File::exists(public_path($oldImg))) {
                        File::delete(public_path($oldImg));
                    }
                }

                // Log de características
                $newCaractTitulos = $producto->caracteristicas()->pluck('titulo')->filter()->toArray();
                $eliminadas = array_diff($oldCaractTitulos, $newCaractTitulos);
                $agregadas = array_diff($newCaractTitulos, $oldCaractTitulos);
                foreach ($eliminadas as $titulo) {
                    $this->registrarLog($producto->id, 'Característica eliminada', $titulo, null);
                }
                foreach ($agregadas as $titulo) {
                    $this->registrarLog($producto->id, 'Característica agregada', null, $titulo);
                }
            } else {
                // Eliminar todas las características e imágenes
                foreach ($oldCaractImages as $oldImg) {
                    if (File::exists(public_path($oldImg))) {
                        File::delete(public_path($oldImg));
                    }
                }
                if (!empty($oldCaractTitulos)) {
                    $this->registrarLog($producto->id, 'Todas las características eliminadas', implode(', ', $oldCaractTitulos), null);
                }
                $producto->caracteristicas()->delete();
            }

            DB::commit();
            
            return redirect()->route('productos')
                           ->with('success', $request->id ? 'Producto actualizado correctamente.' : 'Producto creado correctamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Error al guardar el producto: ' . $e->getMessage());
        }
    }

public function actualizarPreciosExcel(Request $request)
{
    // 1. Validación
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls,csv|max:10240'
    ], [
        'archivo.required' => 'Debe seleccionar un archivo',
        'archivo.mimes'    => 'El archivo debe ser Excel (.xlsx, .xls) o CSV',
        'archivo.max'      => 'El archivo no debe superar los 10MB'
    ]);

    DB::beginTransaction();

    try {
        // 2. Obtener archivo y nombres
        $archivo        = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreArchivo  = time() . '_' . $nombreOriginal;

        // 3. Directorio en public
        $rutaPublic = public_path('uploads/actualizaciones_precios');
        if (! File::exists($rutaPublic)) {
            File::makeDirectory($rutaPublic, 0755, true);
        }

        // 4. Mover archivo y construir ruta relativa
        $archivo->move($rutaPublic, $nombreArchivo);
        $rutaArchivo = 'uploads/actualizaciones_precios/' . $nombreArchivo;

        // 5. Registrar en base de datos
        $actualizacion = ActualizacionPrecio::create([
            'usuario_id'               => auth()->id(),
            'estado'                   => 'procesando',
            'nombre_archivo'           => $nombreOriginal,
            'ruta_archivo'             => $rutaArchivo,
            'total_filas'              => 0,
            'actualizaciones_exitosas' => 0,
            'actualizaciones_fallidas' => 0,
            'errores'                  => [],
            'detalles_procesados'      => []
        ]);

        // 6. Log de inicio
        Log::info('Iniciando actualización de precios', [
            'usuario'          => auth()->user()->name,
            'archivo'          => $nombreArchivo,
            'actualizacion_id' => $actualizacion->id,
        ]);

        // 7. Importar usando la ruta en public
        $pathImport = public_path($rutaArchivo);
        Excel::import(new PreciosImport($actualizacion), $pathImport);

        DB::commit();

        // 8. Preparar mensaje de resultado
        $actualizacion->refresh();
        if ($actualizacion->actualizaciones_fallidas === 0 && $actualizacion->actualizaciones_exitosas > 0) {
            return back()->with('success', "Actualización completada: {$actualizacion->actualizaciones_exitosas} productos actualizados.");
        } elseif ($actualizacion->actualizaciones_exitosas > 0) {
            return back()->with('warning', "Actualización parcial: {$actualizacion->actualizaciones_exitosas} éxitosas, {$actualizacion->actualizaciones_fallidas} con errores.");
        } else {
            return back()->with('error', 'No se pudo actualizar ningún producto. Revisa el reporte de errores.');
        }
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Error en actualización de precios', [
            'mensaje' => $e->getMessage()
        ]);
        return back()->with('error', 'Ocurrió un error procesando el archivo.');
    }
}


    // Métodos AJAX para los modales
    public function variantesAjax(Producto $producto)
    {
        if ($producto->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }
        $variantes = $producto->variantes()->get();
        
        $html = '<div class="table-responsive">';
        
        if ($variantes->isEmpty()) {
            $html .= '<p class="text-center text-muted">Este producto no tiene variantes configuradas.</p>';
        } else {
            $html .= '<table class="table table-striped">';
            $html .= '<thead><tr><th>SKU</th><th>Talla</th><th>Color</th><th>Estado</th></tr></thead>';
            $html .= '<tbody>';
            
            foreach ($variantes as $variante) {
                $html .= '<tr>';
                $html .= '<td><code>' . $variante->sku . '</code></td>';
                $html .= '<td>' . ($variante->talla ?: '-') . '</td>';
                $html .= '<td>' . ($variante->color ?: '-') . '</td>';
                $html .= '<td>' . ($variante->activo ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-secondary">Inactiva</span>') . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        $html .= '</div>';
        
        return response($html);
    }

    public function imagenesAjax(Producto $producto)
    {
        if ($producto->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }
        $imagenes = $producto->imagenes()->orderBy('orden')->get();
        
        $html = '<div class="row">';
        
        if ($imagenes->isEmpty()) {
            $html .= '<p class="text-center text-muted">Este producto no tiene imágenes.</p>';
        } else {
            foreach ($imagenes as $imagen) {
                $html .= '<div class="col-md-3 mb-3">';
                $html .= '<div class="card">';
                $html .= '<img src="' . asset($imagen->ruta_imagen) . '" class="card-img-top" style="height: 200px; object-fit: cover;">';
                $html .= '<div class="card-body p-2 text-center">';
                
                if ($imagen->es_principal) {
                    $html .= '<span class="badge bg-success">Principal</span>';
                }
                
                $html .= '</div></div></div>';
            }
        }
        
        $html .= '</div>';
        
        return response($html);
    }

    public function preciosAjax(Producto $producto)
    {
        if ($producto->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }
        $precios = $producto->precios()->with('listaPrecio')->get();
        
        $html = '<div class="table-responsive">';
        
        if ($precios->isEmpty()) {
            $html .= '<p class="text-center text-muted">Este producto no tiene precios configurados.</p>';
        } else {
            $html .= '<table class="table table-striped">';
            $html .= '<thead><tr><th>Lista de Precios</th><th>Código</th><th>Precio</th><th>Estado</th></tr></thead>';
            $html .= '<tbody>';
            
            foreach ($precios as $precio) {
                $html .= '<tr>';
                $html .= '<td>' . $precio->listaPrecio->nombre . '</td>';
                $html .= '<td><code>' . $precio->listaPrecio->codigo . '</code></td>';
                $html .= '<td>$' . number_format($precio->precio, 2) . '</td>';
                $html .= '<td>' . ($precio->activo ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>') . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        $html .= '</div>';
        
        return response($html);
    }

    // Método AJAX para ver stock (NUEVO)
    public function stockAjax(Producto $producto)
    {
        if ($producto->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }
        $stocks = $producto->stock()->with('variante')->get();
        
        $html = '<div class="table-responsive">';
        
        if ($stocks->isEmpty()) {
            $html .= '<p class="text-center text-muted">Este producto no tiene stock configurado.</p>';
        } else {
            $html .= '<table class="table table-striped">';
            $html .= '<thead><tr><th>Producto/Variante</th><th>Disponible</th><th>Reservado</th><th>Stock Real</th><th>Mín/Máx</th><th>Ubicación</th></tr></thead>';
            $html .= '<tbody>';
            
            foreach ($stocks as $stock) {
                $badge = 'success';
                if ($stock->stock_real <= 0) {
                    $badge = 'danger';
                } elseif ($stock->stock_bajo) {
                    $badge = 'warning';
                }
                
                $html .= '<tr>';
                $html .= '<td>' . ($stock->variante ? $stock->variante->nombre_variante : 'Principal') . '</td>';
                $html .= '<td>' . $stock->cantidad_disponible . '</td>';
                $html .= '<td>' . $stock->cantidad_reservada . '</td>';
                $html .= '<td><span class="badge bg-' . $badge . '">' . $stock->stock_real . '</span></td>';
                $html .= '<td>' . $stock->stock_minimo . '/' . ($stock->stock_maximo ?: '∞') . '</td>';
                $html .= '<td>' . ($stock->ubicacion ?: '-') . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        $html .= '</div>';

        return response($html);
    }

    /**
     * Obtener logs de auditoría de un producto vía AJAX
     */
    public function logsAjax(Producto $producto)
    {
        if ($producto->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $logs = \App\Models\Log::where('tabla', 'productos')
            ->where('id_tabla', $producto->id)
            ->with('usuario')
            ->orderByDesc('created_at')
            ->get();

        $html = '<div class="table-responsive" style="max-height:400px; overflow-y:auto;">';

        if ($logs->isEmpty()) {
            $html .= '<div class="text-center py-4">';
            $html .= '<i class="bi bi-clock-history" style="font-size:2rem; color:#6c757d;"></i>';
            $html .= '<p class="text-muted mt-2">No hay registros de cambios para este producto.</p>';
            $html .= '</div>';
        } else {
            $html .= '<table class="table table-striped table-sm">';
            $html .= '<thead class="table-dark"><tr>';
            $html .= '<th>Fecha</th><th>Usuario</th><th>Detalle</th><th>Valor Anterior</th><th>Valor Nuevo</th>';
            $html .= '</tr></thead><tbody>';

            foreach ($logs as $log) {
                $html .= '<tr>';
                $html .= '<td><small>' . $log->created_at->format('d/m/Y H:i:s') . '</small></td>';
                $html .= '<td><small>' . e($log->usuario->name ?? 'Sistema') . '</small></td>';
                $html .= '<td>' . e($log->detalle) . '</td>';
                $html .= '<td><small class="text-danger">' . e($log->valor_viejo ?? '-') . '</small></td>';
                $html .= '<td><small class="text-success">' . e($log->valor_nuevo ?? '-') . '</small></td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table>';
        }

        $html .= '</div>';

        return response($html);
    }

    /**
     * Eliminar producto lógicamente
     */
    public function eliminar(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return response()->json(['error' => 'No tiene empresa registrada'], 403);
        }

        $request->validate([
            'id' => 'required|exists:productos,id'
        ]);

        try {
            $producto = Producto::findOrFail($request->id);

            // Verificar que el producto pertenezca a la empresa
            if ($producto->empresa_id !== $empresa->id) {
                return response()->json(['error' => 'No tiene permisos para eliminar este producto'], 403);
            }

            // Realizar eliminación lógica
            $producto->update(['eliminado' => true]);

            $this->registrarLog($producto->id, 'Producto eliminado: ' . $producto->nombre, 'activo', 'eliminado');

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente. Los registros de compras se han preservado.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar un log de auditoría para productos
     */
    private function registrarLog(int $productoId, string $detalle, ?string $valorViejo = null, ?string $valorNuevo = null): void
    {
        \App\Models\Log::create([
            'id_tabla'    => $productoId,
            'tabla'       => 'productos',
            'detalle'     => $detalle,
            'tipo_log'    => '1',
            'valor_viejo' => $valorViejo ? \Illuminate\Support\Str::limit($valorViejo, 250, '') : null,
            'valor_nuevo' => $valorNuevo ? \Illuminate\Support\Str::limit($valorNuevo, 250, '') : null,
            'id_usuario'  => auth()->id(),
            'estado'      => true,
        ]);
    }
}