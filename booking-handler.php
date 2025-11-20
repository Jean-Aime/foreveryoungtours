<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to book a tour', 'redirect' => 'auth/login.php']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Use PDO directly instead of Database class
    global $pdo;
    
    // Validate required fields
    $required_fields = ['tour_id', 'customer_name', 'customer_email', 'customer_phone', 'travel_date', 'participants', 'total_price'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit;
        }
    }
    
    // Insert booking with user ID
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, tour_id, customer_name, customer_email, customer_phone, travel_date, participants, total_price, notes, status, payment_status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', NOW())
    ");
    
    $result = $stmt->execute([
        $_SESSION['user_id'],
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