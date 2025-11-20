<?php
require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT id, name, cover_image, image_url FROM tours WHERE id IN (28,29,30,31) ORDER BY id");
$stmt->execute();
$tours = $stmt->fetchAll();

echo "<h2>Tour Images Debug</h2>";
foreach ($tours as $tour) {
    echo "<h3>Tour ID: {$tour['id']} - {$tour['name']}</h3>";
    echo "<p>cover_image: " . ($tour['cover_image'] ?: 'NULL') . "</p>";
    echo "<p>image_url: " . ($tour['image_url'] ?: 'NULL') . "</p>";
    echo "<hr>";
}
?>