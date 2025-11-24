<?php
// Stripe Configuration
// Get your keys from: https://dashboard.stripe.com/apikeys

// Test Mode Keys (use these for development)
define('STRIPE_SECRET_KEY', 'sk_test_YOUR_SECRET_KEY_HERE');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_YOUR_PUBLISHABLE_KEY_HERE');

// Live Mode Keys (use these for production)
// define('STRIPE_SECRET_KEY', 'sk_live_YOUR_SECRET_KEY_HERE');
// define('STRIPE_PUBLISHABLE_KEY', 'pk_live_YOUR_PUBLISHABLE_KEY_HERE');

// Webhook Secret (for payment confirmations)
define('STRIPE_WEBHOOK_SECRET', 'whsec_YOUR_WEBHOOK_SECRET_HERE');

// Currency
define('STRIPE_CURRENCY', 'usd');
