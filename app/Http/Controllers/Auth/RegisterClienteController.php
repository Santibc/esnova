<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Compra;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterClienteController extends Controller
{
    /**
     * Display the registration view for clients.
     */
    public function create(): View
    {
        return view('auth.register-cliente');
    }

    /**
     * Handle an incoming registration request for clients.
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

            // Asignar rol cliente
            $user->assignRole('cliente');

            // Vincular compras existentes por email
            Compra::where('email_cliente', $request->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            // Redirigir al panel de cliente
            return redirect()->route('cliente.compras');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'email' => 'Error al crear la cuenta: ' . $e->getMessage()
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
