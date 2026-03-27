<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

/**
 * Clase principal que maneja la exportación con múltiples hojas
 */
class SolicitudesExport implements WithMultipleSheets
{
    protected $solicitudes;
    
    public function __construct($solicitudes)
    {
        $this->solicitudes = $solicitudes;
    }
    
    /**
     * Define las hojas que se incluirán en el archivo Excel
     */
    public function sheets(): array
    {
        return [
            new ResumenSheet($this->solicitudes),
            new DetalleSheet($this->solicitudes),
            new ProductosSheet($this->solicitudes)
        ];
    }
}

/**
 * Hoja 1: Resumen de Solicitudes
 */
class ResumenSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $solicitudes;
    
    public function __construct($solicitudes)
    {
        $this->solicitudes = $solicitudes;
    }
    
    public function collection()
    {
        return $this->solicitudes;
    }
    
    public function map($solicitud): array
    {
        return [
            $solicitud->numero_solicitud,
            $solicitud->created_at->format('d/m/Y H:i'),
            $solicitud->cliente->numero_identificacion,
            $solicitud->cliente->nombre_contacto,
            $solicitud->cliente->email,
            $solicitud->cliente->telefono,
            $solicitud->cliente->ciudad->nombre . ', ' . $solicitud->cliente->pais->nombre,
            $solicitud->cliente->vendedor->name,
            $solicitud->cliente->listaPrecio?->nombre ?? 'Sin lista',
            $solicitud->total_items,
            '$' . number_format($solicitud->monto_total, 2),
            $solicitud->estado === 'aplicada' ? 'Aplicada' : 'Pendiente',
            $solicitud->estado === 'aplicada' ? $solicitud->aplicada_en->format('d/m/Y H:i') : '',
            $solicitud->aplicadaPor?->name ?? '',
            $solicitud->notas_cliente ?? '',
            $solicitud->observaciones_admin ?? ''
        ];
    }
    
    public function headings(): array
    {
        return [
            'Nº Solicitud',
            'Fecha Solicitud',
            'NIT/CC Cliente',
            'Nombre Cliente',
            'Email Cliente',
            'Teléfono Cliente',
            'Ciudad',
            'Vendedor',
            'Lista de Precios',
            'Total Items',
            'Monto Total',
            'Estado',
            'Fecha Aplicación',
            'Aplicada Por',
            'Notas Cliente',
            'Observaciones Admin'
        ];
    }
    
    public function title(): string
    {
        return 'Resumen Solicitudes';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Encabezados en negrita con fondo azul
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '007BFF']
                ]
            ],
        ];
    }
}

/**
 * Hoja 2: Detalle de Items por Solicitud
 */
class DetalleSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $solicitudes;
    
    public function __construct($solicitudes)
    {
        $this->solicitudes = $solicitudes;
    }
    
    public function collection()
    {
        $detalles = collect();
        
        foreach ($this->solicitudes as $solicitud) {
            foreach ($solicitud->items as $item) {
                $detalles->push([
                    'solicitud' => $solicitud,
                    'item' => $item
                ]);
            }
        }
        
        return $detalles;
    }
    
    public function map($row): array
    {
        $solicitud = $row['solicitud'];
        $item = $row['item'];
        
        return [
            $solicitud->numero_solicitud,
            $solicitud->created_at->format('d/m/Y'),
            $solicitud->cliente->nombre_contacto,
            $item->referencia_producto,
            $item->nombre_producto,
            $item->info_variante ?? '-',
            $item->cantidad,
            '$' . number_format($item->precio_unitario, 2),
            '$' . number_format($item->precio_total, 2),
            $solicitud->estado === 'aplicada' ? 'Aplicada' : 'Pendiente'
        ];
    }
    
    public function headings(): array
    {
        return [
            'Nº Solicitud',
            'Fecha',
            'Cliente',
            'Referencia',
            'Producto',
            'Variante',
            'Cantidad',
            'Precio Unit.',
            'Subtotal',
            'Estado'
        ];
    }
    
    public function title(): string
    {
        return 'Detalle Items';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '28A745']
                ]
            ],
        ];
    }
}

/**
 * Hoja 3: Resumen de Productos más solicitados
 */
class ProductosSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $solicitudes;
    
    public function __construct($solicitudes)
    {
        $this->solicitudes = $solicitudes;
    }
    
    public function collection()
    {
        // Agrupar productos por referencia y sumar cantidades
        $productos = collect();
        $productosAgrupados = [];
        
        foreach ($this->solicitudes as $solicitud) {
            foreach ($solicitud->items as $item) {
                $key = $item->referencia_producto . '|' . ($item->info_variante ?? 'Sin variante');
                
                if (!isset($productosAgrupados[$key])) {
                    $productosAgrupados[$key] = [
                        'referencia' => $item->referencia_producto,
                        'nombre' => $item->nombre_producto,
                        'variante' => $item->info_variante ?? 'Sin variante',
                        'cantidad_total' => 0,
                        'veces_solicitado' => 0,
                        'monto_total' => 0
                    ];
                }
                
                $productosAgrupados[$key]['cantidad_total'] += $item->cantidad;
                $productosAgrupados[$key]['veces_solicitado']++;
                $productosAgrupados[$key]['monto_total'] += $item->precio_total;
            }
        }
        
        // Convertir a colección y ordenar por cantidad total descendente
        foreach ($productosAgrupados as $producto) {
            $productos->push($producto);
        }
        
        return $productos->sortByDesc('cantidad_total');
    }
    
    public function map($producto): array
    {
        return [
            $producto['referencia'],
            $producto['nombre'],
            $producto['variante'],
            $producto['cantidad_total'],
            $producto['veces_solicitado'],
            '$' . number_format($producto['monto_total'], 2)
        ];
    }
    
    public function headings(): array
    {
        return [
            'Referencia',
            'Producto',
            'Variante',
            'Cantidad Total',
            'Veces Solicitado',
            'Monto Total'
        ];
    }
    
    public function title(): string
    {
        return 'Resumen Productos';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFC107']
                ]
            ],
        ];
    }
}