<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../config/database_adapter.php';

$db = new Database();
$conn = $db->getConnection();
$adapter = new DatabaseAdapter($conn);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            handleGet($conn, $action);
            break;
        case 'POST':
            handlePost($conn, $action);
            break;
        case 'PUT':
            handlePut($conn, $action);
            break;
        case 'DELETE':
            handleDelete($conn, $action);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleGet($conn, $action) {
    switch ($action) {
        case 'tours':
            $stmt = $conn->prepare("SELECT * FROM tours WHERE status = 'active' ORDER BY id");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;
        case 'stats':
            $stats = [];
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tours WHERE status = 'active'");
            $stmt->execute();
            $stats['tours'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
            $stmt->execute();
            $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings");
            $stmt->execute();
            $stats['bookings'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $stmt = $conn->prepare("SELECT SUM(total_amount) as revenue FROM bookings WHERE status IN ('confirmed', 'completed')");
            $stmt->execute();
            $stats['revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;
            
            echo json_encode($stats);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
}

function handlePost($conn, $action) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'tour':
            $stmt = $conn->prepare("INSERT INTO tours (name, description, destination, price, duration, max_participants, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['destination'],
                $data['price'],
                $data['duration'],
                $data['max_participants'] ?? 20
            ]);
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            break;
        case 'user':
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['role'] ?? 'user'
            ]);
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
}

function handlePut($conn, $action) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($action) {
        case 'tour':
            $stmt = $conn->prepare("UPDATE tours SET name = ?, description = ?, destination = ?, price = ?, duration = ?, max_participants = ? WHERE id = ?");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['destination'],
                $data['price'],
                $data['duration'],
                $data['max_participants'],
                $data['id']
            ]);
            echo json_encode(['success' => true]);
            break;
        case 'booking_status':
            $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->execute([$data['status'], $data['id']]);
            echo json_encode(['success' => true]);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
}

function handleDelete($conn, $action) {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID required']);
        return;
    }
    
    switch ($action) {
        case 'tour':
            $stmt = $conn->prepare("UPDATE tours SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;
        case 'user':
            $stmt = $conn->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
}
?>