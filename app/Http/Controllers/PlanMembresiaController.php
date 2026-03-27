<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanMembresia;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PlanMembresiaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PlanMembresia::select('planes_membresia.*');

            return DataTables::of($query)
                ->addColumn('precio_formatted', function($plan) {
                    return '$' . number_format($plan->precio, 0);
                })
                ->addColumn('empresas_count', function($plan) {
                    return $plan->empresas()->count();
                })
                ->addColumn('membresias_count', function($plan) {
                    return $plan->membresias()->count();
                })
                ->addColumn('activo', fn($plan) => $plan->activo ? 'Sí' : 'No')
                ->addColumn('marca_de_agua', fn($plan) => $plan->marca_de_agua ? 'Sí' : 'No')
                ->addColumn('action', function($plan) {
                    $url = route('admin.planes-membresia.form', $plan->id);
                    
                    $buttons = '<div class="d-flex justify-content-center gap-1">';
                    $buttons .= '<a href="'.$url.'" class="btn btn-outline-info btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>';
                    
                    // Botón para cambiar estado
                    $iconEstado = $plan->activo ? 'bi-toggle-on' : 'bi-toggle-off';
                    $colorEstado = $plan->activo ? 'success' : 'danger';
                    $buttons .= '<button type="button" class="btn btn-outline-'.$colorEstado.' btn-sm" title="Cambiar Estado" onclick="cambiarEstado('.$plan->id.')">';
                    $buttons .= '<i class="bi '.$iconEstado.'"></i>';
                    $buttons .= '</button>';
                    
                    // Botón eliminar (solo si no tiene empresas o membresías)
                    if ($plan->empresas()->count() == 0 && $plan->membresias()->count() == 0) {
                        $buttons .= '<button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarPlan('.$plan->id.')">';
                        $buttons .= '<i class="bi bi-trash"></i>';
                        $buttons .= '</button>';
                    }
                    
                    $buttons .= '</div>';
                    
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Obtener estadísticas
        $estadisticas = [
            'total_planes' => PlanMembresia::count(),
            'planes_activos' => PlanMembresia::where('activo', true)->count(),
            'planes_con_empresas' => PlanMembresia::whereHas('empresas')->count(),
            'plan_gratuito' => PlanMembresia::where('precio', 0)->exists() ? 'Sí' : 'No'
        ];

        return view('admin.planes-membresia.index', compact('estadisticas'));
    }

    public function form(PlanMembresia $plan = null)
    {
        $plan = $plan ?? new PlanMembresia();
        
        // Obtener el conteo de empresas y membresías de este plan
        $empresasCount = 0;
        $membresiasCount = 0;
        if ($plan->exists) {
            $empresasCount = $plan->empresas()->count();
            $membresiasCount = $plan->membresias()->count();
        }
        
        return view('admin.planes-membresia.form', compact('plan', 'empresasCount', 'membresiasCount'));
    }

    public function guardar(Request $request)
    {
        $plan = $request->id
                   ? PlanMembresia::findOrFail($request->id)
                   : new PlanMembresia();

        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('planes_membresia')
                    ->ignore($plan->id)
            ],
            'descripcion' => ['nullable','string'],
            'precio' => ['required','numeric','min:0'],
            'limite_productos' => ['required','integer','min:1'],
            'limite_transacciones' => ['nullable','integer','min:1'],
            'porcentaje_comision' => ['required','numeric','min:0','max:100'],
            'comision_fija' => ['required','numeric','min:0'],
            'orden' => ['nullable','integer','min:0'],
            'caracteristicas' => ['nullable','array'],
            'caracteristicas.*' => ['string','max:255'],
            'activo' => ['boolean'],
            'marca_de_agua' => ['boolean']
        ];

        $messages = [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un plan con este nombre.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.',
            'limite_productos.required' => 'El límite de productos es obligatorio.',
            'limite_productos.integer' => 'El límite de productos debe ser un número entero.',
            'limite_productos.min' => 'El límite de productos debe ser al menos 1.',
            'limite_transacciones.integer' => 'El límite de transacciones debe ser un número entero.',
            'limite_transacciones.min' => 'El límite de transacciones debe ser al menos 1.',
            'porcentaje_comision.required' => 'El porcentaje de comisión es obligatorio.',
            'porcentaje_comision.numeric' => 'El porcentaje de comisión debe ser un número.',
            'porcentaje_comision.min' => 'El porcentaje de comisión debe ser mayor o igual a 0.',
            'porcentaje_comision.max' => 'El porcentaje de comisión no puede ser mayor a 100.',
            'comision_fija.required' => 'La comisión fija es obligatoria.',
            'comision_fija.numeric' => 'La comisión fija debe ser un número.',
            'comision_fija.min' => 'La comisión fija debe ser mayor o igual a 0.',
            'orden.integer' => 'El orden debe ser un número entero.',
            'orden.min' => 'El orden debe ser mayor o igual a 0.',
        ];

        $data = $request->validate($rules, $messages);

        DB::beginTransaction();
        
        try {
            // Procesar características
            if (isset($data['caracteristicas'])) {
                $data['caracteristicas'] = array_filter($data['caracteristicas'], fn($item) => !empty(trim($item)));
            } else {
                $data['caracteristicas'] = [];
            }

            // Si no se especifica activo, establecer como true
            if (!isset($data['activo'])) {
                $data['activo'] = true;
            }

            // Si no se especifica marca_de_agua, establecer como false
            if (!isset($data['marca_de_agua'])) {
                $data['marca_de_agua'] = false;
            }

            // Generar slug si no existe
            if (!isset($data['slug']) || empty($data['slug'])) {
                $data['slug'] = \Illuminate\Support\Str::slug($data['nombre']);
            }

            // Establecer valores por defecto para campos opcionales
            if (!isset($data['orden'])) {
                $data['orden'] = 0;
            }

            $plan->fill($data)->save();
            
            DB::commit();

            return redirect()->route('admin.planes-membresia.index')
                           ->with('success','Plan de membresía guardado correctamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                         ->with('error', 'Error al guardar el plan: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del plan (AJAX)
     */
    public function cambiarEstado(Request $request, PlanMembresia $plan)
    {
        $plan->activo = !$plan->activo;
        $plan->save();

        return response()->json([
            'success' => true,
            'activo' => $plan->activo,
            'mensaje' => $plan->activo ? 'Plan activado' : 'Plan desactivado'
        ]);
    }

    /**
     * Eliminar plan (AJAX)
     */
    public function eliminar(Request $request, PlanMembresia $plan)
    {
        // Verificar que no tenga empresas o membresías asociadas
        if ($plan->empresas()->count() > 0 || $plan->membresias()->count() > 0) {
            return response()->json([
                'error' => 'No se puede eliminar el plan porque tiene empresas o membresías asociadas'
            ], 400);
        }

        try {
            $plan->delete();
            
            return response()->json([
                'success' => true,
                'mensaje' => 'Plan eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el plan'
            ], 500);
        }
    }
}