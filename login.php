<?php
session_start();
require_once 'config/database.php';
require_once 'subdomain-handler.php';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role and subdomain
        if ($user['role'] === 'admin') {
            if (defined('CURRENT_COUNTRY_CODE')) {
                header('Location: /admin/');
            } elseif (defined('CURRENT_CONTINENT')) {
                header('Location: /admin/');
            } else {
                header('Location: /admin/');
            }
        } else {
            header('Location: /client-dashboard.php');
        }
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <?php if (defined('CURRENT_COUNTRY_NAME')): ?>
                <h1 class="text-2xl font-bold"><?= CURRENT_COUNTRY_NAME ?> Login</h1>
                <?php elseif (defined('CURRENT_CONTINENT')): ?>
                <h1 class="text-2xl font-bold"><?= CURRENT_CONTINENT ?> Login</h1>
                <?php else: ?>
                <h1 class="text-2xl font-bold">Login</h1>
                <?php endif; ?>
            </div>

            <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="/register.php" class="text-blue-600 hover:text-blue-800">Register here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>