<?php
function uploadTourImage($file, $tour_id, $type = 'gallery') {
    $upload_dir = '../uploads/tours/';
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.');
    }
    
    if ($file['size'] > $max_size) {
        throw new Exception('File size too large. Maximum 5MB allowed.');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $tour_id . '_' . $type . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'uploads/tours/' . $filename;
    } else {
        throw new Exception('Failed to upload file.');
    }
}

function deleteFile($filepath) {
    if (file_exists('../' . $filepath)) {
        unlink('../' . $filepath);
    }
}
?>