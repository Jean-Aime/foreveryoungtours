<?php
require_once '../config/database.php';

$stmt = $pdo->query("SELECT id, name, slug FROM tours ORDER BY id");
$tours = $stmt->fetchAll();

echo "<h2>Tour Slugs</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Tour Name</th><th>Slug</th><th>URL</th></tr>";

foreach ($tours as $tour) {
    $url = "http://localhost/ForeverYoungTours/pages/tour-detail.php?slug=" . urlencode($tour['slug']);
    echo "<tr>";
    echo "<td>{$tour['id']}</td>";
    echo "<td>{$tour['name']}</td>";
    echo "<td>{$tour['slug']}</td>";
    echo "<td><a href='{$url}' target='_blank'>View</a></td>";
    echo "</tr>";
}

echo "</table>";
?>
