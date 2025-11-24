# ⚠️ PENDING FEATURES - Implementation Guide

**Status:** Ready for Implementation  
**Priority:** High  
**Timeline:** 2-3 Weeks

---

## 🔴 PRIORITY 1: PAYMENT GATEWAY

### Current Status: NOT IMPLEMENTED

### Implementation Steps

**1. Choose Provider**
```
Recommended: Stripe
Alternatives: PayPal, Flutterwave (Africa-focused)

Stripe Advantages:
✓ Easy integration
✓ Comprehensive documentation
✓ Multiple payment methods
✓ Strong fraud protection
✓ Good for international payments
```

**2. Installation**
```bash
composer require stripe/stripe-php
```

**3. Configuration**
```php
// config/payment.php
define('STRIPE_PUBLIC_KEY', 'pk_test_...');
define('STRIPE_SECRET_KEY', 'sk_test_...');
define('STRIPE_WEBHOOK_SECRET', 'whsec_...');
```

**4. Create Payment Controller**
```php
// includes/payment-controller.php
<?php
require_once 'vendor/autoload.php';

class PaymentController {
    private $stripe;
    
    public function __construct() {
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
    }
    
    public function createCheckoutSession($booking) {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $booking['tour_name'],
                        'description' => $booking['description'],
                    ],
                    'unit_amount' => $booking['amount'] * 100,
                ],
                'quantity' => $booking['participants'],
            ]],
            'mode' => 'payment',
            'success_url' => BASE_URL . '/payment-success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => BASE_URL . '/payment-cancel.php',
            'metadata' => [
                'booking_id' => $booking['id'],
                'booking_reference' => $booking['reference'],
            ],
        ]);
        
        return $session;
    }
    
    public function handleWebhook() {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, STRIPE_WEBHOOK_SECRET
            );
            
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleSuccessfulPayment($event->data->object);
                    break;
                case 'payment_intent.payment_failed':
                    $this->handleFailedPayment($event->data->object);
                    break;
            }
            
            http_response_code(200);
        } catch(\Exception $e) {
            http_response_code(400);
            error_log('Webhook error: ' . $e->getMessage());
        }
    }
    
    private function handleSuccessfulPayment($session) {
        global $pdo;
        
        $booking_id = $session->metadata->booking_id;
        
        $stmt = $pdo->prepare("
            UPDATE bookings 
            SET status = 'paid', 
                payment_status = 'completed',
                payment_id = ?,
                paid_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$session->payment_intent, $booking_id]);
        
        // Send confirmation email
        $this->sendPaymentConfirmation($booking_id);
        
        // Calculate commissions
        $this->calculateCommissions($booking_id);
    }
}
```

**5. Update Booking Flow**
```php
// pages/checkout.php
<?php
require_once '../includes/payment-controller.php';

$payment = new PaymentController();
$session = $payment->createCheckoutSession($booking);
?>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?= STRIPE_PUBLIC_KEY ?>');
stripe.redirectToCheckout({
    sessionId: '<?= $session->id ?>'
});
</script>
```

**Estimated Time: 2-3 days**

---

## 📧 PRIORITY 2: EMAIL NOTIFICATIONS

### Current Status: SMTP NOT CONFIGURED

### Implementation Steps

**1. Choose Email Service**
```
Recommended: SendGrid
Alternatives: Mailgun, Amazon SES

SendGrid Advantages:
✓ Free tier (100 emails/day)
✓ Easy setup
✓ Good deliverability
✓ Email templates
✓ Analytics
```

**2. Configuration**
```php
// config/email-config.php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USER', 'apikey');
define('SMTP_PASS', 'SG.xxx...');
define('FROM_EMAIL', 'noreply@foreveryoungtours.com');
define('FROM_NAME', 'Forever Young Tours');
```

**3. Create Email Service**
```php
// includes/email-service.php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = SMTP_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SMTP_USER;
        $this->mailer->Password = SMTP_PASS;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = SMTP_PORT;
        $this->mailer->setFrom(FROM_EMAIL, FROM_NAME);
    }
    
    public function sendBookingConfirmation($booking) {
        try {
            $this->mailer->addAddress($booking['customer_email'], $booking['customer_name']);
            $this->mailer->Subject = 'Booking Confirmation - ' . $booking['booking_reference'];
            $this->mailer->isHTML(true);
            $this->mailer->Body = $this->getBookingTemplate($booking);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
    
    private function getBookingTemplate($booking) {
        ob_start();
        include __DIR__ . '/../templates/emails/booking-confirmation.php';
        return ob_get_clean();
    }
}
```

**4. Create Email Templates**
```php
// templates/emails/booking-confirmation.php
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #DAA520; color: white; padding: 20px; }
        .content { padding: 20px; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed!</h1>
        </div>
        <div class="content">
            <p>Dear <?= $booking['customer_name'] ?>,</p>
            <p>Your booking has been confirmed!</p>
            
            <h3>Booking Details:</h3>
            <ul>
                <li><strong>Reference:</strong> <?= $booking['booking_reference'] ?></li>
                <li><strong>Tour:</strong> <?= $booking['tour_name'] ?></li>
                <li><strong>Date:</strong> <?= date('F j, Y', strtotime($booking['travel_date'])) ?></li>
                <li><strong>Participants:</strong> <?= $booking['participants'] ?></li>
                <li><strong>Total:</strong> $<?= number_format($booking['total_amount'], 2) ?></li>
            </ul>
            
            <p>We'll send you more details closer to your travel date.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Forever Young Tours. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
```

**5. Trigger Emails**
```php
// Update booking-actions.php
$email = new EmailService();
$email->sendBookingConfirmation($booking);
```

