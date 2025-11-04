<?php
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get all tours with their image data
$stmt = $conn->prepare("SELECT id, name, image_url, cover_image, images, gallery FROM tours ORDER BY id");
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Tour Images Debug</h1>";
foreach ($tours as $tour) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<h3>Tour ID: {$tour['id']} - {$tour['name']}</h3>";
    echo "<p><strong>image_url:</strong> " . ($tour['image_url'] ?: 'NULL') . "</p>";
    echo "<p><strong>cover_image:</strong> " . ($tour['cover_image'] ?: 'NULL') . "</p>";
    echo "<p><strong>images:</strong> " . ($tour['images'] ?: 'NULL') . "</p>";
    echo "<p><strong>gallery:</strong> " . ($tour['gallery'] ?: 'NULL') . "</p>";
    
    // Show actual images if they exist
    $all_images = [];
    
    if (!empty($tour['images']) && $tour['images'] !== '[]') {
        $images_data = json_decode($tour['images'], true);
        if (is_array($images_data)) {
            $all_images = array_merge($all_images, $images_data);
        }
    }
    
    if (!empty($tour['gallery']) && $tour['gallery'] !== '[]') {
        $gallery_data = json_decode($tour['gallery'], true);
        if (is_array($gallery_data)) {
            $all_images = array_merge($all_images, $gallery_data);
        }
    }
    
    if (!empty($tour['image_url'])) {
        $all_images[] = $tour['image_url'];
    }
    
    if (!empty($tour['cover_image'])) {
        $all_images[] = $tour['cover_image'];
    }
    
    $all_images = array_unique(array_filter($all_images));
    
    if (!empty($all_images)) {
        echo "<div style='display: flex; gap: 10px; margin-top: 10px;'>";
        foreach ($all_images as $img) {
            echo "<img src='{$img}' style='width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;' onerror='this.style.border=\"2px solid red\"; this.alt=\"BROKEN: {$img}\"'>";
        }
        echo "</div>";
    } else {
        echo "<p style='color: red;'>No images found!</p>";
    }
    
    echo "</div>";
}
?>