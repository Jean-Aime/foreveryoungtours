@echo off
echo ========================================
echo Forever Young Tours - Week 2 Setup
echo ========================================
echo.

echo [1/3] Creating database tables...
mysql -u root foreveryoungtours < database/license_fees.sql
mysql -u root foreveryoungtours < database/payout_requests.sql
mysql -u root foreveryoungtours < database/membership_tiers.sql

echo.
echo [2/3] Verifying files...
if exist pages\license-payment.php (echo - License payment page: OK) else (echo - License payment page: MISSING)
if exist advisor\payouts.php (echo - Payout request page: OK) else (echo - Payout request page: MISSING)
if exist pages\membership.php (echo - Membership page: OK) else (echo - Membership page: MISSING)

echo.
echo [3/3] Setup complete!
echo.
echo ========================================
echo Week 2 Features Installed:
echo ========================================
echo 1. License Fee Collection ($959/$59)
echo    - Visit: /pages/license-payment.php
echo.
echo 2. Commission Payout Requests
echo    - Visit: /advisor/payouts.php
echo.
echo 3. Membership Tier System
echo    - Visit: /pages/membership.php
echo.
echo 4. Mobile Navigation
echo    - Responsive sidebar on all panels
echo.
echo ========================================
pause