**Estimated Time: 1-2 days**

---

## 📝 PRIORITY 3: REAL CONTENT POPULATION

### Current Status: SAMPLE DATA ONLY

### Content Needed

**Tours (50+ Required)**
```
Rwanda: 10 tours
Kenya: 10 tours
Tanzania: 10 tours
South Africa: 10 tours
Nigeria: 5 tours
Other countries: 5 tours each
```

**Tour Information Template**
```
For each tour:
✓ Name
✓ Description (200+ words)
✓ Detailed description (500+ words)
✓ Price
✓ Duration
✓ Itinerary (day-by-day)
✓ Inclusions (5-10 items)
✓ Exclusions (5-10 items)
✓ Requirements
✓ Best time to visit
✓ Difficulty level
✓ Main image
✓ Cover image
✓ Gallery images (5-10)
```

**Content Sources**
```
1. Tour operator websites
2. Travel blogs
3. Tourism boards
4. Stock photo sites (Unsplash, Pexels)
5. AI content generation (with review)
```

**Bulk Import Script**
```php
// scripts/import-tours.php
<?php
require_once '../config/database.php';

$tours = json_decode(file_get_contents('tours-data.json'), true);

foreach ($tours as $tour) {
    $stmt = $pdo->prepare("
        INSERT INTO tours (
            name, slug, description, detailed_description,
            destination, country_id, category, price,
            duration_days, max_participants, image_url,
            cover_image, gallery, itinerary, inclusions,
            exclusions, highlights, requirements,
            difficulty_level, best_time_to_visit,
            status, featured, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $tour['name'],
        $tour['slug'],
        $tour['description'],
        $tour['detailed_description'],
        $tour['destination'],
        $tour['country_id'],
        $tour['category'],
        $tour['price'],
        $tour['duration_days'],
        $tour['max_participants'],
        $tour['image_url'],
        $tour['cover_image'],
        json_encode($tour['gallery']),
        json_encode($tour['itinerary']),
        json_encode($tour['inclusions']),
        json_encode($tour['exclusions']),
        json_encode($tour['highlights']),
        $tour['requirements'],
        $tour['difficulty_level'],
        $tour['best_time_to_visit'],
        'active',
        $tour['featured'] ?? 0
    ]);
    
    echo "Imported: {$tour['name']}\n";
}
```

**Estimated Time: 3-5 days**

---

## 🔒 PRIORITY 4: SECURITY AUDIT

### Security Checklist

**1. CSRF Protection**
```php
// Add to all forms
<?= CSRF::field() ?>

// Validate on submission
if (!CSRF::validateToken($_POST['csrf_token'])) {
    die('Invalid CSRF token');
}
```

**2. Input Validation**
```php
// Validate all inputs
$validator = new Validator();
$valid = $validator->validate($_POST, [
    'email' => 'required|email',
    'password' => 'required|min:8',
    'name' => 'required|max:100',
]);

if (!$valid) {
    $errors = $validator->getErrors();
}
```

**3. SQL Injection Prevention**
```php
// Always use prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// NEVER do this:
$query = "SELECT * FROM users WHERE email = '$email'";
```

**4. XSS Prevention**
```php
// Always escape output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// Use in templates
<?= htmlspecialchars($data) ?>
```

**5. File Upload Security**
```php
// Validate file uploads
$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die('Invalid file type');
}

// Check file size
if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
    die('File too large');
}

// Rename file
$newName = uniqid() . '.' . $ext;
```

**Estimated Time: 2-3 days**

---

## 🌐 PRIORITY 5: SSL CERTIFICATE

### Implementation Steps

**1. Get Free SSL (Let's Encrypt)**
```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d foreveryoungtours.com -d www.foreveryoungtours.com

# Auto-renewal
sudo certbot renew --dry-run
```

**2. Force HTTPS**
```apache
# .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

**3. Update Configuration**
```php
// config.php
define('FORCE_HTTPS', true);

if (FORCE_HTTPS && !isset($_SERVER['HTTPS'])) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}
```

**Estimated Time: 1 hour**

---

## 📊 IMPLEMENTATION TIMELINE

### Week 1
- [ ] Day 1-2: Payment gateway
- [ ] Day 3-4: Email system
- [ ] Day 5: Testing

### Week 2
- [ ] Day 1-2: Security hardening
- [ ] Day 3-4: Content population
- [ ] Day 5: SSL & final testing

### Week 3
- [ ] Day 1-2: Bug fixes
- [ ] Day 3-4: Documentation
- [ ] Day 5: Deployment prep

---

## ✅ COMPLETION CHECKLIST

### Payment Gateway
- [ ] Stripe account created
- [ ] API keys configured
- [ ] Payment controller created
- [ ] Checkout flow updated
- [ ] Webhook handler implemented
- [ ] Success/failure pages created
- [ ] Testing completed

### Email System
- [ ] SMTP configured
- [ ] Email service created
- [ ] Templates designed
- [ ] Triggers implemented
- [ ] Testing completed
- [ ] Deliverability verified

### Content
- [ ] 50+ tours added
- [ ] All images uploaded
- [ ] Descriptions written
- [ ] Itineraries created
- [ ] Data validated

### Security
- [ ] CSRF protection added
- [ ] Input validation implemented
- [ ] SQL injection prevented
- [ ] XSS protection added
- [ ] File upload secured
- [ ] Security audit passed

### SSL
- [ ] Certificate installed
- [ ] HTTPS forced
- [ ] Mixed content fixed
- [ ] Testing completed

---

**Document Version:** 1.0  
**Priority:** HIGH  
**Deadline:** 2-3 weeks  
**Status:** Ready to implement
