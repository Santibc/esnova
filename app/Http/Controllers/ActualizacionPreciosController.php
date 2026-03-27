<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionPrecio;
use App\Models\Producto;
use App\Models\ListaPrecio;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlantillaPreciosExport;


class ActualizacionPreciosController extends Controller
{
    public function historial(Request $request)
    {
        if ($request->ajax()) {
            $query = ActualizacionPrecio::with('usuario')
                ->select('actualizaciones_precios.*')
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addColumn('usuario', fn($a) => $a->usuario->name)
                ->addColumn('fecha', fn($a) => $a->created_at->format('d/m/Y H:i'))
                ->addColumn('estado_badge', function($a) {
                    $badges = [
                        'procesando' => 'warning',
                        'completado' => 'success',
                        'error' => 'danger'
                    ];
                    $badge = $badges[$a->estado] ?? 'secondary';
                    return '<span class="badge bg-'.$badge.'">'.$a->estado.'</span>';
                })
                ->addColumn('resultados', function($a) {
                    return "Exitosas: {$a->actualizaciones_exitosas} / Fallidas: {$a->actualizaciones_fallidas}";
                })
                ->addColumn('porcentaje', function($a) {
                    $porcentaje = $a->porcentaje_exito;
                    $color = $porcentaje >= 80 ? 'success' : ($porcentaje >= 50 ? 'warning' : 'danger');
                    return '<div class="progress" style="height: 20px;">
                              <div class="progress-bar bg-'.$color.'" style="width: '.$porcentaje.'%">
                                '.$porcentaje.'%
                              </div>
                            </div>';
                })
                ->addColumn('action', function($a) {
                    $buttons = '<div class="btn-group">';
                    $buttons .= '<button onclick="verDetalles('.$a->id.')" class="btn btn-sm btn-info" title="Ver detalles">
                                   <i class="bi bi-eye"></i>
                                 </button>';
if ($a->ruta_archivo && file_exists(public_path($a->ruta_archivo))) {
    $buttons .= '<a href="'.asset($a->ruta_archivo).'" download class="btn btn-sm btn-secondary" title="Descargar archivo">
                   <i class="bi bi-download"></i>
                 </a>';
}

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['estado_badge', 'porcentaje', 'action'])
                ->make(true);
        }

        return view('productos.historial_precios');
    }

    public function verDetalle($id)
    {
        $actualizacion = ActualizacionPrecio::with('usuario')->findOrFail($id);
        
        return response()->json([
            'actualizacion' => $actualizacion,
            'errores' => $actualizacion->errores ?? [],
            'procesados' => $actualizacion->detalles_procesados ?? []
        ]);
    }

    // Descargar plantilla CSV con punto y coma
    public function descargarPlantillaCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_precios.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Agregar BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados con punto y coma
            fputcsv($file, ['Referencia', 'Export1', 'Export2', 'Local1', 'Local2', 'Local3', 'Local4'], ';');
            
            // Obtener algunos productos de ejemplo
            $productos = Producto::limit(10)->get();
            
            if ($productos->count() > 0) {
                foreach ($productos as $producto) {
                    // Obtener precios actuales si existen
                    $precios = [];
                    for ($i = 1; $i <= 6; $i++) {
                        $precio = $producto->precios()->where('lista_precio_id', $i)->first();
                        $precios[] = $precio ? number_format($precio->precio, 2, '.', '') : '';
                    }
                    
                    fputcsv($file, [
                        $producto->referencia,
                        $precios[0], // Export1
                        $precios[1], // Export2
                        $precios[2], // Local1
                        $precios[3], // Local2
                        $precios[4], // Local3
                        $precios[5], // Local4
                    ], ';');
                }
            } else {
                // Ejemplos genéricos si no hay productos
                fputcsv($file, ['PROD001', '100.00', '110.00', '90.00', '95.00', '92.00', '93.00'], ';');
                fputcsv($file, ['PROD002', '200.00', '220.00', '180.00', '190.00', '185.00', '187.00'], ';');
                fputcsv($file, ['PROD003', '', '', '', '', '', ''], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Descargar plantilla Excel
    public function descargarPlantillaExcel()
    {
        return Excel::download(new PlantillaPreciosExport, 'plantilla_precios.xlsx');
    }
        public function descargarArchivoActualizacion($id)
    {
        $actualizacion = ActualizacionPrecio::findOrFail($id);

        if (! $actualizacion->ruta_archivo) {
            return back()->with('error', 'No hay archivo asociado a esta actualización.');
        }

        $fullPath = public_path($actualizacion->ruta_archivo);
        if (! file_exists($fullPath)) {
            return back()->with('error', 'Archivo no encontrado en el servidor.');
        }

        return response()->download($fullPath, $actualizacion->nombre_archivo);
    }
}
