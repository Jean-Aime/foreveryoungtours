<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$commission_ids = $input['ids'] ?? [];
$new_status = $input['status'] ?? '';
$is_bulk = $input['bulk'] ?? false;

if (empty($commission_ids) || !$new_status) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

try {
    $pdo->beginTransaction();
    
    $placeholders = str_repeat('?,', count($commission_ids) - 1) . '?';
    $params = array_merge([$new_status], $commission_ids);
    
    if ($new_status === 'paid') {
        $query = "UPDATE commissions SET status = ?, paid_date = NOW() WHERE id IN ($placeholders)";
    } else {
        $query = "UPDATE commissions SET status = ? WHERE id IN ($placeholders)";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    $affected_rows = $stmt->rowCount();
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => "Successfully updated $affected_rows commission(s)",
        'affected_rows' => $affected_rows
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error updating commission status: ' . $e->getMessage()]);
}
?>