<?php
// MLM Tourism Platform Setup
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS iforyoungtours");
    $pdo->exec("USE iforyoungtours");
    
    // Execute your comprehensive schema
    $schema = file_get_contents('database/mlm_schema.sql');
    $pdo->exec($schema);
    
    // Insert default users for each role
    $superAdminPassword = password_hash('superadmin123', PASSWORD_DEFAULT);
    $mcaPassword = password_hash('mca123', PASSWORD_DEFAULT);
    $advisorPassword = password_hash('advisor123', PASSWORD_DEFAULT);
    $clientPassword = password_hash('client123', PASSWORD_DEFAULT);
    
    $pdo->exec("
        INSERT INTO users (email, password, role, first_name, last_name, status, email_verified, kyc_status) VALUES 
        ('superadmin@iforyoungtours.com', '$superAdminPassword', 'super_admin', 'Super', 'Admin', 'active', TRUE, 'verified'),
        ('mca@iforyoungtours.com', '$mcaPassword', 'mca', 'MCA', 'Manager', 'active', TRUE, 'verified'),
        ('advisor@iforyoungtours.com', '$advisorPassword', 'advisor', 'Travel', 'Advisor', 'active', TRUE, 'verified'),
        ('client@iforyoungtours.com', '$clientPassword', 'client', 'Test', 'Client', 'active', TRUE, 'verified')
    ");
    
    // Insert sample tours
    $pdo->exec("
        INSERT INTO tours (name, slug, description, destination_country, category, duration_days, duration_nights, base_price, status, created_by) VALUES 
        ('Safari Adventure', 'safari-adventure', 'Experience the wild beauty of African safari', 'Kenya', 'safari', 7, 6, 2500.00, 'active', 1),
        ('Victoria Falls Tour', 'victoria-falls-tour', 'Witness the magnificent Victoria Falls', 'Zimbabwe', 'adventure', 5, 4, 1800.00, 'active', 1),
        ('Cape Town Explorer', 'cape-town-explorer', 'Discover the beauty of Cape Town', 'South Africa', 'city_break', 6, 5, 2200.00, 'active', 1)
    ");
    
    // Setup MCA assignment
    $pdo->exec("
        INSERT INTO mca_assignments (mca_id, country_code, country_name, assigned_by) VALUES 
        (2, 'KE', 'Kenya', 1),
        (2, 'ZW', 'Zimbabwe', 1),
        (2, 'ZA', 'South Africa', 1)
    ");
    
    // Setup team hierarchy
    $pdo->exec("
        INSERT INTO team_hierarchy (advisor_id, team_member_id, level, mca_id) VALUES 
        (3, 3, 'L1', 2)
    ");
    
    echo "<h1>MLM Tourism Platform Setup Complete!</h1>";
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>Super Admin:</strong> superadmin@iforyoungtours.com / superadmin123</p>";
    echo "<p><strong>MCA:</strong> mca@iforyoungtours.com / mca123</p>";
    echo "<p><strong>Advisor:</strong> advisor@iforyoungtours.com / advisor123</p>";
    echo "<p><strong>Client:</strong> client@iforyoungtours.com / client123</p>";
    echo "<br><p><a href='index.php'>Go to Website</a> | <a href='admin/'>Admin Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>