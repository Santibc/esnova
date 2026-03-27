<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enlace Inválido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                        <h3 class="mt-3">Enlace Inválido o Expirado</h3>
                        <p class="text-muted mt-3">
                            El enlace que está intentando acceder no es válido o ha expirado.
                            Por favor, contacte a su vendedor para obtener un nuevo enlace de acceso.
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-house"></i> Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>