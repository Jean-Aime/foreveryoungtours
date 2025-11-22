<?php
require_once 'config/database.php';

echo "<h2>Fixing User Roles</h2>";

// Check current users
$stmt = $pdo->query("SELECT id, email, first_name, last_name, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Current Users:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>Current Role</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td>{$user['first_name']} {$user['last_name']}</td>";
    echo "<td>" . ($user['role'] ?: '<span style="color:red;">EMPTY</span>') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Fix roles
echo "<h3>Fixing Roles...</h3>";

$updates = [
    'admin@foreveryoung.com' => 'admin',
    'mca@foreveryoung.com' => 'mca',
    'advisor@foreveryoung.com' => 'advisor',
    'client@foreveryoung.com' => 'user'
];

foreach ($updates as $email => $role) {
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE email = ?");
    $stmt->execute([$role, $email]);
    echo "<p>✓ Updated {$email} to role: <strong>{$role}</strong></p>";
}

echo "<h3>Updated Users:</h3>";
$stmt = $pdo->query("SELECT id, email, first_name, last_name, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td>{$user['first_name']} {$user['last_name']}</td>";
    echo "<td><strong style='color:green;'>{$user['role']}</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<p style='margin-top: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;'>";
echo "<strong>✓ Done!</strong> Now <a href='auth/logout.php' style='color: #155724; text-decoration: underline;'>logout</a> and login again.";
echo "</p>";
?>
