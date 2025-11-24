<?php
require_once 'config/database.php';

echo "<h2>Setting up MLM Commission System...</h2>";

try {
    // Read and execute SQL file
    $sql = file_get_contents('database/mlm_system_update.sql');
    
    // Split by delimiter and execute each statement
    $statements = explode('$$', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || $statement === 'DELIMITER') continue;
        
        try {
            $pdo->exec($statement);
            echo "<p style='color: green;'>✓ Executed statement successfully</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠ " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3 style='color: green;'>✓ MLM System Setup Complete!</h3>";
    echo "<p>The following features are now active:</p>";
    echo "<ul>";
    echo "<li>3-Level Commission Structure (L1: Direct, L2: 10%, L3: 5%)</li>";
    echo "<li>Advisor Ranks (Certified: 30%, Senior: 35%, Executive: 40%)</li>";
    echo "<li>MCA Override (7.5%)</li>";
    echo "<li>Referral Code System</li>";
    echo "<li>Team Building & Tracking</li>";
    echo "<li>Automatic Commission Calculation</li>";
    echo "</ul>";
    
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li>Visit <a href='/foreveryoungtours/advisor/team.php'>Advisor Team Page</a></li>";
    echo "<li>Share referral codes to build teams</li>";
    echo "<li>Track commissions at <a href='/foreveryoungtours/advisor/commissions.php'>Commissions Page</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
}
?>
