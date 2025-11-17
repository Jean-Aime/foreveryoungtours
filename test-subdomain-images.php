<?php
echo "<h1>Subdomain Image Path Test</h1>";
echo "<pre>";

// Simulate being in countries/rwanda/pages/ context (3 levels deep)
echo "=== SIMULATING SUBDOMAIN CONTEXT ===\n";
echo "Context: countries/rwanda/pages/tour-detail.php\n";
echo "Relative path depth: 3 levels (../../../)\n\n";

// Get tour data
require_once 'config/database.php';
$tour_id = $_GET['id'] ?? 28;

$stmt = $pdo->prepare("SELECT name, image_url, cover_image FROM tours WHERE id = ?");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch();

if (!$tour) {
    echo "❌ Tour not found\n";
    exit;
}

echo "=== TOUR DATA ===\n";
echo "Name: " . $tour['name'] . "\n";
echo "Image URL: " . ($tour['image_url'] ?: 'NULL') . "\n";
echo "Cover Image: " . ($tour['cover_image'] ?: 'NULL') . "\n\n";

// Test image paths from subdomain context
function fixImagePathSubdomain($imagePath) {
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

// Test from main directory context (simulate subdomain paths)
function testImageFromMainDir($relativePath) {
    // Convert subdomain relative path to main directory path
    $mainPath = str_replace('../../../', '', $relativePath);
    return file_exists($mainPath);
}

echo "=== IMAGE PATH TESTING (SUBDOMAIN CONTEXT) ===\n";

// Test cover image
if ($tour['cover_image']) {
    $cover_path = fixImagePathSubdomain($tour['cover_image']);
    echo "Cover image path: $cover_path\n";
    echo "Cover image exists: " . (testImageFromMainDir($cover_path) ? "✅ YES" : "❌ NO") . "\n";
    
    // Show actual file path for verification
    $actual_path = str_replace('../../../', '', $cover_path);
    echo "Actual file path: $actual_path\n";
    echo "File size: " . (file_exists($actual_path) ? filesize($actual_path) . " bytes" : "N/A") . "\n\n";
}

// Test main image
if ($tour['image_url']) {
    $image_path = fixImagePathSubdomain($tour['image_url']);
    echo "Main image path: $image_path\n";
    echo "Main image exists: " . (testImageFromMainDir($image_path) ? "✅ YES" : "❌ NO") . "\n";
    
    // Show actual file path for verification
    $actual_path = str_replace('../../../', '', $image_path);
    echo "Actual file path: $actual_path\n";
    echo "File size: " . (file_exists($actual_path) ? filesize($actual_path) . " bytes" : "N/A") . "\n\n";
}

echo "=== URL GENERATION TEST ===\n";
$bg_image = fixImagePathSubdomain($tour['cover_image'] ?: $tour['image_url']);
echo "Background image for CSS: $bg_image\n";
echo "Background image exists: " . (testImageFromMainDir($bg_image) ? "✅ YES" : "❌ NO") . "\n\n";

echo "=== DIRECT FILE VERIFICATION ===\n";
echo "Direct check - uploads/tours/28_cover_1763207330_5662.jpeg: " . (file_exists('uploads/tours/28_cover_1763207330_5662.jpeg') ? "✅ EXISTS" : "❌ MISSING") . "\n";
echo "Direct check - assets/images/africa.png: " . (file_exists('assets/images/africa.png') ? "✅ EXISTS" : "❌ MISSING") . "\n";

echo "</pre>";

// Show actual images if they exist
echo "<h2>Image Preview</h2>";

if ($tour['cover_image'] && file_exists($tour['cover_image'])) {
    echo "<p><strong>Cover Image:</strong></p>";
    echo "<img src='" . htmlspecialchars($tour['cover_image']) . "' style='max-width: 300px; height: auto;' alt='Cover Image'>";
}

if ($tour['image_url'] && file_exists(str_replace('../../', '', $tour['image_url']))) {
    echo "<p><strong>Main Image:</strong></p>";
    $main_img_path = str_replace('../../', '', $tour['image_url']);
    echo "<img src='" . htmlspecialchars($main_img_path) . "' style='max-width: 300px; height: auto;' alt='Main Image'>";
}
?>
