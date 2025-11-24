<?php
session_start();
require_once '../config/database.php';
require_once '../config/stripe-config.php';
require_once '../includes/csrf.php';

verifyCSRF();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$payment_type = $_POST['payment_type'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);

if (!in_array($payment_type, ['license_basic', 'license_premium', 'booking'])) {
    die('Invalid payment type');
}

if ($payment_type === 'license_basic') {
    $amount = LICENSE_FEE_BASIC;
    $description = 'Basic License Fee';
} elseif ($payment_type === 'license_premium') {
    $amount = LICENSE_FEE_PREMIUM;
    $description = 'Premium License Fee';
} else {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT total_amount FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch();
    if (!$booking) die('Invalid booking');
    $amount = $booking['total_amount'];
    $description = "Booking Payment #$booking_id";
}

if ($amount <= 0) die('Invalid amount');

$amount_cents = intval($amount * 100);

require_once '../vendor/autoload.php';
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => strtolower(STRIPE_CURRENCY),
                'product_data' => ['name' => $description],
                'unit_amount' => $amount_cents,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => PAYMENT_SUCCESS_URL . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => PAYMENT_CANCEL_URL,
        'metadata' => [
            'user_id' => $user_id,
            'payment_type' => $payment_type,
            'booking_id' => $booking_id ?? null,
        ],
    ]);
    
    header('Location: ' . $checkout_session->url);
    exit();
} catch (Exception $e) {
    error_log('Stripe Error: ' . $e->getMessage());
    $_SESSION['error'] = 'Payment error. Please try again.';
    header('Location: ../dashboard.php');
    exit();
}
?>
