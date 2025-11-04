<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$booking_id = $input['booking_id'] ?? 0;

try {
    switch ($action) {
        case 'update_status':
            $status = $input['status'] ?? '';
            $valid_statuses = ['pending', 'confirmed', 'paid', 'cancelled', 'completed'];
            
            if (!in_array($status, $valid_statuses)) {
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                exit;
            }
            
            $updates = ['status' => $status];
            
            if ($status === 'confirmed') {
                $updates['confirmed_date'] = date('Y-m-d H:i:s');
            } elseif ($status === 'cancelled') {
                $updates['cancelled_date'] = date('Y-m-d H:i:s');
            }
            
            $setClause = [];
            $params = [];
            foreach ($updates as $key => $value) {
                $setClause[] = "$key = ?";
                $params[] = $value;
            }
            $params[] = $booking_id;
            
            $sql = "UPDATE bookings SET " . implode(', ', $setClause) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            break;
            
        case 'send_email':
            $email = $input['email'] ?? '';
            $subject = $input['subject'] ?? '';
            $message = $input['message'] ?? '';
            
            if (!$email || !$subject || !$message) {
                echo json_encode(['success' => false, 'message' => 'Missing email parameters']);
                exit;
            }
            
            $headers = "From: noreply@foreveryoungtours.com\r\n";
            $headers .= "Reply-To: support@foreveryoungtours.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            $sent = mail($email, $subject, $message, $headers);
            
            if ($sent) {
                echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to send email']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
