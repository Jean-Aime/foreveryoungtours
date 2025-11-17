<?php
require_once 'config/database.php';

echo "<h1>🧪 Testing Tour 28 for Rwanda Subdomain</h1>";
echo "<hr>";

// Test database connection
try {
    $tour_id = 28;
    
    // Get tour details
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name 
        FROM tours t 
        LEFT JOIN countries c ON t.country_id = c.id 
        LEFT JOIN regions r ON c.region_id = r.id 
        WHERE t.id = ? AND t.status = 'active'
    ");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch();
    
    if (!$tour) {
        echo "<h2>❌ Tour 28 NOT FOUND or INACTIVE</h2>";
        
        // Check if tour exists at all
        $check_stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
        $check_stmt->execute([$tour_id]);
        $check_tour = $check_stmt->fetch();
        
        if ($check_tour) {
            echo "<p>Tour exists but status is: <strong>" . $check_tour['status'] . "</strong></p>";
            echo "<pre>" . print_r($check_tour, true) . "</pre>";
        } else {
            echo "<p>Tour 28 does not exist in database</p>";
        }
        exit;
    }
    
    echo "<h2>✅ Tour 28 Found</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><td><strong>ID</strong></td><td>" . $tour['id'] . "</td></tr>";
    echo "<tr><td><strong>Name</strong></td><td>" . htmlspecialchars($tour['name']) . "</td></tr>";
    echo "<tr><td><strong>Status</strong></td><td>" . $tour['status'] . "</td></tr>";
    echo "<tr><td><strong>Country</strong></td><td>" . htmlspecialchars($tour['country_name']) . " (ID: " . $tour['country_id'] . ")</td></tr>";
    echo "<tr><td><strong>Country Slug</strong></td><td>" . $tour['country_slug'] . "</td></tr>";
    echo "<tr><td><strong>Region</strong></td><td>" . htmlspecialchars($tour['region_name']) . "</td></tr>";
    echo "</table>";
    
    echo "<h2>🖼️ Image Fields Analysis</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><td><strong>image_url</strong></td><td>" . htmlspecialchars($tour['image_url'] ?: 'NULL') . "</td></tr>";
    echo "<tr><td><strong>cover_image</strong></td><td>" . htmlspecialchars($tour['cover_image'] ?: 'NULL') . "</td></tr>";
    echo "<tr><td><strong>gallery_images</strong></td><td>" . htmlspecialchars($tour['gallery_images'] ?: 'NULL') . "</td></tr>";
    echo "<tr><td><strong>images</strong></td><td>" . htmlspecialchars($tour['images'] ?: 'NULL') . "</td></tr>";
    echo "<tr><td><strong>gallery</strong></td><td>" . htmlspecialchars($tour['gallery'] ?: 'NULL') . "</td></tr>";
    echo "</table>";
    
    // Test image path function
    function getImagePath($imagePath, $fallback = '../../../assets/images/default-tour.jpg') {
        if (empty($imagePath)) {
            return $fallback;
        }
        
        // For uploads directory, always use relative path from country page context
        if (strpos($imagePath, 'uploads/') === 0) {
            return '../../../' . $imagePath;
        }
        
        // For assets directory, use relative path
        if (strpos($imagePath, 'assets/') === 0) {
            return '../../../' . $imagePath;
        }
        
        // If already relative path, use as is
        if (strpos($imagePath, '../') === 0) {
            return $imagePath;
        }
        
        // External URLs unchanged
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // Default: assume it's a relative path from root
        return '../../../' . $imagePath;
    }
    
    echo "<h2>🔧 Processed Image Paths</h2>";
    $bg_image = getImagePath($tour['cover_image'] ?: $tour['image_url']);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><td><strong>Hero Background</strong></td><td>" . htmlspecialchars($bg_image) . "</td></tr>";
    
    // Process gallery images
    if (!empty($tour['gallery'])) {
        $gallery_images = json_decode($tour['gallery'], true);
        if (is_array($gallery_images)) {
            foreach ($gallery_images as $index => $image) {
                $processed = getImagePath($image);
                echo "<tr><td><strong>Gallery " . ($index + 1) . "</strong></td><td>" . htmlspecialchars($processed) . "</td></tr>";
            }
        }
    }
    
    // Process images array
    if (!empty($tour['images'])) {
        $images_array = json_decode($tour['images'], true);
        if (is_array($images_array)) {
            foreach ($images_array as $index => $image) {
                $processed = getImagePath($image);
                echo "<tr><td><strong>Image " . ($index + 1) . "</strong></td><td>" . htmlspecialchars($processed) . "</td></tr>";
            }
        }
    }
    echo "</table>";
    
    echo "<h2>🌐 Test URLs</h2>";
    echo "<p><strong>Main Domain:</strong> <a href='http://localhost/foreveryoungtours/pages/tour-detail?id=28' target='_blank'>http://localhost/foreveryoungtours/pages/tour-detail?id=28</a></p>";
    echo "<p><strong>Rwanda Subdomain:</strong> <a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28' target='_blank'>http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28</a></p>";
    
    echo "<h2>📁 File Existence Check</h2>";
    $test_paths = [
        $bg_image,
        '../assets/images/default-tour.jpg',
        'assets/images/default-tour.jpg'
    ];
    
    foreach ($test_paths as $path) {
        $exists = file_exists($path) ? '✅ EXISTS' : '❌ NOT FOUND';
        echo "<p><strong>" . htmlspecialchars($path) . "</strong>: " . $exists . "</p>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Database Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
