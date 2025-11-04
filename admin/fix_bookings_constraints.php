<?php
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<h3>Fixing Bookings Table Constraints</h3>";
    
    // Step 1: Check current table structure
    echo "<h4>Current bookings table structure:</h4>";
    $result = $conn->query("DESCRIBE bookings");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Key'] . "<br>";
    }
    
    echo "<br><h4>Executing fixes:</h4>";
    
    // Step 2: Drop foreign key constraints that are causing issues
    $dropConstraints = [
        "ALTER TABLE bookings DROP FOREIGN KEY bookings_ibfk_1",
        "ALTER TABLE bookings DROP FOREIGN KEY bookings_ibfk_3"
    ];
    
    foreach ($dropConstraints as $query) {
        try {
            $conn->exec($query);
            echo "✓ Dropped constraint: " . $query . "<br>";
        } catch (Exception $e) {
            echo "⚠ Constraint not found or already dropped: " . $query . "<br>";
        }
    }
    
    // Step 3: Modify columns to allow NULL values
    $modifyColumns = [
        "ALTER TABLE bookings MODIFY COLUMN user_id INT NULL",
        "ALTER TABLE bookings MODIFY COLUMN advisor_id INT NULL"
    ];
    
    foreach ($modifyColumns as $query) {
        try {
            $conn->exec($query);
            echo "✓ Modified column: " . $query . "<br>";
        } catch (Exception $e) {
            echo "⚠ Column modification failed: " . $query . " - " . $e->getMessage() . "<br>";
        }
    }
    
    // Step 4: Add missing columns if they don't exist
    $addColumns = [
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS emergency_contact VARCHAR(255) NULL",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS accommodation_type VARCHAR(50) DEFAULT 'standard'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS transport_type VARCHAR(50) DEFAULT 'shared'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS special_requests TEXT NULL",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'card'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS booking_reference VARCHAR(20) NULL"
    ];
    
    foreach ($addColumns as $query) {
        try {
            $conn->exec($query);
            echo "✓ Added column: " . $query . "<br>";
        } catch (Exception $e) {
            echo "⚠ Column already exists: " . $query . "<br>";
        }
    }
    
    echo "<br><strong>✅ Database fixes completed!</strong><br>";
    echo "<br><a href='bookings.php'>Go to Bookings Management</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>

<style>
body { font-family: Arial, sans-serif; padding: 20px; }
h3, h4 { color: #333; }
</style>