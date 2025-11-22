<?php
require_once 'config/database.php';

echo "<h1>🖼️ Main Tour Detail Images Test</h1>";

// Test the fixImageSrc function
function fixImageSrc($imageSrc) {
    if (empty($imageSrc)) {
        return '../assets/images/default-tour.jpg';
    }
    
    // If it's an upload path, add relative prefix
    if (strpos($imageSrc, 'uploads/') === 0) {
        return '../' . $imageSrc;
    }
    
    // If it already starts with ../, use as is
    if (strpos($imageSrc, '../') === 0) {
        return $imageSrc;
    }
    
    // If it's an assets path, add relative prefix
    if (strpos($imageSrc, 'assets/') === 0) {
        return '../' . $imageSrc;
    }
    
    // If it's external URL, use as is
    if (strpos($imageSrc, 'http') === 0) {
        return $imageSrc;
    }
    
    // Default: assume it needs relative prefix
    return '../' . $imageSrc;
}

// Get tour 29 data
$stmt = $pdo->prepare('SELECT * FROM tours WHERE id = 29');
$stmt->execute();
$tour = $stmt->fetch();

if (!$tour) {
    echo "<p>❌ Tour 29 not found</p>";
    exit;
}

echo "<h2>📊 Tour Data</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Tour Name</strong></td><td>" . htmlspecialchars($tour['name']) . "</td></tr>";
echo "<tr><td><strong>Cover Image (DB)</strong></td><td>" . ($tour['cover_image'] ?: 'NULL') . "</td></tr>";
echo "<tr><td><strong>Image URL (DB)</strong></td><td>" . ($tour['image_url'] ?: 'NULL') . "</td></tr>";
echo "</table>";

echo "<h2>🎯 Image Path Testing</h2>";

// Test background image
$bg_image = $tour['cover_image'] ?: $tour['image_url'] ?: '../assets/images/default-tour.jpg';
$bg_image_fixed = fixImageSrc($bg_image);
echo "<h3>1. Background Image</h3>";
echo "<p><strong>Original:</strong> " . ($tour['cover_image'] ?: $tour['image_url'] ?: 'NULL') . "</p>";
echo "<p><strong>Processed:</strong> " . $bg_image_fixed . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($bg_image_fixed) ? '✅ YES' : '❌ NO') . "</p>";

// Test gallery images
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

echo "<h3>2. Gallery Images (" . count($gallery_images) . " images)</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Original</th><th>Processed</th><th>Exists</th><th>Preview</th></tr>";

foreach ($gallery_images as $index => $image) {
    $processed = fixImageSrc($image);
    $exists = file_exists($processed);
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($image) . "</td>";
    echo "<td>" . htmlspecialchars($processed) . "</td>";
    echo "<td>" . ($exists ? '✅ YES' : '❌ NO') . "</td>";
    echo "<td>";
    if ($exists) {
        echo "<img src='" . htmlspecialchars($processed) . "' style='width: 50px; height: 30px; object-fit: cover;' onerror=\"this.src='../assets/images/default-tour.jpg'\">";
    } else {
        echo "❌ No preview";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>🧪 Visual Test</h2>";
echo "<p>Testing the actual image display as it would appear on the main tour detail page:</p>";

echo "<h3>Background Image Test</h3>";
echo "<div style='width: 300px; height: 150px; background-image: url(\"" . htmlspecialchars($bg_image_fixed) . "\"); background-size: cover; background-position: center; border: 2px solid #ccc; margin: 10px 0;'>";
echo "<div style='background: rgba(0,0,0,0.5); color: white; padding: 10px; height: 100%; display: flex; align-items: center; justify-content: center;'>";
echo "Background Image Test";
echo "</div>";
echo "</div>";

echo "<h3>Gallery Images Test</h3>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin: 10px 0;'>";
foreach (array_slice($gallery_images, 0, 6) as $index => $image) {
    $processed = fixImageSrc($image);
    echo "<div style='border: 2px solid #ccc;'>";
    echo "<img src='" . htmlspecialchars($processed) . "' style='width: 100%; height: 80px; object-fit: cover;' onerror=\"this.src='../assets/images/default-tour.jpg'; this.style.border='2px solid red';\">";
    echo "</div>";
}
echo "</div>";

echo "<h2>🎯 Test URLs</h2>";
echo "<ul>";
echo "<li><strong>Main Tour Detail:</strong> <a href='http://localhost/foreveryoungtours/pages/tour-detail?id=29'>http://localhost/foreveryoungtours/pages/tour-detail?id=29</a></li>";
echo "</ul>";

echo "<h2>✅ Expected Results</h2>";
echo "<ul>";
echo "<li>✅ All images should have valid processed paths with '../' prefix</li>";
echo "<li>✅ All processed paths should exist as files</li>";
echo "<li>✅ Visual test should show images correctly</li>";
echo "<li>✅ Main tour detail page should display all images</li>";
echo "</ul>";
?>
