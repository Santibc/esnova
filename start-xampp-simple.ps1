# Script simple para iniciar XAMPP y Laravel
Write-Host "🚀 Iniciando entorno de desarrollo..." -ForegroundColor Green

# Iniciar XAMPP Control Panel
Write-Host "🔧 Abriendo XAMPP Control Panel..." -ForegroundColor Yellow
Start-Process "C:\xampp\xampp-control.exe"
Write-Host "✅ XAMPP Control Panel abierto" -ForegroundColor Green
Write-Host ""
Write-Host "📋 INSTRUCCIONES:" -ForegroundColor Cyan
Write-Host "1. En XAMPP Control Panel, haz clic en 'Start' para Apache" -ForegroundColor White
Write-Host "2. Haz clic en 'Start' para MySQL" -ForegroundColor White
Write-Host "3. Presiona Enter aquí cuando ambos servicios estén ejecutándose" -ForegroundColor White
Write-Host ""
Read-Host "Presiona Enter para continuar cuando Apache y MySQL estén activos"

# Verificar servicios
$apache = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
$mysql = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue

if ($apache) {
    Write-Host "✅ Apache ejecutándose" -ForegroundColor Green
} else {
    Write-Host "❌ Apache no detectado - verifica XAMPP Control Panel" -ForegroundColor Red
}

if ($mysql) {
    Write-Host "✅ MySQL ejecutándose" -ForegroundColor Green
} else {
    Write-Host "❌ MySQL no detectado - verifica XAMPP Control Panel" -ForegroundColor Red
}

# Ir al directorio del proyecto
Set-Location "C:\xampp\htdocs\betoge"

# Iniciar Laravel
Write-Host "🐘 Iniciando Laravel..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "php artisan serve"
Start-Sleep 3

# Abrir navegador
Write-Host "🌐 Abriendo navegador..." -ForegroundColor Yellow
Start-Process "http://127.0.0.1:8000"

Write-Host ""
Write-Host "🎉 ¡Listo!" -ForegroundColor Green
Write-Host "🌐 Laravel: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "🗄️ phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor Cyan