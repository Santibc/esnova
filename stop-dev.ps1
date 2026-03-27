# Script para detener todos los servicios de desarrollo
Write-Host "Deteniendo entorno de desarrollo..." -ForegroundColor Red

# Detener jobs de PowerShell
Write-Host "Deteniendo jobs de Laravel y Vite..." -ForegroundColor Yellow
Get-Job | Remove-Job -Force -ErrorAction SilentlyContinue
Write-Host "Jobs detenidos" -ForegroundColor Green

# Detener procesos PHP (Laravel)
Write-Host "Deteniendo Laravel..." -ForegroundColor Yellow
Get-Process -Name "php" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Write-Host "Laravel detenido" -ForegroundColor Green

# Detener Apache
Write-Host "Deteniendo Apache..." -ForegroundColor Yellow
try {
    Stop-Service "Apache2.4" -ErrorAction Stop
    Write-Host "Apache servicio detenido" -ForegroundColor Green
} catch {
    Get-Process -Name "httpd" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue  
    Write-Host "Apache proceso detenido" -ForegroundColor Green
}

# Detener MySQL
Write-Host "Deteniendo MySQL..." -ForegroundColor Yellow
try {
    Stop-Service "MySQL" -ErrorAction Stop
    Write-Host "MySQL servicio detenido" -ForegroundColor Green
} catch {
    Get-Process -Name "mysqld" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
    Write-Host "MySQL proceso detenido" -ForegroundColor Green
}

# Detener Node.js/Vite
Write-Host "Deteniendo Vite/Node..." -ForegroundColor Yellow
Get-Process -Name "node" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Write-Host "Vite detenido" -ForegroundColor Green

Start-Sleep 2

Write-Host ""
Write-Host "Entorno de desarrollo detenido completamente!" -ForegroundColor Green
Write-Host "Para reiniciar usa: .\dev-auto.ps1" -ForegroundColor Cyan