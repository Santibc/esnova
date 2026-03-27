<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use App\Models\ListaPrecio;
use App\Models\Empresa;
use App\Models\Pais;
use App\Models\Ciudad;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Departamento;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar clientes.');
        }

        if ($request->ajax()) {
            $query = Cliente::with(['vendedor', 'listaPrecio', 'pais', 'ciudad'])
                          ->where('empresa_id', $empresa->id) // Filtrar por empresa
                          ->select('clientes.*');

            return DataTables::of($query)
                ->addColumn('pais', fn($c) => $c->pais?->nombre)
                ->addColumn('ciudad', fn($c) => $c->ciudad?->nombre)
                ->addColumn('vendedor', fn($c) => $c->vendedor?->name)
                ->addColumn('lista_precio', fn($c) => $c->listaPrecio?->nombre)
                ->addColumn('activo', fn($c) => $c->activo ? 'Sí' : 'No')
                ->addColumn('action', function($c) {
                    $url = route('clientes.form', $c->id);
                    
                    $buttons = '<div class="d-flex justify-content-center gap-1">';
                    $buttons .= '<a href="'.$url.'" class="btn btn-outline-info btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>';
                    
                    // Botón para ver enlaces de acceso
                    $enlacesCount = $c->enlacesAccesoActivos()->count();
                    $badgeClass = $enlacesCount > 0 ? 'success' : 'secondary';
                    $buttons .= '<button type="button" class="btn btn-outline-primary btn-sm position-relative" title="Enlaces de Acceso" onclick="verEnlaces('.$c->id.')">';
                    $buttons .= '<i class="bi bi-link-45deg"></i>';
                    $buttons .= '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-'.$badgeClass.'">'.$enlacesCount.'</span>';
                    $buttons .= '</button>';
                    
                    // Botón para cambiar estado
                    $iconEstado = $c->activo ? 'bi-toggle-on' : 'bi-toggle-off';
                    $colorEstado = $c->activo ? 'success' : 'danger';
                    $buttons .= '<button type="button" class="btn btn-outline-'.$colorEstado.' btn-sm" title="Cambiar Estado" onclick="cambiarEstado('.$c->id.')">';
                    $buttons .= '<i class="bi '.$iconEstado.'"></i>';
                    $buttons .= '</button>';
                    
                    $buttons .= '</div>';
                    
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('clientes.clientes_index', compact('empresa'));
    }

    public function form(Cliente $cliente = null)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar clientes.');
        }

        // Si es edición, verificar que el cliente pertenezca a la empresa
        if ($cliente && $cliente->exists && $cliente->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este cliente.');
        }

        $cliente = $cliente ?? new Cliente();
        
        // Solo mostrar vendedores de la empresa o con rol admin
        $vendedores = User::role(['vendedor', 'admin'])
                         ->pluck('name', 'id');
        
        $listas = ListaPrecio::activas()->pluck('nombre', 'id');
        $departamentos = Departamento::orderBy('nombre')->pluck('nombre','id');
        $pais_id = 1; // Colombia por defecto

        return view('clientes.clientes_form', compact(
            'cliente', 'departamentos', 'vendedores', 'listas', 'pais_id', 'empresa'
        ));
    }

    public function guardar(Request $request)
    {
        // Verificar si el usuario tiene empresa
        $empresa = auth()->user()->empresa;
        if (!$empresa) {
            return redirect()->route('empresa.crear')
                           ->with('warning', 'Debe crear su empresa antes de gestionar clientes.');
        }

        $cliente = $request->id
                 ? Cliente::findOrFail($request->id)
                 : new Cliente();

        // Si es edición, verificar que el cliente pertenezca a la empresa
        if ($cliente->exists && $cliente->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permisos para editar este cliente.');
        }

        $rules = [
            'numero_identificacion' => [
                'required','string','max:255',
                Rule::unique('clientes')->ignore($cliente->id)
            ],
            'nombre_contacto'  => ['required','string','max:255'],
            'email'            => [
                'required','email','max:255',
                Rule::unique('clientes')->ignore($cliente->id)
            ],
            'telefono'         => ['nullable','string','max:100'],
            'pais_id'          => ['required','exists:paises,id'],
            'departamento_id'  => ['required','exists:departamentos,id'],
            'ciudad_id'        => ['required','exists:ciudades,id'],
            'vendedor_id'      => ['required','exists:users,id'],
            'lista_precio_id'  => ['required','exists:listas_precios,id'],
        ];

        $messages = [
            'required' => 'Este campo es obligatorio.',
            'email'    => 'Debe ser un correo válido.',
            'max'      => 'No debe superar los :max caracteres.',
            'unique'   => 'Ya existe un registro con este valor.',
            'exists'   => 'El valor seleccionado no es válido.',
        ];

        $data = $request->validate($rules, $messages);
        
        // Asignar empresa_id
        $data['empresa_id'] = $empresa->id;
        $data['activo'] = true; // Por defecto activo

        $cliente->fill($data)->save();

        return redirect()->route('clientes')
                         ->with('success','Cliente guardado correctamente.');
    }

    /**
     * Cambiar estado del cliente (AJAX)
     */
    public function cambiarEstado(Request $request, Cliente $cliente)
    {
        // Verificar que el cliente pertenezca a la empresa del usuario
        if ($cliente->empresa_id !== auth()->user()->empresa->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente->activo = !$cliente->activo;
        $cliente->save();

        return response()->json([
            'success' => true,
            'activo' => $cliente->activo,
            'mensaje' => $cliente->activo ? 'Cliente activado' : 'Cliente desactivado'
        ]);
    }

    /**
     * Ver enlaces de acceso del cliente (AJAX)
     */
    public function enlacesAjax(Cliente $cliente)
    {
        // Verificar que el cliente pertenezca a la empresa del usuario
        if ($cliente->empresa_id !== auth()->user()->empresa->id) {
            abort(403);
        }

        $enlaces = $cliente->enlacesAcceso()
                         ->with('creadoPor')
                         ->orderBy('created_at', 'desc')
                         ->get();

        $html = '<div class="table-responsive">';
        
        if ($enlaces->isEmpty()) {
            $html .= '<p class="text-center text-muted">Este cliente no tiene enlaces de acceso generados.</p>';
        } else {
            $html .= '<table class="table table-striped">';
            $html .= '<thead><tr><th>Token</th><th>Válido hasta</th><th>Estado</th><th>Visitas</th><th>Creado por</th><th>Acciones</th></tr></thead>';
            $html .= '<tbody>';
            
            foreach ($enlaces as $enlace) {
                $estado = $enlace->activo && $enlace->expira_en > now() ? 'Activo' : 'Expirado';
                $badgeClass = $estado == 'Activo' ? 'success' : 'secondary';
                
                $html .= '<tr>';
                $html .= '<td><code>' . substr($enlace->token, 0, 8) . '...</code></td>';
                $html .= '<td>' . $enlace->expira_en->format('d/m/Y H:i') . '</td>';
                $html .= '<td><span class="badge bg-' . $badgeClass . '">' . $estado . '</span></td>';
                $html .= '<td>' . $enlace->visitas . '</td>';
                $html .= '<td>' . $enlace->creadoPor->name . '</td>';
                $html .= '<td>';
                
                if ($estado == 'Activo') {
                    $url = route('tienda.acceso', $enlace->token);
                    $html .= '<button class="btn btn-sm btn-outline-primary" onclick="copiarEnlace(\'' . $url . '\')"><i class="bi bi-clipboard"></i></button>';
                }
                
                $html .= '</td></tr>';
            }
            
            $html .= '</tbody></table>';
        }
        
        $html .= '</div>';
        
        $html .= '<div class="mt-3 text-center">';
        $html .= '<a href="' . route('enlaces.crear', ['cliente_id' => $cliente->id]) . '" class="btn btn-primary">';
        $html .= '<i class="bi bi-plus-circle"></i> Crear Nuevo Enlace</a>';
        $html .= '</div>';
        
        return response($html);
    }

    /**
     * Obtener ciudades por departamento (AJAX)
     */
    public function ciudadesAjax(Request $request)
    {
        $ciudades = Ciudad::where('departamento_id', $request->departamento_id)
                         ->orderBy('nombre')
                         ->get(['id', 'nombre']);
                         
        return response()->json($ciudades);
    }
}