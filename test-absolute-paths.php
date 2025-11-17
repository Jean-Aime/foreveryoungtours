<?php
echo "<h1>Test Absolute Paths for Subdomain Images</h1>";
echo "<pre>";

// Test the new absolute path function
function fixImagePath($imagePath) {
    if (empty($imagePath)) {
        return '/foreveryoungtours/assets/images/default-tour.jpg';
    }

    // If it's an upload path, use absolute path from web root
    if (strpos($imagePath, 'uploads/') === 0) {
        return '/foreveryoungtours/' . $imagePath;
    }

    // If it's already a relative path starting with ../
    if (strpos($imagePath, '../') === 0) {
        // Check if it's the wrong depth (../../ instead of ../../../)
        if (strpos($imagePath, '../../assets/') === 0) {
            return '/foreveryoungtours/assets/' . substr($imagePath, strlen('../../assets/'));
        }
        // Convert any relative path to absolute
        $cleanPath = str_replace(['../../../', '../../', '../'], '', $imagePath);
        return '/foreveryoungtours/' . $cleanPath;
    }

    // If it's an assets path
    if (strpos($imagePath, 'assets/') === 0) {
        return '/foreveryoungtours/' . $imagePath;
    }

    // If it's an external URL, return as-is
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }

    // Default case - assume it needs the full absolute path
    return '/foreveryoungtours/' . $imagePath;
}

echo "=== TESTING ABSOLUTE PATH CONVERSION ===\n\n";

// Test cases
$test_cases = [
    'uploads/tours/29_cover_1763240404_7030.png',
    'uploads/tours/29_main_1763240404_5958.jpeg',
    '../../assets/images/africa.png',
    '../../../assets/images/africa.png',
    'assets/images/default-tour.jpg',
    'https://example.com/image.jpg',
    '',
    null
];

foreach ($test_cases as $test_path) {
    echo "Input: " . ($test_path ?: 'NULL') . "\n";
    $result = fixImagePath($test_path);
    echo "Output: $result\n";
    
    // Test if the absolute path would work
    if (strpos($result, '/foreveryoungtours/') === 0) {
        $file_path = substr($result, strlen('/foreveryoungtours/'));
        $exists = file_exists($file_path);
        echo "File exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
        if ($exists) {
            echo "File size: " . filesize($file_path) . " bytes\n";
        }
    } else {
        echo "External URL or special case\n";
    }
    echo "\n";
}

echo "=== TOUR 29 SPECIFIC TEST ===\n";
require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT name, image_url, cover_image FROM tours WHERE id = 29");
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "Tour: " . $tour['name'] . "\n\n";
    
    echo "Cover Image:\n";
    echo "Database: " . ($tour['cover_image'] ?: 'NULL') . "\n";
    $cover_fixed = fixImagePath($tour['cover_image']);
    echo "Absolute Path: $cover_fixed\n";
    $cover_file = substr($cover_fixed, strlen('/foreveryoungtours/'));
    echo "File Exists: " . (file_exists($cover_file) ? "✅ YES" : "❌ NO") . "\n\n";
    
    echo "Main Image:\n";
    echo "Database: " . ($tour['image_url'] ?: 'NULL') . "\n";
    $image_fixed = fixImagePath($tour['image_url']);
    echo "Absolute Path: $image_fixed\n";
    $image_file = substr($image_fixed, strlen('/foreveryoungtours/'));
    echo "File Exists: " . (file_exists($image_file) ? "✅ YES" : "❌ NO") . "\n\n";
    
    echo "Background Image (cover or main):\n";
    $bg_image = fixImagePath($tour['cover_image'] ?: $tour['image_url']);
    echo "Background: $bg_image\n";
    $bg_file = substr($bg_image, strlen('/foreveryoungtours/'));
    echo "File Exists: " . (file_exists($bg_file) ? "✅ YES" : "❌ NO") . "\n";
}

echo "</pre>";

echo "<h2>✅ EXPECTED RESULTS</h2>";
echo "<ul>";
echo "<li>✅ All paths should start with /foreveryoungtours/</li>";
echo "<li>✅ uploads/tours/29_cover_1763240404_7030.png → /foreveryoungtours/uploads/tours/29_cover_1763240404_7030.png</li>";
echo "<li>✅ uploads/tours/29_main_1763240404_5958.jpeg → /foreveryoungtours/uploads/tours/29_main_1763240404_5958.jpeg</li>";
echo "<li>✅ Both images should exist and show file sizes</li>";
echo "<li>✅ Background image should use the cover image (exists)</li>";
echo "</ul>";

echo "<h2>🎯 NEXT STEP</h2>";
echo "<p><strong>Test the subdomain URL:</strong></p>";
echo "<p><code>http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29</code></p>";
echo "<p>Images should now display correctly because they use absolute paths!</p>";
?>
