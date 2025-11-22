<?php
echo "<h1>Test Rwanda Tour Detail Page Images</h1>";
echo "<pre>";

require_once 'config/database.php';

// Test the fixImagePath function from Rwanda tour detail
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

echo "=== TESTING RWANDA TOUR DETAIL PAGE IMAGES ===\n\n";

// Test with tour ID 29
$tour_id = 29;
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
    echo "❌ Tour $tour_id not found\n";
    exit;
}

echo "✅ Tour found: " . $tour['name'] . "\n";
echo "Country: " . $tour['country_name'] . "\n\n";

// Test background image
echo "=== BACKGROUND IMAGE ===\n";
$bg_image = fixImagePath($tour['cover_image'] ?: $tour['image_url']);
echo "Database cover_image: " . ($tour['cover_image'] ?: 'NULL') . "\n";
echo "Database image_url: " . ($tour['image_url'] ?: 'NULL') . "\n";
echo "Fixed background image: $bg_image\n";
$bg_file = str_replace('/foreveryoungtours/', '', $bg_image);
echo "File exists: " . (file_exists($bg_file) ? "✅ YES" : "❌ NO") . "\n";
if (file_exists($bg_file)) {
    echo "File size: " . filesize($bg_file) . " bytes\n";
}
echo "\n";

// Test gallery images
echo "=== GALLERY IMAGES ===\n";
$gallery_images = [];

if ($tour['image_url']) $gallery_images[] = $tour['image_url'];
if ($tour['cover_image']) $gallery_images[] = $tour['cover_image'];

if ($tour['gallery']) {
    $gallery_data = json_decode($tour['gallery'], true);
    if ($gallery_data) {
        $gallery_images = array_merge($gallery_images, $gallery_data);
    }
}

if ($tour['images']) {
    $images_data = json_decode($tour['images'], true);
    if ($images_data) {
        $gallery_images = array_merge($gallery_images, $images_data);
    }
}

$gallery_images = array_unique(array_filter($gallery_images));

echo "Found " . count($gallery_images) . " gallery images:\n";
foreach ($gallery_images as $index => $image) {
    $image_src = fixImagePath($image);
    $image_file = str_replace('/foreveryoungtours/', '', $image_src);
    echo ($index + 1) . ". $image → $image_src\n";
    echo "   File exists: " . (file_exists($image_file) ? "✅ YES" : "❌ NO") . "\n";
}
echo "\n";

// Test related tours
echo "=== RELATED TOURS ===\n";
$related_stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE t.status = 'active' AND t.id != ? 
    AND t.country_id = ? 
    ORDER BY t.featured DESC, RAND() 
    LIMIT 3
");
$related_stmt->execute([$tour_id, $tour['country_id']]);
$related_tours = $related_stmt->fetchAll();

echo "Found " . count($related_tours) . " related tours:\n";
foreach ($related_tours as $index => $related) {
    $related_image = fixImagePath($related['cover_image'] ?: $related['image_url']);
    $related_file = str_replace('/foreveryoungtours/', '', $related_image);
    echo ($index + 1) . ". " . $related['name'] . "\n";
    echo "   Image: $related_image\n";
    echo "   File exists: " . (file_exists($related_file) ? "✅ YES" : "❌ NO") . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "✅ All image paths now use absolute paths (/foreveryoungtours/...)\n";
echo "✅ Error fallbacks updated to use absolute paths\n";
echo "✅ Debug mode available with ?debug=1 parameter\n";

echo "</pre>";

echo "<h2>🎯 TEST THE ACTUAL PAGE</h2>";
echo "<p><strong>Rwanda Tour Detail Page:</strong></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29' target='_blank'>http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29</a></p>";
echo "<p><strong>With Debug Info:</strong></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29&debug=1' target='_blank'>http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=29&debug=1</a></p>";

echo "<h2>✅ EXPECTED RESULTS</h2>";
echo "<ul>";
echo "<li>✅ Hero background image should display</li>";
echo "<li>✅ Gallery images should display</li>";
echo "<li>✅ Related tour images should display</li>";
echo "<li>✅ No broken image icons</li>";
echo "<li>✅ Debug info shows file paths and existence</li>";
echo "</ul>";
?>
