<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\PlanMembresia;
use App\Models\Membresia;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telefono' => ['nullable', 'string', 'max:255'],
            'nombre_empresa' => ['required', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
        ]);

        DB::beginTransaction();
        
        try {
            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'password' => Hash::make($request->password),
            ]);
            
            // Obtener plan fundador
            $planFundador = PlanMembresia::where('slug', 'plan-fundador')->first();
            
            if (!$planFundador) {
                throw new \Exception('Plan fundador no encontrado. Por favor contacte al administrador.');
            }
            
            // Crear empresa con plan fundador
            $empresa = Empresa::create([
                'usuario_id' => $user->id,
                'nombre' => $request->nombre_empresa,
                'slug' => $this->generarSlugUnico($request->nombre_empresa),
                'email' => $request->email,
                'telefono' => $request->telefono,
                'plan_membresia_id' => $planFundador->id,
                'activo' => true
            ]);
            
            // Crear membresía activa para plan fundador
            Membresia::create([
                'empresa_id' => $empresa->id,
                'plan_membresia_id' => $planFundador->id,
                'estado' => 'activa',
                'precio_pagado' => 0,
                'fecha_inicio' => now(),
                'fecha_fin' => null // Plan gratuito no expira
            ]);
            
            DB::commit();
            
            event(new Registered($user));
            
            Auth::login($user);
            
            return redirect(RouteServiceProvider::HOME);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si el usuario fue creado, eliminarlo
            if (isset($user) && $user->exists) {
                $user->delete();
            }
            
            return back()->withErrors([
                'email' => 'Error al crear la cuenta: ' . $e->getMessage()
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }
    
    /**
     * Generar un slug único para la empresa
     */
    private function generarSlugUnico($nombre)
    {
        $slug = Str::slug($nombre);
        $slugOriginal = $slug;
        $contador = 1;
        
        while (Empresa::where('slug', $slug)->exists()) {
            $slug = $slugOriginal . '-' . $contador;
            $contador++;
        }
        
        return $slug;
    }
}