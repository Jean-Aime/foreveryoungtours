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
    if ($action === 'get_all') {
        $stmt = $pdo->query("SELECT * FROM booking_engine_orders ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'];
    
    if ($action === 'update_status') {
        $id = $input['id'];
        $status = $input['status'];
        $field = $input['field'];
        
        if ($field === 'status') {
            $stmt = $pdo->prepare("UPDATE booking_engine_orders SET status = ? WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE booking_engine_orders SET payment_status = ? WHERE id = ?");
        }
        
        $stmt->execute([$status, $id]);
        echo json_encode(['success' => true]);
    }
}
?>
