<?php
session_start();
require_once '../config/database.php';
require_once '../config/stripe.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$license_type = $data['license_type'];
$amount = $data['amount'];
$user_id = $_SESSION['user_id'];

try {
    // Create license fee record
    $stmt = $pdo->prepare("INSERT INTO license_fees (user_id, license_type, amount, payment_status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $license_type, $amount]);
    $license_fee_id = $pdo->lastInsertId();
    
    // Create Stripe checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => ucfirst($license_type) . ' License - 12 Months',
                    'description' => 'Professional license for Forever Young Tours platform'
                ],
                'unit_amount' => $amount * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => BASE_URL . '/pages/license-success.php?session_id={CHECKOUT_SESSION_ID}&license_id=' . $license_fee_id,
        'cancel_url' => BASE_URL . '/pages/license-payment.php',
        'metadata' => [
            'user_id' => $user_id,
            'license_fee_id' => $license_fee_id,
            'license_type' => $license_type
        ]
    ]);
    
    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
