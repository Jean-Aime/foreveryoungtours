<?php
require_once 'config/database.php';

// Insert demo users
$users = [
    ['admin@foreveryoung.com', 'admin123', 'super_admin'],
    ['mca@foreveryoung.com', 'mca123', 'mca'],
    ['advisor@foreveryoung.com', 'advisor123', 'advisor'],
    ['client@foreveryoung.com', 'client123', 'client']
];

foreach ($users as $user) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->execute($user);
}

echo "Demo users created successfully!<br>";
echo "You can now login at: <a href='auth/login.php'>auth/login.php</a>";
?>