<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Solicitud de Cotización - {{ $solicitud->numero_solicitud }}</title>
    <style>
        @page {
            margin: 100px 25px;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            position: fixed;
            top: -60px;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-number:after {
            content: counter(page);
        }
        
        .content {
            margin-top: 20px;
        }
        
        h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        h2 {
            color: #333;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-col {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 14px;
        }
        
        .notes-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 123, 255, 0.1);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3 style="margin: 0;">SOLICITUD DE COTIZACIÓN</h3>
    </div>
    
    <div class="footer">
        <p>Página <span class="page-number"></span> | Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="watermark">
        {{ $solicitud->estado === 'aplicada' ? 'CONFIRMADA' : 'PENDIENTE' }}
    </div>
    
    <div class="content">
        <h1>Solicitud Nº {{ $solicitud->numero_solicitud }}</h1>
        
        <div class="info-section">
            <h2>Información del Cliente</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Cliente:</span> {{ $solicitud->cliente->nombre_contacto }}
                    </div>
                    <div class="info-col">
                        <span class="info-label">NIT/CC:</span> {{ $solicitud->cliente->numero_identificacion }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Email:</span> {{ $solicitud->cliente->email }}
                    </div>
                    <div class="info-col">
                        <span class="info-label">Teléfono:</span> {{ $solicitud->cliente->telefono }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Ciudad:</span> {{ $solicitud->cliente->ciudad->nombre }}, {{ $solicitud->cliente->pais->nombre }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Información de la Solicitud</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Fecha de Solicitud:</span> {{ $solicitud->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="info-col">
                        <span class="info-label">Estado:</span> 
                        <span class="badge {{ $solicitud->estado === 'aplicada' ? 'badge-success' : 'badge-warning' }}">
                            {{ $solicitud->estado === 'aplicada' ? 'CONFIRMADA' : 'PENDIENTE' }}
                        </span>
                    </div>
                </div>
                @if($solicitud->estado === 'aplicada')
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Fecha de Confirmación:</span> {{ $solicitud->aplicada_en->format('d/m/Y H:i') }}
                    </div>
                    <div class="info-col">
                        <span class="info-label">Procesada por:</span> {{ $solicitud->aplicadaPor->name }}
                    </div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-col">
                        <span class="info-label">Vendedor Asignado:</span> {{ $solicitud->cliente->vendedor->name }}
                    </div>
                    <div class="info-col">
                        <span class="info-label">Total de Items:</span> {{ $solicitud->total_items }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h2>Productos Solicitados</h2>
            <table>
                <thead>
                    <tr>
                        <th width="60">Imagen</th>
                        <th width="80">Referencia</th>
                        <th>Producto</th>
                        <th width="60" class="text-center">Cantidad</th>
                        <th width="80" class="text-right">Precio Unit.</th>
                        <th width="90" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitud->items as $item)
                    <tr>
                        <td class="text-center">
                            @if($item->producto->imagenPrincipal)
                                <img src="{{ public_path($item->producto->imagenPrincipal->ruta_imagen) }}" 
                                     class="product-image" 
                                     alt="{{ $item->nombre_producto }}">
                            @else
                                <div style="width:50px;height:50px;background:#f8f9fa;display:inline-block;text-align:center;line-height:50px;color:#999;font-size:10px;">
                                    Sin imagen
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->referencia_producto }}</td>
                        <td>
                            {{ $item->nombre_producto }}
                            @if($item->info_variante)
                                <br><small style="color:#666;">{{ $item->info_variante }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->cantidad }}</td>
                        <td class="text-right">${{ number_format($item->precio_unitario, 2) }}</td>
                        <td class="text-right">${{ number_format($item->precio_total, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="5" class="text-right">TOTAL:</td>
                        <td class="text-right">${{ number_format($solicitud->monto_total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        @if($solicitud->notas_cliente)
        <div class="notes-box">
            <h3 style="margin-top: 0;">Notas del Cliente:</h3>
            <p>{{ $solicitud->notas_cliente }}</p>
        </div>
        @endif
        
        @if($solicitud->observaciones_admin)
        <div class="notes-box">
            <h3 style="margin-top: 0;">Observaciones del Vendedor:</h3>
            <p>{{ $solicitud->observaciones_admin }}</p>
        </div>
        @endif
    </div>
</body>
</html>