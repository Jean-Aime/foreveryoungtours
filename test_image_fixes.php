<?php
require_once 'config.php';
require_once 'config/database.php';

echo "<h1>Tour Image Fix Verification</h1>";

// Test 1: Check if uploads directory exists
echo "<h2>1. Upload Directory Check</h2>";
$upload_dir = 'uploads/tours/';
if (is_dir($upload_dir)) {
    echo "<p style='color: green;'>✓ Upload directory exists: " . realpath($upload_dir) . "</p>";
    $files = glob($upload_dir . '*');
    echo "<p>Files in directory: " . count($files) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Upload directory does not exist</p>";
}

// Test 2: Check database tours with images
echo "<h2>2. Database Tours with Images</h2>";
$stmt = $pdo->query("SELECT id, name, image_url, cover_image FROM tours WHERE image_url IS NOT NULL OR cover_image IS NOT NULL LIMIT 5");
$tours = $stmt->fetchAll();

if (empty($tours)) {
    echo "<p style='color: orange;'>No tours with images found in database</p>";
} else {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Tour ID</th><th>Name</th><th>Image URL</th><th>Cover Image</th><th>Resolved URL</th></tr>";
    foreach ($tours as $tour) {
        $resolved = getImageUrl($tour['image_url'] ?: $tour['cover_image']);
        echo "<tr>";
        echo "<td>" . $tour['id'] . "</td>";
        echo "<td>" . htmlspecialchars($tour['name']) . "</td>";
        echo "<td>" . htmlspecialchars($tour['image_url'] ?: 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($tour['cover_image'] ?: 'NULL') . "</td>";
        echo "<td><a href='" . htmlspecialchars($resolved) . "' target='_blank'>" . htmlspecialchars($resolved) . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 3: Test getImageUrl function
echo "<h2>3. Image URL Resolution Tests</h2>";
$test_paths = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    '../uploads/tours/image.jpg',
    'assets/images/default-tour.jpg',
    '',
    'http://example.com/image.jpg'
];

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Input Path</th><th>Resolved URL</th></tr>";
foreach ($test_paths as $path) {
    $resolved = getImageUrl($path);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($path ?: '(empty)') . "</td>";
    echo "<td>" . htmlspecialchars($resolved) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 4: Check BASE_URL
echo "<h2>4. Configuration Check</h2>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";

echo "<h2>Summary</h2>";
echo "<p>If all tests pass, tour images should display correctly. If not, check the error messages above.</p>";
?>
