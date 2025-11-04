<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Generate booking reference
    $booking_reference = 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    
    // Get form data
    $tour_id = $_POST['tour_id'] ?? 0;
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $participants = $_POST['participants'] ?? 1;
    $accommodation = $_POST['accommodation'] ?? 'standard';
    $transport = $_POST['transport'] ?? 'shared';
    $special_requests = $_POST['special_requests'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'card';
    
    // Get tour price
    $stmt = $pdo->prepare("SELECT price FROM tours WHERE id = ?");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch();
    
    if (!$tour) {
        echo json_encode(['success' => false, 'message' => 'Tour not found']);
        exit;
    }
    
    // Calculate total amount
    $base_price = $tour['price'];
    $accommodation_price = 0;
    $transport_price = 0;
    
    if ($accommodation === 'luxury') $accommodation_price = 200;
    else if ($accommodation === 'premium') $accommodation_price = 100;
    
    if ($transport === 'private') $transport_price = 150;
    else if ($transport === 'premium') $transport_price = 75;
    
    $subtotal = ($base_price + $accommodation_price + $transport_price) * $participants;
    $tax = $subtotal * 0.1;
    $total_amount = $subtotal + $tax;
    
    // Get user_id if logged in
    $user_id = $_SESSION['user_id'] ?? null;
    
    // Insert booking
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            booking_reference, user_id, tour_id, customer_name, customer_email, 
            customer_phone, travel_date, participants, total_amount, total_price,
            status, payment_status, payment_method, emergency_contact, 
            accommodation_type, transport_type, special_requests, booking_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $booking_reference, $user_id, $tour_id, $customer_name, $customer_email,
        $customer_phone, $travel_date, $participants, $total_amount, $total_amount,
        $payment_method, $emergency_contact, $accommodation, $transport, $special_requests
    ]);
    
    echo json_encode([
        'success' => true,
        'booking_reference' => $booking_reference,
        'message' => 'Booking created successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
