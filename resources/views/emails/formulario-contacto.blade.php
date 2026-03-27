<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud Lista de Espera</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #ff00c8 0%, #7000ff 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #444;
            display: inline-block;
            width: 200px;
        }
        .field-value {
            color: #666;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Nueva Solicitud Lista de Espera</h1>
        <p>Esnova - Formulario de Contacto</p>
    </div>
    
    <div class="content">
        <p><strong>Se ha recibido una nueva solicitud para la lista de espera:</strong></p>
        
        <div class="field">
            <span class="field-label">Nombre:</span>
            <span class="field-value">{{ $nombre }}</span>
        </div>
        
        <div class="field">
            <span class="field-label">Tipo de negocio:</span>
            <span class="field-value">{{ $tipo }}</span>
        </div>
        
        <div class="field">
            <span class="field-label">Email:</span>
            <span class="field-value">{{ $email }}</span>
        </div>
        
        @if($whatsapp)
        <div class="field">
            <span class="field-label">WhatsApp:</span>
            <span class="field-value">{{ $whatsapp }}</span>
        </div>
        @endif
        
        <div class="field">
            <span class="field-label">¿Ya vende en línea?:</span>
            <span class="field-value">{{ $online }}</span>
        </div>
        
        <div class="field">
            <span class="field-label">¿Ha invertido en festivales?:</span>
            <span class="field-value">{{ $festival }}</span>
        </div>
        
        <div class="field">
            <span class="field-label">¿Le gustaría participar en eventos?:</span>
            <span class="field-value">{{ $participar_eventos }}</span>
        </div>
        
        @if($redes_sociales)
        <div class="field">
            <span class="field-label">Redes sociales:</span>
            <span class="field-value">{{ $redes_sociales }}</span>
        </div>
        @endif
        
        @if($red_social)
        <div class="field">
            <span class="field-label">Red social preferida:</span>
            <span class="field-value">{{ ucfirst($red_social) }}</span>
        </div>
        @endif
        
        @if($mensaje_adicional)
        <div class="field">
            <span class="field-label">Mensaje adicional:</span>
            <div class="field-value" style="margin-top: 10px; padding: 15px; background: white; border-left: 4px solid #ff00c8; border-radius: 4px;">
                {{ $mensaje_adicional }}
            </div>
        </div>
        @endif
        
        <div class="field">
            <span class="field-label">Fecha de envío:</span>
            <span class="field-value">{{ $fecha_envio }}</span>
        </div>
    </div>
    
    <div class="footer">
        <p>Este email fue generado automáticamente desde el formulario de lista de espera de Esnova.</p>
        <p><strong>Esnova.com.co</strong> - Tu ecosistema de crecimiento sin límites</p>
    </div>
</body>
</html>