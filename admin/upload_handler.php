<?php

require_once 'config.php';
function uploadTourImage($file, $tour_id, $type = 'gallery') {
    $upload_dir = '../uploads/tours/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception('Failed to create upload directory.');
        }
    }
    
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!isset($file['type']) || !in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed. Received: ' . ($file['type'] ?? 'unknown'));
    }
    
    if (!isset($file['size']) || $file['size'] > $max_size) {
        throw new Exception('File size too large. Maximum 5MB allowed.');
    }
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new Exception('Invalid uploaded file.');
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        throw new Exception('Invalid file extension: ' . $extension);
    }
    
    $timestamp = time();
    $random = mt_rand(1000, 9999);
    $filename = $tour_id . '_' . $type . '_' . $timestamp . '_' . $random . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to move uploaded file to: ' . $filepath);
    }
    
    // Return path relative to web root
    return 'uploads/tours/' . $filename;
}

function deleteFile($filepath) {
    if (file_exists('../' . $filepath)) {
        if (!unlink('../' . $filepath)) {
            error_log('Failed to delete file: ' . $filepath);
        }
    }
}
?>
