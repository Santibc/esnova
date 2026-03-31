<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\StockProducto;
use App\Models\MovimientoStock;
use App\Models\VarianteProducto;
use App\Models\Empresa;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockController extends Controller
{
    // Vista principal de gestión de stock
    public function index(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar el stock.');
        }

        if ($request->ajax()) {
            $query = StockProducto::with(['producto', 'variante'])
                ->whereHas('producto', function($q) use ($empresa) {
                    $q->where('empresa_id', $empresa->id)->noEliminados();
                })
                ->select('stock_productos.*');

            // Filtrar por producto si se especifica
            if ($request->has('producto_id') && $request->producto_id) {
                $query->where('producto_id', $request->producto_id);
            }
            
            // Filtrar por estado de stock
            if ($request->has('estado') && $request->estado) {
                switch ($request->estado) {
                    case 'con_stock':
                        $query->conStock();
                        break;
                    case 'sin_stock':
                        $query->sinStock();
                        break;
                    case 'stock_bajo':
                        $query->conStockBajo();
                        break;
                }
            }

            return DataTables::of($query)
                ->addColumn('producto_info', function($stock) {
                    $info = '<strong>' . $stock->producto->referencia . '</strong><br>';
                    $info .= $stock->producto->nombre;
                    if ($stock->variante) {
                        $info .= '<br><small class="text-muted">' . $stock->variante->nombre_variante . '</small>';
                    }
                    return $info;
                })
                ->addColumn('stock_actual', function($stock) {
                    $badge = $stock->stock_bajo ? 'danger' : ($stock->stock_real > 0 ? 'success' : 'warning');
                    return '<span class="badge bg-'.$badge.'">' . $stock->stock_real . '</span>';
                })
                ->addColumn('disponible_reservado', function($stock) {
                    return 'Disponible: ' . $stock->cantidad_disponible . '<br>Reservado: ' . $stock->cantidad_reservada;
                })
                ->addColumn('stock_minimo_maximo', function($stock) {
                    $info = 'Mínimo: ' . $stock->stock_minimo;
                    if ($stock->stock_maximo) {
                        $info .= '<br>Máximo: ' . $stock->stock_maximo;
                    }
                    return $info;
                })
                ->addColumn('ubicacion', fn($stock) => $stock->ubicacion ?: '-')
                ->addColumn('action', function($stock) {
                    $buttons = '<div class="btn-group btn-group-sm">';
                    
                    // Botón entrada
                    $buttons .= '<button type="button" class="btn btn-success" onclick="entradaStock('.$stock->id.')" title="Entrada">
                                    <i class="bi bi-plus-circle"></i>
                                </button>';
                    
                    // Botón salida
                    $buttons .= '<button type="button" class="btn btn-danger" onclick="salidaStock('.$stock->id.')" title="Salida">
                                    <i class="bi bi-dash-circle"></i>
                                </button>';
                    
                    // Botón ajuste
                    $buttons .= '<button type="button" class="btn btn-warning" onclick="ajusteStock('.$stock->id.')" title="Ajuste">
                                    <i class="bi bi-gear"></i>
                                </button>';
                    
                    // Botón configuración
                    $buttons .= '<button type="button" class="btn btn-info" onclick="configurarStock('.$stock->id.')" title="Configurar">
                                    <i class="bi bi-sliders"></i>
                                </button>';
                    
                    // Botón historial
                    $buttons .= '<button type="button" class="btn btn-secondary" onclick="verHistorial('.$stock->producto_id.', '.($stock->variante_producto_id ?: 'null').')" title="Historial">
                                    <i class="bi bi-clock-history"></i>
                                </button>';
                    
                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['producto_info', 'stock_actual', 'disponible_reservado', 'stock_minimo_maximo', 'action'])
                ->make(true);
        }

        // Estadísticas de la empresa
        $productosConStockBajo = StockProducto::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })->conStockBajo()->count();
        
        $productosSinStock = StockProducto::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })->sinStock()->count();
        
        // Obtener información del producto si viene filtrado
        $productoFiltrado = null;
        if ($request->has('producto_id') && $request->producto_id) {
            $productoFiltrado = Producto::where('empresa_id', $empresa->id)
                                      ->find($request->producto_id);
            
            // Si el producto no es de la empresa, redireccionar
            if (!$productoFiltrado) {
                return redirect()->route('stock.index')
                               ->with('error', 'Producto no encontrado o no autorizado.');
            }
        }
        
        return view('stock.index', compact('productosConStockBajo', 'productosSinStock', 'productoFiltrado', 'empresa'));
    }

    // Entrada de stock
    public function entrada(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stock_productos,id',
            'cantidad' => 'required|integer|min:1',
            'referencia' => 'nullable|string|max:255',
            'motivo' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $stock = StockProducto::findOrFail($request->stock_id);
            
            // Verificar que el producto pertenezca a la empresa del usuario
            if ($stock->producto->empresa_id !== auth()->user()->empresa->id) {
                throw new \Exception('No autorizado para modificar este stock.');
            }
            
            $stock->entrada(
                $request->cantidad,
                'compra',
                $request->referencia,
                $request->motivo
            );

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Entrada de stock registrada correctamente',
                'stock_actual' => $stock->fresh()->stock_real
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    // Salida de stock
    public function salida(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stock_productos,id',
            'cantidad' => 'required|integer|min:1',
            'referencia' => 'nullable|string|max:255',
            'motivo' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $stock = StockProducto::findOrFail($request->stock_id);
            
            // Verificar que el producto pertenezca a la empresa del usuario
            if ($stock->producto->empresa_id !== auth()->user()->empresa->id) {
                throw new \Exception('No autorizado para modificar este stock.');
            }
            
            if (!$stock->hayDisponibilidad($request->cantidad)) {
                throw new \Exception('Stock insuficiente. Disponible: ' . $stock->stock_real);
            }
            
            $resultado = $stock->salida(
                $request->cantidad,
                'venta',
                $request->referencia,
                $request->motivo
            );

            if (!$resultado) {
                throw new \Exception('No se pudo procesar la salida de stock');
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Salida de stock registrada correctamente',
                'stock_actual' => $stock->fresh()->stock_real
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Ajuste de inventario
    public function ajuste(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stock_productos,id',
            'nueva_cantidad' => 'required|integer|min:0',
            'motivo' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $stock = StockProducto::findOrFail($request->stock_id);
            
            // Verificar que el producto pertenezca a la empresa del usuario
            if ($stock->producto->empresa_id !== auth()->user()->empresa->id) {
                throw new \Exception('No autorizado para modificar este stock.');
            }
            
            $stock->ajustar($request->nueva_cantidad, $request->motivo);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ajuste de inventario realizado correctamente',
                'stock_actual' => $stock->fresh()->stock_real
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al ajustar inventario: ' . $e->getMessage()
            ], 500);
        }
    }

    // Configurar parámetros de stock
    public function configurar(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stock_productos,id',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'alerta_stock_bajo' => 'boolean',
            'notas' => 'nullable|string'
        ]);

        try {
            $stock = StockProducto::findOrFail($request->stock_id);
            
            // Verificar que el producto pertenezca a la empresa del usuario
            if ($stock->producto->empresa_id !== auth()->user()->empresa->id) {
                throw new \Exception('No autorizado para modificar este stock.');
            }
            
            $stock->update([
                'stock_minimo' => $request->stock_minimo,
                'stock_maximo' => $request->stock_maximo,
                'ubicacion' => $request->ubicacion,
                'alerta_stock_bajo' => $request->alerta_stock_bajo ?? true,
                'notas' => $request->notas
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar configuración: ' . $e->getMessage()
            ], 500);
        }
    }

    // Ver historial de movimientos
    public function historial(Request $request)
    {
        $empresa = auth()->user()->empresa;
        $productoId = $request->producto_id;
        $varianteId = $request->variante_id;
        
        // Verificar que el producto pertenezca a la empresa
        $producto = Producto::where('empresa_id', $empresa->id)->find($productoId);
        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        
        $movimientos = MovimientoStock::with(['usuario', 'producto', 'variante'])
            ->where('producto_id', $productoId);
            
        if ($varianteId) {
            $movimientos->where('variante_producto_id', $varianteId);
        } else {
            $movimientos->whereNull('variante_producto_id');
        }
        
        $movimientos = $movimientos->orderBy('created_at', 'desc')
                                   ->limit(50)
                                   ->get();
        
        $html = view('stock.historial', compact('movimientos'))->render();
        
        return response()->json(['html' => $html]);
    }

    // Obtener datos de stock para edición
    public function obtenerStock($id)
    {
        $stock = StockProducto::with(['producto', 'variante'])->findOrFail($id);
        
        // Verificar que el producto pertenezca a la empresa del usuario
        if ($stock->producto->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        return response()->json([
            'stock' => $stock,
            'producto_nombre' => $stock->producto->nombre,
            'variante_nombre' => $stock->variante ? $stock->variante->nombre_variante : null
        ]);
    }

    // Dashboard de stock
    public function dashboard()
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de ver el dashboard de stock.');
        }

        // Estadísticas generales de la empresa
        $totalProductos = $empresa->productos()->where('controlar_stock', true)->noEliminados()->count();

        $productosConStock = StockProducto::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->noEliminados();
        })->conStock()->count();

        $productosSinStock = StockProducto::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->noEliminados();
        })->sinStock()->count();

        $productosStockBajo = StockProducto::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->noEliminados();
        })->conStockBajo()->count();
        
        // Valor total del inventario (necesitaría precio de costo)
        $valorInventario = 0; // Implementar si se tiene precio de costo
        
        // Movimientos del mes de la empresa
        $movimientosMes = MovimientoStock::whereHas('producto', function($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->noEliminados();
        })->delMes()->get();
        
        $entradasMes = $movimientosMes->where('tipo_movimiento', 'entrada')->sum('cantidad');
        $salidasMes = $movimientosMes->where('tipo_movimiento', 'salida')->sum('cantidad');
        
        // Productos con mayor rotación de la empresa
        $productosTopRotacion = DB::table('movimientos_stock')
            ->join('productos', 'movimientos_stock.producto_id', '=', 'productos.id')
            ->select('movimientos_stock.producto_id', DB::raw('SUM(movimientos_stock.cantidad) as total_movimiento'))
            ->where('productos.empresa_id', $empresa->id)
            ->where('productos.eliminado', false)
            ->where('movimientos_stock.tipo_movimiento', 'salida')
            ->whereBetween('movimientos_stock.created_at', [Carbon::now()->subMonth(), Carbon::now()])
            ->groupBy('movimientos_stock.producto_id')
            ->orderBy('total_movimiento', 'desc')
            ->limit(10)
            ->get();

        // Productos críticos (stock bajo) de la empresa
        $productosCriticos = StockProducto::with(['producto', 'variante'])
            ->whereHas('producto', function($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id)->noEliminados();
            })
            ->conStockBajo()
            ->orderBy('cantidad_disponible', 'asc')
            ->limit(10)
            ->get();
        
        return view('stock.dashboard', compact(
            'empresa',
            'totalProductos',
            'productosConStock',
            'productosSinStock',
            'productosStockBajo',
            'valorInventario',
            'entradasMes',
            'salidasMes',
            'productosTopRotacion',
            'productosCriticos'
        ));
    }

    // Inicializar stock para todos los productos de la empresa
    public function inicializarTodos()
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene empresa registrada'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $productos = $empresa->productos()->where('controlar_stock', true)->get();
            
            foreach ($productos as $producto) {
                $producto->inicializarStock();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock inicializado para todos los productos de su empresa'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al inicializar stock: ' . $e->getMessage()
            ], 500);
        }
    }

    // Obtener productos para selector (AJAX) - filtrado por empresa
    public function productosJson(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return response()->json(['results' => []]);
        }

        $query = Producto::where('empresa_id', $empresa->id)
                        ->where('controlar_stock', true)
                        ->noEliminados();
        
        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('referencia', 'like', "%{$search}%");
            });
        }
        
        $productos = $query->orderBy('nombre')
                           ->limit(50)
                           ->get()
                           ->map(function($producto) {
                               return [
                                   'id' => $producto->id,
                                   'text' => $producto->referencia . ' - ' . $producto->nombre . 
                                            ($producto->tiene_variantes ? ' (Con variantes)' : ''),
                                   'tiene_variantes' => $producto->tiene_variantes,
                                   'stock_total' => $producto->stock_total
                               ];
                           });
        
        return response()->json([
            'results' => $productos
        ]);
    }

    // Reporte de movimientos
    public function reporteMovimientos(Request $request)
    {
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de ver reportes.');
        }

        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : null;
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : null;

        $movimientos = MovimientoStock::with(['producto', 'variante', 'usuario'])
            ->whereHas('producto', function($q) use ($empresa) {
                $q->where('empresa_id', $empresa->id)->noEliminados();
            });

        if ($fechaInicio && $fechaFin) {
            $movimientos->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $movimientos->where('created_at', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $movimientos->where('created_at', '<=', $fechaFin);
        }
        
        if ($request->producto_id) {
            // Verificar que el producto sea de la empresa
            $producto = Producto::where('empresa_id', $empresa->id)->find($request->producto_id);
            if ($producto) {
                $movimientos->where('producto_id', $request->producto_id);
            }
        }
        
        if ($request->tipo_movimiento) {
            $movimientos->where('tipo_movimiento', $request->tipo_movimiento);
        }
        
        $movimientos = $movimientos->orderBy('created_at', 'desc')->get();
        
        return view('stock.reporte_movimiento', compact('movimientos', 'fechaInicio', 'fechaFin', 'empresa'));
    }
}