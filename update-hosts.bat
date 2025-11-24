@echo off
echo Adding subdomain entries to hosts file...
echo.

echo 127.0.0.1 foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 africa.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 north-america.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 south-america.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 asia.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 europe.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 oceania.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 caribbean.foreveryoungtours.local >> C:\Windows\System32\drivers\etc\hosts

echo.
echo Done! Hosts file updated successfully.
echo.
echo Now restart Apache from XAMPP Control Panel.
pause
