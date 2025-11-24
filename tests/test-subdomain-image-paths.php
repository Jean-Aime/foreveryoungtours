<?php
echo "<h1>🔧 Subdomain Image Path Test</h1>";
echo "<hr>";

// Simulate subdomain detection
function testImagePath($imagePath, $host) {
    if (empty($imagePath)) {
        return getDefaultFallback($host);
    }
    
    $imagePath = trim($imagePath);
    
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
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
            return '../../../' . $imagePath;
        }
        if (strpos($imagePath, 'assets/') === 0) {
            return '../../../' . $imagePath;
        }
        if (strpos($imagePath, '../') === 0) {
            return $imagePath;
        }
        if (strpos($imagePath, '/') === 0) {
            return '../../../' . ltrim($imagePath, '/');
        }
        return '../../../' . $imagePath;
    }
}

function getDefaultFallback($host) {
    $isSubdomain = preg_match('/^visit-([a-z]{2,3})\./', $host);
    
    if ($isSubdomain) {
        return '/foreveryoungtours/assets/images/default-tour.jpg';
    } else {
        return '../../../assets/images/default-tour.jpg';
    }
}

echo "<h2>📊 Current Environment</h2>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
echo "<p><strong>Is Subdomain:</strong> " . (preg_match('/^visit-([a-z]{2,3})\./', $_SERVER['HTTP_HOST'] ?? '') ? '✅ YES' : '❌ NO') . "</p>";

echo "<h2>🧪 Image Path Tests</h2>";

$test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'uploads/tours/28_main_1763207330_6197.png',
    'uploads/tours/28_gallery_0_1763207330_1676.png',
    'assets/images/default-tour.jpg',
    'https://images.unsplash.com/photo-1547036967-23d11aacaee0'
];

$test_hosts = [
    'localhost',
    'visit-rw.foreveryoungtours.local'
];

foreach ($test_hosts as $host) {
    echo "<h3>🌐 Host: " . $host . "</h3>";
    echo "<table border='1' cellpadding='5' style='width: 100%; margin-bottom: 20px;'>";
    echo "<tr><th>Input Image</th><th>Processed Path</th><th>File Exists</th></tr>";
    
    foreach ($test_images as $image) {
        $processed = testImagePath($image, $host);
        
        // Check if file exists (only for local paths)
        $exists = '';
        if (!str_starts_with($processed, 'http') && !str_starts_with($processed, '/foreveryoungtours/')) {
            $exists = file_exists($processed) ? '✅ YES' : '❌ NO';
        } elseif (str_starts_with($processed, '/foreveryoungtours/')) {
            // For absolute paths, check relative to document root
            $relative_path = ltrim($processed, '/foreveryoungtours/');
            $exists = file_exists($relative_path) ? '✅ YES' : '❌ NO';
        } else {
            $exists = '🌐 External';
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($image) . "</td>";
        echo "<td>" . htmlspecialchars($processed) . "</td>";
        echo "<td>" . $exists . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>🖼️ Visual Test</h2>";
echo "<p>Testing actual image display with current host:</p>";

$current_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
foreach (array_slice($test_images, 0, 3) as $index => $image) {
    $processed = testImagePath($image, $current_host);
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<p><strong>Image " . ($index + 1) . ":</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>Processed:</strong> " . htmlspecialchars($processed) . "</p>";
    echo "<img src='" . htmlspecialchars($processed) . "' style='max-width: 200px; height: auto; border: 1px solid #ddd;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
    echo "<div style='display: none; background: #f0f0f0; padding: 20px; text-align: center;'>❌ Image not found</div>";
    echo "</div>";
}

echo "<h2>🔗 Test Links</h2>";
echo "<p><a href='http://localhost/foreveryoungtours/test-subdomain-image-paths.php' target='_blank'>Main Domain Test</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/test-subdomain-image-paths.php' target='_blank'>Subdomain Test</a></p>";
echo "<p><a href='http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28' target='_blank'>Direct Rwanda Tour Detail</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28' target='_blank'>Subdomain Rwanda Tour Detail</a></p>";
?>
