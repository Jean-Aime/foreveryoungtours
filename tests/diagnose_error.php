<?php
// Diagnostic script - Run this: http://localhost:8000/diagnose_error.php

require_once 'config/database.php';

echo "<h1>Booking Error Diagnostic</h1>";
echo "<pre>";

try {
    // Check for foreign keys
    echo "1. Checking foreign key constraints on bookings table...\n";
    $stmt = $pdo->query("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = 'forevveryoungtours'
        AND TABLE_NAME = 'bookings'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($fks)) {
        echo "No foreign keys found.\n";
    } else {
        print_r($fks);
    }
    
    // Check for triggers
    echo "\n2. Checking triggers on bookings table...\n";
    $stmt = $pdo->query("SHOW TRIGGERS WHERE `Table` = 'bookings'");
    $triggers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($triggers)) {
        echo "No triggers found.\n";
    } else {
        print_r($triggers);
    }
    
    // Test update query
    echo "\n3. Testing update query on a booking...\n";
    $stmt = $pdo->query("SELECT id FROM bookings LIMIT 1");
    $booking = $stmt->fetch();
    
    if ($booking) {
        $test_id = $booking['id'];
        echo "Testing with booking ID: $test_id\n";
        
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed', confirmed_date = NOW() WHERE id = ?");
            $stmt->execute([$test_id]);
            echo "✓ Update successful!\n";
        } catch (PDOException $e) {
            echo "✗ Update failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No bookings found to test.\n";
    }
    
    // Check database name
    echo "\n4. Checking database connection...\n";
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $db = $stmt->fetch();
    echo "Connected to database: " . $db['db_name'] . "\n";
    
    // Check if there are multiple bookings tables
    echo "\n5. Checking for multiple bookings tables...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%booking%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    
} catch (PDOException $e) {
    echo "\n✗✗✗ ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<p><strong>After reviewing the results, delete this file for security!</strong></p>";
?>
