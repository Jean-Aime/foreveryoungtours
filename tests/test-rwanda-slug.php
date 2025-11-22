<?php
require_once 'config/database.php';

echo "<h1>Rwanda Slug Test</h1>";
echo "<pre>";

// Check what's in the database
$stmt = $pdo->prepare("SELECT id, name, slug, country_code FROM countries WHERE name = 'Rwanda'");
$stmt->execute();
$rwanda = $stmt->fetch();

echo "Database Query Result:\n";
print_r($rwanda);

echo "\n\nGenerated URL:\n";
$url = "http://{$rwanda['slug']}.foreveryoungtours.local";
echo $url;

echo "\n\nExpected: http://visit-rw.foreveryoungtours.local";
echo "\n\nMatch: " . ($url === "http://visit-rw.foreveryoungtours.local" ? "YES ✅" : "NO ❌");

echo "</pre>";
?>
