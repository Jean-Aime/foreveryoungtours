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

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$type = $_GET['type'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get') {
        $table = "booking_" . $type;
        $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } elseif ($action === 'count') {
        $table = "booking_" . $type;
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'];
    $type = $input['type'];
    $table = "booking_" . $type;
    
    if ($action === 'create') {
        $data = $input['data'];
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } elseif ($action === 'update') {
        $data = $input['data'];
        $id = $input['id'];
        $sets = array_map(fn($k) => "$k = ?", array_keys($data));
        
        $sql = "UPDATE $table SET " . implode(', ', $sets) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([...array_values($data), $id]);
        
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete') {
        $id = $input['id'];
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
    }
}
?>
