<?php
// Audit Logging System

function logAudit($user_id, $action, $entity_type, $entity_id = null, $old_values = null, $new_values = null) {
    global $pdo;
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $action,
        $entity_type,
        $entity_id,
        $old_values ? json_encode($old_values) : null,
        $new_values ? json_encode($new_values) : null,
        $ip,
        $user_agent
    ]);
}

function getAuditLogs($filters = []) {
    global $pdo;
    
    $sql = "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['user_id'])) {
        $sql .= " AND al.user_id = ?";
        $params[] = $filters['user_id'];
    }
    
    if (!empty($filters['entity_type'])) {
        $sql .= " AND al.entity_type = ?";
        $params[] = $filters['entity_type'];
    }
    
    if (!empty($filters['date_from'])) {
        $sql .= " AND al.created_at >= ?";
        $params[] = $filters['date_from'];
    }
    
    if (!empty($filters['date_to'])) {
        $sql .= " AND al.created_at <= ?";
        $params[] = $filters['date_to'];
    }
    
    $sql .= " ORDER BY al.created_at DESC LIMIT " . ($filters['limit'] ?? 100);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
