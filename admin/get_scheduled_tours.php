<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode(['tours' => []]);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            ts.id as schedule_id,
            ts.tour_id,
            ts.scheduled_date,
            ts.available_slots,
            ts.booked_slots,
            ts.price,
            t.name as tour_name,
            t.destination,
            t.duration_days
        FROM tour_schedules ts
        JOIN tours t ON ts.tour_id = t.id
        WHERE ts.scheduled_date = ? 
        AND ts.status = 'active'
        AND ts.available_slots > ts.booked_slots
        ORDER BY t.name
    ");
    $stmt->execute([$date]);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['tours' => $tours]);
} catch (PDOException $e) {
    echo json_encode(['tours' => [], 'error' => $e->getMessage()]);
}
