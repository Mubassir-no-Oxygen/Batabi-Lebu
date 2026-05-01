@echo off
REM =====================================================
REM  Batabi Lebu — Quick Start Script
REM  Run this from the batabi-lebu directory
REM =====================================================

SET PHP=C:\xampp\php\php.exe
SET COMPOSER=C:\xampp\php\composer.phar

echo.
echo ========================================
echo  Batabi Lebu - Laravel Project
echo ========================================
echo.
echo [1] Run Dev Server (php artisan serve)
echo [2] Run Migrations + Seed (fresh)
echo [3] Clear Cache
echo [4] Create Storage Symlink
echo [5] Exit
echo.
set /p choice=Select option: 

if "%choice%"=="1" goto serve
if "%choice%"=="2" goto migrate
if "%choice%"=="3" goto clear
if "%choice%"=="4" goto symlink
if "%choice%"=="5" exit

:serve
echo Starting development server at http://localhost:8000 ...
%PHP% artisan serve --host=0.0.0.0 --port=8000
goto end

:migrate
echo Running fresh migrations with seed...
%PHP% artisan migrate:fresh --seed
goto end

:clear
%PHP% artisan cache:clear
%PHP% artisan config:clear
%PHP% artisan view:clear
echo Cache cleared!
goto end

:symlink
%PHP% artisan storage:link
echo Storage symlink created!
goto end

:end
pause
