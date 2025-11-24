# Forever Young Tours - Implementation Roadmap

## ✅ COMPLETED (Week 1 - Day 1)

### Security Fixes
- [x] **SQL Injection Fix** - Fixed unsafe country_ids concatenation in `mca/index.php`
- [x] **CSRF Protection** - Created `includes/csrf.php` helper functions

---

## 🔥 WEEK 1: CRITICAL SECURITY & CORE FUNCTIONALITY

### Day 1-2: Security Hardening
- [ ] Add CSRF tokens to ALL forms:
  - [ ] `admin/tours.php` (add/edit tour forms)
  - [ ] `admin/commission-management.php` (status update forms)
  - [ ] `advisor/index.php` (any action forms)
  - [ ] `client/index.php` (profile update forms)
  - [ ] `auth/login.php` (login form)
  - [ ] `auth/register.php` (registration form)
  
**Implementation**: Add at top of each file:
```php
require_once '../includes/csrf.php';
```

Add in forms:
```php
<?php echo getCsrfField(); ?>
```

Add in POST handlers:
```php
requireCsrf();
```

### Day 3-4: Payment Gateway Integration
- [ ] Install Stripe PHP SDK: `composer require stripe/stripe-php`
- [ ] Create `config/stripe.php` with API keys
- [ ] Create `api/create-checkout-session.php`
- [ ] Update `pages/tour-detail.php` with Stripe checkout button
- [ ] Create `pages/payment-success.php` callback
- [ ] Create `pages/payment-cancel.php` callback
- [ ] Update bookings table with `payment_intent_id` column

### Day 5: Email Notifications
- [ ] Configure PHPMailer in `config/email.php`
- [ ] Create email templates in `includes/email-templates/`
  - [ ] `booking-confirmation.php`
  - [ ] `payment-receipt.php`
  - [ ] `advisor-welcome.php`
- [ ] Update booking process to send emails

### Day 6-7: Client Dashboard Analytics Fix
- [ ] Fix hardcoded chart data in `client/index.php`
- [ ] Query real monthly booking data from database
- [ ] Add date range filters
- [ ] Export booking history to PDF

---

## 📊 WEEK 2: HIGH PRIORITY FEATURES

### Advisor Panel
- [ ] **Client Registration Form** (`advisor/register-client.php`)
  - Form fields: name, email, phone, passport info
  - Auto-assign advisor as sponsor
  - Send welcome email to client
  
- [ ] **License Fee Collection** (`admin/license-fees.php`)
  - MCA: $959 one-time fee
  - Advisor: $59 one-time fee
  - Payment via Stripe
  - Update user status after payment

- [ ] **Commission Payout System** (`advisor/payout-requests.php`)
  - Request withdrawal form
  - Bank details collection
  - Admin approval workflow
  - Payment tracking

### Admin Panel
- [ ] **Membership Tiers** (`admin/membership-tiers.php`)
  - Bronze/Silver/Gold/Platinum levels
  - Pricing: $0/$99/$199/$499 per year
  - Benefits configuration
  - Auto-upgrade based on sales volume

---

## 🛠️ WEEK 3: MEDIUM PRIORITY

### All Panels
- [ ] **Profile Management**
  - `client/profile.php` - Edit personal info, change password
  - `advisor/profile.php` - Edit bio, upload photo, bank details
  - `mca/profile.php` - Edit contact info, assigned countries
  
- [ ] **Booking Management**
  - `client/bookings.php` - Cancel/modify bookings
  - Cancellation policy enforcement
  - Refund processing

- [ ] **Training Center** (`advisor/training.php`)
  - Video tutorials
  - Certification quizzes
  - Downloadable resources

### Admin Panel
- [ ] **Audit Log** (`admin/audit-log.php`)
  - Track all admin actions
  - User activity monitoring
  - Export logs to CSV

---

## 🎨 FRONTEND IMPROVEMENTS

### Critical
- [ ] **Tour Detail Page** (`pages/tour-detail.php`)
  - Date picker for tour dates
  - Traveler count selector
  - Real-time price calculation
  - Stripe payment button

- [ ] **Search & Filters** (`pages/packages.php`)
  - Fix broken filter functionality
  - Add price range slider
  - Add date availability filter

- [ ] **Wishlist** (`pages/wishlist.php`)
  - Add to wishlist button on tour cards
  - Persist to database
  - Remove from wishlist

- [ ] **Contact Form** (`pages/contact.php`)
  - Form validation
  - Email sending via PHPMailer
  - Success/error messages

### Enhancements
- [ ] Mobile menu fix (hamburger not working)
- [ ] Image lazy loading
- [ ] SEO meta tags
- [ ] Customer reviews system

---

## 📋 MCA PANEL FEATURES

- [ ] **Country Management** (`mca/countries.php`)
  - View assigned countries
  - Edit country descriptions
  - Upload country images

- [ ] **Advisor Recruitment** (`mca/advisor-applications.php`)
  - Approve/reject applications
  - Set commission rates
  - Assign to countries

- [ ] **Tour Approval** (`mca/tour-approvals.php`)
  - Review pending tours
  - Approve/reject with comments
  - Bulk approval

- [ ] **Revenue Analytics** (`mca/analytics.php`)
  - Country-specific revenue
  - Advisor performance metrics
  - Export reports

---

## 🔐 ADDITIONAL SECURITY

- [ ] **Rate Limiting** (`includes/rate-limit.php`)
  - Login attempt limiting (5 attempts per 15 min)
  - API rate limiting
  
- [ ] **File Upload Security** (`includes/upload-handler.php`)
  - Validate file types (whitelist)
  - Scan for malware
  - Rename uploaded files
  - Store outside web root

- [ ] **Session Security**
  - Regenerate session ID on login
  - Set secure cookie flags
  - Implement session timeout

---

## 📦 DEPLOYMENT CHECKLIST

- [ ] SSL certificate installation
- [ ] Environment variables for sensitive data
- [ ] Database backup automation
- [ ] Error logging configuration
- [ ] Performance optimization (caching, CDN)
- [ ] Security headers (CSP, HSTS, X-Frame-Options)

---

## 📝 NOTES

### CSRF Implementation Example:
```php
// At top of file
require_once '../includes/csrf.php';

// In form
<form method="POST">
    <?php echo getCsrfField(); ?>
    <!-- other fields -->
</form>

// In POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    // process form
}
```

### Stripe Integration Example:
```php
require_once '../vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_...');

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => ['name' => $tour['name']],
            'unit_amount' => $tour['price'] * 100,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => BASE_URL . '/pages/payment-success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => BASE_URL . '/pages/payment-cancel.php',
]);
```

---

## 🎯 SUCCESS METRICS

- [ ] 0 SQL injection vulnerabilities
- [ ] 100% forms protected with CSRF tokens
- [ ] Payment gateway processing live transactions
- [ ] Email notifications sending successfully
- [ ] All panels have complete CRUD operations
- [ ] Mobile responsive on all pages
- [ ] Page load time < 3 seconds
- [ ] Security audit passed

---

**Last Updated**: 2025-01-XX
**Status**: Week 1 - Day 1 (Security Fixes Started)
