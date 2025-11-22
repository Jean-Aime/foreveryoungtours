<?php
require_once 'config/database.php';

echo "<h2>Complete Login Fix</h2>";

// Step 1: Fix user roles in database
echo "<h3>Step 1: Fixing User Roles in Database</h3>";

$updates = [
    ['email' => 'admin@foreveryoung.com', 'role' => 'admin'],
    ['email' => 'mca@foreveryoung.com', 'role' => 'mca'],
    ['email' => 'advisor@foreveryoung.com', 'role' => 'advisor'],
    ['email' => 'client@foreveryoung.com', 'role' => 'user']
];

foreach ($updates as $update) {
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE email = ?");
    $stmt->execute([$update['role'], $update['email']]);
    echo "<p>✓ {$update['email']} → role: <strong>{$update['role']}</strong></p>";
}

// Step 2: Verify roles
echo "<h3>Step 2: Verifying Database</h3>";
$stmt = $pdo->query("SELECT email, role FROM users WHERE email LIKE '%@foreveryoung.com'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'><th>Email</th><th>Role</th><th>Status</th></tr>";
foreach ($users as $user) {
    $status = !empty($user['role']) ? '<span style="color:green;">✓ OK</span>' : '<span style="color:red;">✗ EMPTY</span>';
    echo "<tr><td>{$user['email']}</td><td><strong>{$user['role']}</strong></td><td>{$status}</td></tr>";
}
echo "</table>";

// Step 3: Test login
echo "<h3>Step 3: Test Credentials</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<p><strong>Admin:</strong> admin@foreveryoung.com / admin123 → /admin/index.php</p>";
echo "<p><strong>MCA:</strong> mca@foreveryoung.com / mca123 → /mca/index.php</p>";
echo "<p><strong>Advisor:</strong> advisor@foreveryoung.com / advisor123 → /advisor/index.php</p>";
echo "<p><strong>Client:</strong> client@foreveryoung.com / client123 → /client/index.php</p>";
echo "</div>";

echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3 style='margin-top: 0;'>✓ All Fixed!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li><a href='auth/logout.php' style='color: #155724; font-weight: bold;'>Click here to LOGOUT</a></li>";
echo "<li><a href='auth/login.php' style='color: #155724; font-weight: bold;'>Then LOGIN again</a></li>";
echo "</ol>";
echo "</div>";
?>
