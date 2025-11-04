<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Generate booking reference
    $booking_ref = 'BK' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Insert booking
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            booking_reference, tour_id, customer_name, customer_email, customer_phone,
            emergency_contact, travel_date, participants, accommodation_type,
            transport_type, special_requests, payment_method, total_price,
            total_amount, status, payment_status, booking_source
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', 'direct')
    ");
    
    $total = floatval($_POST['total_price']);
    
    $stmt->execute([
        $booking_ref,
        $_POST['tour_id'] ?? null,
        $_POST['customer_name'],
        $_POST['customer_email'],
        $_POST['customer_phone'],
        $_POST['emergency_contact'] ?? '',
        $_POST['travel_date'],
        $_POST['participants'],
        $_POST['accommodation_type'] ?? 'standard',
        $_POST['transport_type'] ?? 'shared',
        $_POST['special_requests'] ?? '',
        $_POST['payment_method'],
        $total,
        $total
    ]);
    
    echo json_encode([
        'success' => true,
        'booking_reference' => $booking_ref,
        'message' => 'Booking created successfully'
    ]);
    
} catch (Exception $e) {
    error_log("Booking Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
