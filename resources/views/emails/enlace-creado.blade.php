<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Catálogo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #218838;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .url-box {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            word-break: break-all;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido al Catálogo de Productos!</h1>
        </div>
        
        <p>Estimado/a <strong>{{ $enlace->cliente->nombre_contacto }}</strong>,</p>
        
        <p>Se ha generado un enlace de acceso exclusivo para que pueda explorar nuestro catálogo de productos y realizar solicitudes de cotización de manera fácil y rápida.</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0;">Información del Enlace:</h3>
            <ul>
                <li><strong>Válido por:</strong> {{ $enlace->dias_validos }} días</li>
                <li><strong>Expira el:</strong> {{ $enlace->expira_en->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $url }}" class="btn">Acceder al Catálogo</a>
        </div>
        
        <p><strong>O copie y pegue el siguiente enlace en su navegador:</strong></p>
        <div class="url-box">{{ $url }}</div>
        
        @if($enlace->notas)
        <div class="info-box" style="border-left-color: #28a745;">
            <h4 style="margin-top: 0;">Información adicional:</h4>
            <p>{{ $enlace->notas }}</p>
        </div>
        @endif
        
        <h3>¿Cómo funciona?</h3>
        <ol>
            <li>Haga clic en el botón o copie el enlace en su navegador</li>
            <li>Explore nuestro catálogo de productos</li>
            <li>Agregue los productos deseados al carrito</li>
            <li>Envíe su solicitud de cotización</li>
            <li>Recibirá una confirmación por correo electrónico</li>
        </ol>
        
        <div class="footer">
            <p>Este enlace es único y personal. Por favor, no lo comparta con terceros.</p>
            <p>Si tiene alguna pregunta, no dude en contactar a su vendedor:<br>
            <strong>{{ $enlace->creadoPor->name }}</strong> - {{ $enlace->creadoPor->email }}</p>
            <p>&copy; {{ date('Y') }} - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>