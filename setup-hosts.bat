@echo off
echo Adding Forever Young Tours domains to hosts file...
echo.
echo Run this file as Administrator!
echo.

set HOSTS_FILE=C:\Windows\System32\drivers\etc\hosts

echo # Forever Young Tours - Local Development >> %HOSTS_FILE%
echo 127.0.0.1 visit-rwa.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-uga.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-ken.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-tza.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-zaf.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-egy.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-mar.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-gha.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-nga.localhost >> %HOSTS_FILE%
echo 127.0.0.1 visit-eth.localhost >> %HOSTS_FILE%

echo.
echo Done! Domains added to hosts file.
echo.
echo Now restart Apache in XAMPP Control Panel
echo.
echo Then visit:
echo - http://localhost:8000/foreveryoungtours
echo - http://visit-rwa.localhost:8000 (Rwanda)
echo - http://visit-ken.localhost:8000 (Kenya)
echo.
pause
