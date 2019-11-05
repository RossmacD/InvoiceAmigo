@echo off
FOR /F "tokens=4 delims= " %%i in ('route print ^| find " 0.0.0.0"') do set localIp=%%i
REM powershell write-host -back Red -fore Yellow WARNING WILL BREAK GOOGLE API - FOR DESIGN TESTING ONLY
powershell write-host -fore Cyan -back black Ipv4 connect on: http://%localIp%:8000
php artisan serve  --host=0.0.0.0