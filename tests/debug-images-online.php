<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h1>Image Path Debug for Online Server</h1>";
echo "<p><strong>Current BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";

// Test image paths
$test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'assets/images/default-tour.jpg',
    'assets/images/africa.png',
    'assets/images/logo.png'
];

echo "<h2>Image Path Tests:</h2>";
foreach ($test_images as $image) {
    $full_url = getImageUrl($image);
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<p><strong>Input:</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>Output URL:</strong> " . htmlspecialchars($full_url) . "</p>";
    echo "<p><strong>Test Image:</strong></p>";
    echo "<img src='" . htmlspecialchars($full_url) . "' alt='Test' style='max-width: 200px; height: auto;' onerror='this.style.border=\"2px solid red\"; this.alt=\"FAILED TO LOAD\";'>";
    echo "</div>";
}

// Test database images
echo "<h2>Database Image Tests:</h2>";
try {
    $stmt = $pdo->prepare("SELECT name, image_url, cover_image FROM tours WHERE image_url IS NOT NULL OR cover_image IS NOT NULL LIMIT 5");
    $stmt->execute();
    $tours = $stmt->fetchAll();
    
    foreach ($tours as $tour) {
        $image_path = $tour['image_url'] ?: $tour['cover_image'];
        $full_url = getImageUrl($image_path);
        
        echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
        echo "<p><strong>Tour:</strong> " . htmlspecialchars($tour['name']) . "</p>";
        echo "<p><strong>DB Path:</strong> " . htmlspecialchars($image_path) . "</p>";
        echo "<p><strong>Full URL:</strong> " . htmlspecialchars($full_url) . "</p>";
        echo "<img src='" . htmlspecialchars($full_url) . "' alt='Tour Image' style='max-width: 200px; height: auto;' onerror='this.style.border=\"2px solid red\"; this.alt=\"FAILED TO LOAD\";'>";
        echo "</div>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check if files exist
echo "<h2>File Existence Check:</h2>";
$check_paths = [
    __DIR__ . '/assets/images/default-tour.jpg',
    __DIR__ . '/assets/images/africa.png',
    __DIR__ . '/uploads/tours/',
];

foreach ($check_paths as $path) {
    $exists = file_exists($path);
    $is_dir = is_dir($path);
    echo "<p><strong>" . htmlspecialchars($path) . ":</strong> ";
    if ($exists) {
        echo "<span style='color: green;'>EXISTS</span>";
        if ($is_dir) {
            echo " (Directory)";
            $files = scandir($path);
            echo " - Contains " . (count($files) - 2) . " files";
        }
    } else {
        echo "<span style='color: red;'>NOT FOUND</span>";
    }
    echo "</p>";
}
?>