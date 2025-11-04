<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$booking_id = $input['id'] ?? 0;
$new_status = $input['status'] ?? '';

if (!$booking_id || !$new_status) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

try {
    $pdo->beginTransaction();
    
    // Update booking status
    $stmt = $pdo->prepare("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $booking_id]);
    
    // If confirming booking, create commission records
    if ($new_status === 'confirmed') {
        // Get booking details
        $booking_query = "
            SELECT b.*, t.advisor_commission_rate, t.mca_commission_rate, 
                   advisor.mca_id, advisor.upline_id
            FROM bookings b
            LEFT JOIN tours t ON b.tour_id = t.id
            LEFT JOIN users advisor ON b.advisor_id = advisor.id
            WHERE b.id = ?
        ";
        $booking_stmt = $pdo->prepare($booking_query);
        $booking_stmt->execute([$booking_id]);
        $booking = $booking_stmt->fetch();
        
        if ($booking && $booking['advisor_id']) {
            // Create direct commission for advisor
            $advisor_commission = $booking['total_amount'] * ($booking['advisor_commission_rate'] / 100);
            $commission_stmt = $pdo->prepare("
                INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount, status)
                VALUES (?, ?, 'direct', ?, ?, 'pending')
            ");
            $commission_stmt->execute([
                $booking_id, 
                $booking['advisor_id'], 
                $booking['advisor_commission_rate'], 
                $advisor_commission
            ]);
            
            // Create MCA commission if applicable
            if ($booking['mca_id']) {
                $mca_commission = $booking['total_amount'] * ($booking['mca_commission_rate'] / 100);
                $commission_stmt->execute([
                    $booking_id, 
                    $booking['mca_id'], 
                    $booking['mca_commission_rate'], 
                    $mca_commission
                ]);
            }
        }
        
        // Update confirmed date
        $pdo->prepare("UPDATE bookings SET confirmed_date = NOW() WHERE id = ?")->execute([$booking_id]);
    }
    
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Booking status updated successfully']);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error updating booking: ' . $e->getMessage()]);
}
?>