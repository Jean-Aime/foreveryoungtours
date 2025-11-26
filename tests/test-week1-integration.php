<?php
session_start();
require_once 'config.php';
require_once 'config/database.php';

$tests = [];

// Test 1: CSRF Functions
try {
    require_once 'includes/csrf.php';
    $token = generateCsrfToken();
    $tests['CSRF Protection'] = !empty($token) ? '✅ PASS' : '❌ FAIL';
} catch (Exception $e) {
    $tests['CSRF Protection'] = '❌ FAIL: ' . $e->getMessage();
}

// Test 2: Stripe Config
try {
    require_once 'config/stripe.php';
    $tests['Stripe Config'] = defined('STRIPE_SECRET_KEY') ? '✅ PASS' : '❌ FAIL';
} catch (Exception $e) {
    $tests['Stripe Config'] = '❌ FAIL: ' . $e->getMessage();
}

// Test 3: Email Config
try {
    require_once 'config/email.php';
    $tests['Email Config'] = function_exists('sendEmail') ? '✅ PASS' : '❌ FAIL';
} catch (Exception $e) {
    $tests['Email Config'] = '❌ FAIL: ' . $e->getMessage();
}

// Test 4: Database Connection
try {
    $stmt = $pdo->query("SELECT 1");
    $tests['Database Connection'] = '✅ PASS';
} catch (Exception $e) {
    $tests['Database Connection'] = '❌ FAIL: ' . $e->getMessage();
}

// Test 5: BASE_URL
$tests['BASE_URL'] = defined('BASE_URL') ? '✅ PASS (' . BASE_URL . ')' : '❌ FAIL';

// Test 6: Email Templates
$booking_template = __DIR__ . '/includes/email-templates/booking-confirmation.php';
$welcome_template = __DIR__ . '/includes/email-templates/client-welcome.php';
$tests['Email Templates'] = (file_exists($booking_template) && file_exists($welcome_template)) ? '✅ PASS' : '❌ FAIL';

// Test 7: API Endpoint
$api_file = __DIR__ . '/api/create-checkout-session.php';
$tests['Stripe API Endpoint'] = file_exists($api_file) ? '✅ PASS' : '❌ FAIL';

// Test 8: Payment Pages
$success_page = __DIR__ . '/pages/payment-success.php';
$cancel_page = __DIR__ . '/pages/payment-cancel.php';
$tests['Payment Pages'] = (file_exists($success_page) && file_exists($cancel_page)) ? '✅ PASS' : '❌ FAIL';

// Test 9: Advisor Register Client
$register_client = __DIR__ . '/advisor/register-client.php';
$tests['Advisor Register Client'] = file_exists($register_client) ? '✅ PASS' : '❌ FAIL';

// Test 10: Client Dashboard Fix
try {
    $client_dashboard = file_get_contents(__DIR__ . '/client/index.php');
    $has_real_data = strpos($client_dashboard, 'DATE_FORMAT(created_at') !== false;
    $tests['Client Dashboard Analytics'] = $has_real_data ? '✅ PASS (Real Data)' : '❌ FAIL (Still Hardcoded)';
} catch (Exception $e) {
    $tests['Client Dashboard Analytics'] = '❌ FAIL: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week 1 Integration Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold mb-2">🧪 Week 1 Integration Test</h1>
            <p class="text-gray-600 mb-8">Testing all implemented features</p>
            
            <div class="space-y-3">
                <?php foreach ($tests as $name => $result): ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <span class="font-semibold"><?= $name ?></span>
                    <span class="font-mono"><?= $result ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-bold text-blue-900 mb-2">📋 Next Steps:</h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-800">
                    <li>Run: <code class="bg-blue-100 px-2 py-1 rounded">composer require stripe/stripe-php</code></li>
                    <li>Update Stripe keys in <code class="bg-blue-100 px-2 py-1 rounded">config/stripe.php</code></li>
                    <li>Update email credentials in <code class="bg-blue-100 px-2 py-1 rounded">config/email.php</code></li>
                    <li>Add database column: <code class="bg-blue-100 px-2 py-1 rounded">ALTER TABLE bookings ADD payment_intent_id VARCHAR(255)</code></li>
                    <li>Test login at: <a href="auth/login.php" class="text-blue-600 underline">auth/login.php</a></li>
                    <li>Test advisor client registration: <a href="advisor/register-client.php" class="text-blue-600 underline">advisor/register-client.php</a></li>
                </ol>
            </div>
            
            <div class="mt-6 flex gap-4">
                <a href="auth/login.php" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition">
                    Test Login (CSRF)
                </a>
                <a href="advisor/register-client.php" class="bg-blue-600 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition">
                    Test Client Registration
                </a>
                <a href="index.php" class="border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
