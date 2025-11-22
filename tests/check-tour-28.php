<?php
require_once 'config/database.php';

echo "<h1>Tour ID 28 Database Check</h1>";
echo "<pre>";

try {
    $stmt = $pdo->prepare("SELECT id, name, image_url, cover_image, country_id FROM tours WHERE id = 28");
    $stmt->execute();
    $tour = $stmt->fetch();
    
    if ($tour) {
        echo "✅ Tour ID 28 found:\n";
        echo "Name: " . $tour['name'] . "\n";
        echo "Country ID: " . $tour['country_id'] . "\n";
        echo "Image URL: " . ($tour['image_url'] ?: 'NULL') . "\n";
        echo "Cover Image: " . ($tour['cover_image'] ?: 'NULL') . "\n";
        
        // Check if image files exist
        if ($tour['image_url']) {
            $image_path = $tour['image_url'];
            if (file_exists($image_path)) {
                echo "✅ Image file exists: $image_path\n";
            } else {
                echo "❌ Image file missing: $image_path\n";
            }
        }
        
        if ($tour['cover_image']) {
            $cover_path = $tour['cover_image'];
            if (file_exists($cover_path)) {
                echo "✅ Cover image exists: $cover_path\n";
            } else {
                echo "❌ Cover image missing: $cover_path\n";
            }
        }
        
    } else {
        echo "❌ Tour ID 28 not found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
