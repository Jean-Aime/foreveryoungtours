<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $action = $_POST['action'];
    
    $db = getDB();
    
    if ($action == 'signin') {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND role = 'user'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: ../pages/dashboard.php');
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else if ($action == 'signup') {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')");
            
            if ($stmt->execute([$name, $email, $hashed_password, $phone])) {
                $user_id = $db->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = 'user';
                header('Location: ../pages/dashboard.php');
                exit();
            } else {
                $error = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - iForYoungTours</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-yellow-50 to-orange-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Welcome to iForYoungTours</h2>
                <p class="mt-2 text-gray-600">Sign in to book your African adventure</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div id="signinForm" class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST">
                    <input type="hidden" name="action" value="signin">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <button type="submit" class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 font-medium">
                            Sign In
                        </button>
                    </div>
                </form>
                <p class="mt-4 text-center text-sm text-gray-600">
                    Don't have an account? 
                    <button onclick="toggleForm()" class="text-yellow-600 hover:text-yellow-500 font-medium">Sign up</button>
                </p>
            </div>

            <div id="signupForm" class="bg-white rounded-lg shadow-lg p-8 hidden">
                <form method="POST">
                    <input type="hidden" name="action" value="signup">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="tel" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <button type="submit" class="w-full bg-yellow-600 text-white py-2 px-4 rounded-md hover:bg-yellow-700 font-medium">
                            Create Account
                        </button>
                    </div>
                </form>
                <p class="mt-4 text-center text-sm text-gray-600">
                    Already have an account? 
                    <button onclick="toggleForm()" class="text-yellow-600 hover:text-yellow-500 font-medium">Sign in</button>
                </p>
            </div>

            <div class="text-center">
                <a href="../index.php" class="text-yellow-600 hover:text-yellow-500 font-medium">← Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const signinForm = document.getElementById('signinForm');
            const signupForm = document.getElementById('signupForm');
            
            if (signinForm.classList.contains('hidden')) {
                signinForm.classList.remove('hidden');
                signupForm.classList.add('hidden');
            } else {
                signinForm.classList.add('hidden');
                signupForm.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>