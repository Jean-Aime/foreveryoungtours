<?php
// Database fix script - Run this file in your browser: http://localhost:8000/fix_database.php

require_once 'config/database.php';

echo "<h1>Database Fix Script</h1>";
echo "<pre>";

try {
    // Check current structure
    echo "1. Checking current bookings table structure...\n";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCurrent user_id column:\n";
    foreach ($columns as $col) {
        if ($col['Field'] === 'user_id') {
            print_r($col);
        }
    }
    
    // Fix the user_id column
    echo "\n\n2. Fixing user_id column to allow NULL...\n";
    $pdo->exec("ALTER TABLE bookings MODIFY COLUMN user_id INT(11) NULL DEFAULT NULL");
    echo "✓ Successfully modified user_id column!\n";
    
    // Verify the fix
    echo "\n3. Verifying the fix...\n";
    $stmt = $pdo->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nUpdated user_id column:\n";
    foreach ($columns as $col) {
        if ($col['Field'] === 'user_id') {
            print_r($col);
            if ($col['Null'] === 'YES') {
                echo "\n✓✓✓ SUCCESS! user_id now allows NULL values!\n";
            } else {
                echo "\n✗✗✗ FAILED! user_id still doesn't allow NULL!\n";
            }
        }
    }
    
    echo "\n\n4. Testing with a sample query...\n";
    $testStmt = $pdo->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id IS NULL");
    $testStmt->execute();
    $result = $testStmt->fetch();
    echo "Bookings with NULL user_id: " . $result['count'] . "\n";
    
    echo "\n✓✓✓ ALL DONE! You can now close this page and try booking again.\n";
    echo "\nDELETE THIS FILE (fix_database.php) after running it for security!\n";
    
} catch (PDOException $e) {
    echo "\n✗✗✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nPlease run this SQL manually in phpMyAdmin:\n";
    echo "ALTER TABLE bookings MODIFY COLUMN user_id INT(11) NULL DEFAULT NULL;\n";
}

echo "</pre>";
?>
