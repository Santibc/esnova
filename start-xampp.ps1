Write-Host "🚀 Iniciando servicios XAMPP..." -ForegroundColor Green

# Function to check if process is running
function Test-ProcessRunning($processName) {
    return (Get-Process -Name $processName -ErrorAction SilentlyContinue) -ne $null
}

# Function to start service or process
function Start-XamppService($serviceName, $processName, $executablePath) {
    Write-Host "Iniciando $serviceName..." -ForegroundColor Yellow
    
    # Try to start as Windows service first
    try {
        $service = Get-Service -Name $serviceName -ErrorAction SilentlyContinue
        if ($service -and $service.Status -eq 'Stopped') {
            Start-Service -Name $serviceName
            Write-Host "✅ $serviceName iniciado como servicio" -ForegroundColor Green
            return $true
        }
    } catch {
        # Service doesn't exist, try executable
    }
    
    # Check if already running
    if (Test-ProcessRunning $processName) {
        Write-Host "✅ $serviceName ya está ejecutándose" -ForegroundColor Green
        return $true
    }
    
    # Try to start executable
    if (Test-Path $executablePath) {
        try {
            Start-Process -FilePath $executablePath -WindowStyle Hidden
            Start-Sleep 2
            if (Test-ProcessRunning $processName) {
                Write-Host "✅ $serviceName iniciado exitosamente" -ForegroundColor Green
                return $true
            }
        } catch {
            Write-Host "❌ Error iniciando $serviceName" -ForegroundColor Red
        }
    }
    
    return $false
}

# Start Apache
$apacheStarted = Start-XamppService "Apache2.4" "httpd" "C:\xampp\apache\bin\httpd.exe"

# Start MySQL  
$mysqlStarted = Start-XamppService "MySQL" "mysqld" "C:\xampp\mysql\bin\mysqld.exe"

# Final status check
Write-Host "`n🔍 Verificando estado final..." -ForegroundColor Cyan

if ($apacheStarted -or (Test-ProcessRunning "httpd")) {
    Write-Host "✅ Apache: EJECUTÁNDOSE" -ForegroundColor Green
    try {
        $response = Invoke-WebRequest -Uri "http://localhost" -TimeoutSec 5 -UseBasicParsing
        Write-Host "   👉 Accesible en: http://localhost" -ForegroundColor Blue
    } catch {
        Write-Host "   ⚠️  Puerto 80 podría estar ocupado" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ Apache: NO EJECUTÁNDOSE" -ForegroundColor Red
}

if ($mysqlStarted -or (Test-ProcessRunning "mysqld")) {
    Write-Host "✅ MySQL: EJECUTÁNDOSE" -ForegroundColor Green
    Write-Host "   👉 Accesible en: http://localhost/phpmyadmin" -ForegroundColor Blue
} else {
    Write-Host "❌ MySQL: NO EJECUTÁNDOSE" -ForegroundColor Red
}

# Open XAMPP Control Panel if services failed
if (!$apacheStarted -and !$mysqlStarted) {
    Write-Host "`n🔧 Abriendo Panel de Control XAMPP..." -ForegroundColor Yellow
    $xamppControl = "C:\xampp\xampp-control.exe"
    if (Test-Path $xamppControl) {
        Start-Process $xamppControl
    }
}

Write-Host "`n🎉 ¡Proceso completado!" -ForegroundColor Green