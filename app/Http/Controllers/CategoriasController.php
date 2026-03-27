<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Empresa;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriasController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar categorías.');
        }

        if ($request->ajax()) {
            // Filtrar categorías por empresa
            $query = Categoria::where('empresa_id', $empresa->id)
                            ->select('categorias.*');

            return DataTables::of($query)
                ->addColumn('productos_count', function($c) {
                    // Contar productos de esta categoría
                    return $c->productos()->count();
                })
                ->addColumn('imagen_preview', function($c) {
                    if ($c->imagen) {
                        $url = asset($c->imagen);
                        return '<img src="'.$url.'" class="rounded" style="height: 40px; width: 40px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">Sin imagen</span>';
                })
                ->addColumn('activo', fn($c) => $c->activo ? 'Sí' : 'No')
                ->addColumn('action', function($c) {
                    $url = route('categorias.form', $c->id);
                    
                    $buttons = '<div class="d-flex justify-content-center gap-1">';
                    $buttons .= '<a href="'.$url.'" class="btn btn-outline-info btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>';
                    
                    // Botón para cambiar estado
                    $iconEstado = $c->activo ? 'bi-toggle-on' : 'bi-toggle-off';
                    $colorEstado = $c->activo ? 'success' : 'danger';
                    $buttons .= '<button type="button" class="btn btn-outline-'.$colorEstado.' btn-sm" title="Cambiar Estado" onclick="cambiarEstado('.$c->id.')">';
                    $buttons .= '<i class="bi '.$iconEstado.'"></i>';
                    $buttons .= '</button>';
                    
                    // Botón eliminar (solo si no tiene productos)
                    if ($c->productos()->count() == 0) {
                        $buttons .= '<button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarCategoria('.$c->id.')">';
                        $buttons .= '<i class="bi bi-trash"></i>';
                        $buttons .= '</button>';
                    }
                    
                    $buttons .= '</div>';
                    
                    return $buttons;
                })
                ->rawColumns(['action', 'imagen_preview'])
                ->make(true);
        }

        // Obtener estadísticas
        $estadisticas = [
            'total_categorias' => $empresa->categorias()->count(),
            'categorias_activas' => $empresa->categorias()->where('activo', true)->count(),
            'categorias_con_productos' => $empresa->categorias()
                ->whereHas('productos')
                ->count()
        ];

        return view('categorias.categorias_index', compact('empresa', 'estadisticas'));
    }

    public function form(Categoria $categoria = null)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar categorías.');
        }

        // Si es edición, verificar que la categoría pertenezca a la empresa
        if ($categoria && $categoria->exists && $categoria->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar esta categoría.');
        }

        $categoria = $categoria ?? new Categoria();
        
        // Obtener el conteo de productos de esta categoría
        $productosCount = 0;
        if ($categoria->exists) {
            $productosCount = $categoria->productos()->count();
        }
        
        // Obtener el orden máximo actual
        $maxOrden = $empresa->categorias()->max('orden') ?? 0;
        
        return view('categorias.categorias_form', compact('categoria', 'productosCount', 'empresa', 'maxOrden'));
    }

    public function guardar(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar categorías.');
        }

        $categoria = $request->id
                   ? Categoria::findOrFail($request->id)
                   : new Categoria();

        // Si es edición, verificar que la categoría pertenezca a la empresa
        if ($categoria->exists && $categoria->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar esta categoría.');
        }

        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias')
                    ->where('empresa_id', $empresa->id)
                    ->ignore($categoria->id)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categorias')
                    ->where('empresa_id', $empresa->id)
                    ->ignore($categoria->id)
            ],
            'descripcion' => ['nullable','string'],
            'orden' => ['required','integer','min:0'],
            'imagen' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:2048'],
        ];

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre en su empresa.',
            'slug.unique' => 'Ya existe una categoría con este slug en su empresa.',
            'orden.required' => 'El orden es obligatorio.',
            'orden.integer' => 'El orden debe ser un número entero.',
            'orden.min' => 'El orden debe ser mayor o igual a 0.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'imagen.max' => 'La imagen no debe superar los 2MB.',
        ];

        $data = $request->validate($rules, $messages);

        DB::beginTransaction();
        
        try {
            // Si no proporcionó slug, el Model lo genera en boot()
            if (empty($data['slug'])) {
                unset($data['slug']);
            }

            // Asignar empresa_id
            $data['empresa_id'] = $empresa->id;
            $data['activo'] = true;

            // Manejar la imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($categoria->imagen && File::exists(public_path($categoria->imagen))) {
                    File::delete(public_path($categoria->imagen));
                }

                // Crear directorio si no existe
                $directory = public_path('imagenes/categorias');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Guardar nueva imagen
                $imagen = $request->file('imagen');
                $filename = time() . '_' . uniqid() . '_' . $imagen->getClientOriginalName();
                $imagen->move($directory, $filename);
                $data['imagen'] = 'imagenes/categorias/' . $filename;
            }

            // Si se marcó eliminar imagen
            if ($request->input('eliminar_imagen') && $categoria->imagen) {
                if (File::exists(public_path($categoria->imagen))) {
                    File::delete(public_path($categoria->imagen));
                }
                $data['imagen'] = null;
            }

            $categoria->fill($data)->save();
            
            DB::commit();

            return redirect()->route('categorias')
                           ->with('success','Categoría guardada correctamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si hubo error y se subió una imagen nueva, eliminarla
            if (isset($data['imagen']) && File::exists(public_path($data['imagen']))) {
                File::delete(public_path($data['imagen']));
            }
            
            return back()->withInput()
                         ->with('error', 'Error al guardar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de la categoría (AJAX)
     */
    public function cambiarEstado(Request $request, Categoria $categoria)
    {
        // Verificar que la categoría pertenezca a la empresa del usuario
        if ($categoria->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $categoria->activo = !$categoria->activo;
        $categoria->save();

        return response()->json([
            'success' => true,
            'activo' => $categoria->activo,
            'mensaje' => $categoria->activo ? 'Categoría activada' : 'Categoría desactivada'
        ]);
    }

    /**
     * Eliminar categoría (AJAX)
     */
    public function eliminar(Request $request, Categoria $categoria)
    {
        // Verificar que la categoría pertenezca a la empresa del usuario
        if ($categoria->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Verificar que no tenga productos asociados
        if ($categoria->productos()->count() > 0) {
            return response()->json([
                'error' => 'No se puede eliminar la categoría porque tiene productos asociados'
            ], 400);
        }

        try {
            // Eliminar imagen si existe
            if ($categoria->imagen && File::exists(public_path($categoria->imagen))) {
                File::delete(public_path($categoria->imagen));
            }
            
            $categoria->delete();
            
            return response()->json([
                'success' => true,
                'mensaje' => 'Categoría eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la categoría'
            ], 500);
        }
    }
}