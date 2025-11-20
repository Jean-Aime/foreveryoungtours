<?php
// Simple test to identify the loading issue
echo "Starting test...<br>";

try {
    session_start();
    echo "Session started...<br>";
    
    require_once __DIR__ . '/../../config.php';
    echo "Config loaded...<br>";
    
    require_once __DIR__ . '/../../config/database.php';
    echo "Database loaded...<br>";
    
    // Test continent query
    $continent_slug = basename(dirname(__FILE__));
    echo "Continent slug: " . $continent_slug . "<br>";
    
    $stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
    $stmt->execute([$continent_slug]);
    $continent = $stmt->fetch();
    echo "Continent query executed...<br>";
    
    if (!$continent) {
        echo "No continent found!<br>";
    } else {
        echo "Continent found: " . $continent['name'] . "<br>";
    }
    
    // Test countries query
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
    $stmt->execute([$continent['id']]);
    $countries = $stmt->fetchAll();
    echo "Countries found: " . count($countries) . "<br>";
    
    // Test featured tours query
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name FROM tours t
        INNER JOIN countries c ON t.country_id = c.id
        WHERE c.region_id = ? AND t.status = 'active' AND t.featured = 1
        ORDER BY t.popularity_score DESC
        LIMIT 6
    ");
    $stmt->execute([$continent['id']]);
    $featured_tours = $stmt->fetchAll();
    echo "Featured tours found: " . count($featured_tours) . "<br>";
    
    echo "All queries completed successfully!<br>";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>