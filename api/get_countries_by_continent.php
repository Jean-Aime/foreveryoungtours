<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$region_id = $_GET['region_id'] ?? null;

if (!$region_id) {
    echo json_encode(['success' => false, 'error' => 'Region ID required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
    $stmt->execute([$region_id]);
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $countries]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
