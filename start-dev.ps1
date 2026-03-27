Write-Host '🔧 Iniciando XAMPP...' -ForegroundColor Yellow

# Iniciar Apache
$apache = Get-Process httpd -ErrorAction SilentlyContinue
if (!$apache) {
    try {
        Start-Service Apache2.4 -ErrorAction Stop
        Write-Host '✅ Apache: Servicio iniciado' -ForegroundColor Green
    } catch {
        Start-Process 'C:\xampp\apache\bin\httpd.exe' -WindowStyle Hidden
        Write-Host '✅ Apache: Proceso iniciado' -ForegroundColor Green
    }
} else {
    Write-Host '✅ Apache: Ya ejecutándose' -ForegroundColor Green
}

# Iniciar MySQL
$mysql = Get-Process mysqld -ErrorAction SilentlyContinue
if (!$mysql) {
    try {
        Start-Service MySQL -ErrorAction Stop
        Write-Host '✅ MySQL: Servicio iniciado' -ForegroundColor Green
    } catch {
        Start-Process 'C:\xampp\mysql\bin\mysqld.exe' -ArgumentList '--defaults-file=C:\xampp\mysql\bin\my.ini' -WindowStyle Hidden
        Write-Host '✅ MySQL: Proceso iniciado' -ForegroundColor Green
    }
} else {
    Write-Host '✅ MySQL: Ya ejecutándose' -ForegroundColor Green
}

Start-Sleep 3

# Cambiar al directorio del proyecto
Set-Location 'C:\xampp\htdocs\betoge'

Write-Host '🐘 Iniciando Laravel...' -ForegroundColor Yellow

# Verificar si Laravel ya está corriendo
try {
    $response = Invoke-WebRequest -Uri 'http://127.0.0.1:8000' -TimeoutSec 2 -UseBasicParsing -ErrorAction Stop
    Write-Host '✅ Laravel: Ya ejecutándose' -ForegroundColor Green
} catch {
    # Iniciar Laravel
    $job = Start-Job { Set-Location 'C:\xampp\htdocs\betoge'; php artisan serve --port=8000 }
    Write-Host '✅ Laravel: Iniciando...' -ForegroundColor Green
    Start-Sleep 5
}

# Iniciar Vite si existe package.json
if (Test-Path 'package.json') {
    Write-Host '⚡ Iniciando Vite...' -ForegroundColor Yellow
    try {
        $response = Invoke-WebRequest -Uri 'http://localhost:5173' -TimeoutSec 2 -UseBasicParsing -ErrorAction Stop
        Write-Host '✅ Vite: Ya ejecutándose' -ForegroundColor Green
    } catch {
        $job = Start-Job { Set-Location 'C:\xampp\htdocs\betoge'; npm run dev }
        Write-Host '✅ Vite: Iniciando...' -ForegroundColor Green
        Start-Sleep 3
    }
}

# Abrir Chrome
Write-Host '🌐 Abriendo Chrome...' -ForegroundColor Yellow
try {
    Start-Process chrome 'http://127.0.0.1:8000'
    Write-Host '✅ Chrome abierto' -ForegroundColor Green
} catch {
    Write-Host '⚠️  Abre manualmente: http://127.0.0.1:8000' -ForegroundColor Yellow
}

Write-Host ''
Write-Host '🎉 ¡Entorno iniciado!' -ForegroundColor Green
Write-Host '🌐 Laravel: http://127.0.0.1:8000' -ForegroundColor Cyan
Write-Host '⚡ Vite: http://localhost:5173' -ForegroundColor Cyan
Write-Host '🗄️  phpMyAdmin: http://localhost/phpmyadmin' -ForegroundColor Cyan