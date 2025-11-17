<?php
require_once 'config/database.php';

$stmt = $pdo->prepare('SELECT id, name, cover_image, image_url FROM tours WHERE id = 29');
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "Tour: " . $tour['name'] . "\n";
    echo "Cover: " . ($tour['cover_image'] ?: 'NULL') . "\n";
    echo "Image: " . ($tour['image_url'] ?: 'NULL') . "\n";
    
    // Check if files exist
    if ($tour['cover_image']) {
        echo "Cover file exists: " . (file_exists($tour['cover_image']) ? 'YES' : 'NO') . "\n";
    }
    if ($tour['image_url']) {
        echo "Image file exists: " . (file_exists($tour['image_url']) ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "Tour 29 not found\n";
}
?>
