<?php
require_once 'config/database.php';

// Get Africa region ID
$stmt = $pdo->query("SELECT id FROM regions WHERE slug='africa'");
$africa = $stmt->fetch();

if ($africa) {
    $sql = "INSERT INTO countries (name, slug, country_code, region_id, description, status) 
            VALUES ('Rwanda', 'rwanda', 'RW', {$africa['id']}, 'Land of a Thousand Hills', 'active')
            ON DUPLICATE KEY UPDATE status='active'";
    
    if ($pdo->exec($sql)) {
        echo "✅ Rwanda added successfully!";
    } else {
        echo "❌ Error: " . print_r($pdo->errorInfo(), true);
    }
} else {
    echo "❌ Africa region not found";
}
?>
