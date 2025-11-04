<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
if ($user_role !== 'super_admin' && $user_role !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_schedules') {
        $stmt = $pdo->query("
            SELECT ts.*, t.name as tour_title 
            FROM tour_schedules ts 
            JOIN tours t ON ts.tour_id = t.id 
            WHERE ts.scheduled_date >= CURDATE() - INTERVAL 30 DAY
            ORDER BY ts.scheduled_date
        ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'];
    
    if ($action === 'create') {
        $data = $input['data'];
        $stmt = $pdo->prepare("
            INSERT INTO tour_schedules (tour_id, scheduled_date, end_date, available_slots, price, notes, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['tour_id'],
            $data['scheduled_date'],
            $data['end_date'] ?: null,
            $data['available_slots'],
            $data['price'],
            $data['notes'],
            $_SESSION['user_id']
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } elseif ($action === 'delete') {
        $id = $input['id'];
        $stmt = $pdo->prepare("DELETE FROM tour_schedules WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    }
}
?>
