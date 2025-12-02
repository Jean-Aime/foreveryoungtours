<?php
// Temporary debug file - check database connection
session_start();
require_once '../config/database.php';

echo "<h2>Database Debug Info</h2>";
echo "<p><strong>Connection:</strong> " . (isset($pdo) ? "Connected" : "Failed") . "</p>";

// Check tours table structure
try {
    $stmt = $pdo->query("DESCRIBE tours");
    echo "<h3>Tours Table Structure:</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . ($row['Key'] == 'PRI' ? 'PRIMARY KEY' : '') . "\n";
    }
    echo "</pre>";
    
    // Test insert
    echo "<h3>Test Insert:</h3>";
    $test_stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, destination, destination_country, country_id, category, price, base_price, duration, duration_days, max_participants, min_participants, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $result = $test_stmt->execute([
        'Test Tour',
        'test-tour-' . time(),
        'Test description',
        'Test Destination',
        'Test Country',
        1,
        'cultural tours',
        100,
        100,
        '3 days',
        3,
        20,
        2,
        'active',
        0,
        1
    ]);
    
    echo "<p><strong>Insert Result:</strong> " . ($result ? "Success" : "Failed") . "</p>";
    echo "<p><strong>Last Insert ID:</strong> " . $pdo->lastInsertId() . "</p>";
    
    if ($result) {
        $id = $pdo->lastInsertId();
        $pdo->prepare("DELETE FROM tours WHERE id = ?")->execute([$id]);
        echo "<p>Test record deleted.</p>";
    }
    
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
