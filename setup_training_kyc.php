<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Read and execute SQL file
    $sql = file_get_contents('database/training_kyc_system.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    
    echo "Training and KYC system setup completed successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>