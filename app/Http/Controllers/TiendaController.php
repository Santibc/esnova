<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\PrecioProducto;
use App\Models\ListaPrecio;
use App\Models\Carrito;
use App\Models\Compra;
use App\Models\ItemCompra;
use App\Models\TransaccionPago;
use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\ConfiguracionPasarela;
use App\Models\CalificacionProducto;
use App\Models\ReaccionCalificacion;
use App\Models\BlogPost;
use App\Models\BlogCategoria;
use App\Models\BlogConfiguracion;
use App\Services\WompiService;
use App\Services\Templates\TemplateResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TiendaController extends Controller
{
    protected TemplateResolver $templateResolver;

    public function __construct(TemplateResolver $templateResolver)
    {
        $this->templateResolver = $templateResolver;
    }

    /**
     * Obtener la empresa principal (single-tenant)
     * En modo single-tenant siempre hay una sola empresa activa
     */
    private function getEmpresa()
    {
        return Empresa::where('activo', true)
            ->orderBy('id')
            ->firstOrFail();
    }

    /**
     * Mostrar la tienda de una empresa
     * NOTA: El parámetro $slug se mantiene por compatibilidad pero no se usa
     */
    public function show($slug = null, Request $request = null)
    {
        // Si $request es null (llamado desde closure de ruta raíz), obtener del helper global
        if ($request === null) {
            $request = request();
        }

        $empresa = $this->getEmpresa();
        $empresa->load(['carruselImagenesActivas', 'bannerSlidesActivos']);

        // Obtener primera lista de precios activa
        $listaPrecio = ListaPrecio::activas()->first();
        
        if (!$listaPrecio) {
            abort(404, 'No hay listas de precios configuradas');
        }

        // Obtener categorías con productos
        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->whereHas('productos', function($q) {
                $q->where('activo', true);
            })
            ->withCount([
                'productos as productos_count' => function ($q) use ($empresa) {
                    $q->where('activo', true)
                    ->where('empresa_id', $empresa->id); // quítalo si Producto no tiene empresa_id
                }
            ])
            ->orderBy('orden')
            ->get();

        // Query base de productos con calificaciones
        $query = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->noEliminados()
            ->with(['imagenPrincipal', 'categoria', 'stockPrincipal'])
            ->withCount(['calificaciones as total_calificaciones' => function($q) {
                $q->where('aprobada', true);
            }])
            ->withAvg(['calificaciones as promedio_calificaciones' => function($q) {
                $q->where('aprobada', true);
            }], 'estrellas');

        // Filtros
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'precio_asc':
                    $query->select('productos.*')
                        ->leftJoin('precios_productos', function($join) use ($listaPrecio) {
                            $join->on('productos.id', '=', 'precios_productos.producto_id')
                                 ->where('precios_productos.lista_precio_id', $listaPrecio->id)
                                 ->where('precios_productos.activo', true);
                        })
                        ->orderBy('precios_productos.precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->select('productos.*')
                        ->leftJoin('precios_productos', function($join) use ($listaPrecio) {
                            $join->on('productos.id', '=', 'precios_productos.producto_id')
                                 ->where('precios_productos.lista_precio_id', $listaPrecio->id)
                                 ->where('precios_productos.activo', true);
                        })
                        ->orderBy('precios_productos.precio', 'desc');
                    break;
                case 'nombre':
                    $query->orderBy('nombre');
                    break;
                default:
                    $query->orderBy('orden')->latest();
            }
        } else {
            $query->orderBy('orden')->latest();
        }

        // Filtro de stock
        if ($request->filled('stock') && $request->stock == '1') {
            $query->conStock();
        }

        $productos = $query->paginate(12)->withQueryString();

        // Cargar precios para la lista seleccionada
        foreach ($productos as $producto) {
            $producto->precio_actual = $producto->getPrecioPorLista($listaPrecio->id);
        }

        // Obtener carrito
        $carrito = $this->obtenerCarrito($empresa->id);

        // Productos destacados (más vendidos o aleatorios)
        // TODO: Implementar lógica de más vendidos cuando se configure la relación itemsCompra
        $productosDestacados = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->noEliminados()
            ->with(['imagenPrincipal', 'categoria', 'stockPrincipal'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Cargar precios para productos destacados
        foreach ($productosDestacados as $producto) {
            $producto->precio_actual = $producto->precios()
                ->where('lista_precio_id', $listaPrecio->id)
                ->where('activo', true)
                ->first();
        }

        // Productos nuevos (últimos 6 productos creados)
        $productosNuevos = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->noEliminados()
            ->with(['imagenPrincipal', 'categoria', 'stockPrincipal'])
            ->latest('created_at')
            ->take(6)
            ->get();

        // Cargar precios para productos nuevos
        foreach ($productosNuevos as $producto) {
            $producto->precio_actual = $producto->precios()
                ->where('lista_precio_id', $listaPrecio->id)
                ->where('activo', true)
                ->first();
        }

        // Producto aleatorio para showcase
        $productoAleatorio = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->noEliminados()
            ->with(['imagenPrincipal', 'categoria', 'stockPrincipal', 'variantes'])
            ->inRandomOrder()
            ->first();

        if ($productoAleatorio) {
            $productoAleatorio->precio_actual = $productoAleatorio->precios()
                ->where('lista_precio_id', $listaPrecio->id)
                ->where('activo', true)
                ->first();
        }

        // Obtener descuentos activos de la empresa
        $descuentosActivos = \App\Models\Descuento::porEmpresa($empresa->id)
            ->activos()
            ->vigentes()
            ->disponibles()
            ->get();

        // Productos con descuentos (para la sección de ofertas)
        $productosConDescuento = collect();

        foreach ($descuentosActivos as $descuento) {
            if ($descuento->aplica_a === 'producto' && !empty($descuento->productos_aplicables)) {
                $prodDescuento = Producto::where('empresa_id', $empresa->id)
                    ->where('activo', true)
                    ->noEliminados()
                    ->whereIn('id', $descuento->productos_aplicables)
                    ->with(['imagenPrincipal', 'categoria'])
                    ->get();

                foreach ($prodDescuento as $prod) {
                    $prod->precio_actual = $prod->precios()
                        ->where('lista_precio_id', $listaPrecio->id)
                        ->where('activo', true)
                        ->first();
                    $prod->descuento_info = $descuento;
                    $productosConDescuento->push($prod);
                }
            } elseif ($descuento->aplica_a === 'categoria' && !empty($descuento->categorias_aplicables)) {
                $prodDescuento = Producto::where('empresa_id', $empresa->id)
                    ->where('activo', true)
                    ->noEliminados()
                    ->whereIn('categoria_id', $descuento->categorias_aplicables)
                    ->with(['imagenPrincipal', 'categoria'])
                    ->take(5)
                    ->get();

                foreach ($prodDescuento as $prod) {
                    $prod->precio_actual = $prod->precios()
                        ->where('lista_precio_id', $listaPrecio->id)
                        ->where('activo', true)
                        ->first();
                    $prod->descuento_info = $descuento;
                    $productosConDescuento->push($prod);
                }
            }
        }

        // Limitar a 6 productos únicos
        $productosConDescuento = $productosConDescuento->unique('id')->take(6);

        // Resolver estrategia de template
        $strategy = $this->templateResolver->resolveForEmpresa($empresa);

        // Preparar datos específicos del template
        $data = $strategy->prepareData(compact(
            'empresa',
            'productos',
            'categorias',
            'listaPrecio',
            'carrito',
            'productosDestacados',
            'productosNuevos',
            'productoAleatorio',
            'productosConDescuento',
            'descuentosActivos'
        ));

        // Renderizar vista del template
        return view($strategy->getViewIndex(), $data);
    }

    /**
     * Mostrar detalle de producto
     */
    public function producto($slug)
    {
        $empresa = $this->getEmpresa();

        // Buscar producto por slug generado desde el nombre
        $producto = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->noEliminados()
            ->with(['imagenes', 'categoria', 'variantes' => function($q) {
                $q->where('activo', true);
            }])
            ->get()
            ->first(fn($p) => Str::slug($p->nombre) === $slug);

        if (!$producto) {
            // Verificar si el producto existe pero fue eliminado → 410 Gone para SEO
            $productoEliminado = Producto::where('empresa_id', $empresa->id)
                ->where(function ($q) {
                    $q->where('eliminado', true)->orWhere('activo', false);
                })
                ->get()
                ->first(fn($p) => Str::slug($p->nombre) === $slug);

            abort($productoEliminado ? 410 : 404);
        }

        // Obtener primera lista de precios
        $listaPrecio = ListaPrecio::activas()->first();
        $producto->precio_actual = $producto->getPrecioPorLista($listaPrecio->id);

        // Si tiene variantes, cargar stock de cada una
        if ($producto->tiene_variantes) {
            $producto->load(['variantes.stock']);
        } else {
            $producto->load('stockPrincipal');
        }

        // Obtener categorías con productos (para el menú)
        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->whereHas('productos', function($q) {
                $q->where('activo', true);
            })
            ->withCount([
                'productos as productos_count' => function ($q) use ($empresa) {
                    $q->where('activo', true)
                    ->where('empresa_id', $empresa->id);
                }
            ])
            ->orderBy('orden')
            ->get();

        // Productos relacionados con calificaciones
        $relacionados = Producto::where('empresa_id', $empresa->id)
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('activo', true)
            ->with('imagenPrincipal')
            ->withCount(['calificaciones as total_calificaciones' => function($q) {
                $q->where('aprobada', true);
            }])
            ->withAvg(['calificaciones as promedio_calificaciones' => function($q) {
                $q->where('aprobada', true);
            }], 'estrellas')
            ->limit(4)
            ->get();

        foreach ($relacionados as $prod) {
            $prod->precio_actual = $prod->getPrecioPorLista($listaPrecio->id);
        }

        $carrito = $this->obtenerCarrito($empresa->id);

        // Obtener descuentos activos de la empresa
        $descuentosActivos = \App\Models\Descuento::porEmpresa($empresa->id)
            ->activos()
            ->vigentes()
            ->disponibles()
            ->get();

        // Cargar calificaciones del producto (solo principales, con respuestas y reacciones)
        $calificaciones = CalificacionProducto::where('producto_id', $producto->id)
            ->whereNull('parent_id') // Solo principales
            ->aprobadas()
            ->with([
                'user',
                'respuestasAprobadas' => function($q) {
                    $q->with('user')->orderBy('created_at', 'asc');
                },
                'reacciones'
            ])
            ->withCount('respuestasAprobadas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener estadísticas de calificaciones
        $promedioCalificacion = CalificacionProducto::getPromedioEstrellas($producto->id);
        $totalCalificaciones = CalificacionProducto::getTotalCalificaciones($producto->id);
        $distribucionCalificaciones = CalificacionProducto::getDistribucion($producto->id);

        // Verificar si el usuario autenticado puede calificar este producto
        $puedeCalificar = false;
        $itemCompraParaCalificar = null;

        if (Auth::check()) {
            $user = Auth::user();

            // Buscar compras del usuario que contengan este producto y que se puedan calificar
            $comprasDelUsuario = Compra::where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email_cliente', $user->email);
                })
                ->whereIn('estado', ['pagada', 'enviada', 'entregada'])
                ->with(['items' => function($q) use ($producto) {
                    $q->where('producto_id', $producto->id);
                }])
                ->get();

            // Buscar un item que aún no haya sido calificado por el usuario
            foreach ($comprasDelUsuario as $compra) {
                foreach ($compra->items as $item) {
                    $yaCalificado = CalificacionProducto::where('user_id', $user->id)
                        ->where('item_compra_id', $item->id)
                        ->exists();

                    if (!$yaCalificado) {
                        $puedeCalificar = true;
                        $itemCompraParaCalificar = $item->id;
                        break 2;
                    }
                }
            }
        }

        $strategy = $this->templateResolver->resolveForEmpresa($empresa);

        $data = $strategy->prepareData(compact(
            'empresa',
            'producto',
            'relacionados',
            'categorias',
            'listaPrecio',
            'carrito',
            'descuentosActivos',
            'calificaciones',
            'promedioCalificacion',
            'totalCalificaciones',
            'distribucionCalificaciones',
            'puedeCalificar',
            'itemCompraParaCalificar'
        ));

        return view($strategy->getViewProducto(), $data);
    }

    /**
     * Ver carrito
     */
    public function verCarrito()
    {
        $empresa = $this->getEmpresa();

        $carrito = $this->obtenerCarrito($empresa->id);
        $listaPrecio = ListaPrecio::activas()->first();

        return view('tienda.carrito', compact('empresa', 'carrito', 'listaPrecio'));
    }

    /**
     * Agregar producto al carrito
     */
    public function agregarCarrito(Request $request)
    {
        $empresa = $this->getEmpresa();

        // LÍMITE DE TRANSACCIONES DESHABILITADO - Single-tenant
        // $transaccionesMes = Compra::where('empresa_id', $empresa->id)
        //     ->where('estado', 'pagada')
        //     ->whereMonth('created_at', now()->month)
        //     ->whereYear('created_at', now()->year)
        //     ->count();
        //
        // if ($empresa->planMembresia->limite_transacciones &&
        //     $transaccionesMes >= $empresa->planMembresia->limite_transacciones) {
        //     return response()->json([
        //         'error' => 'La tienda ha alcanzado el límite de ventas mensuales. Por favor contacta al vendedor.'
        //     ], 403);
        // }

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'variante_id' => 'nullable|exists:variantes_productos,id'
        ]);
        $producto = Producto::findOrFail($request->producto_id);
        
        // Verificar que el producto pertenece a la empresa
        if ($producto->empresa_id != $empresa->id) {
            return response()->json(['error' => 'Producto no válido'], 400);
        }

        // Verificar stock
        if (!$producto->hayStock($request->cantidad, $request->variante_id)) {
            return response()->json(['error' => 'Stock insuficiente'], 400);
        }

        // Obtener precio
        $listaPrecio = ListaPrecio::activas()->first();
        $precio = $producto->getPrecioPorLista($listaPrecio->id);

        if (!$precio) {
            return response()->json(['error' => 'Precio no configurado'], 400);
        }

        $carrito = $this->obtenerCarrito($empresa->id);
        $carrito->agregarItem(
            $request->producto_id,
            $request->cantidad,
            $request->variante_id,
            $precio
        );

        return response()->json([
            'success' => true,
            'total_items' => $carrito->total_items,
            'subtotal' => $carrito->subtotal
        ]);
    }

    /**
     * Actualizar cantidad en carrito
     */
    public function actualizarCarrito(Request $request)
    {
        $empresa = $this->getEmpresa();
        $request->validate([
            'key' => 'required|string',
            'cantidad' => 'required|integer|min:0'
        ]);

        $carrito = $this->obtenerCarrito($empresa->id);

        if ($request->cantidad == 0) {
            $carrito->quitarItem($request->key);
        } else {
            // Verificar stock antes de actualizar
            $item = $carrito->items[$request->key] ?? null;
            if ($item) {
                $producto = Producto::find($item['producto_id']);
                if (!$producto->hayStock($request->cantidad, $item['variante_id'] ?? null)) {
                    return response()->json(['error' => 'Stock insuficiente'], 400);
                }
            }
            
            $carrito->actualizarCantidad($request->key, $request->cantidad);
        }

        return response()->json([
            'success' => true,
            'total_items' => $carrito->total_items,
            'subtotal' => $carrito->subtotal
        ]);
    }

    /**
     * Quitar item del carrito
     */
    public function quitarDelCarrito(Request $request)
    {
        $empresa = $this->getEmpresa();
        $request->validate([
            'key' => 'required|string'
        ]);
        $carrito = $this->obtenerCarrito($empresa->id);
        $carrito->quitarItem($request->key);

        return response()->json([
            'success' => true,
            'total_items' => $carrito->total_items,
            'subtotal' => $carrito->subtotal
        ]);
    }

    /**
     * Mostrar checkout
     */
    public function checkout()
    {
        $empresa = $this->getEmpresa();

        $carrito = $this->obtenerCarrito($empresa->id);

        if (empty($carrito->items)) {
            return redirect()->route('tienda.carrito')
                ->with('error', 'El carrito está vacío');
        }

        // Validar monto mínimo de compra
        $montoMinimo = $empresa->monto_minimo_compra ?? 0;
        $totalCarrito = $carrito->total ?? $carrito->subtotal;
        if ($montoMinimo > 0 && $totalCarrito < $montoMinimo) {
            return redirect()->route('tienda.carrito')
                ->with('error', 'El monto mínimo de compra es $' . number_format($montoMinimo, 0, ',', '.') . '. Tu carrito actual es $' . number_format($totalCarrito, 0, ',', '.') . '.');
        }

        // Validar stock antes de permitir checkout
        $stockErrors = [];
        foreach ($carrito->items as $key => $item) {
            $producto = Producto::find($item['producto_id']);
            if (!$producto) continue;
            
            $varianteId = $item['variante_id'] ?? null;
            $cantidad = $item['cantidad'];
            
            $stockInfo = $producto->getStockInfo($varianteId);
            $hayStock = $producto->hayStock($cantidad, $varianteId);
            
            if (!$hayStock && $stockInfo['stock_limitado']) {
                $stockErrors[] = [
                    'producto' => $item['nombre'],
                    'cantidad_solicitada' => $cantidad,
                    'stock_disponible' => $stockInfo['stock_disponible'],
                    'variante' => isset($item['info_variante']) ? $item['info_variante'] : null
                ];
            }
        }

        if (!empty($stockErrors)) {
            $errorMessage = 'Algunos productos en tu carrito ya no tienen stock disponible:<br>';
            foreach ($stockErrors as $error) {
                $varianteInfo = '';
                if ($error['variante']) {
                    $varianteInfo = ' (' . implode(', ', array_filter($error['variante'])) . ')';
                }
                $errorMessage .= "• {$error['producto']}{$varianteInfo}: Stock disponible {$error['stock_disponible']}, solicitaste {$error['cantidad_solicitada']}<br>";
            }
            $errorMessage .= 'Por favor ajusta las cantidades en tu carrito.';

            return redirect()->route('tienda.carrito')
                ->with('error', $errorMessage);
        }

        $departamentos = Departamento::with('ciudades')->get();
        $configuracionPasarela = ConfiguracionPasarela::obtenerConfiguracionActiva();

        return view('tienda.checkout', compact(
            'empresa',
            'carrito',
            'departamentos',
            'configuracionPasarela'
        ));
    }

    /**
     * Procesar compra
     */
