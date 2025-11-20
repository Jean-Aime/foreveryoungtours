<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';

$continent_slug = 'africa';
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

echo "<h2>Continent: " . $continent['name'] . "</h2>";

$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active'
    ORDER BY t.featured DESC, t.popularity_score DESC
    LIMIT 6
");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();

echo "<h3>Tours Found: " . count($featured_tours) . "</h3>";

foreach ($featured_tours as $tour) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
    echo "<h4>" . htmlspecialchars($tour['name']) . "</h4>";
    echo "<p>Price: $" . number_format($tour['price']) . "</p>";
    echo "<p>Country: " . htmlspecialchars($tour['country_name']) . "</p>";
    
    $tour_image = $tour['cover_image'] ?: $tour['image_url'];
    if ($tour_image) {
        $image_path = getImageUrl($tour_image);
        echo "<p>Image Path: $image_path</p>";
        echo "<p>Image: <img src='$image_path' width='200' onerror=\"this.src='" . getImageUrl('assets/images/default-tour.jpg') . "'\"></p>";
    } else {
        echo "<p>No image</p>";
    }
    echo "</div>";
}
?>
