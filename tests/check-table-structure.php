<?php
require_once 'config/database.php';

echo "<h2>Users Table Structure</h2>";

// Get table structure
$stmt = $pdo->query("DESCRIBE users");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
foreach ($columns as $col) {
    $highlight = ($col['Field'] == 'role') ? 'background: yellow;' : '';
    echo "<tr style='{$highlight}'>";
    echo "<td><strong>{$col['Field']}</strong></td>";
    echo "<td>{$col['Type']}</td>";
    echo "<td>{$col['Null']}</td>";
    echo "<td>{$col['Key']}</td>";
    echo "<td>{$col['Default']}</td>";
    echo "<td>{$col['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Current User Roles:</h3>";
$stmt = $pdo->query("SELECT id, email, role FROM users WHERE email LIKE '%@foreveryoung.com'");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Email</th><th>Role Value</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>" . var_export($row['role'], true) . "</td></tr>";
}
echo "</table>";

// Try to update using ID instead of email
echo "<h3>Fixing by ID:</h3>";
$stmt = $pdo->query("SELECT id, email FROM users WHERE email LIKE '%@foreveryoung.com' ORDER BY email");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $role = match($user['email']) {
        'admin@foreveryoung.com' => 'admin',
        'mca@foreveryoung.com' => 'mca',
        'advisor@foreveryoung.com' => 'advisor',
        'client@foreveryoung.com' => 'user',
        default => null
    };
    
    if ($role) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $result = $stmt->execute([$role, $user['id']]);
        echo "<p>ID {$user['id']} ({$user['email']}): " . ($result ? "✓" : "✗") . " Set to '{$role}' - Rows affected: {$stmt->rowCount()}</p>";
    }
}

echo "<h3>Final Check:</h3>";
$stmt = $pdo->query("SELECT id, email, role FROM users WHERE email LIKE '%@foreveryoung.com'");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Email</th><th>Role</th></tr>";
while ($row = $stmt->fetch()) {
    $color = empty($row['role']) ? 'red' : 'green';
    echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td style='color: {$color}; font-weight: bold;'>" . ($row['role'] ?: 'EMPTY') . "</td></tr>";
}
echo "</table>";
?>
