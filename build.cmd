@echo off
REM Build script for markdown2html PHAR archive

echo Building markdown2html PHAR archive...
echo.

REM Check if composer dependencies are installed
if not exist "vendor" (
    echo Error: vendor directory not found.
    echo Please run 'composer install' first.
    exit /b 1
)

REM Build the PHAR with phar.readonly disabled
php -d phar.readonly=0 build-phar.php

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Build completed successfully!
    echo The portable executable is: markdown2html.phar
) else (
    echo.
    echo Build failed!
    exit /b 1
)
