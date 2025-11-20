<?php
require_once 'config/database.php';

$sql = "INSERT INTO regions (name, slug, description, status, image_url) 
        VALUES ('Africa', 'africa', 'Discover the diverse beauty and rich culture of Africa', 'active', 'assets/images/africa.png')
        ON DUPLICATE KEY UPDATE status='active'";

if ($pdo->exec($sql)) {
    echo "✅ Africa region added successfully!";
} else {
    echo "❌ Error: " . print_r($pdo->errorInfo(), true);
}
?>
