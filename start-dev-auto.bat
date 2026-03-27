@echo off
title Betoge - Inicio Automatico
echo.
echo ========================================
echo    BETOGE - DESARROLLO AUTOMATICO
echo ========================================
echo.

powershell.exe -ExecutionPolicy Bypass -File "%~dp0dev-auto.ps1"

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERROR: No se pudo ejecutar el script
    echo Presiona cualquier tecla para salir...
    pause > nul
    exit /b 1
)

echo.
echo Presiona cualquier tecla para cerrar...
pause > nul