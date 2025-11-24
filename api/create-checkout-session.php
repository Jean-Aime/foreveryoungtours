<?php
session_start();
require_once '../config.php';
require_once '../config/database.php';
require_once '../config/stripe.php';
require_once '../vendor/autoload.php';

header('Content-Type: application/json');

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $tour_id = $data['tour_id'] ?? null;
    $travelers = $data['travelers'] ?? 1;
    $tour_date = $data['tour_date'] ?? null;
    
    if (!$tour_id) {
        throw new Exception('Tour ID is required');
    }
    
    // Get tour details
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ? AND status = 'active'");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch();
    
    if (!$tour) {
        throw new Exception('Tour not found');
    }
    
    $total_amount = $tour['price'] * $travelers;
    
    // Create Stripe checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => STRIPE_CURRENCY,
                'product_data' => [
                    'name' => $tour['name'],
                    'description' => substr($tour['description'], 0, 200),
                    'images' => [$tour['cover_image'] ?? $tour['image_url']],
                ],
                'unit_amount' => $tour['price'] * 100, // Convert to cents
            ],
            'quantity' => $travelers,
        ]],
        'mode' => 'payment',
        'success_url' => BASE_URL . '/pages/payment-success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => BASE_URL . '/pages/payment-cancel.php?tour_id=' . $tour_id,
        'metadata' => [
            'tour_id' => $tour_id,
            'travelers' => $travelers,
            'tour_date' => $tour_date,
            'user_id' => $_SESSION['user_id'] ?? 'guest',
        ],
    ]);
    
    echo json_encode(['id' => $session->id]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
