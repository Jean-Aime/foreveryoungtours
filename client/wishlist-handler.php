<?php
session_start();
require_once '../includes/csrf.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$tour_id = $_POST['tour_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    
    if ($action === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO wishlist (user_id, tour_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $tour_id]);
            echo json_encode(['success' => true, 'message' => 'Added to wishlist']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to add']);
        }
    } elseif ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND tour_id = ?");
        $stmt->execute([$user_id, $tour_id]);
        echo json_encode(['success' => true, 'message' => 'Removed from wishlist']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
