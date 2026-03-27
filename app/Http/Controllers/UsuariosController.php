<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Services\UserCreationService;
use Spatie\Permission\Models\Role;      
use App\Services\CalendlyUserImporter;
use App\Services\UserSynchronizationService;

class UsuariosController extends Controller
{

    private UserCreationService $userService;

    public function __construct()
    {
        $this->userService = new UserCreationService();;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['empresa.planMembresia', 'empresa.membresiaActiva.plan'])
                         ->where('id', '!=', 1);

            return DataTables::of($users)
                    ->addColumn('roles', function($u) {
                        // toma el primer rol o concatena varios
                        return $u->getRoleNames()
                                ->map(fn($r) => ucfirst($r))
                                ->join(', ');
                    })
                    ->addColumn('empresa_info', function($user) {
                        if ($user->empresa) {
                            $estado = $user->empresa->activo ? 
                                '<span class="badge bg-success">Activa</span>' : 
                                '<span class="badge bg-danger">Inactiva</span>';
                            return '<strong>' . $user->empresa->nombre . '</strong><br>' . $estado;
                        }
                        return '<span class="text-muted">Sin empresa</span>';
                    })
                    ->addColumn('membresia_info', function($user) {
                        if ($user->empresa && $user->empresa->membresiaActiva) {
                            $membresia = $user->empresa->membresiaActiva;
                            $diasRestantes = $membresia->diasRestantes();
                            
                            if ($diasRestantes == 999) {
                                // Plan sin fecha de fin (gratuito/permanente)
                                $textoTiempo = 'Ilimitado';
                                $colorBadge = 'bg-success';
                            } else {
                                $textoTiempo = $diasRestantes . ' días restantes';
                                $colorBadge = $diasRestantes <= 7 ? 'bg-warning' : 'bg-info';
                            }
                            
                            return '<strong>' . $membresia->plan->nombre . '</strong><br>
                                   <span class="badge ' . $colorBadge . '">' . $textoTiempo . '</span>';
                        } elseif ($user->empresa && $user->empresa->planMembresia) {
                            return '<strong>' . $user->empresa->planMembresia->nombre . '</strong><br>
                                   <span class="badge bg-secondary">Plan actual</span>';
                        }
                        return '<span class="text-muted">Sin membresía</span>';
                    })
                    ->addColumn('tienda_link', function($user) {
                        if ($user->empresa && $user->empresa->activo) {
                            $url = route('tienda.empresa', $user->empresa->slug);
                            return '<a href="' . $url . '" target="_blank" class="btn btn-outline-primary btn-sm" title="Ver tienda">
                                   <i class="bi bi-shop"></i></a>';
                        }
                        return '<span class="text-muted">-</span>';
                    })
                ->addColumn('action', function ($user) {
                    $editUrl = route('usuarios.form', $user->id);

                    $buttons = '<div class="d-flex justify-content-center align-items-center gap-2">';
                    $buttons .= '<a href="' . $editUrl . '" class="btn btn-outline-info btn-sm" title="Editar">';
                    $buttons .= '<i class="bi bi-pencil"></i>';
                    $buttons .= '</a>';
                    
                    // Botón para activar/desactivar empresa si existe
                    if ($user->empresa) {
                        $estadoTexto = $user->empresa->activo ? 'Desactivar' : 'Activar';
                        $estadoClass = $user->empresa->activo ? 'btn-outline-danger' : 'btn-outline-success';
                        $estadoIcon = $user->empresa->activo ? 'bi-x-circle' : 'bi-check-circle';
                        
                        $buttons .= '<button class="btn ' . $estadoClass . ' btn-sm toggle-empresa-btn" 
                                   title="' . $estadoTexto . ' empresa"
                                   data-user-id="' . $user->id . '"
                                   data-empresa-id="' . $user->empresa->id . '"
                                   data-estado="' . ($user->empresa->activo ? 0 : 1) . '">';
                        $buttons .= '<i class="' . $estadoIcon . '"></i>';
                        $buttons .= '</button>';
                    }
                    
                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['action', 'empresa_info', 'membresia_info', 'tienda_link'])
                ->make(true);
        }

        return view('usuarios.usuarios_index');
    }



    public function form(User $user = null)
    {
        $user  = $user ?? new User();
        $roles = Role::pluck('name','name');      // ← lista de roles (clave and valor = nombre)

        return view('usuarios.usuarios_form', compact('user','roles'));
    }

    public function guardar(Request $request)
    {
        $user = $request->id ? User::findOrFail($request->id) : null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user?->id)
            ],
            'password' => $user ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'],
             'role'     => ['required','exists:roles,name'],   
        ];

        $messages = [
            'required' => 'Este campo es obligatorio.',
            'email' => 'Debe ser un correo válido.',
            'max' => 'No debe superar los :max caracteres.',
            'unique' => 'Ya existe un usuario con este correo.',
            'min' => 'Debe tener al menos :min caracteres.',
        ];

        $data = $request->validate($rules, $messages);

        // 1) Crear o actualizar usuario
        if ($user) {
            $this->userService->update($user, $data);
        } else {
            $user = $this->userService->create($data);
        }
   $user->syncRoles($data['role']);
        return redirect()->route('usuarios')->with('success', 'Usuario guardado correctamente.');
    }

    public function cambiarEstadoEmpresa(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'estado' => 'required|boolean'
        ]);

        $empresa = \App\Models\Empresa::findOrFail($request->empresa_id);
        $empresa->update(['activo' => $request->estado]);

        $mensaje = $request->estado ? 'Empresa activada correctamente' : 'Empresa desactivada correctamente';
        
        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'nuevo_estado' => $request->estado
        ]);
    }
}
