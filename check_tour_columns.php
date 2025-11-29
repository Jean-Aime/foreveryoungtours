<?php
require_once 'config/database.php';

echo "<h1>Tours Table Structure</h1>";

$stmt = $pdo->query("DESCRIBE tours");
$columns = $stmt->fetchAll();

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

foreach ($columns as $col) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
    echo "<td>" . htmlspecialchars($col['Extra']) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Check for image columns:</h2>";
$image_cols = ['image_url', 'cover_image', 'gallery', 'images'];
foreach ($image_cols as $col) {
    $found = false;
    foreach ($columns as $c) {
        if ($c['Field'] === $col) {
            $found = true;
            break;
        }
    }
    echo "<p>" . ($found ? "✓" : "✗") . " " . $col . "</p>";
}
?>
