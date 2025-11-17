<?php
echo "<h1>Test Environment Detection for Image Paths</h1>";
echo "<pre>";

// Replicate the fixImagePath function from Rwanda tour detail
function fixImagePath($imagePath) {
    if (empty($imagePath)) {
        // Detect if we're on a subdomain
        $is_subdomain = strpos($_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos($_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false;
        if ($is_subdomain) {
            return '../../../assets/images/default-tour.jpg';
        } else {
            return '/foreveryoungtours/assets/images/default-tour.jpg';
        }
    }

    // Detect if we're on a subdomain
    $is_subdomain = strpos($_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos($_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false;
    
    if ($is_subdomain) {
        // On subdomain, use relative paths from the country page context
        if (strpos($imagePath, 'uploads/') === 0) {
            return '../../../' . $imagePath;
        }
        
        if (strpos($imagePath, '../') === 0) {
            // Already relative, but ensure correct depth
            if (strpos($imagePath, '../../assets/') === 0) {
                return '../../../assets/' . substr($imagePath, strlen('../../assets/'));
            }
            return $imagePath;
        }
        
        if (strpos($imagePath, 'assets/') === 0) {
            return '../../../' . $imagePath;
        }
        
        // External URLs unchanged
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // Default case for subdomain
        return '../../../' . $imagePath;
    } else {
        // On main domain, use absolute paths
        if (strpos($imagePath, 'uploads/') === 0) {
            return '/foreveryoungtours/' . $imagePath;
        }
        
        if (strpos($imagePath, '../') === 0) {
            $cleanPath = str_replace(['../../../', '../../', '../'], '', $imagePath);
            return '/foreveryoungtours/' . $cleanPath;
        }
        
        if (strpos($imagePath, 'assets/') === 0) {
            return '/foreveryoungtours/' . $imagePath;
        }
        
        // External URLs unchanged
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // Default case for main domain
        return '/foreveryoungtours/' . $imagePath;
    }
}

echo "=== ENVIRONMENT DETECTION ===\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
$is_subdomain = strpos($_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos($_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false;
echo "Is subdomain: " . ($is_subdomain ? "YES" : "NO") . "\n";
echo "Environment: " . ($is_subdomain ? "SUBDOMAIN (use relative paths)" : "MAIN DOMAIN (use absolute paths)") . "\n\n";

echo "=== IMAGE PATH TESTING ===\n";
$test_images = [
    'uploads/tours/29_cover_1763240404_7030.png',
    'uploads/tours/29_main_1763240404_5958.jpeg',
    '../../assets/images/africa.png',
    'assets/images/default-tour.jpg',
    '',
    null
];

foreach ($test_images as $test_path) {
    echo "Input: " . ($test_path ?: 'NULL') . "\n";
    $result = fixImagePath($test_path);
    echo "Output: $result\n";
    
    // Test if the path exists
    if ($is_subdomain && strpos($result, '../') === 0) {
        // For subdomain relative paths, check from current context
        $exists = file_exists($result);
        echo "File exists (relative): " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    } elseif (!$is_subdomain && strpos($result, '/foreveryoungtours/') === 0) {
        // For main domain absolute paths, check by removing prefix
        $file_path = substr($result, strlen('/foreveryoungtours/'));
        $exists = file_exists($file_path);
        echo "File exists (absolute): " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    } else {
        echo "External URL or special case\n";
    }
    echo "\n";
}

echo "=== ONERROR FALLBACK TEST ===\n";
$fallback_path = ($is_subdomain) ? '../../../assets/images/default-tour.jpg' : '/foreveryoungtours/assets/images/default-tour.jpg';
echo "Fallback path: $fallback_path\n";
if ($is_subdomain) {
    echo "Fallback exists: " . (file_exists($fallback_path) ? "✅ YES" : "❌ NO") . "\n";
} else {
    $fallback_file = substr($fallback_path, strlen('/foreveryoungtours/'));
    echo "Fallback exists: " . (file_exists($fallback_file) ? "✅ YES" : "❌ NO") . "\n";
}

echo "</pre>";

echo "<h2>🎯 EXPECTED BEHAVIOR</h2>";
echo "<ul>";
if ($is_subdomain) {
    echo "<li>✅ <strong>Subdomain detected:</strong> Using relative paths (../../../)</li>";
    echo "<li>✅ Images should resolve relative to the country page location</li>";
    echo "<li>✅ uploads/tours/image.jpg → ../../../uploads/tours/image.jpg</li>";
} else {
    echo "<li>✅ <strong>Main domain detected:</strong> Using absolute paths (/foreveryoungtours/)</li>";
    echo "<li>✅ Images should resolve from web root</li>";
    echo "<li>✅ uploads/tours/image.jpg → /foreveryoungtours/uploads/tours/image.jpg</li>";
}
echo "</ul>";

echo "<h2>🧪 TEST URLS</h2>";
echo "<ul>";
echo "<li><strong>Main Domain:</strong> <a href='http://localhost/foreveryoungtours/test-environment-detection.php' target='_blank'>http://localhost/foreveryoungtours/test-environment-detection.php</a></li>";
echo "<li><strong>Subdomain:</strong> <a href='http://visit-rw.foreveryoungtours.local/test-environment-detection.php' target='_blank'>http://visit-rw.foreveryoungtours.local/test-environment-detection.php</a></li>";
echo "</ul>";
?>
