<?php
// Test Africa Subdomain
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h1>Africa Subdomain Test</h1>";

// Test 1: Check continent slug
$continent_slug = 'africa';
echo "<p><strong>Looking for continent:</strong> $continent_slug</p>";

// Test 2: Query database
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ?");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if ($continent) {
    echo "<p style='color:green;'><strong>✓ Continent found!</strong></p>";
    echo "<pre>" . print_r($continent, true) . "</pre>";
} else {
    echo "<p style='color:red;'><strong>✗ Continent NOT found in database!</strong></p>";
    
    // Show all regions
    $stmt = $pdo->query("SELECT * FROM regions");
    $all_regions = $stmt->fetchAll();
    echo "<p><strong>Available regions in database:</strong></p>";
    echo "<pre>" . print_r($all_regions, true) . "</pre>";
}
?>
