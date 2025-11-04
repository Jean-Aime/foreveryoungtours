<?php
require_once 'config/database.php';

// Create test users for all roles
$users = [
    [
        'name' => 'Super Admin',
        'email' => 'admin@foreveryoung.com',
        'password' => 'admin123',
        'role' => 'admin',
        'phone' => '+1234567890'
    ],
    [
        'name' => 'MCA User',
        'email' => 'mca@foreveryoung.com',
        'password' => 'mca123',
        'role' => 'mca',
        'phone' => '+1234567891'
    ],
    [
        'name' => 'Advisor User',
        'email' => 'advisor@foreveryoung.com',
        'password' => 'advisor123',
        'role' => 'advisor',
        'phone' => '+1234567892'
    ],
    [
        'name' => 'Client User',
        'email' => 'client@foreveryoung.com',
        'password' => 'client123',
        'role' => 'user',
        'phone' => '+1234567893'
    ]
];

echo "<h2>Creating Test Users...</h2>";

foreach ($users as $user) {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$user['email']]);
    
    if ($stmt->fetch()) {
        echo "<p style='color: orange;'>User {$user['email']} already exists - skipping</p>";
        continue;
    }
    
    // Create user
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    
    // Check if table has 'name' or 'first_name' column
    $columns = $pdo->query("SHOW COLUMNS FROM users LIKE 'name'")->fetchAll();
    
    if (count($columns) > 0) {
        // Table has 'name' column
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $params = [$user['name'], $user['email'], $hashed_password, $user['phone'], $user['role']];
    } else {
        // Table has 'first_name' and 'last_name' columns
        $nameParts = explode(' ', $user['name'], 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, phone, role, status, email_verified, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', 1, NOW())");
        $params = [$firstName, $lastName, $user['email'], $hashed_password, $user['phone'], $user['role']];
    }
    
    if ($stmt->execute($params)) {
        echo "<p style='color: green;'>✓ Created {$user['role']}: {$user['email']} / {$user['password']}</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create {$user['email']}</p>";
    }
}

echo "<hr>";
echo "<h3>Test Credentials:</h3>";
echo "<ul>";
echo "<li><strong>Admin:</strong> admin@foreveryoung.com / admin123</li>";
echo "<li><strong>MCA:</strong> mca@foreveryoung.com / mca123</li>";
echo "<li><strong>Advisor:</strong> advisor@foreveryoung.com / advisor123</li>";
echo "<li><strong>Client:</strong> client@foreveryoung.com / client123</li>";
echo "</ul>";
echo "<br>";
echo "<a href='login.php' style='background: #DAA520; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login</a>";
?>
