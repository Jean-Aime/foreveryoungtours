<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$booking_type = $input['booking_type'] ?? null;
$item_id = $input['item_id'] ?? null;
$customer_name = $input['customer_name'] ?? null;
$customer_email = $input['customer_email'] ?? null;
$customer_phone = $input['customer_phone'] ?? null;
$booking_date = $input['booking_date'] ?? null;
$return_date = $input['return_date'] ?? null;
$passengers = $input['passengers'] ?? 1;
$total_price = $input['total_price'] ?? 0;
$notes = $input['notes'] ?? null;

if (!$booking_type || !$item_id || !$customer_name || !$customer_email || !$booking_date) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

// Generate unique booking reference
$booking_reference = strtoupper($booking_type[0]) . date('Ymd') . rand(1000, 9999);

$user_id = $_SESSION['user_id'] ?? null;

try {
    $stmt = $pdo->prepare("INSERT INTO booking_engine_orders 
        (booking_reference, user_id, booking_type, item_id, customer_name, customer_email, customer_phone, 
         booking_date, return_date, passengers, total_price, notes, status, payment_status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'unpaid')");
    
    $stmt->execute([
        $booking_reference,
        $user_id,
        $booking_type,
        $item_id,
        $customer_name,
        $customer_email,
        $customer_phone,
        $booking_date,
        $return_date,
        $passengers,
        $total_price,
        $notes
    ]);
    
    echo json_encode([
        'success' => true,
        'booking_reference' => $booking_reference,
        'message' => 'Booking created successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
