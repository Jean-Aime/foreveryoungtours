<!DOCTYPE html>
<html>
<head>
    <title>Reset User Passwords</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        button { background: #DAA520; color: black; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px; font-weight: bold; }
        button:hover { background: #B8860B; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>🔐 Reset User Passwords</h1>

<?php
require_once 'config/database.php';

if (isset($_POST['reset_passwords'])) {
    echo "<div class='box'>";
    echo "<h2>Resetting Passwords...</h2>";
    
    $updates = [
        ['email' => 'admin@foreveryoung.com', 'password' => 'admin123'],
        ['email' => 'mca@foreveryoung.com', 'password' => 'mca123'],
        ['email' => 'advisor@foreveryoung.com', 'password' => 'advisor123'],
        ['email' => 'client@foreveryoung.com', 'password' => 'client123']
    ];
    
    foreach ($updates as $update) {
        // Check if user exists
        $check = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
        $check->execute([$update['email']]);
        $user = $check->fetch();
        
        if (!$user) {
            echo "<p class='error'>✗ User {$update['email']} not found</p>";
            continue;
        }
        
        // Update password
        $newHash = password_hash($update['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        
        if ($stmt->execute([$newHash, $update['email']])) {
            echo "<p class='success'>✓ Password reset for {$update['email']} → {$update['password']}</p>";
            
            // Verify it works
            $verify = password_verify($update['password'], $newHash);
            if ($verify) {
                echo "<p style='margin-left: 20px; color: green;'>→ Password verification: ✓ Working</p>";
            } else {
                echo "<p style='margin-left: 20px; color: red;'>→ Password verification: ✗ Failed</p>";
            }
        } else {
            echo "<p class='error'>✗ Failed to update {$update['email']}</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>✅ Password Reset Complete!</h3>";
    echo "<p><strong>You can now login with these credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@foreveryoung.com / admin123</li>";
    echo "<li><strong>MCA:</strong> mca@foreveryoung.com / mca123</li>";
    echo "<li><strong>Advisor:</strong> advisor@foreveryoung.com / advisor123</li>";
    echo "<li><strong>Client:</strong> client@foreveryoung.com / client123</li>";
    echo "</ul>";
    echo "<p style='margin-top: 20px;'><a href='auth/login.php'><button>Go to Login Page</button></a></p>";
    echo "</div>";
    
} else {
    // Show current users
    echo "<div class='box'>";
    echo "<h2>Current Users in Database</h2>";
    
    $stmt = $pdo->query("SELECT id, email, role, first_name, last_name FROM users WHERE email LIKE '%foreveryoung.com' ORDER BY role");
    $users = $stmt->fetchAll();
    
    if (count($users) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th></tr>";
        foreach ($users as $user) {
            $name = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>" . trim($name) . "</td>";
            echo "<td><strong>{$user['role']}</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p style='margin-top: 20px;'><strong>Found " . count($users) . " user(s)</strong></p>";
    } else {
        echo "<p class='error'>No users found with @foreveryoung.com email</p>";
        echo "<p>Please run <a href='fix-login.php'>fix-login.php</a> first to create users.</p>";
    }
    echo "</div>";
    
    if (count($users) > 0) {
        echo "<div class='box'>";
        echo "<h2>Reset Passwords</h2>";
        echo "<p>This will reset passwords for all @foreveryoung.com users to:</p>";
        echo "<ul>";
        echo "<li><strong>admin@foreveryoung.com</strong> → admin123</li>";
        echo "<li><strong>mca@foreveryoung.com</strong> → mca123</li>";
        echo "<li><strong>advisor@foreveryoung.com</strong> → advisor123</li>";
        echo "<li><strong>client@foreveryoung.com</strong> → client123</li>";
        echo "</ul>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='reset_passwords'>Reset All Passwords Now</button>";
        echo "</form>";
        echo "</div>";
    }
}
?>

</body>
</html>
