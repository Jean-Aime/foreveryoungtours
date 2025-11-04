<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required = ['tour_id', 'customer_name', 'customer_email', 'customer_phone', 'travel_date', 'participants'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Get tour details
    $stmt = $conn->prepare("SELECT * FROM tours WHERE id = ? AND status = 'active'");
    $stmt->execute([$data['tour_id']]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$tour) {
        http_response_code(404);
        echo json_encode(['error' => 'Tour not found']);
        exit;
    }
    
    // Calculate total amount
    $total_amount = $tour['price'] * $data['participants'];
    
    // Generate booking reference
    $booking_reference = 'FYT' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Insert booking
    $stmt = $conn->prepare("
        INSERT INTO bookings (booking_reference, tour_id, customer_name, customer_email, customer_phone, travel_date, participants, total_amount, status, notes, advisor_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, 1)
    ");
    
    $stmt->execute([
        $booking_reference,
        $data['tour_id'],
        $data['customer_name'],
        $data['customer_email'],
        $data['customer_phone'],
        $data['travel_date'],
        $data['participants'],
        $total_amount,
        $data['notes'] ?? ''
    ]);
    
    $booking_id = $conn->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'booking_id' => $booking_id,
        'booking_reference' => $booking_reference,
        'total_amount' => $total_amount,
        'message' => 'Booking created successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>