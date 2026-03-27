<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parametros;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index()
    {
        $membresiaActiva = null;
        
        if (Auth::user()->hasRole('empresa') && Auth::user()->empresa) {
            $membresiaActiva = Auth::user()->empresa->membresiaActiva;
        }
        
        return view('dashboard', compact('membresiaActiva'));
    }
}
