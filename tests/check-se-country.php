<?php
require_once 'config/database.php';

echo "<h1>Checking for SE Country</h1>\n";

// Check for SE country
$stmt = $pdo->query("SELECT * FROM countries WHERE country_code LIKE '%SE%' OR slug LIKE '%se%' ORDER BY name");
$countries = $stmt->fetchAll();

if (empty($countries)) {
    echo "<p style='color:red;'>No country found with code 'SE'</p>\n";
} else {
    echo "<table border='1' cellpadding='10'>\n";
    echo "<tr><th>Name</th><th>Slug</th><th>Country Code</th><th>Status</th></tr>\n";
    foreach ($countries as $country) {
        echo "<tr>";
        echo "<td>{$country['name']}</td>";
        echo "<td>{$country['slug']}</td>";
        echo "<td>{$country['country_code']}</td>";
        echo "<td>{$country['status']}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
}

echo "<hr>\n";
echo "<h2>All Countries in Database</h2>\n";
$stmt = $pdo->query("SELECT * FROM countries ORDER BY name");
$all_countries = $stmt->fetchAll();

echo "<table border='1' cellpadding='10'>\n";
echo "<tr><th>Name</th><th>Slug</th><th>Country Code</th><th>Status</th><th>Folder</th></tr>\n";
foreach ($all_countries as $country) {
    $folder = str_replace('visit-', '', $country['slug']);
    echo "<tr>";
    echo "<td>{$country['name']}</td>";
    echo "<td>{$country['slug']}</td>";
    echo "<td>{$country['country_code']}</td>";
    echo "<td>{$country['status']}</td>";
    echo "<td>countries/{$folder}/</td>";
    echo "</tr>\n";
}
echo "</table>\n";
?>

