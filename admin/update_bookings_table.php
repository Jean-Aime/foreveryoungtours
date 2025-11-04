<?php
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Add missing columns to bookings table
    $alterQueries = [
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS emergency_contact VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS accommodation_type VARCHAR(50) DEFAULT 'standard'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS transport_type VARCHAR(50) DEFAULT 'shared'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS special_requests TEXT DEFAULT NULL",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'card'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS booking_reference VARCHAR(20) DEFAULT NULL"
    ];
    
    foreach ($alterQueries as $query) {
        try {
            $conn->exec($query);
            echo "✓ Executed: " . $query . "<br>";
        } catch (Exception $e) {
            echo "⚠ Skipped (already exists): " . $query . "<br>";
        }
    }
    
    echo "<br><strong>Database update completed!</strong>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<p><a href="bookings.php">Go to Bookings Management</a></p>