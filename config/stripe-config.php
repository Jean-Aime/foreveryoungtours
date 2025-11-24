<?php
/**
 * Stripe Payment Gateway Configuration
 * Get your keys from: https://dashboard.stripe.com/apikeys
 */

// Stripe API Keys
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_PUBLISHABLE_KEY_HERE');
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_SECRET_KEY_HERE');

// Stripe Webhook Secret
define('STRIPE_WEBHOOK_SECRET', 'whsec_YOUR_WEBHOOK_SECRET_HERE');

// Currency
define('STRIPE_CURRENCY', 'USD');

// Payment Settings
define('PAYMENT_SUCCESS_URL', 'https://yourdomain.com/payment/success.php');
define('PAYMENT_CANCEL_URL', 'https://yourdomain.com/payment/cancel.php');

// License Fees
define('LICENSE_FEE_BASIC', 59.00);
define('LICENSE_FEE_PREMIUM', 959.00);

// Minimum Payout Amount
define('MINIMUM_PAYOUT_AMOUNT', 50.00);
?>
