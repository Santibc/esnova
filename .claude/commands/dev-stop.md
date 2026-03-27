---
description: "Stop all development services (XAMPP, Laravel, Vite)"
tools: [Bash]
---

# Stopping Development Services

```bash
powershell.exe -ExecutionPolicy Bypass -Command "
Write-Host '🛑 Deteniendo servicios de desarrollo...' -ForegroundColor Yellow

Get-Process -Name 'php' -ErrorAction SilentlyContinue | ForEach-Object { \$_.Kill(); Write-Host '✅ PHP detenido' -ForegroundColor Green }
Get-Process -Name 'node' -ErrorAction SilentlyContinue | ForEach-Object { \$_.Kill(); Write-Host '✅ Node detenido' -ForegroundColor Green }

try {
    Stop-Service 'Apache2.4' -ErrorAction Stop
    Write-Host '✅ Apache detenido' -ForegroundColor Green
} catch {
    Get-Process -Name 'httpd' -ErrorAction SilentlyContinue | ForEach-Object { \$_.Kill(); Write-Host '✅ Apache detenido' -ForegroundColor Green }
}

try {
    Stop-Service 'MySQL' -ErrorAction Stop  
    Write-Host '✅ MySQL detenido' -ForegroundColor Green
} catch {
    Get-Process -Name 'mysqld' -ErrorAction SilentlyContinue | ForEach-Object { \$_.Kill(); Write-Host '✅ MySQL detenido' -ForegroundColor Green }
}

Write-Host '🎉 ¡Servicios detenidos!' -ForegroundColor Green
"
```

🛑 **All development services stopped!**