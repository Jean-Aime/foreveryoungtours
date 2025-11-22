<?php
echo "<h1>Final Image Fix Test</h1>";
echo "<pre>";

// Test the enhanced fixImagePath function
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
        // Check if it's the wrong depth (../../ instead of ../../../)
        if (strpos($imagePath, '../../assets/') === 0) {
            return '../../../assets/' . substr($imagePath, strlen('../../assets/'));
        }
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

echo "=== TESTING ENHANCED fixImagePath FUNCTION ===\n\n";

// Test cases
$test_cases = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
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
    
    // Test if file exists (convert subdomain path to main path for testing)
    $main_path = str_replace('../../../', '', $result);
    $exists = file_exists($main_path);
    echo "File exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    if ($exists) {
        echo "File size: " . filesize($main_path) . " bytes\n";
    }
    echo "\n";
}

echo "=== TOUR 28 SPECIFIC TEST ===\n";
require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT name, image_url, cover_image FROM tours WHERE id = 28");
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "Tour: " . $tour['name'] . "\n\n";
    
    echo "Cover Image:\n";
    echo "Database: " . ($tour['cover_image'] ?: 'NULL') . "\n";
    $cover_fixed = fixImagePath($tour['cover_image']);
    echo "Fixed: $cover_fixed\n";
    $cover_main = str_replace('../../../', '', $cover_fixed);
    echo "Exists: " . (file_exists($cover_main) ? "✅ YES" : "❌ NO") . "\n\n";
    
    echo "Main Image:\n";
    echo "Database: " . ($tour['image_url'] ?: 'NULL') . "\n";
    $image_fixed = fixImagePath($tour['image_url']);
    echo "Fixed: $image_fixed\n";
    $image_main = str_replace('../../../', '', $image_fixed);
    echo "Exists: " . (file_exists($image_main) ? "✅ YES" : "❌ NO") . "\n\n";
    
    echo "Background Image (cover or main):\n";
    $bg_image = fixImagePath($tour['cover_image'] ?: $tour['image_url']);
    echo "Background: $bg_image\n";
    $bg_main = str_replace('../../../', '', $bg_image);
    echo "Exists: " . (file_exists($bg_main) ? "✅ YES" : "❌ NO") . "\n";
}

echo "</pre>";

echo "<h2>✅ EXPECTED RESULTS</h2>";
echo "<ul>";
echo "<li>✅ uploads/tours/28_cover_1763207330_5662.jpeg → ../../../uploads/tours/28_cover_1763207330_5662.jpeg</li>";
echo "<li>✅ ../../assets/images/africa.png → ../../../assets/images/africa.png (FIXED!)</li>";
echo "<li>✅ Both images should exist and show file sizes</li>";
echo "<li>✅ Background image should use the cover image (exists)</li>";
echo "</ul>";
?>
