<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Descuento;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DescuentosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar listado de descuentos
     */
    public function index()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $descuentos = Descuento::where('empresa_id', $empresa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('descuentos.index', compact('descuentos', 'empresa'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $productos = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->with('precios')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('descuentos.create', compact('empresa', 'productos', 'categorias'));
    }

    /**
     * Guardar nuevo descuento
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:descuentos,codigo',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,monto_fijo,envio_gratis,producto_gratis,2x1,3x2',
            'valor' => 'required|numeric|min:0',
            'descuento_maximo' => 'nullable|numeric|min:0',
            'aplica_a' => 'required|in:orden,producto,categoria,carrito',
            'productos_aplicables' => 'nullable|array',
            'categorias_aplicables' => 'nullable|array',
            'monto_minimo_compra' => 'nullable|numeric|min:0',
            'cantidad_minima_productos' => 'nullable|integer|min:1',
            'solo_primera_compra' => 'boolean',
            'limite_usos_total' => 'nullable|integer|min:1',
            'limite_usos_por_cliente' => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'boolean',
            'es_acumulable' => 'boolean',
            'prioridad' => 'integer|min:0',
        ]);

        // No generar código automático - dejar NULL para descuentos automáticos
        $validated['empresa_id'] = $empresa->id;
        $validated['usos_actuales'] = 0;

        $descuento = Descuento::create($validated);

        // Sincronizar productos si es necesario
        if ($request->aplica_a === 'producto' && $request->has('productos_aplicables')) {
            $descuento->productos()->sync($request->productos_aplicables);
        } else {
            $descuento->productos()->sync([]);
        }

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $descuento = Descuento::where('empresa_id', $empresa->id)->findOrFail($id);

        $productos = Producto::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->with('precios')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('descuentos.edit', compact('descuento', 'empresa', 'productos', 'categorias'));
    }

    /**
     * Actualizar descuento
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $descuento = Descuento::where('empresa_id', $empresa->id)->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50|unique:descuentos,codigo,' . $id,
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:porcentaje,monto_fijo,envio_gratis,producto_gratis,2x1,3x2',
            'valor' => 'required|numeric|min:0',
            'descuento_maximo' => 'nullable|numeric|min:0',
            'aplica_a' => 'required|in:orden,producto,categoria,carrito',
            'productos_aplicables' => 'nullable|array',
            'categorias_aplicables' => 'nullable|array',
            'monto_minimo_compra' => 'nullable|numeric|min:0',
            'cantidad_minima_productos' => 'nullable|integer|min:1',
            'solo_primera_compra' => 'boolean',
            'limite_usos_total' => 'nullable|integer|min:1',
            'limite_usos_por_cliente' => 'nullable|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'boolean',
            'es_acumulable' => 'boolean',
            'prioridad' => 'integer|min:0',
        ]);

        $descuento->update($validated);

        // Sincronizar productos si es necesario
        if ($request->aplica_a === 'producto' && $request->has('productos_aplicables')) {
            $descuento->productos()->sync($request->productos_aplicables);
        } else {
            $descuento->productos()->sync([]);
        }

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento actualizado exitosamente.');
    }

    /**
     * Eliminar descuento
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $descuento = Descuento::where('empresa_id', $empresa->id)->findOrFail($id);
        $descuento->delete();

        return redirect()->route('descuentos.index')
            ->with('success', 'Descuento eliminado exitosamente.');
    }

    /**
     * Activar/Desactivar descuento
     */
    public function toggleEstado($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return response()->json(['error' => 'No tienes una empresa asociada.'], 403);
        }

        $descuento = Descuento::where('empresa_id', $empresa->id)->findOrFail($id);
        $descuento->activo = !$descuento->activo;
        $descuento->save();

        return response()->json([
            'success' => true,
            'activo' => $descuento->activo,
            'message' => $descuento->activo ? 'Descuento activado' : 'Descuento desactivado'
        ]);
    }

    /**
     * Ver estadísticas del descuento
     */
    public function estadisticas($id)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asociada.');
        }

        $descuento = Descuento::where('empresa_id', $empresa->id)
            ->with('aplicaciones.compra')
            ->findOrFail($id);

        $totalAhorrado = $descuento->aplicaciones->sum('monto_descuento');
        $totalVentas = $descuento->aplicaciones->whereNotNull('compra_id')->count();

        return view('descuentos.estadisticas', compact('descuento', 'totalAhorrado', 'totalVentas'));
    }
}
