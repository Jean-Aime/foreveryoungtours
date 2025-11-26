# Week 1 Critical Features - Setup Instructions

## ✅ COMPLETED FEATURES

1. ✅ **CSRF Protection** - All forms now protected
2. ✅ **Stripe Payment Integration** - Ready for payments
3. ✅ **Email Notification System** - PHPMailer configured
4. ✅ **Client Dashboard Analytics** - Real data from database
5. ✅ **Advisor Client Registration** - Complete form with email

---

## 🚀 SETUP STEPS

### 1. Install Stripe PHP SDK

Open terminal in project root and run:
```bash
cd c:\xampp1\htdocs\foreveryoungtours
composer require stripe/stripe-php
```

If you don't have Composer, download it from: https://getcomposer.org/download/

---

### 2. Configure Stripe API Keys

1. Go to: https://dashboard.stripe.com/register
2. Create a free account
3. Get your API keys from: https://dashboard.stripe.com/test/apikeys
4. Open `config/stripe.php` and replace:
   ```php
   define('STRIPE_SECRET_KEY', 'sk_test_YOUR_KEY_HERE');
   define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_KEY_HERE');
   ```

---

### 3. Configure Email (PHPMailer)

#### Option A: Gmail (Recommended for Testing)

1. Open `config/email.php`
2. Update these lines:
   ```php
   $mail->Username = 'your-email@gmail.com';
   $mail->Password = 'your-app-password'; // NOT your Gmail password!
   ```

3. **Get Gmail App Password:**
   - Go to: https://myaccount.google.com/security
   - Enable 2-Step Verification
   - Go to: https://myaccount.google.com/apppasswords
   - Generate app password for "Mail"
   - Copy the 16-character password

#### Option B: Other SMTP (Mailgun, SendGrid, etc.)

Update `config/email.php` with your SMTP settings:
```php
$mail->Host = 'smtp.your-provider.com';
$mail->Username = 'your-username';
$mail->Password = 'your-password';
$mail->Port = 587; // or 465 for SSL
```

---

### 4. Update Database (Add Payment Column)

Run this SQL in phpMyAdmin:

```sql
ALTER TABLE bookings 
ADD COLUMN payment_intent_id VARCHAR(255) NULL AFTER total_price;
```

---

### 5. Test CSRF Protection

1. Go to: http://foreveryoungtours.local/auth/login.php
2. Try to login - should work normally
3. Check page source - you should see: `<input type="hidden" name="csrf_token" value="...">`

---

### 6. Test Stripe Payment

1. Update a tour detail page to include Stripe checkout button
2. Use test card: `4242 4242 4242 4242`
3. Any future date, any CVC
4. Should redirect to payment-success.php

---

### 7. Test Email Notifications

1. Go to: http://foreveryoungtours.local/advisor/register-client.php
2. Register a test client
3. Check the email inbox - should receive welcome email

---

### 8. Test Client Dashboard Analytics

1. Login as a client with bookings
2. Go to dashboard
3. Charts should show real data (not hardcoded)

---

## 🔧 TROUBLESHOOTING

### Stripe Error: "No such API key"
- Make sure you copied the FULL key from Stripe dashboard
- Keys start with `sk_test_` or `pk_test_`

### Email Not Sending
- Check `config/email.php` credentials
- Make sure 2-Step Verification is enabled (Gmail)
- Check spam folder
- Enable error logging: `error_reporting(E_ALL);`

### CSRF Token Error
- Clear browser cookies
- Make sure session_start() is at top of file
- Check that `includes/csrf.php` exists

### Composer Not Found
- Download from: https://getcomposer.org/download/
- Install globally
- Restart terminal

---

## 📋 NEXT STEPS (Week 2)

After completing setup, implement:

1. **License Fee Collection** ($959 MCA / $59 Advisor)
2. **Commission Payout System**
3. **Membership Tiers** (Bronze/Silver/Gold/Platinum)
4. **Profile Management** (All panels)
5. **Booking Cancellation**

---

## 🎯 TESTING CHECKLIST

- [ ] CSRF tokens appear in all forms
- [ ] Login form works with CSRF protection
- [ ] Stripe checkout creates session
- [ ] Payment success page saves booking
- [ ] Email sends to new clients
- [ ] Client dashboard shows real chart data
- [ ] Advisor can register new clients
- [ ] Welcome email received by client

---

## 📞 SUPPORT

If you encounter issues:
1. Check error logs: `c:\xampp1\apache\logs\error.log`
2. Enable PHP errors: Add to top of file:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Check database connection in `config/database.php`

---

**Status**: Week 1 Complete ✅
**Next**: Week 2 High Priority Features
