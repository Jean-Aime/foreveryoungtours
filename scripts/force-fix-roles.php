<?php
require_once 'config/database.php';

echo "<h2>Force Fix User Roles</h2>";

// Show current state
echo "<h3>Before Fix:</h3>";
$stmt = $pdo->query("SELECT id, email, role FROM users WHERE email LIKE '%@foreveryoung.com'");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Email</th><th>Role</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>" . ($row['role'] ?: 'EMPTY') . "</td></tr>";
}
echo "</table>";

// Direct SQL updates
echo "<h3>Applying Updates...</h3>";

try {
    // Update admin
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = 'admin@foreveryoung.com'");
    $result = $stmt->execute();
    echo "<p>Admin update: " . ($result ? "✓ Success" : "✗ Failed") . " (Rows: " . $stmt->rowCount() . ")</p>";
    
    // Update MCA
    $stmt = $pdo->prepare("UPDATE users SET role = 'mca' WHERE email = 'mca@foreveryoung.com'");
    $result = $stmt->execute();
    echo "<p>MCA update: " . ($result ? "✓ Success" : "✗ Failed") . " (Rows: " . $stmt->rowCount() . ")</p>";
    
    // Update Advisor
    $stmt = $pdo->prepare("UPDATE users SET role = 'advisor' WHERE email = 'advisor@foreveryoung.com'");
    $result = $stmt->execute();
    echo "<p>Advisor update: " . ($result ? "✓ Success" : "✗ Failed") . " (Rows: " . $stmt->rowCount() . ")</p>";
    
    // Update Client
    $stmt = $pdo->prepare("UPDATE users SET role = 'user' WHERE email = 'client@foreveryoung.com'");
    $result = $stmt->execute();
    echo "<p>Client update: " . ($result ? "✓ Success" : "✗ Failed") . " (Rows: " . $stmt->rowCount() . ")</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Show after state
echo "<h3>After Fix:</h3>";
$stmt = $pdo->query("SELECT id, email, role FROM users WHERE email LIKE '%@foreveryoung.com'");
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Email</th><th>Role</th></tr>";
while ($row = $stmt->fetch()) {
    $color = empty($row['role']) ? 'red' : 'green';
    echo "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td style='color: {$color}; font-weight: bold;'>" . ($row['role'] ?: 'STILL EMPTY!') . "</td></tr>";
}
echo "</table>";

echo "<div style='background: #fff3cd; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
echo "<p><strong>Next:</strong> <a href='auth/logout.php'>Logout</a> then <a href='auth/login.php'>Login</a> again</p>";
echo "</div>";
?>
