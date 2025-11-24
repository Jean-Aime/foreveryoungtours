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
$tier_id = $data['tier_id'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM membership_tiers WHERE id = ? AND status = 'active'");
    $stmt->execute([$tier_id]);
    $tier = $stmt->fetch();
    
    if (!$tier) {
        echo json_encode(['error' => 'Invalid membership tier']);
        exit;
    }
    
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $tier['name'] . ' Membership',
                    'description' => $tier['duration_months'] . ' months of exclusive benefits'
                ],
                'unit_amount' => $tier['price'] * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => BASE_URL . '/pages/membership-success.php?session_id={CHECKOUT_SESSION_ID}&tier_id=' . $tier_id,
        'cancel_url' => BASE_URL . '/pages/membership.php',
        'metadata' => [
            'user_id' => $user_id,
            'tier_id' => $tier_id
        ]
    ]);
    
    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
