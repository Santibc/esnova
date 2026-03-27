<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class MisComprasController extends Controller
{
    /**
     * Mostrar lista de compras del cliente
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener compras del usuario por user_id o email
        $compras = Compra::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('email_cliente', $user->email);
        })
        ->with(['items.producto', 'ciudad', 'empresa'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('cliente.mis-compras.index', compact('compras'));
    }

    /**
     * Mostrar detalle de una compra
     */
    public function show($id)
    {
        $user = Auth::user();

        $compra = Compra::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('email_cliente', $user->email);
        })
        ->with(['items.producto.imagenPrincipal', 'items.variante', 'ciudad', 'empresa', 'envio'])
        ->findOrFail($id);

        return view('cliente.mis-compras.show', compact('compra'));
    }
}
