<?php
require_once 'config/database.php';

echo "<h1>Login System Test</h1>";
echo "<hr>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
try {
    $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connected successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Test 2: Users Table
echo "<h2>2. Users Table</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✓ Users table exists</p>";
    echo "<p>Total users in database: <strong>" . $result['count'] . "</strong></p>";
    
    if ($result['count'] == 0) {
        echo "<p style='color: orange;'>⚠ No users found. <a href='create-test-users.php' style='background: #DAA520; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Create Test Users</a></p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Users table error: " . $e->getMessage() . "</p>";
}

// Test 3: List All Users
echo "<h2>3. Existing Users</h2>";
try {
    $stmt = $pdo->query("SELECT id, name, email, role FROM users");
    $users = $stmt->fetchAll();
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td><strong>" . $user['role'] . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test 4: Password Test
echo "<h2>4. Password Verification Test</h2>";
$testPassword = 'admin123';
$testHash = password_hash($testPassword, PASSWORD_DEFAULT);
$verify = password_verify($testPassword, $testHash);
echo "<p>Test password: <code>admin123</code></p>";
echo "<p>Hash: <code>" . substr($testHash, 0, 50) . "...</code></p>";
echo "<p>Verification: " . ($verify ? "<span style='color: green;'>✓ Working</span>" : "<span style='color: red;'>✗ Failed</span>") . "</p>";

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If no users exist, <a href='create-test-users.php'>create test users</a></li>";
echo "<li>Then try <a href='auth/login.php'>logging in</a></li>";
echo "</ol>";
?>
