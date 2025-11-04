<?php
// Fix trigger script - Run this: http://localhost:8000/fix_trigger.php

require_once 'config/database.php';

echo "<h1>Fix Trigger Script</h1>";
echo "<pre>";

try {
    echo "Dropping old trigger...\n";
    $pdo->exec("DROP TRIGGER IF EXISTS calculate_commissions");
    echo "✓ Old trigger dropped\n\n";
    
    echo "Creating new trigger with NULL check...\n";
    $sql = "
    CREATE TRIGGER calculate_commissions
    AFTER UPDATE ON bookings
    FOR EACH ROW
    BEGIN
        -- Only process if advisor_id is NOT NULL
        IF NEW.status = 'confirmed' AND OLD.status != 'confirmed' AND NEW.advisor_id IS NOT NULL THEN
            -- Direct commission to advisor (10%)
            INSERT INTO commissions (booking_id, user_id, commission_type, commission_rate, commission_amount)
            VALUES (NEW.id, NEW.advisor_id, 'direct', 10.00, NEW.total_amount * 0.10);
        END IF;
    END
    ";
    
    $pdo->exec($sql);
    echo "✓ New trigger created successfully!\n\n";
    
    echo "Testing the fix...\n";
    $stmt = $pdo->query("SELECT id FROM bookings WHERE status = 'pending' LIMIT 1");
    $booking = $stmt->fetch();
    
    if ($booking) {
        $test_id = $booking['id'];
        echo "Testing with booking ID: $test_id\n";
        
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed', confirmed_date = NOW() WHERE id = ?");
            $stmt->execute([$test_id]);
            echo "✓✓✓ SUCCESS! Update works now!\n";
            
            // Revert the test
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'pending', confirmed_date = NULL WHERE id = ?");
            $stmt->execute([$test_id]);
            echo "✓ Test reverted\n";
        } catch (PDOException $e) {
            echo "✗ Update failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No pending bookings to test with.\n";
    }
    
    echo "\n✓✓✓ ALL DONE! You can now confirm bookings without errors.\n";
    echo "\nDELETE THIS FILE (fix_trigger.php) after running it for security!\n";
    
} catch (PDOException $e) {
    echo "\n✗✗✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nIf you see an error, run the SQL file fix_trigger.sql in phpMyAdmin instead.\n";
}

echo "</pre>";
?>
