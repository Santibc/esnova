<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ciudad;

class CiudadController extends Controller
{
    public function byDepartamento(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $ciudades = Ciudad::where('departamento_id', $request->departamento_id)
                          ->orderBy('nombre')
                          ->get(['id','nombre']);

        return response()->json($ciudades);
    }
}
