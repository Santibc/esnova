@echo off
echo Starting XAMPP Services...

REM Try to start Apache service
echo Starting Apache...
sc start "Apache2.4" >nul 2>&1
if errorlevel 1 (
    echo Apache service not found, trying direct start...
    "C:\xampp\apache\bin\httpd.exe" -k start >nul 2>&1
    if errorlevel 1 (
        echo Apache start failed, trying alternative...
        start "" /min "C:\xampp\apache_start.bat" >nul 2>&1
    ) else (
        echo Apache started successfully
    )
) else (
    echo Apache service started
)

REM Try to start MySQL service  
echo Starting MySQL...
sc start "MySQL" >nul 2>&1
if errorlevel 1 (
    echo MySQL service not found, trying direct start...
    start "" /min "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" >nul 2>&1
    if errorlevel 1 (
        echo MySQL start failed, trying alternative...
        start "" /min "C:\xampp\mysql_start.bat" >nul 2>&1
    ) else (
        echo MySQL started successfully
    )
) else (
    echo MySQL service started
)

echo.
echo Checking processes...
tasklist | findstr /i "httpd apache mysqld" >nul 2>&1
if errorlevel 1 (
    echo No XAMPP processes found running
    echo Opening XAMPP Control Panel...
    start "" "C:\xampp\xampp-control.exe"
) else (
    echo XAMPP processes are running!
    tasklist | findstr /i "httpd apache mysqld"
)

echo.
echo Done! Check if services are running on:
echo Apache: http://localhost
echo PhpMyAdmin: http://localhost/phpmyadmin
pause