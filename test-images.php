<?php
require_once 'config.php';
require_once 'config/database.php';

// Get a sample tour
$stmt = $pdo->prepare("SELECT id, name, cover_image, image_url FROM tours WHERE status = 'active' LIMIT 1");
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "<h2>Tour: " . htmlspecialchars($tour['name']) . "</h2>";
    echo "<p><strong>ID:</strong> " . $tour['id'] . "</p>";
    echo "<p><strong>cover_image:</strong> " . htmlspecialchars($tour['cover_image']) . "</p>";
    echo "<p><strong>image_url:</strong> " . htmlspecialchars($tour['image_url']) . "</p>";
    echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
    
    $test_image = getImageUrl($tour['cover_image']);
    echo "<p><strong>getImageUrl result:</strong> " . htmlspecialchars($test_image) . "</p>";
    
    echo "<h3>Test Image:</h3>";
    echo "<img src='" . htmlspecialchars($test_image) . "' style='max-width: 300px;' onerror=\"this.style.border='2px solid red'; this.alt='FAILED TO LOAD';\">";
}
?>