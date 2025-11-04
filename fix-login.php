<!DOCTYPE html>
<html>
<head>
    <title>Fix Login - Diagnostic Tool</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        button { background: #DAA520; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #B8860B; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>🔧 Login System Diagnostic & Fix Tool</h1>

<?php
require_once 'config/database.php';

echo "<div class='box'>";
echo "<h2>Step 1: Database Connection</h2>";
try {
    $pdo->query("SELECT 1");
    echo "<p class='success'>✓ Database connected successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/database.php</p>";
    exit;
}
echo "</div>";

echo "<div class='box'>";
echo "<h2>Step 2: Check Users Table</h2>";
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p class='success'>✓ Users table exists</p>";
    echo "<p><strong>Columns:</strong> " . implode(', ', $columns) . "</p>";
    
    $hasName = in_array('name', $columns);
    $hasFirstName = in_array('first_name', $columns);
    
    if ($hasFirstName) {
        echo "<p class='success'>✓ Table uses first_name/last_name structure</p>";
    } elseif ($hasName) {
        echo "<p class='success'>✓ Table uses name structure</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Users table error: " . $e->getMessage() . "</p>";
    exit;
}
echo "</div>";

echo "<div class='box'>";
echo "<h2>Step 3: Check Existing Users</h2>";
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$count = $stmt->fetch()['count'];
echo "<p>Total users in database: <strong>$count</strong></p>";

if ($count > 0) {
    echo "<h3>Existing Users:</h3>";
    $stmt = $pdo->query("SELECT id, email, role, " . ($hasFirstName ? "first_name, last_name" : "name") . " FROM users LIMIT 10");
    $users = $stmt->fetchAll();
    echo "<table>";
    echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th></tr>";
    foreach ($users as $user) {
        $name = $hasFirstName ? ($user['first_name'] . ' ' . $user['last_name']) : $user['name'];
        echo "<tr><td>{$user['id']}</td><td>{$user['email']}</td><td>$name</td><td><strong>{$user['role']}</strong></td></tr>";
    }
    echo "</table>";
}
echo "</div>";

// Auto-fix: Create test users if none exist
if (isset($_POST['create_users'])) {
    echo "<div class='box'>";
    echo "<h2>Step 4: Creating Test Users</h2>";
    
    $testUsers = [
        ['email' => 'admin@foreveryoung.com', 'password' => 'admin123', 'role' => 'admin', 'first' => 'Super', 'last' => 'Admin'],
        ['email' => 'mca@foreveryoung.com', 'password' => 'mca123', 'role' => 'mca', 'first' => 'MCA', 'last' => 'User'],
        ['email' => 'advisor@foreveryoung.com', 'password' => 'advisor123', 'role' => 'advisor', 'first' => 'Advisor', 'last' => 'User'],
        ['email' => 'client@foreveryoung.com', 'password' => 'client123', 'role' => 'user', 'first' => 'Client', 'last' => 'User']
    ];
    
    foreach ($testUsers as $user) {
        // Check if exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$user['email']]);
        
        if ($check->fetch()) {
            echo "<p class='warning'>⚠ User {$user['email']} already exists - skipping</p>";
            continue;
        }
        
        // Create user
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        
        if ($hasFirstName) {
            $sql = "INSERT INTO users (email, password, role, first_name, last_name, phone, status, email_verified, created_at) 
                    VALUES (?, ?, ?, ?, ?, '+1234567890', 'active', 1, NOW())";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$user['email'], $hash, $user['role'], $user['first'], $user['last']]);
        } else {
            $sql = "INSERT INTO users (email, password, role, name, phone, created_at) 
                    VALUES (?, ?, ?, ?, '+1234567890', NOW())";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$user['email'], $hash, $user['role'], $user['first'] . ' ' . $user['last']]);
        }
        
        if ($result) {
            echo "<p class='success'>✓ Created {$user['role']}: {$user['email']} / {$user['password']}</p>";
        } else {
            echo "<p class='error'>✗ Failed to create {$user['email']}</p>";
        }
    }
    
    echo "<p style='margin-top: 20px;'><a href='auth/login.php'><button>Go to Login Page</button></a></p>";
    echo "</div>";
}

// Test password verification
echo "<div class='box'>";
echo "<h2>Step 5: Password Verification Test</h2>";
$testPass = 'admin123';
$testHash = password_hash($testPass, PASSWORD_DEFAULT);
$verify = password_verify($testPass, $testHash);
echo "<p>Test password: <code>admin123</code></p>";
echo "<p>Verification: " . ($verify ? "<span class='success'>✓ Working</span>" : "<span class='error'>✗ Failed</span>") . "</p>";
echo "</div>";

if ($count == 0 || !isset($_POST['create_users'])) {
    echo "<div class='box'>";
    echo "<h2>Action Required</h2>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='create_users'>Create Test Users Now</button>";
    echo "</form>";
    echo "<p style='margin-top: 20px;'>This will create 4 test users:</p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@foreveryoung.com / admin123</li>";
    echo "<li><strong>MCA:</strong> mca@foreveryoung.com / mca123</li>";
    echo "<li><strong>Advisor:</strong> advisor@foreveryoung.com / advisor123</li>";
    echo "<li><strong>Client:</strong> client@foreveryoung.com / client123</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<div class='box'>";
echo "<h2>Quick Links</h2>";
echo "<p><a href='auth/login.php'><button>Go to Login Page</button></a></p>";
echo "<p><a href='test-login.php'><button>Run Test Page</button></a></p>";
echo "</div>";
?>

</body>
</html>
