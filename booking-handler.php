<?php
header('Content-Type: application/json');

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Validate required fields
    $required_fields = ['tour_id', 'customer_name', 'customer_email', 'customer_phone', 'travel_date', 'participants', 'total_price'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit;
        }
    }
    
    // Insert booking
    $stmt = $conn->prepare("
        INSERT INTO bookings (tour_id, customer_name, customer_email, customer_phone, travel_date, participants, total_price, notes, status, payment_status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')
    ");
    
    $result = $stmt->execute([
        $_POST['tour_id'],
        $_POST['customer_name'],
        $_POST['customer_email'],
        $_POST['customer_phone'],
        $_POST['travel_date'],
        $_POST['participants'],
        $_POST['total_price'],
        $_POST['notes'] ?? ''
    ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Booking submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit booking']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}