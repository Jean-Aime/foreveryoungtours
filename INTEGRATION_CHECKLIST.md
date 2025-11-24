# ✅ Week 1 Integration Checklist

## 🔍 VERIFICATION STEPS

### 1. Test Integration Page
Visit: `http://foreveryoungtours.local/test-week1-integration.php`

All tests should show ✅ PASS

---

### 2. Test CSRF Protection (Login Form)

**Steps:**
1. Go to: `http://foreveryoungtours.local/auth/login.php`
2. Right-click → View Page Source
3. Search for: `csrf_token`
4. ✅ Should find: `<input type="hidden" name="csrf_token" value="..."`
5. Try to login with valid credentials
6. ✅ Should login successfully

**Expected:** Login works normally with CSRF protection

---

### 3. Test Advisor Client Registration

**Steps:**
1. Login as advisor
2. Go to: `http://foreveryoungtours.local/advisor/register-client.php`
3. Fill form:
   - First Name: Test
   - Last Name: Client
   - Email: testclient@example.com
   - Password: password123
4. Click "Register Client"
5. ✅ Should see success message
6. Check database: `SELECT * FROM users WHERE email='testclient@example.com'`
7. ✅ Should have new user with role='user' and sponsor_id=advisor_id

**Expected:** Client registered successfully

---

### 4. Test Email Notification (After Setup)

**Prerequisites:**
- Email configured in `config/email.php`
- Gmail app password set

**Steps:**
1. Register a client (step 3 above)
2. Check email inbox for testclient@example.com
3. ✅ Should receive welcome email with login credentials

**Expected:** Email delivered successfully

---

### 5. Test Client Dashboard Analytics

**Steps:**
1. Login as client with existing bookings
2. Go to dashboard
3. Scroll to "Travel Spending" chart
4. ✅ Chart should show real monthly data (not fake percentages)
5. Check "Destination Preferences" chart
6. ✅ Should show actual tour types from bookings

**Expected:** Charts display real database data

---

### 6. Test Stripe Integration (After Setup)

**Prerequisites:**
- Stripe SDK installed: `composer require stripe/stripe-php`
- API keys configured in `config/stripe.php`

**Steps:**
1. Create a test tour booking page with Stripe button
2. Click "Book Now"
3. ✅ Should redirect to Stripe checkout
4. Use test card: 4242 4242 4242 4242
5. Complete payment
6. ✅ Should redirect to payment-success.php
7. Check database: `SELECT * FROM bookings ORDER BY id DESC LIMIT 1`
8. ✅ Should have payment_intent_id filled

**Expected:** Payment processed and booking created

---

## 🐛 TROUBLESHOOTING

### CSRF Token Error
**Symptom:** "CSRF token validation failed"
**Fix:**
1. Clear browser cookies
2. Ensure `session_start()` is at top of file
3. Check `includes/csrf.php` exists

### Email Not Sending
**Symptom:** No email received
**Fix:**
1. Check `config/email.php` credentials
2. Use Gmail app password (not regular password)
3. Check spam folder
4. Enable error logging: `error_reporting(E_ALL);`

### Stripe Error
**Symptom:** "No such API key"
**Fix:**
1. Run: `composer require stripe/stripe-php`
2. Copy FULL key from Stripe dashboard
3. Keys start with `sk_test_` or `pk_test_`

### Database Error
**Symptom:** "Column 'payment_intent_id' doesn't exist"
**Fix:**
```sql
ALTER TABLE bookings ADD COLUMN payment_intent_id VARCHAR(255) NULL AFTER total_price;
```

### BASE_URL Not Defined
**Symptom:** "Undefined constant BASE_URL"
**Fix:**
Add at top of file:
```php
require_once '../config.php';
```

---

## ✅ INTEGRATION VERIFICATION

Mark each as complete:

- [ ] CSRF protection working on login
- [ ] Advisor can register clients
- [ ] Client registration saves to database
- [ ] Email notifications sending (after setup)
- [ ] Client dashboard shows real data
- [ ] Stripe checkout creates session (after setup)
- [ ] Payment success saves booking (after setup)
- [ ] All navigation links work
- [ ] No PHP errors in error log
- [ ] All files use correct BASE_URL

---

## 🚀 READY FOR WEEK 2?

Once all checkboxes are ✅, you're ready for:
- License fee collection
- Commission payout system
- Membership tiers
- Profile management
- Booking cancellation

---

## 📞 SUPPORT

**Error Logs:**
- Apache: `c:\xampp1\apache\logs\error.log`
- PHP: Enable with `error_reporting(E_ALL);`

**Database:**
- phpMyAdmin: `http://localhost/phpmyadmin`
- Database: `forevveryoungtours`

**Test Page:**
- `http://foreveryoungtours.local/test-week1-integration.php`
