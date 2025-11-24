<?php
session_start();
require_once '../config/database.php';
require_once '../config/stripe-config.php';
require_once '../config/email-config.php';

$session_id = $_GET['session_id'] ?? '';

if (!$session_id) {
    header('Location: ../dashboard.php');
    exit();
}

require_once '../vendor/autoload.php';
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    
    if ($session->payment_status === 'paid') {
        $user_id = $session->metadata->user_id;
        $payment_type = $session->metadata->payment_type;
        $amount = $session->amount_total / 100;
        
        // Process license payment
        if (in_array($payment_type, ['license_basic', 'license_premium'])) {
            $license_type = str_replace('license_', '', $payment_type);
            $expiry_date = date('Y-m-d', strtotime('+1 year'));
            
            $stmt = $pdo->prepare("UPDATE users SET license_type = ?, license_paid_date = NOW(), license_expiry_date = ?, license_amount = ?, license_status = 'active' WHERE id = ?");
            $stmt->execute([$license_type, $expiry_date, $amount, $user_id]);
            
            $stmt = $pdo->prepare("INSERT INTO license_payments (user_id, license_type, amount, transaction_id, stripe_payment_intent_id, status, payment_date) VALUES (?, ?, ?, ?, ?, 'completed', NOW())");
            $stmt->execute([$user_id, $license_type, $amount, $session_id, $session->payment_intent, ]);
            
            $message = "License activated successfully!";
        }
        // Process booking payment
        elseif ($payment_type === 'booking') {
            $booking_id = $session->metadata->booking_id;
            
            $stmt = $pdo->prepare("UPDATE bookings SET payment_status = 'paid', status = 'confirmed' WHERE id = ?");
            $stmt->execute([$booking_id]);
            
            $stmt = $pdo->prepare("INSERT INTO booking_payments (booking_id, amount, transaction_id, stripe_payment_intent_id, status, payment_date) VALUES (?, ?, ?, ?, 'completed', NOW())");
            $stmt->execute([$booking_id, $amount, $session_id, $session->payment_intent]);
            
            $message = "Booking confirmed! Payment received.";
        }
        
        $_SESSION['success'] = $message;
    }
} catch (Exception $e) {
    error_log('Payment verification error: ' . $e->getMessage());
    $_SESSION['error'] = 'Payment verification failed.';
}

header('Location: ../dashboard.php');
exit();
?>
