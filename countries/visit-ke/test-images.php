<?php

require_once 'config.php';
echo "<h1>🇷🇼 Rwanda Image Test</h1>";
echo "<hr>";

echo "<h2>📊 Environment Info</h2>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";

// Test image path function
function getImageUrl($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?: getDefaultFallback();
    }
    
    $imagePath = trim($imagePath);
    
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isSubdomain = preg_match('/^visit-([a-z]{2,3})\./', $host);
    
    if ($isSubdomain) {
        $basePath = '/foreveryoungtours/';
        
        if (strpos($imagePath, 'uploads/') === 0) {
            return $basePath . $imagePath;
        }
        if (strpos($imagePath, 'assets/') === 0) {
            return $basePath . $imagePath;
        }
        if (strpos($imagePath, '/') === 0) {
            return $basePath . ltrim($imagePath, '/');
        }
        return $basePath . $imagePath;
    } else {
        if (strpos($imagePath, 'uploads/') === 0) {
            return '../../' . $imagePath;
        }
        if (strpos($imagePath, 'assets/') === 0) {
            return '../../' . $imagePath;
        }
        if (strpos($imagePath, '../') === 0) {
            return $imagePath;
        }
        if (strpos($imagePath, '/') === 0) {
            return '../../' . ltrim($imagePath, '/');
        }
        return '../../' . $imagePath;
    }
}

function getDefaultFallback() {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isSubdomain = preg_match('/^visit-([a-z]{2,3})\./', $host);
    
    if ($isSubdomain) {
        return '/foreveryoungtours/assets/images/default-tour.jpg';
    } else {
        return '../../assets/images/default-tour.jpg';
    }
}

echo "<h2>🖼️ Image Path Tests</h2>";

$test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'uploads/tours/28_main_1763207330_6197.png',
    'uploads/tours/28_gallery_0_1763207330_1676.png'
];

foreach ($test_images as $index => $image) {
    $processed = getImageUrl($image);
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<p><strong>Image " . ($index + 1) . ":</strong></p>";
    echo "<p><strong>Raw:</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>Processed:</strong> " . htmlspecialchars($processed) . "</p>";
    
    // Try to display the image
    echo "<img src='" . htmlspecialchars($processed) . "' style='max-width: 300px; height: auto; border: 1px solid #ddd;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
    echo "<div style='display: none; background: #f0f0f0; padding: 20px; text-align: center; color: red;'>❌ Image not found: " . htmlspecialchars($processed) . "</div>";
    echo "</div>";
}

echo "<h2>🔗 Test Links</h2>";
echo "<p><a href='http://localhost/foreveryoungtours/countries/rwanda/test-images.php' target='_blank'>Direct Access</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/test-images.php' target='_blank'>Subdomain Access</a></p>";
echo "<p><a href='http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28' target='_blank'>Direct Tour Detail</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28' target='_blank'>Subdomain Tour Detail</a></p>";
?>
