<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';

if (!$date) {
    echo json_encode(['tours' => []]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT DISTINCT t.id, t.name, t.destination, t.duration_days, t.price
        FROM tours t
        INNER JOIN bookings b ON t.id = b.tour_id
        WHERE DATE(b.travel_date) = ? AND t.status = 'active'
        ORDER BY t.name
    ");
    $stmt->execute([$date]);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['tours' => $tours]);
} catch (PDOException $e) {
    echo json_encode(['tours' => [], 'error' => $e->getMessage()]);
}