/**
 * Procesar compra y redirigir a Wompi
 */
public function procesarCompra(Request $request)
{
    $empresa = $this->getEmpresa();

    // LÍMITE DE TRANSACCIONES DESHABILITADO - Single-tenant
    // $transaccionesMes = Compra::where('empresa_id', $empresa->id)
    //     ->where('estado', 'pagada')
    //     ->whereMonth('created_at', now()->month)
    //     ->whereYear('created_at', now()->year)
    //     ->count();
    //
    // if ($empresa->planMembresia->limite_transacciones &&
    //     $transaccionesMes >= $empresa->planMembresia->limite_transacciones) {
    //     return response()->json([
    //         'error' => 'La tienda ha alcanzado el límite de ventas mensuales. Por favor contacta al vendedor.'
    //     ], 403);
    // }

    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'telefono' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'ciudad_id' => 'required|exists:ciudades,id',
        'notas' => 'nullable|string',
        'metodo_pago' => 'required|in:wompi,otro',
        'mensaje_pago' => 'required_if:metodo_pago,otro|nullable|string|max:1000',
        'archivo_pago' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    $metodoPago = $request->input('metodo_pago', 'wompi');

    $carrito = $this->obtenerCarrito($empresa->id);

    if (empty($carrito->items)) {
        return redirect()->route('tienda.carrito')
            ->with('error', 'El carrito está vacío');
    }

    // Validar monto mínimo de compra
    $montoMinimo = $empresa->monto_minimo_compra ?? 0;
    $totalCarrito = $carrito->total ?? $carrito->subtotal;
    if ($montoMinimo > 0 && $totalCarrito < $montoMinimo) {
        return redirect()->route('tienda.carrito')
            ->with('error', 'El monto mínimo de compra es $' . number_format($montoMinimo, 0, ',', '.') . '. Tu carrito actual es $' . number_format($totalCarrito, 0, ',', '.') . '.');
    }

    DB::beginTransaction();

    try {
        // Crear compra
        $compra = Compra::create([
            'empresa_id' => $empresa->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'nombre_cliente' => $request->nombre,
            'email_cliente' => $request->email,
            'telefono_cliente' => $request->telefono,
            'direccion_envio' => $request->direccion,
            'ciudad_id' => $request->ciudad_id,
            'subtotal' => $carrito->subtotal,
            'descuento_total' => $carrito->descuento_total ?? 0,
            'descuentos_aplicados' => $carrito->descuentos_aplicados ?? [],
            'impuestos' => 0,
            'costo_envio' => 0,
            'total' => $carrito->total ?? $carrito->subtotal,
            'estado' => 'pendiente',
            'metodo_pago' => $metodoPago,
            'mensaje_pago' => $metodoPago === 'otro' ? $request->mensaje_pago : null,
            'notas' => $request->notas
        ]);

        // Subir archivo de pago si existe (guardado en public/pagos/)
        if ($metodoPago === 'otro' && $request->hasFile('archivo_pago')) {
            $archivo = $request->file('archivo_pago');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaDestino = "pagos/{$empresa->id}/{$compra->id}";

            // Mover a public/pagos/
            $archivo->move(public_path($rutaDestino), $nombreArchivo);

            $compra->update(['archivo_pago' => $rutaDestino . '/' . $nombreArchivo]);
        }

        // Crear items de compra
        foreach ($carrito->items as $item) {
            ItemCompra::create([
                'compra_id' => $compra->id,
                'producto_id' => $item['producto_id'],
                'variante_producto_id' => $item['variante_id'] ?? null,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'descuento' => 0,
                'precio_total' => $item['cantidad'] * $item['precio'],
                'referencia_producto' => $item['referencia'],
                'nombre_producto' => $item['nombre'],
                'info_variante' => isset($item['info_variante']) ? 
                    "Talla: {$item['info_variante']['talla']}, Color: {$item['info_variante']['color']}" : null
            ]);

            // Descontar stock
            $producto = Producto::find($item['producto_id']);
            if ($producto->controlar_stock) {
                $stock = $producto->tiene_variantes && isset($item['variante_id']) ?
                    $producto->stock()->where('variante_producto_id', $item['variante_id'])->first() :
                    $producto->stockPrincipal;
                
                if ($stock) {
                    $stock->salida($item['cantidad'], 'venta', $compra->numero_compra);
                }
            }
        }

        // Registrar descuentos aplicados
        if (!empty($carrito->descuentos_aplicados)) {
            $compra->registrarDescuentos();
        }

        // Crear transacción de pago
        $transaccion = TransaccionPago::create([
            'compra_id' => $compra->id,
            'pasarela' => $metodoPago,
            'monto' => $compra->total,
            'moneda' => 'COP',
            'estado' => 'pendiente'
        ]);

        // Vaciar carrito
        $carrito->vaciar();

        DB::commit();

        // Bifurcar flujo según método de pago
        if ($metodoPago === 'wompi') {
            // Flujo de Wompi
            $wompiService = new WompiService();
            $datosCheckout = $wompiService->generarDatosCheckout($compra, $transaccion);
            return view('tienda.redirect-wompi', compact('datosCheckout'));
        } else {
            // Flujo de pago "Otro" - mostrar confirmación
            return view('tienda.pago-otro-enviado', [
                'compra' => $compra,
                'empresa' => $empresa,
                'email' => $request->email
            ]);
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error procesando compra: ' . $e->getMessage(), [
            'exception' => $e,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // En desarrollo mostrar el error real
        $errorMessage = config('app.debug')
            ? 'Error: ' . $e->getMessage()
            : 'Error al procesar la compra. Por favor intente nuevamente.';

        return back()->withInput()->with('error', $errorMessage);
    }
}

    /**
     * Confirmación de pago (webhook/callback)
     */
/**
 * Confirmación de pago (callback de Wompi)
 */
public function confirmarPago(Request $request, $referencia)
{
    $empresa = $this->getEmpresa();
    $transaccion = TransaccionPago::where('referencia_transaccion', $referencia)->firstOrFail();

    // Verificar si ya fue procesada
    if ($transaccion->estado !== 'pendiente') {
        if ($transaccion->estado === 'aprobada') {
            return view('tienda.confirmacion', [
                'compra' => $transaccion->compra,
                'transaccion' => $transaccion
            ]);
        } else {
            return view('tienda.pago-rechazado', [
                'compra' => $transaccion->compra,
                'transaccion' => $transaccion
            ]);
        }
    }
    
    // Obtener ID de transacción de Wompi desde query params
    $transaccionWompiId = $request->get('id');
    
    if ($transaccionWompiId) {
        // Consultar estado en Wompi
        $wompiService = new WompiService();
        $datosTransaccion = $wompiService->consultarTransaccion($transaccionWompiId);
        
        if ($datosTransaccion) {
            $estado = $datosTransaccion['status'] ?? null;
            
            switch ($estado) {
                case 'APPROVED':
                    $transaccion->update([
                        'estado' => 'aprobada',
                        'id_transaccion_pasarela' => $transaccionWompiId,
                        'metodo_pago' => $datosTransaccion['payment_method_type'] ?? null,
                        'fecha_procesamiento' => now(),
                        'respuesta_pasarela' => $datosTransaccion,
                        'codigo_autorizacion' => $datosTransaccion['authorization_code'] ?? null
                    ]);
                    
                    // Actualizar compra
                    $transaccion->compra->update(['estado' => 'pagada']);
                    
                    // Generar comisión
                    $transaccion->compra->generarComision();
                    
                    return view('tienda.confirmacion', [
                        'compra' => $transaccion->compra,
                        'transaccion' => $transaccion
                    ]);
                    
                case 'DECLINED':
                case 'VOIDED':
                    $transaccion->update([
                        'estado' => 'rechazada',
                        'id_transaccion_pasarela' => $transaccionWompiId,
                        'mensaje_error' => $datosTransaccion['status_message'] ?? 'Transacción rechazada',
                        'respuesta_pasarela' => $datosTransaccion
                    ]);
                    
                    // Liberar stock
                    $this->liberarStockCompra($transaccion->compra);
                    
                    return view('tienda.pago-rechazado', [
                        'compra' => $transaccion->compra,
                        'transaccion' => $transaccion
                    ]);
                    
                case 'PENDING':
                    // Mostrar página de pendiente
                    return view('tienda.pago-pendiente', [
                        'empresa' => $transaccion->compra->empresa,
                        'transaccion' => $transaccion
                    ]);
                    
                default:
                    $transaccion->update([
                        'estado' => 'error',
                        'mensaje_error' => 'Estado desconocido: ' . $estado,
                        'respuesta_pasarela' => $datosTransaccion
                    ]);
                    
                    return view('tienda.pago-error', [
                        'compra' => $transaccion->compra,
                        'transaccion' => $transaccion
                    ]);
            }
        }
    }
    
    // Si no hay ID o no se pudo consultar, mostrar pendiente
    return view('tienda.pago-pendiente', [
        'empresa' => $transaccion->compra->empresa,
        'transaccion' => $transaccion
    ]);
}

/**
 * Liberar stock de una compra cancelada/rechazada
 */
private function liberarStockCompra($compra)
{
    foreach ($compra->items as $item) {
        $producto = $item->producto;
        
        if ($producto && $producto->controlar_stock) {
            $stock = $item->variante_producto_id 
                ? $producto->stock()->where('variante_producto_id', $item->variante_producto_id)->first()
                : $producto->stockPrincipal;
            
            if ($stock) {
                // Devolver el stock
                $stock->entrada(
                    $item->cantidad, 
                    'devolucion', 
                    $compra->numero_compra,
                    'Pago rechazado/cancelado'
                );
            }
        }
    }
}

    /**
     * Obtener carrito de la sesión
     */
    private function obtenerCarrito($empresaId)
    {
        $sessionId = Session::getId();
        return Carrito::obtenerOCrear($sessionId, $empresaId);
    }

    /**
     * Redirigir a pasarela de pago Wompi
     */
    private function redirigirAPasarela($compra, $transaccion)
    {
        $wompiService = new WompiService();
        $resultado = $wompiService->crearLinkPago($compra, $transaccion);
        
        if ($resultado['success'] && $resultado['payment_url']) {
            return redirect()->away($resultado['payment_url']);
        } else {
            // Si falla, mostrar página de error o volver al checkout
            return redirect()->route('tienda.checkout')
                ->with('error', 'Error al procesar el pago. Por favor intente nuevamente.');
        }
    }
    /**
     * Mostrar página de categorías con filtros
     */
/**
 * Mostrar página de categorías con filtros
 */
public function categorias(Request $request)
{
    $empresa = $this->getEmpresa();

    // Obtener primera lista de precios activa
    $listaPrecio = ListaPrecio::activas()->first();
    
    if (!$listaPrecio) {
        abort(404, 'No hay listas de precios configuradas');
    }

    // Obtener todas las categorías con conteo de productos
    $categorias = Categoria::where('empresa_id', $empresa->id)
        ->where('activo', true)
        ->whereHas('productos', function($q) {
            $q->where('activo', true);
        })
        ->withCount([
            'productos as productos_count' => function ($q) use ($empresa) {
                $q->where('activo', true)
                ->where('empresa_id', $empresa->id);
            }
        ])
        ->orderBy('orden')
        ->get();

    // Obtener categoría seleccionada si existe
    $categoriaSeleccionada = null;
    if ($request->filled('categoria')) {
        $categoriaSeleccionada = Categoria::find($request->categoria);
    }

    // Query base de productos con calificaciones
    $query = Producto::where('empresa_id', $empresa->id)
        ->where('productos.activo', true)
        ->noEliminados()
        ->with(['imagenPrincipal', 'imagenes', 'categoria', 'stockPrincipal'])
        ->withCount(['calificaciones as total_calificaciones' => function($q) {
            $q->where('aprobada', true);
        }])
        ->withAvg(['calificaciones as promedio_calificaciones' => function($q) {
            $q->where('aprobada', true);
        }], 'estrellas');

    // Filtro por categoría
    if ($request->filled('categoria')) {
        $query->where('categoria_id', $request->categoria);
    }

    // Filtro por búsqueda
    if ($request->filled('buscar')) {
        $query->buscar($request->buscar);
    }

    // Obtener rango de precios antes de aplicar filtros de precio
    // Primero obtener todos los productos de la empresa (o categoría si está filtrada)
    $productosParaRango = Producto::where('empresa_id', $empresa->id)
        ->where('activo', true);
    
    // Si hay categoría seleccionada, aplicar ese filtro
    if ($request->filled('categoria')) {
        $productosParaRango->where('categoria_id', $request->categoria);
    }
    
    // Obtener los IDs de productos
    $productosIds = $productosParaRango->pluck('id');
    
    // Ahora obtener el rango de precios de estos productos
    $rangoPreciosQuery = PrecioProducto::whereIn('producto_id', $productosIds)
        ->where('lista_precio_id', $listaPrecio->id)
        ->where('activo', true);
    
    $precioMin = floor($rangoPreciosQuery->min('precio') ?? 0);
    $precioMax = ceil($rangoPreciosQuery->max('precio') ?? 1000000);

    // Filtro por rango de precio (select)
    if ($request->filled('rango_precio')) {
        $rango = explode('-', $request->rango_precio);
        $min = $rango[0];
        $max = $rango[1] ?? null;

        $query->whereHas('precios', function($q) use ($listaPrecio, $min, $max) {
            $q->where('lista_precio_id', $listaPrecio->id)
              ->where('activo', true)
              ->where('precio', '>=', $min);
            
            if ($max && $max > 0) {
                $q->where('precio', '<=', $max);
            }
        });
    }

    // Filtro por precio mínimo y máximo (slider)
    if ($request->filled('precio_min') || $request->filled('precio_max')) {
        $minFilter = $request->precio_min ?? $precioMin;
        $maxFilter = $request->precio_max ?? $precioMax;

        $query->whereHas('precios', function($q) use ($listaPrecio, $minFilter, $maxFilter) {
            $q->where('lista_precio_id', $listaPrecio->id)
              ->where('activo', true)
              ->whereBetween('precio', [$minFilter, $maxFilter]);
        });
    }

    // Ordenamiento
    if ($request->filled('orden')) {
        switch ($request->orden) {
            case 'precio_asc':
                $query->select('productos.*')
                    ->leftJoin('precios_productos', function($join) use ($listaPrecio) {
                        $join->on('productos.id', '=', 'precios_productos.producto_id')
                             ->where('precios_productos.lista_precio_id', $listaPrecio->id)
                             ->where('precios_productos.activo', true);
                    })
                    ->orderBy('precios_productos.precio', 'asc');
                break;
            case 'precio_desc':
                $query->select('productos.*')
                    ->leftJoin('precios_productos', function($join) use ($listaPrecio) {
                        $join->on('productos.id', '=', 'precios_productos.producto_id')
                             ->where('precios_productos.lista_precio_id', $listaPrecio->id)
                             ->where('precios_productos.activo', true);
                    })
                    ->orderBy('precios_productos.precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre');
                break;
            default:
                $query->latest();
        }
    } else {
        $query->latest();
    }

    // Paginación
    $porPagina = $request->get('por_pagina', 12);
    $productos = $query->paginate($porPagina)->withQueryString();

    // Cargar precios para la lista seleccionada
    foreach ($productos as $producto) {
        $producto->precio_actual = $producto->getPrecioPorLista($listaPrecio->id);
    }

    // Obtener carrito
    $carrito = $this->obtenerCarrito($empresa->id);

    // Obtener descuentos activos
    $descuentosActivos = \App\Models\Descuento::where('empresa_id', $empresa->id)
        ->where('activo', true)
        ->where(function($q) {
            $q->whereNull('fecha_inicio')
              ->orWhere('fecha_inicio', '<=', now());
        })
        ->where(function($q) {
            $q->whereNull('fecha_fin')
              ->orWhere('fecha_fin', '>=', now());
        })
        ->get();

    $strategy = $this->templateResolver->resolveForEmpresa($empresa);

    $data = $strategy->prepareData(compact(
        'empresa',
        'productos',
        'categorias',
        'categoriaSeleccionada',
        'listaPrecio',
        'carrito',
        'precioMin',
        'precioMax',
        'descuentosActivos'
    ));

    return view($strategy->getViewCategoria(), $data);
}

    /**
     * Obtener información de stock por AJAX
     */
    public function obtenerStockInfo(Request $request)
    {
        $empresa = $this->getEmpresa();
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'variante_id' => 'nullable|exists:variantes_productos,id'
        ]);

        $producto = Producto::where('id', $request->producto_id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();
        
        $stockInfo = $producto->getStockInfo($request->variante_id);
        
        return response()->json($stockInfo);
    }

    /**
     * Validar stock completo del carrito
     */
    public function validarStockCarrito(Request $request)
    {
        $empresa = $this->getEmpresa();
        $carrito = $this->obtenerCarrito($empresa->id);
        
        $stockErrors = [];
        $totalValid = true;
        
        foreach ($carrito->items as $key => $item) {
            $producto = Producto::find($item['producto_id']);
            if (!$producto) continue;
            
            $varianteId = $item['variante_id'] ?? null;
            $cantidad = $item['cantidad'];
            
            // Obtener información actual del stock
            $stockInfo = $producto->getStockInfo($varianteId);
            $hayStock = $producto->hayStock($cantidad, $varianteId);
            
            if (!$hayStock && $stockInfo['stock_limitado']) {
                $totalValid = false;
                $stockErrors[] = [
                    'key' => $key,
                    'producto' => $item['nombre'],
                    'cantidad_solicitada' => $cantidad,
                    'stock_disponible' => $stockInfo['stock_disponible'],
                    'permite_venta_sin_stock' => $stockInfo['puede_agregar_sin_stock'],
                    'variante' => isset($item['info_variante']) ? $item['info_variante'] : null
                ];
            }
        }
        
        return response()->json([
            'valid' => $totalValid,
            'errors' => $stockErrors,
            'total_items' => count($carrito->items)
        ]);
    }

    /**
     * Aplicar código de descuento al carrito
     */
    public function aplicarDescuento(Request $request)
    {
        $empresa = $this->getEmpresa();
        $request->validate([
            'codigo' => 'required|string'
        ]);
        $carrito = $this->obtenerCarrito($empresa->id);

        if (empty($carrito->items)) {
            return response()->json(['error' => 'El carrito está vacío'], 400);
        }

        try {
            $resultado = $carrito->aplicarDescuento($request->codigo);

            if (empty($resultado['descuentos'])) {
                return response()->json([
                    'error' => 'El código de descuento no es válido o no cumple con los requisitos'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Descuento aplicado correctamente',
                'descuentos' => $resultado['descuentos'],
                'descuento_total' => $resultado['total_descuento'],
                'subtotal' => $carrito->subtotal,
                'total' => $carrito->total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al aplicar el descuento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover descuento del carrito
     */
    public function removerDescuento()
    {
        $empresa = $this->getEmpresa();
        $carrito = $this->obtenerCarrito($empresa->id);

        $carrito->removerDescuento();

        return response()->json([
            'success' => true,
            'message' => 'Descuento removido',
            'subtotal' => $carrito->subtotal,
            'descuento_total' => 0,
            'total' => $carrito->subtotal
        ]);
    }

    /**
     * Guardar reseña de un producto (público)
     * Cualquier visitante puede dejar una reseña, pero requiere aprobación del admin
     * Excepción: si el usuario está logueado como admin, se aprueba automáticamente
     */
    public function guardarResena(Request $request, $slug)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'estrellas' => 'required|integer|min:1|max:5',
            'titulo' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'nombre.required' => 'Por favor ingresa tu nombre',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
            'estrellas.required' => 'Por favor selecciona una calificación',
            'estrellas.min' => 'La calificación mínima es 1 estrella',
            'estrellas.max' => 'La calificación máxima es 5 estrellas',
            'titulo.max' => 'El título no puede exceder 255 caracteres',
            'comentario.max' => 'El comentario no puede exceder 1000 caracteres',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG, GIF o WebP',
            'imagen.max' => 'La imagen no puede exceder 5MB',
        ]);

        // Verificar que el producto existe por slug
        $empresa = $this->getEmpresa();
        $producto = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->get(['id', 'nombre', 'empresa_id'])
            ->first(fn($p) => Str::slug($p->nombre) === $slug);

        if (!$producto) {
            abort(404);
        }

        // Procesar imagen si existe
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $directorio = 'imagenes/resenas/' . $producto->id;

            if (!File::exists(public_path($directorio))) {
                File::makeDirectory(public_path($directorio), 0755, true);
            }

            $imagen->move(public_path($directorio), $nombreArchivo);
            $rutaImagen = $directorio . '/' . $nombreArchivo;
        }

        // Determinar si la reseña se aprueba automáticamente
        $aprobadaAutomaticamente = false;
        $userId = null;

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            if ($user->empresa) {
                $aprobadaAutomaticamente = true;
            }
        }

        // Crear la calificación
        CalificacionProducto::create([
            'producto_id' => $producto->id,
            'user_id' => $userId,
            'nombre_visitante' => $request->nombre,
            'estrellas' => $request->estrellas,
            'titulo' => $request->titulo,
            'comentario' => $request->comentario,
            'imagen' => $rutaImagen,
            'verificada' => false,
            'aprobada' => $aprobadaAutomaticamente,
        ]);

        // Respuesta según si es AJAX o no
        if ($request->ajax() || $request->wantsJson()) {
            $mensaje = $aprobadaAutomaticamente
                ? 'Tu reseña ha sido publicada'
                : 'Gracias por tu reseña. Será publicada después de ser revisada';

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'aprobada' => $aprobadaAutomaticamente
            ]);
        }

        $mensaje = $aprobadaAutomaticamente
            ? 'Tu reseña ha sido publicada'
            : 'Gracias por tu reseña. Será publicada después de ser revisada';

        return back()->with('success', $mensaje);
    }

    /**
     * Guardar respuesta a una reseña
     */
    public function guardarRespuesta(Request $request, $calificacionId)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'comentario' => 'required|string|max:500',
        ], [
            'nombre.required' => 'Por favor ingresa tu nombre',
            'comentario.required' => 'Por favor escribe tu respuesta',
            'comentario.max' => 'La respuesta no puede exceder 500 caracteres',
        ]);

        $calificacionPadre = CalificacionProducto::findOrFail($calificacionId);

        // No permitir responder a respuestas (solo 1 nivel)
        if ($calificacionPadre->parent_id !== null) {
            return response()->json([
                'error' => 'No se puede responder a una respuesta'
            ], 400);
        }

        $userId = Auth::check() ? Auth::id() : null;
        $aprobadaAutomaticamente = false;

        if (Auth::check() && Auth::user()->empresa) {
            $aprobadaAutomaticamente = true;
        }

        CalificacionProducto::create([
            'producto_id' => $calificacionPadre->producto_id,
            'parent_id' => $calificacionId,
            'user_id' => $userId,
            'nombre_visitante' => $request->nombre,
            'estrellas' => 0, // Las respuestas no tienen estrellas
            'comentario' => $request->comentario,
            'verificada' => false,
            'aprobada' => $aprobadaAutomaticamente,
        ]);

        $mensaje = $aprobadaAutomaticamente
            ? 'Tu respuesta ha sido publicada'
            : 'Gracias por tu respuesta. Será publicada después de ser revisada';

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'aprobada' => $aprobadaAutomaticamente
        ]);
    }

    /**
     * Toggle reacción en una reseña
     */
    public function toggleReaccion(Request $request, $calificacionId)
    {
        $request->validate([
            'emoji' => 'required|in:hearts,wink,kiss,thumbsup'
        ]);

        $calificacion = CalificacionProducto::findOrFail($calificacionId);
        $visitorId = ReaccionCalificacion::generarVisitorId();

        // Buscar reacción existente
        $reaccionExistente = ReaccionCalificacion::where('calificacion_id', $calificacionId)
            ->where('visitor_id', $visitorId)
            ->where('emoji', $request->emoji)
            ->first();

        if ($reaccionExistente) {
            // Quitar reacción
            $reaccionExistente->delete();
            $accion = 'removed';
        } else {
            // Agregar reacción
            ReaccionCalificacion::create([
                'calificacion_id' => $calificacionId,
                'visitor_id' => $visitorId,
                'emoji' => $request->emoji,
            ]);
            $accion = 'added';
        }

        // Retornar conteos actualizados
        $conteos = $calificacion->fresh()->conteo_reacciones;

        return response()->json([
            'success' => true,
            'action' => $accion,
            'conteos' => $conteos
        ]);
    }

    // ==================== BLOG PÚBLICO ====================

    public function blogIndex(Request $request)
    {
        $empresa = $this->getEmpresa();

        $query = BlogPost::where('empresa_id', $empresa->id)
            ->publicados()
            ->with(['categoria', 'autor'])
            ->orderBy('publicado_en', 'desc');

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria) {
            $categoriaSlug = $request->categoria;
            $query->whereHas('categoria', function ($q) use ($categoriaSlug) {
                $q->where('slug', $categoriaSlug);
            });
        }

        $posts = $query->paginate(9);

        $categoriasBlog = BlogCategoria::where('empresa_id', $empresa->id)
            ->activas()
            ->withCount(['posts' => function ($q) {
                $q->publicados();
            }])
            ->get();

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->whereHas('productos', function ($q) {
                $q->where('activo', true);
            })
            ->orderBy('orden')
            ->get();

        $carrito = $this->obtenerCarrito($empresa->id);

        $blogConfig = BlogConfiguracion::where('empresa_id', $empresa->id)->first();

        return view('tienda.blog', compact('empresa', 'posts', 'categoriasBlog', 'categorias', 'carrito', 'blogConfig'));
    }

    public function blogPost($slug)
    {
        $empresa = $this->getEmpresa();

        $post = BlogPost::where('empresa_id', $empresa->id)
            ->where('slug', $slug)
            ->publicados()
            ->with(['categoria', 'autor', 'productoEnlace', 'relacionados' => function ($q) {
                $q->publicados()->limit(3);
            }])
            ->firstOrFail();

        $listaPrecio = ListaPrecio::activas()->first();

        // Si tiene producto enlazado, obtener su precio
        if ($post->productoEnlace && $listaPrecio) {
            $post->productoEnlace->precio_actual = $post->productoEnlace->getPrecioPorLista($listaPrecio->id);
        }

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->whereHas('productos', function ($q) {
                $q->where('activo', true);
            })
            ->orderBy('orden')
            ->get();

        // Productos para carrusel (activos de la empresa, aleatorios)
        $productosCarrusel = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->with('imagenPrincipal')
            ->inRandomOrder()
            ->limit(12)
            ->get();

        // Asignar precios a los productos del carrusel
        if ($listaPrecio) {
            foreach ($productosCarrusel as $prod) {
                $prod->precio_actual = $prod->getPrecioPorLista($listaPrecio->id);
            }
        }

        $carrito = $this->obtenerCarrito($empresa->id);

        return view('tienda.blog-post', compact('empresa', 'post', 'categorias', 'carrito', 'productosCarrusel'));
    }
}