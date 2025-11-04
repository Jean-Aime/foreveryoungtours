<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_schedules') {
        $stmt = $pdo->query("
            SELECT ts.*, t.title as tour_title 
            FROM tour_schedules ts 
            JOIN tours t ON ts.tour_id = t.id 
            WHERE ts.scheduled_date >= CURDATE() AND ts.status = 'active'
            ORDER BY ts.scheduled_date
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'];
    
    if ($action === 'book') {
        $data = $input['data'];
        
        // Check availability
        $stmt = $pdo->prepare("SELECT available_slots, booked_slots FROM tour_schedules WHERE id = ?");
        $stmt->execute([$data['schedule_id']]);
        $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (($schedule['available_slots'] - $schedule['booked_slots']) < $data['number_of_people']) {
            echo json_encode(['success' => false, 'message' => 'Not enough slots available']);
            exit();
        }
        
        // Generate booking reference
        $booking_reference = 'TS' . date('Ymd') . rand(1000, 9999);
        
        // Create booking
        $stmt = $pdo->prepare("
            INSERT INTO tour_schedule_bookings 
            (schedule_id, user_id, booking_reference, customer_name, customer_email, customer_phone, number_of_people, total_amount, special_requests) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['schedule_id'],
            $_SESSION['user_id'] ?? null,
            $booking_reference,
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'],
            $data['number_of_people'],
            $data['total_amount'],
            $data['special_requests']
        ]);
        
        // Update booked slots
        $pdo->prepare("UPDATE tour_schedules SET booked_slots = booked_slots + ? WHERE id = ?")
            ->execute([$data['number_of_people'], $data['schedule_id']]);
        
        // Check if full
        $stmt = $pdo->prepare("SELECT available_slots, booked_slots FROM tour_schedules WHERE id = ?");
        $stmt->execute([$data['schedule_id']]);
        $updated = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($updated['booked_slots'] >= $updated['available_slots']) {
            $pdo->prepare("UPDATE tour_schedules SET status = 'full' WHERE id = ?")
                ->execute([$data['schedule_id']]);
        }
        
        echo json_encode(['success' => true, 'booking_reference' => $booking_reference]);
    }
}
?>
