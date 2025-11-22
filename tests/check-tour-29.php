<?php
require_once 'config/database.php';

echo "<h1>Tour ID 29 Check</h1>";
echo "<pre>";

$stmt = $pdo->prepare("SELECT id, name, image_url, cover_image FROM tours WHERE id = 29");
$stmt->execute();
$tour = $stmt->fetch();

if ($tour) {
    echo "✅ Tour 29 found:\n";
    echo "Name: " . $tour['name'] . "\n";
    echo "Image URL: " . ($tour['image_url'] ?: 'NULL') . "\n";
    echo "Cover Image: " . ($tour['cover_image'] ?: 'NULL') . "\n";
} else {
    echo "❌ Tour 29 not found\n";
}

echo "</pre>";
?>
