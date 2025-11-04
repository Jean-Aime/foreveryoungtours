<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Add new columns to tours table if they don't exist
    $alterQueries = [
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS detailed_description TEXT COMMENT 'Comprehensive tour description'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS highlights JSON COMMENT 'Key tour highlights'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS difficulty_level ENUM('easy','moderate','challenging','extreme') DEFAULT 'moderate'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS best_time_to_visit VARCHAR(100) COMMENT 'Best months to take this tour'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS what_to_bring TEXT COMMENT 'What participants should bring'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS tour_type ENUM('group','private','custom') DEFAULT 'group'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS languages JSON COMMENT 'Available guide languages'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS age_restriction VARCHAR(50) COMMENT 'Age requirements'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS accommodation_type VARCHAR(100) COMMENT 'Type of accommodation included'",
        "ALTER TABLE tours ADD COLUMN IF NOT EXISTS meal_plan VARCHAR(100) COMMENT 'Meal plan details'"
    ];

    foreach ($alterQueries as $query) {
        try {
            $conn->exec($query);
            echo "✓ Executed: " . substr($query, 0, 50) . "...<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') === false) {
                echo "✗ Error: " . $e->getMessage() . "<br>";
            } else {
                echo "✓ Column already exists: " . substr($query, 0, 50) . "...<br>";
            }
        }
    }

    echo "<br><strong>Database update completed!</strong><br>";
    echo "<a href='tours.php'>← Back to Tours Management</a>";

} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>