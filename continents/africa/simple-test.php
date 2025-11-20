<?php
echo "TEST PAGE LOADED<br>";

require_once __DIR__ . '/../../config/database.php';

echo "Database connected<br>";

$stmt = $pdo->query("SELECT id, name, cover_image FROM tours LIMIT 3");
$tours = $stmt->fetchAll();

echo "Tours found: " . count($tours) . "<br><br>";

foreach ($tours as $tour) {
    echo "Tour: " . $tour['name'] . "<br>";
    echo "Cover: " . $tour['cover_image'] . "<br>";
    
    if ($tour['cover_image']) {
        $img = "http://localhost/ForeverYoungTours/" . $tour['cover_image'];
        echo "<img src='$img' width='200'><br>";
    }
    echo "<hr>";
}
?>
