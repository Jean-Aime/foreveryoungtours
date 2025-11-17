<?php

require_once 'config.php';
require_once '../../../config/database.php';

echo "<h1>Simple Image Test - Rwanda Subdomain</h1>";

// Get tour 29
$stmt = $pdo->prepare('SELECT id, name, cover_image, image_url FROM tours WHERE id = 29');
$stmt->execute();
$tour = $stmt->fetch();

if (!$tour) {
    echo "<p>Tour 29 not found</p>";
    exit;
}

echo "<h2>Environment Info</h2>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";

// Detect environment
$is_subdomain = strpos($_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos($_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false;
echo "<p><strong>Is Subdomain:</strong> " . ($is_subdomain ? 'YES' : 'NO') . "</p>";

echo "<h2>Tour Data</h2>";
echo "<p><strong>Name:</strong> " . $tour['name'] . "</p>";
echo "<p><strong>Cover Image (DB):</strong> " . ($tour['cover_image'] ?: 'NULL') . "</p>";
echo "<p><strong>Image URL (DB):</strong> " . ($tour['image_url'] ?: 'NULL') . "</p>";

// Test different path formats
echo "<h2>Image Path Testing</h2>";

$cover_image = $tour['cover_image'];
if ($cover_image) {
    echo "<h3>Cover Image: $cover_image</h3>";
    
    // Test relative path
    $relative_path = "../../../" . $cover_image;
    echo "<p><strong>Relative Path:</strong> $relative_path</p>";
    echo "<p><strong>File Exists (relative):</strong> " . (file_exists($relative_path) ? 'YES' : 'NO') . "</p>";
    
    // Test absolute path
    $absolute_path = "/foreveryoungtours/" . $cover_image;
    echo "<p><strong>Absolute Path:</strong> $absolute_path</p>";
    
    // Test direct file
    echo "<p><strong>Direct File Exists:</strong> " . (file_exists($cover_image) ? 'YES' : 'NO') . "</p>";
    
    echo "<h3>Image Display Tests</h3>";
    
    echo "<h4>1. Relative Path Image</h4>";
    echo "<img src='$relative_path' alt='Relative Path' style='max-width: 200px; border: 2px solid blue;' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: Relative Path';\">";
    
    echo "<h4>2. Absolute Path Image</h4>";
    echo "<img src='$absolute_path' alt='Absolute Path' style='max-width: 200px; border: 2px solid green;' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: Absolute Path';\">";
    
    echo "<h4>3. Direct Path Image</h4>";
    echo "<img src='$cover_image' alt='Direct Path' style='max-width: 200px; border: 2px solid orange;' onerror=\"this.style.border='2px solid red'; this.alt='FAILED: Direct Path';\">";
}

echo "<h2>Expected Results</h2>";
echo "<ul>";
if ($is_subdomain) {
    echo "<li>✅ <strong>Relative path</strong> should work (blue border)</li>";
    echo "<li>❌ <strong>Absolute path</strong> might fail (red border)</li>";
} else {
    echo "<li>❌ <strong>Relative path</strong> might fail (red border)</li>";
    echo "<li>✅ <strong>Absolute path</strong> should work (green border)</li>";
}
echo "<li>❓ <strong>Direct path</strong> depends on context (orange border)</li>";
echo "</ul>";

echo "<h2>Test URLs</h2>";
echo "<ul>";
echo "<li><strong>Main Domain:</strong> <a href='http://localhost/foreveryoungtours/countries/rwanda/pages/simple-image-test.php'>http://localhost/foreveryoungtours/countries/rwanda/pages/simple-image-test.php</a></li>";
echo "<li><strong>Subdomain:</strong> <a href='http://visit-rw.foreveryoungtours.local/pages/simple-image-test'>http://visit-rw.foreveryoungtours.local/pages/simple-image-test</a></li>";
echo "</ul>";
?>
