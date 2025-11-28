<?php
require_once 'config/database.php';

$stmt = $pdo->query('SELECT id, name, image_url, cover_image, gallery, images FROM tours LIMIT 5');
$tours = $stmt->fetchAll();

foreach($tours as $t) {
    echo "Tour: " . $t['name'] . "\n";
    echo "Image URL: " . ($t['image_url'] ?: 'NULL') . "\n";
    echo "Cover: " . ($t['cover_image'] ?: 'NULL') . "\n";
    echo "Gallery: " . ($t['gallery'] ?: 'NULL') . "\n";
    echo "Images: " . ($t['images'] ?: 'NULL') . "\n";
    echo "---\n";
}
?>
