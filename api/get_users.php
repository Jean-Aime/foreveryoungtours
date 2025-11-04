<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, email, role, status, phone, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>