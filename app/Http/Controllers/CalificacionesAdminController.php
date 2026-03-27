<?php

namespace App\Http\Controllers;

use App\Models\CalificacionProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CalificacionesAdminController extends Controller
{
    /**
     * Listar calificaciones pendientes de aprobación (solo principales)
     */
    public function index()
    {
        $calificaciones = CalificacionProducto::with(['producto', 'user', 'parent'])
            ->pendientes()
            ->principales()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('calificaciones.index', compact('calificaciones'));
    }

    /**
     * Listar calificaciones aprobadas (solo principales con sus respuestas)
     */
    public function aprobadas()
    {
        $calificaciones = CalificacionProducto::with(['producto', 'user', 'respuestasAprobadas'])
            ->aprobadas()
            ->principales()
            ->withCount('respuestasAprobadas')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('calificaciones.aprobadas', compact('calificaciones'));
    }

    /**
     * Listar respuestas pendientes de aprobación
     */
    public function respuestas()
    {
        $calificaciones = CalificacionProducto::with(['producto', 'user', 'parent.producto'])
            ->pendientes()
            ->whereNotNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('calificaciones.respuestas', compact('calificaciones'));
    }

    /**
     * Aprobar una calificación
     */
    public function aprobar($id)
    {
        $calificacion = CalificacionProducto::findOrFail($id);
        $calificacion->update(['aprobada' => true]);

        $tipo = $calificacion->esRespuesta() ? 'Respuesta' : 'Calificación';
        return back()->with('success', "{$tipo} aprobada correctamente");
    }

    /**
     * Rechazar (eliminar) una calificación
     */
    public function rechazar($id)
    {
        $calificacion = CalificacionProducto::findOrFail($id);

        // Eliminar imagen física si existe
        if ($calificacion->imagen && File::exists(public_path($calificacion->imagen))) {
            File::delete(public_path($calificacion->imagen));
        }

        $tipo = $calificacion->esRespuesta() ? 'Respuesta' : 'Calificación';
        $calificacion->delete();

        return back()->with('success', "{$tipo} rechazada y eliminada");
    }
}
