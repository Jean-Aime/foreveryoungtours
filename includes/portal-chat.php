<?php
// PORTAL CHAT SYSTEM - Handle real-time messaging

require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$portalCode = $_POST['portal_code'] ?? $_GET['portal_code'] ?? '';

if (empty($portalCode)) {
    echo json_encode(['success' => false, 'error' => 'Portal code required']);
    exit;
}

switch ($action) {
    case 'send_message':
        $message = trim($_POST['message'] ?? '');
        $senderType = $_POST['sender_type'] ?? 'client'; // client, advisor, admin
        $senderName = $_POST['sender_name'] ?? 'Client';
        $senderId = $_SESSION['user_id'] ?? null;
        
        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
            exit;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO portal_messages (portal_code, sender_type, sender_id, sender_name, message)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([$portalCode, $senderType, $senderId, $senderName, $message]);
        
        if ($result) {
            // Update last activity
            $pdo->prepare("UPDATE client_registry SET last_activity = NOW() WHERE portal_code = ?")
                ->execute([$portalCode]);
            
            // Create alert for advisor if message from client
            if ($senderType === 'client') {
                $portal = $pdo->prepare("SELECT owned_by_user_id FROM client_registry WHERE portal_code = ?");
                $portal->execute([$portalCode]);
                $portalData = $portal->fetch();
                
                if ($portalData) {
                    $pdo->prepare("
                        INSERT INTO ownership_alerts (portal_code, alert_type, advisor_id, alert_message)
                        VALUES (?, 'message_received', ?, ?)
                    ")->execute([$portalCode, $portalData['owned_by_user_id'], "New message: " . substr($message, 0, 50)]);
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Message sent']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to send message']);
        }
        break;
        
    case 'get_messages':
        $stmt = $pdo->prepare("
            SELECT * FROM portal_messages 
            WHERE portal_code = ? 
            ORDER BY created_at ASC
        ");
        $stmt->execute([$portalCode]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mark messages as read if advisor/admin viewing
        if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && in_array($_SESSION['role'], ['advisor', 'admin'])) {
            $pdo->prepare("UPDATE portal_messages SET is_read = 1 WHERE portal_code = ? AND sender_type = 'client'")
                ->execute([$portalCode]);
        }
        
        echo json_encode(['success' => true, 'messages' => $messages]);
        break;
        
    case 'mark_read':
        $stmt = $pdo->prepare("UPDATE portal_messages SET is_read = 1 WHERE portal_code = ?");
        $result = $stmt->execute([$portalCode]);
        echo json_encode(['success' => $result]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
