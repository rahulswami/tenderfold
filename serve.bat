@echo off
echo Starting Tenderfold Server on http://127.0.0.1:8000 ...
:loop
php artisan serve --host=127.0.0.1 --port=8000
timeout /t 1 /nobreak >nul
goto loop
