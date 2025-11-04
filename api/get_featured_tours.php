<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$region_id = $_GET['region_id'] ?? null;

if (!$region_id) {
    echo json_encode(['success' => false, 'error' => 'Region ID required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT t.* FROM tours t
        INNER JOIN countries c ON t.country_id = c.id
        WHERE c.region_id = ? AND t.status = 'active' AND t.featured = 1
        ORDER BY t.popularity_score DESC, t.average_rating DESC
        LIMIT 6
    ");
    $stmt->execute([$region_id]);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $tours]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
