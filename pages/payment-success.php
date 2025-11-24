<?php
session_start();
require_once '../config.php';
require_once '../config/database.php';
require_once '../config/stripe.php';
require_once '../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$session_id = $_GET['session_id'] ?? null;

if (!$session_id) {
    header('Location: ../index.php');
    exit;
}

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    
    // Create booking record
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, tour_id, customer_name, customer_email, travelers, tour_date, total_price, payment_intent_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())");
    $stmt->execute([
        $session->metadata->user_id ?? null,
        $session->metadata->tour_id,
        $session->customer_details->name ?? 'Guest',
        $session->customer_details->email,
        $session->metadata->travelers,
        $session->metadata->tour_date,
        $session->amount_total / 100,
        $session->payment_intent,
    ]);
    
    $booking_id = $pdo->lastInsertId();
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            <p class="text-gray-600 mb-8">Your booking has been confirmed. We've sent a confirmation email to your inbox.</p>
            <div class="space-y-3">
                <a href="../client/index.php" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition">
                    View My Bookings
                </a>
                <a href="../index.php" class="block w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
