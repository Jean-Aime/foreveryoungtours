<?php
/**
 * Test script to verify image paths are working correctly
 */
require_once 'config/database.php';

echo "<h1>Image Path Testing</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
    .image-test { display: inline-block; margin: 10px; text-align: center; }
    .image-test img { width: 200px; height: 150px; object-fit: cover; border: 1px solid #ccc; }
    .path-info { font-size: 12px; color: #666; margin-top: 5px; }
</style>";

// Test tour with ID 28
$stmt = $pdo->prepare("SELECT * FROM tours WHERE id = 28");
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "<div class='test-section'>";
    echo "<h2>Tour ID 28: " . htmlspecialchars($tour['name']) . "</h2>";
    
    // Test different image fields
    $image_fields = ['image_url', 'cover_image', 'images'];
    
    foreach ($image_fields as $field) {
        if (!empty($tour[$field])) {
            echo "<div class='image-test'>";
            echo "<h3>$field</h3>";
            
            if ($field === 'images') {
                $images = json_decode($tour[$field], true);
                if ($images && is_array($images)) {
                    foreach (array_slice($images, 0, 3) as $i => $img) {
                        echo "<div style='margin: 5px;'>";
                        echo "<img src='$img' alt='Gallery Image $i' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: $img'\">";
                        echo "<div class='path-info'>Gallery[$i]: $img</div>";
                        echo "</div>";
                    }
                }
            } else {
                $img_path = $tour[$field];
                echo "<img src='$img_path' alt='$field' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: $img_path'\">";
                echo "<div class='path-info'>$img_path</div>";
            }
            echo "</div>";
        }
    }
    echo "</div>";
} else {
    echo "<p>Tour ID 28 not found!</p>";
}

// Test featured tours from Africa
echo "<div class='test-section'>";
echo "<h2>Featured Tours from Africa</h2>";

$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    INNER JOIN regions r ON c.region_id = r.id
    WHERE r.slug = 'africa' AND t.status = 'active' AND t.featured = 1
    ORDER BY t.popularity_score DESC
    LIMIT 3
");
$stmt->execute();
$featured_tours = $stmt->fetchAll();

foreach ($featured_tours as $tour) {
    echo "<div class='image-test'>";
    echo "<h4>" . htmlspecialchars($tour['name']) . "</h4>";
    
    $img_path = $tour['cover_image'] ?: $tour['image_url'] ?: 'assets/images/default-tour.jpg';
    echo "<img src='$img_path' alt='{$tour['name']}' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: $img_path'\">";
    echo "<div class='path-info'>$img_path</div>";
    echo "<div class='path-info'>Country: {$tour['country_name']}</div>";
    echo "</div>";
}
echo "</div>";

echo "<p><strong>Instructions:</strong></p>";
echo "<ul>";
echo "<li>Images with red borders failed to load</li>";
echo "<li>Check the path info below each image</li>";
echo "<li>Test this from: <a href='http://localhost/foreveryoungtours/test-images.php'>Main Domain</a></li>";
echo "</ul>";
?>
