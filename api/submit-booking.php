<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    require_once '../config.php';
    require_once '../config/database.php';
    
    $required_fields = ['tour_id', 'full_name', 'email', 'phone', 'adults'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("$field is required");
        }
    }
    
    $tour_id = (int)$_POST['tour_id'];
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $adults = (int)$_POST['adults'];
    $children = (int)($_POST['children'] ?? 0);
    $preferred_date = $_POST['preferred_date'] ?: null;
    $special_requirements = trim($_POST['special_requirements'] ?? '');
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    $stmt = $pdo->prepare("SELECT name, price FROM tours WHERE id = ? AND status = 'active'");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch();
    
    if (!$tour) {
        throw new Exception('Tour not found');
    }
    
    $price_per_person = $tour['price'];
    $total_participants = $adults + $children;
    $total_price = $price_per_person * $total_participants;
    
    $booking_reference = 'FYT' . date('Ymd') . str_pad($tour_id, 3, '0', STR_PAD_LEFT) . rand(100, 999);
    
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            booking_reference, tour_id, full_name, email, phone, 
            adults, children, total_participants, price_per_person, 
            total_price, preferred_date, special_requirements, 
            status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->execute([
        $booking_reference, $tour_id, $full_name, $email, $phone,
        $adults, $children, $total_participants, $price_per_person,
        $total_price, $preferred_date, $special_requirements
    ]);
    
    $booking_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking submitted successfully',
        'booking_reference' => $booking_reference,
        'booking_id' => $booking_id
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>