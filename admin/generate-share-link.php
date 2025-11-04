<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$tour_id = $input['tour_id'] ?? 0;
$user_id = 5; // Default advisor ID

if (!$tour_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid tour ID']);
    exit();
}

try {
    // Generate unique link code
    $link_code = 'ADV' . $user_id . '_' . $tour_id . '_' . time();
    
    // Get tour details
    $tour_query = "SELECT slug FROM tours WHERE id = ?";
    $tour_stmt = $pdo->prepare($tour_query);
    $tour_stmt->execute([$tour_id]);
    $tour = $tour_stmt->fetch();
    
    if (!$tour) {
        echo json_encode(['success' => false, 'message' => 'Tour not found']);
        exit();
    }
    
    // Create full URL
    $base_url = 'https://foreveryoungtours.com/tour/' . $tour['slug'];
    $full_url = $base_url . '?ref=' . $link_code;
    
    // Check if link already exists
    $existing_query = "SELECT id FROM shared_links WHERE tour_id = ? AND user_id = ? AND is_active = 1";
    $existing_stmt = $pdo->prepare($existing_query);
    $existing_stmt->execute([$tour_id, $user_id]);
    
    if ($existing_stmt->fetch()) {
        // Update existing link
        $update_query = "UPDATE shared_links SET link_code = ?, full_url = ?, created_at = NOW() WHERE tour_id = ? AND user_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$link_code, $full_url, $tour_id, $user_id]);
    } else {
        // Create new link
        $insert_query = "INSERT INTO shared_links (tour_id, user_id, link_code, full_url, is_active) VALUES (?, ?, ?, ?, 1)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->execute([$tour_id, $user_id, $link_code, $full_url]);
    }
    
    echo json_encode([
        'success' => true, 
        'link' => $full_url,
        'code' => $link_code
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error generating link: ' . $e->getMessage()]);
}
?>