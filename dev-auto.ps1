param(
    [string]$Project = "betoge"
)

$baseDir = "C:\xampp\htdocs"
$projectPath = "$baseDir\$Project"

Write-Host "Iniciando $Project AUTOMATICO..." -ForegroundColor Green

# Verificar proyecto existe
if (!(Test-Path $projectPath)) {
    Write-Host "Error: Proyecto '$Project' no existe en $projectPath" -ForegroundColor Red
    exit 1
}

# Funcion para verificar si un puerto esta en uso
function Test-Port($port) {
    $connection = New-Object System.Net.Sockets.TcpClient
    try {
        $connection.Connect("127.0.0.1", $port)
        $connection.Close()
        return $true
    } catch {
        return $false
    }
}

# Matar procesos existentes si estan ejecutandose
Write-Host "Deteniendo servicios existentes..." -ForegroundColor Yellow
Get-Process -Name "httpd" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Get-Process -Name "mysqld" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Get-Process -Name "php" -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
Start-Sleep 2

# INICIAR APACHE AUTOMATICAMENTE
Write-Host "Iniciando Apache..." -ForegroundColor Yellow

# Metodo 1: Intentar como servicio
try {
    Start-Service "Apache2.4" -ErrorAction Stop
    Write-Host "Apache iniciado como servicio" -ForegroundColor Green
} catch {
    # Metodo 2: Ejecutar directamente
    try {
        $apacheProcess = Start-Process "C:\xampp\apache\bin\httpd.exe" -ArgumentList "-D FOREGROUND" -WindowStyle Hidden -PassThru -ErrorAction Stop
        Write-Host "Apache iniciado como proceso" -ForegroundColor Green
    } catch {
        # Metodo 3: Usar xampp_start.exe
        try {
            Start-Process "C:\xampp\xampp_start.exe" -ErrorAction Stop
            Write-Host "Apache iniciado con xampp_start" -ForegroundColor Green
        } catch {
            Write-Host "Error iniciando Apache" -ForegroundColor Red
        }
    }
}

Start-Sleep 3

# INICIAR MYSQL AUTOMATICAMENTE  
Write-Host "Iniciando MySQL..." -ForegroundColor Yellow

# Metodo 1: Intentar como servicio
try {
    Start-Service "MySQL" -ErrorAction Stop  
    Write-Host "MySQL iniciado como servicio" -ForegroundColor Green
} catch {
    # Metodo 2: Ejecutar directamente
    try {
        $mysqlProcess = Start-Process "C:\xampp\mysql\bin\mysqld.exe" -ArgumentList "--defaults-file=C:\xampp\mysql\bin\my.ini", "--standalone", "--console" -WindowStyle Hidden -PassThru -ErrorAction Stop
        Write-Host "MySQL iniciado como proceso" -ForegroundColor Green
    } catch {
        Write-Host "Error iniciando MySQL" -ForegroundColor Red
    }
}

Start-Sleep 5

# VERIFICAR SERVICIOS
Write-Host "Verificando servicios..." -ForegroundColor Yellow
$apacheRunning = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
$mysqlRunning = Get-Process -Name "mysqld" -ErrorAction SilentlyContinue
$apache80 = Test-Port 80
$mysql3306 = Test-Port 3306

if ($apacheRunning) {
    Write-Host "Apache ejecutandose (PID: $($apacheRunning.Id))" -ForegroundColor Green
} else {
    Write-Host "Apache no detectado" -ForegroundColor Red
}

if ($mysqlRunning) {
    Write-Host "MySQL ejecutandose (PID: $($mysqlRunning.Id))" -ForegroundColor Green  
} else {
    Write-Host "MySQL no detectado" -ForegroundColor Red
}

if ($apache80) {
    Write-Host "Puerto 80 (Apache) disponible" -ForegroundColor Green
} else {
    Write-Host "Puerto 80 no responde" -ForegroundColor Red
}

if ($mysql3306) {
    Write-Host "Puerto 3306 (MySQL) disponible" -ForegroundColor Green
} else {  
    Write-Host "Puerto 3306 no responde" -ForegroundColor Red
}

# Si no estan ejecutandose, forzar inicio alternativo
if (!$apacheRunning -or !$apache80) {
    Write-Host "Intentando metodo alternativo para Apache..." -ForegroundColor Yellow
    Start-Process "C:\xampp\apache\bin\httpd.exe" -WindowStyle Hidden
    Start-Sleep 2
}

if (!$mysqlRunning -or !$mysql3306) {
    Write-Host "Intentando metodo alternativo para MySQL..." -ForegroundColor Yellow  
    Start-Process "C:\xampp\mysql\bin\mysqld.exe" -ArgumentList "--defaults-file=C:\xampp\mysql\bin\my.ini" -WindowStyle Hidden
    Start-Sleep 3
}

Set-Location $projectPath

# INICIAR LARAVEL AUTOMATICAMENTE
Write-Host "Iniciando servidor Laravel..." -ForegroundColor Yellow

# Verificar si Laravel ya esta ejecutandose en puerto 8000
if (Test-Port 8000) {
    Write-Host "Puerto 8000 ya esta en uso - deteniendo proceso..." -ForegroundColor Yellow
    Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*artisan serve*" } | Stop-Process -Force -ErrorAction SilentlyContinue
    Start-Sleep 2
}

# Iniciar Laravel en background
$laravelJob = Start-Job -ScriptBlock {
    param($path)
    Set-Location $path
    php artisan serve --host=127.0.0.1 --port=8000
} -ArgumentList $projectPath

Write-Host "Laravel iniciando en puerto 8000..." -ForegroundColor Green

# INICIAR VITE SI EXISTE
if (Test-Path "package.json") {
    Write-Host "Iniciando servidor Vite..." -ForegroundColor Yellow
    
    if (Test-Port 5173) {
        Write-Host "Puerto 5173 ya esta en uso" -ForegroundColor Yellow
    } else {
        $viteJob = Start-Job -ScriptBlock {
            param($path)
            Set-Location $path
            npm run dev
        } -ArgumentList $projectPath
        Write-Host "Vite iniciando en puerto 5173..." -ForegroundColor Green
    }
}

# Esperar que Laravel inicie
Write-Host "Esperando que Laravel inicie completamente..." -ForegroundColor Yellow
$timeout = 15
$counter = 0

while ($counter -lt $timeout) {
    Start-Sleep 1
    if (Test-Port 8000) {
        Write-Host "Laravel respondiendo en puerto 8000!" -ForegroundColor Green
        break
    }
    $counter++
    Write-Host "." -NoNewline -ForegroundColor Yellow
}

if ($counter -eq $timeout) {
    Write-Host "Timeout esperando Laravel" -ForegroundColor Red
}

# ABRIR NAVEGADOR AUTOMATICAMENTE
Write-Host "Abriendo navegador..." -ForegroundColor Yellow
try {
    Start-Process "http://127.0.0.1:8000"
    Write-Host "Navegador abierto" -ForegroundColor Green
} catch {
    Write-Host "No se pudo abrir el navegador automaticamente" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "ENTORNO COMPLETAMENTE AUTOMATICO INICIADO!" -ForegroundColor Green
Write-Host "Laravel: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "Vite: http://localhost:5173" -ForegroundColor Cyan  
Write-Host "phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor Cyan
Write-Host ""
Write-Host "Para detener todo: Get-Job | Remove-Job -Force" -ForegroundColor Yellow