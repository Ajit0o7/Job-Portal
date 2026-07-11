@echo off
title Job Portal - XAMPP Setup
echo ============================================
echo   Job Portal - One-Click XAMPP Setup
echo ============================================
echo.

:: Check for admin rights
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Please run this script as Administrator.
    echo Right-click setup.bat and select "Run as administrator"
    pause
    exit /b 1
)

:: Try to find XAMPP httpd.conf in common locations
set "HTTPD_CONF="

if exist "C:\xampp\apache\conf\httpd.conf" (
    set "HTTPD_CONF=C:\xampp\apache\conf\httpd.conf"
)
if exist "D:\xampp\apache\conf\httpd.conf" (
    set "HTTPD_CONF=D:\xampp\apache\conf\httpd.conf"
)
if exist "C:\wamp64\bin\apache\apache2.4.58\conf\httpd.conf" (
    set "HTTPD_CONF=C:\wamp64\bin\apache\apache2.4.58\conf\httpd.conf"
)

if "%HTTPD_CONF%"=="" (
    echo [ERROR] Could not find httpd.conf automatically.
    echo Please open it manually and:
    echo   1. Change "AllowOverride None" to "AllowOverride All"
    echo   2. Uncomment "LoadModule rewrite_module modules/mod_rewrite.so"
    echo.
    pause
    exit /b 1
)

echo [OK] Found Apache config at: %HTTPD_CONF%
echo.
echo [1/3] Backing up httpd.conf...
copy "%HTTPD_CONF%" "%HTTPD_CONF%.bak" >nul
echo [OK] Backup saved as httpd.conf.bak

echo.
echo [2/3] Enabling mod_rewrite and AllowOverride All...

powershell -Command "$content = Get-Content '%HTTPD_CONF%' -Raw; $content = $content -replace '#LoadModule rewrite_module', 'LoadModule rewrite_module'; $content = $content -replace 'AllowOverride None', 'AllowOverride All'; Set-Content '%HTTPD_CONF%' $content -Encoding UTF8; Write-Host '[OK] httpd.conf updated successfully.'"

echo.
echo [3/3] Restarting Apache...
if exist "C:\xampp\apache\bin\httpd.exe" (
    "C:\xampp\apache\bin\httpd.exe" -k restart >nul 2>&1
    echo [OK] Apache restarted.
) else (
    echo [INFO] Please manually restart Apache in XAMPP/WAMP Control Panel.
)

echo.
echo ============================================
echo   Setup Complete!
echo ============================================
echo.
echo You can now access the Job Portal at:
echo   http://localhost/Job-Portal/
echo.
echo If you still see errors, restart Apache manually
echo in your XAMPP or WAMP Control Panel.
echo.
pause
