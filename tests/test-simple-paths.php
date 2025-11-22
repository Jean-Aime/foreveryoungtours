<?php
echo "<h1>🔧 Simple Path Test</h1>";
echo "<hr>";

echo "<h2>📊 Environment</h2>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";

function getImagePath($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?: 'uploads/tours/default-tour.jpg';
    }
    
    $imagePath = trim($imagePath);
    
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
    // Remove any leading slashes or relative path prefixes
    $cleanPath = ltrim($imagePath, './');
    $cleanPath = preg_replace('/^\.\.\/+/', '', $cleanPath);
    
    // Always return the clean path
    return $cleanPath;
}

echo "<h2>🖼️ Image Path Tests</h2>";

$test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'uploads/tours/28_main_1763207330_6197.png',
    'uploads/tours/28_gallery_0_1763207330_1676.png'
];

foreach ($test_images as $index => $image) {
    $processed = getImagePath($image);
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<p><strong>Image " . ($index + 1) . ":</strong></p>";
    echo "<p><strong>Raw:</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>Processed:</strong> " . htmlspecialchars($processed) . "</p>";
    echo "<p><strong>File Exists:</strong> " . (file_exists($processed) ? '✅ YES' : '❌ NO') . "</p>";
    
    echo "<img src='" . htmlspecialchars($processed) . "' style='max-width: 300px; height: auto; border: 1px solid #ddd;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
    echo "<div style='display: none; background: #f0f0f0; padding: 20px; text-align: center; color: red;'>❌ Image not found</div>";
    echo "</div>";
}

echo "<h2>🔗 Test Links</h2>";
echo "<p><a href='http://localhost/foreveryoungtours/test-simple-paths.php' target='_blank'>Main Domain</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/test-simple-paths.php' target='_blank'>Subdomain</a></p>";
?>
