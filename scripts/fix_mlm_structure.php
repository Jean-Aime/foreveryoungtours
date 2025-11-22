<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<h2>Fixing MLM Structure...</h2>";
    
    // Add MLM hierarchy columns to users table
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS level INT DEFAULT 1");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS upline_id INT NULL");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS mca_id INT NULL");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS team_size INT DEFAULT 0");
    $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS kyc_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    echo "✓ Added MLM columns to users table<br>";
    
    // Update bookings table for proper tracking
    $conn->exec("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS referred_by INT NULL");
    $conn->exec("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS commission_level INT DEFAULT 1");
    echo "✓ Updated bookings table<br>";
    
    // Create team hierarchy view
    $conn->exec("CREATE OR REPLACE VIEW team_hierarchy_view AS
        SELECT 
            u1.id as advisor_id,
            u1.full_name as advisor_name,
            u1.level,
            u2.id as upline_id,
            u2.full_name as upline_name,
            u3.id as mca_id,
            u3.full_name as mca_name,
            COUNT(u4.id) as team_count
        FROM users u1
        LEFT JOIN users u2 ON u1.upline_id = u2.id
        LEFT JOIN users u3 ON u1.mca_id = u3.id
        LEFT JOIN users u4 ON u4.upline_id = u1.id
        WHERE u1.role IN ('advisor', 'client')
        GROUP BY u1.id");
    echo "✓ Created team hierarchy view<br>";
    
    // Update existing users with proper hierarchy
    $conn->exec("UPDATE users SET level = 1 WHERE role = 'advisor' AND upline_id IS NULL");
    $conn->exec("UPDATE users SET level = 2 WHERE role = 'advisor' AND upline_id IS NOT NULL AND upline_id IN (SELECT id FROM (SELECT id FROM users WHERE level = 1) as t)");
    $conn->exec("UPDATE users SET level = 3 WHERE role = 'advisor' AND upline_id IS NOT NULL AND upline_id IN (SELECT id FROM (SELECT id FROM users WHERE level = 2) as t)");
    echo "✓ Updated user levels<br>";
    
    echo "<h3>✅ MLM Structure Fixed!</h3>";
    echo "<p>Hierarchy: Super Admin → MCA → L1 Advisor → L2 Team → L3 Team → Clients</p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error: " . $e->getMessage() . "</h3>";
}
?>