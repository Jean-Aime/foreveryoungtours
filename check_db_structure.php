<?php
require_once 'config/database.php';

echo "<h1>Tours Table Structure</h1>";
$stmt = $pdo->query("DESCRIBE tours");
$columns = $stmt->fetchAll();

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
foreach ($columns as $col) {
    echo "<tr>";
    echo "<td>" . $col['Field'] . "</td>";
    echo "<td>" . $col['Type'] . "</td>";
    echo "<td>" . $col['Null'] . "</td>";
    echo "<td>" . $col['Key'] . "</td>";
    echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Sample Tour Data</h2>";
$stmt = $pdo->query("SELECT id, name, image_url, cover_image, gallery, images FROM tours LIMIT 1");
$tour = $stmt->fetch();
if ($tour) {
    echo "<pre>";
    print_r($tour);
    echo "</pre>";
}
?>
