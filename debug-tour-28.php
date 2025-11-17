<?php
// Debug script for tour 28 images
require_once 'config/database.php';

echo "<h1>🔧 Tour 28 Image Debug</h1>";
echo "<hr>";

// Check tour 28
$tour_id = 28;
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
    echo "<h2>❌ Tour 28 not found</h2>";
    exit;
}

echo "<h2>✅ Tour 28 Data</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><td><strong>Name</strong></td><td>" . htmlspecialchars($tour['name']) . "</td></tr>";
echo "<tr><td><strong>Country</strong></td><td>" . htmlspecialchars($tour['country_name']) . " (ID: " . $tour['country_id'] . ")</td></tr>";
echo "<tr><td><strong>Country Slug</strong></td><td>" . $tour['country_slug'] . "</td></tr>";
echo "<tr><td><strong>image_url</strong></td><td>" . htmlspecialchars($tour['image_url'] ?: 'NULL') . "</td></tr>";
echo "<tr><td><strong>cover_image</strong></td><td>" . htmlspecialchars($tour['cover_image'] ?: 'NULL') . "</td></tr>";
echo "<tr><td><strong>gallery</strong></td><td>" . htmlspecialchars($tour['gallery'] ?: 'NULL') . "</td></tr>";
echo "<tr><td><strong>images</strong></td><td>" . htmlspecialchars($tour['images'] ?: 'NULL') . "</td></tr>";
echo "</table>";

// Image path function for subdomain context
function getImagePath($imagePath, $fallback = '../../../assets/images/default-tour.jpg') {
    if (empty($imagePath)) {
        return $fallback;
    }
    
    // For uploads directory, always use relative path from country page context
    if (strpos($imagePath, 'uploads/') === 0) {
        return '../../../' . $imagePath;
    }
    
    // For assets directory, use relative path
    if (strpos($imagePath, 'assets/') === 0) {
        return '../../../' . $imagePath;
    }
    
    // If already relative path, use as is
    if (strpos($imagePath, '../') === 0) {
        return $imagePath;
    }
    
    // External URLs unchanged
    if (strpos($imagePath, 'http') === 0) {
        return $imagePath;
    }
    
    // Default: assume it's a relative path from root
    return '../../../' . $imagePath;
}

echo "<h2>🖼️ Image Processing Test</h2>";

// Hero image
$bg_image = getImagePath($tour['cover_image'] ?: $tour['image_url']);
echo "<h3>Hero Background Image</h3>";
echo "<p><strong>Raw:</strong> " . htmlspecialchars($tour['cover_image'] ?: $tour['image_url'] ?: 'NULL') . "</p>";
echo "<p><strong>Processed:</strong> " . htmlspecialchars($bg_image) . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($bg_image) ? '✅ YES' : '❌ NO') . "</p>";

// Gallery images
echo "<h3>Gallery Images</h3>";
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

echo "<p><strong>Total gallery images:</strong> " . count($gallery_images) . "</p>";

foreach ($gallery_images as $index => $image) {
    $processed = getImagePath($image);
    $exists = file_exists($processed) ? '✅ EXISTS' : '❌ NOT FOUND';
    echo "<p><strong>Image " . ($index + 1) . ":</strong></p>";
    echo "<p>&nbsp;&nbsp;Raw: " . htmlspecialchars($image) . "</p>";
    echo "<p>&nbsp;&nbsp;Processed: " . htmlspecialchars($processed) . "</p>";
    echo "<p>&nbsp;&nbsp;File: " . $exists . "</p>";
    
    // Try to display the image
    if (file_exists($processed)) {
        echo "<p>&nbsp;&nbsp;<img src='" . htmlspecialchars($processed) . "' style='max-width: 200px; height: auto;' onerror='this.style.display=\"none\";'></p>";
    }
    echo "<hr>";
}

echo "<h2>🌐 Test Links</h2>";
echo "<p><a href='http://localhost/foreveryoungtours/pages/tour-detail?id=28' target='_blank'>Main Domain Tour Detail</a></p>";
echo "<p><a href='http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28' target='_blank'>Direct Rwanda Page</a></p>";
echo "<p><strong>Subdomain URL (may not work in local environment):</strong> http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28</p>";

echo "<h2>📁 File System Check</h2>";
$paths_to_check = [
    'uploads/tours/',
    'assets/images/',
    'assets/images/default-tour.jpg',
    'countries/rwanda/pages/tour-detail.php'
];

foreach ($paths_to_check as $path) {
    $exists = (is_dir($path) || file_exists($path)) ? '✅ EXISTS' : '❌ NOT FOUND';
    $type = is_dir($path) ? '(Directory)' : (file_exists($path) ? '(File)' : '');
    echo "<p><strong>" . htmlspecialchars($path) . "</strong>: " . $exists . " " . $type . "</p>";
}
?>
