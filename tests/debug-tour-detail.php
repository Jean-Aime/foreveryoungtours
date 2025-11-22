<?php
echo "<h1>Debug Tour Detail Page</h1>";
echo "<pre>";

echo "=== ENVIRONMENT ===\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
echo "GET parameters: " . print_r($_GET, true) . "\n";

echo "=== DATABASE CONNECTION ===\n";
try {
    require_once 'config/database.php';
    echo "✅ Database connected successfully\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

echo "=== TOUR LOOKUP ===\n";
$tour_id = $_GET['id'] ?? 0;
echo "Tour ID: $tour_id\n";

if ($tour_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name 
            FROM tours t 
            LEFT JOIN countries c ON t.country_id = c.id 
            LEFT JOIN regions r ON c.region_id = r.id 
            WHERE t.id = ? AND t.status = 'active'
        ");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch();
        
        if ($tour) {
            echo "✅ Tour found:\n";
            echo "Name: " . $tour['name'] . "\n";
            echo "Country: " . $tour['country_name'] . "\n";
            echo "Image URL: " . ($tour['image_url'] ?: 'NULL') . "\n";
            echo "Cover Image: " . ($tour['cover_image'] ?: 'NULL') . "\n";
            echo "Status: " . $tour['status'] . "\n";
        } else {
            echo "❌ Tour not found or inactive\n";
        }
    } catch (Exception $e) {
        echo "❌ Database query failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ No tour ID provided\n";
}

echo "=== FILE PATHS ===\n";
echo "Current working directory: " . getcwd() . "\n";
echo "Script directory: " . __DIR__ . "\n";

// Test image paths
if (isset($tour) && $tour) {
    echo "\n=== IMAGE PATH TESTING ===\n";
    
    function fixImagePath($imagePath) {
        if (empty($imagePath)) {
            return '../../../assets/images/default-tour.jpg';
        }
        
        // If it's an upload path, prepend the correct relative path
        if (strpos($imagePath, 'uploads/') === 0) {
            return '../../../' . $imagePath;
        }
        
        // If it's already a relative path starting with ../
        if (strpos($imagePath, '../') === 0) {
            return $imagePath;
        }
        
        // If it's an assets path
        if (strpos($imagePath, 'assets/') === 0) {
            return '../../../' . $imagePath;
        }
        
        // If it's an external URL, return as-is
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // Default case - assume it needs the full relative path
        return '../../../' . $imagePath;
    }
    
    $bg_image = fixImagePath($tour['cover_image'] ?: $tour['image_url']);
    echo "Background image path: $bg_image\n";
    echo "Background image exists: " . (file_exists($bg_image) ? "YES" : "NO") . "\n";
    
    if ($tour['image_url']) {
        $fixed_image = fixImagePath($tour['image_url']);
        echo "Main image path: $fixed_image\n";
        echo "Main image exists: " . (file_exists($fixed_image) ? "YES" : "NO") . "\n";
    }
}

echo "</pre>";
?>
