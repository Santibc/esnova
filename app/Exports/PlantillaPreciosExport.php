<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PlantillaPreciosExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        $productos = Producto::limit(20)->get();
        $data = collect();
        
        foreach ($productos as $producto) {
            // Obtener precios actuales
            $row = [
                'referencia' => $producto->referencia,
            ];
            
            // Agregar precios de cada lista
            for ($i = 1; $i <= 6; $i++) {
                $precio = $producto->precios()->where('lista_precio_id', $i)->first();
                $nombreLista = $i <= 2 ? "Export{$i}" : "Local" . ($i - 2);
                $row[$nombreLista] = $precio ? $precio->precio : null;
            }
            
            $data->push($row);
        }
        
        // Si no hay productos, agregar ejemplos
        if ($data->isEmpty()) {
            $data->push([
                'referencia' => 'PROD001',
                'Export1' => 100.00,
                'Export2' => 110.00,
                'Local1' => 90.00,
                'Local2' => 95.00,
                'Local3' => 92.00,
                'Local4' => 93.00,
            ]);
            $data->push([
                'referencia' => 'PROD002',
                'Export1' => 200.00,
                'Export2' => 220.00,
                'Local1' => 180.00,
                'Local2' => 190.00,
                'Local3' => 185.00,
                'Local4' => 187.00,
            ]);
            $data->push([
                'referencia' => 'PROD003',
                'Export1' => null,
                'Export2' => null,
                'Local1' => null,
                'Local2' => null,
                'Local3' => null,
                'Local4' => null,
            ]);
        }
        
        return $data;
    }
    
    public function headings(): array
    {
        return [
            'Referencia',
            'Export1',
            'Export2',
            'Local1',
            'Local2',
            'Local3',
            'Local4'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Estilo para los encabezados
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Altura de la fila de encabezados
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Aplicar bordes a todas las celdas con datos
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:G{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ]);
        
        // Formato de moneda para las columnas de precios
        $sheet->getStyle("B2:G{$highestRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');
        
        // Centrar la columna de referencia
        $sheet->getStyle("A2:A{$highestRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        return [];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Referencia
            'B' => 15,  // Export1
            'C' => 15,  // Export2
            'D' => 15,  // Local1
            'E' => 15,  // Local2
            'F' => 15,  // Local3
            'G' => 15,  // Local4
        ];
    }
    
    public function title(): string
    {
        return 'Plantilla Precios';
    }
}