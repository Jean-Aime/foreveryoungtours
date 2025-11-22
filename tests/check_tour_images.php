<?php
require_once 'config/database.php';

echo "<h2>Tour Images Debug</h2>";

// Get tours with their image data
$stmt = $pdo->prepare("SELECT id, name, cover_image, image_url FROM tours ORDER BY id");
$stmt->execute();
$tours = $stmt->fetchAll();

foreach ($tours as $tour) {
    echo "<h3>Tour ID: {$tour['id']} - {$tour['name']}</h3>";
    echo "<p><strong>Database cover_image:</strong> " . ($tour['cover_image'] ?: 'NULL') . "</p>";
    echo "<p><strong>Database image_url:</strong> " . ($tour['image_url'] ?: 'NULL') . "</p>";
    
    // Check what files actually exist for this tour
    $tour_files = glob("uploads/tours/{$tour['id']}_*");
    echo "<p><strong>Actual files found:</strong></p>";
    if (!empty($tour_files)) {
        foreach ($tour_files as $file) {
            $filename = basename($file);
            echo "- $filename<br>";
        }
    } else {
        echo "- No files found<br>";
    }
    echo "<hr>";
}
?>