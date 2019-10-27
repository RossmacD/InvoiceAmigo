@echo off
FOR /F "tokens=4 delims= " %%i in ('route print ^| find " 0.0.0.0"') do set localIp=%%i
powershell write-host -back Red -fore Yellow WARNING WILL BREAK GOOGLE API - FOR DESIGN TESTING ONLY
php artisan serve  --host=%localIp% 