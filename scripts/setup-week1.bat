@echo off
echo ========================================
echo Forever Young Tours - Week 1 Setup
echo ========================================
echo.

echo Step 1: Installing Stripe PHP SDK...
cd /d c:\xampp1\htdocs\foreveryoungtours
composer require stripe/stripe-php
echo.

echo Step 2: Creating database table for contact messages...
mysql -u root -e "USE forevveryoungtours; CREATE TABLE IF NOT EXISTS contact_messages (id INT AUTO_INCREMENT PRIMARY KEY, first_name VARCHAR(100), last_name VARCHAR(100), email VARCHAR(255), phone VARCHAR(50), subject VARCHAR(255), message TEXT, created_at DATETIME, INDEX(created_at));"
echo.

echo Step 3: Adding payment_intent_id column to bookings...
mysql -u root -e "USE forevveryoungtours; ALTER TABLE bookings ADD COLUMN IF NOT EXISTS payment_intent_id VARCHAR(255) NULL AFTER total_price;"
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next Steps:
echo 1. Update Stripe keys in config/stripe.php
echo 2. Update email credentials in config/email.php
echo 3. Test booking at: http://localhost/ForeverYoungTours/pages/tour-booking.php?id=1
echo.
pause
