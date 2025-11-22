<?php
session_start();
require_once 'config/database.php';

echo "<h2>Login Debug Tool</h2>";

// Check database users
echo "<h3>1. Users in Database:</h3>";
$stmt = $pdo->query("SELECT id, email, role, first_name, last_name FROM users WHERE email LIKE '%@foreveryoung.com' ORDER BY email");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Email</th><th>Role</th><th>Name</th></tr>";
foreach ($users as $user) {
    $roleColor = empty($user['role']) ? 'red' : 'green';
    $roleText = empty($user['role']) ? 'EMPTY - THIS IS THE PROBLEM!' : $user['role'];
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td style='color: {$roleColor}; font-weight: bold;'>{$roleText}</td>";
    echo "<td>{$user['first_name']} {$user['last_name']}</td>";
    echo "</tr>";
}
echo "</table>";

// Check current session
echo "<h3>2. Current Session:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "<pre style='background: #f0f0f0; padding: 10px;'>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>Not logged in</p>";
}

// Manual fix button
echo "<h3>3. Fix Empty Roles:</h3>";
if (isset($_POST['fix_roles'])) {
    $pdo->exec("UPDATE users SET role = 'admin' WHERE email = 'admin@foreveryoung.com'");
    $pdo->exec("UPDATE users SET role = 'mca' WHERE email = 'mca@foreveryoung.com'");
    $pdo->exec("UPDATE users SET role = 'advisor' WHERE email = 'advisor@foreveryoung.com'");
    $pdo->exec("UPDATE users SET role = 'user' WHERE email = 'client@foreveryoung.com'");
    echo "<p style='color: green; font-weight: bold;'>✓ Roles updated! Refresh this page to verify.</p>";
}

echo "<form method='POST'>";
echo "<button type='submit' name='fix_roles' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>FIX ROLES NOW</button>";
echo "</form>";

echo "<h3>4. Test Login:</h3>";
echo "<p>After fixing roles, <a href='auth/logout.php' style='color: blue; font-weight: bold;'>LOGOUT</a> then <a href='auth/login.php' style='color: blue; font-weight: bold;'>LOGIN</a> again.</p>";
?>
