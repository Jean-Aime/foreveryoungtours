<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $redirect = match($_SESSION['user_role']) {
        'super_admin', 'admin' => '../admin/index.php',
        'mca' => '../mca/index.php',
        'advisor' => '../advisor/index.php',
        'client' => '../client/index.php',
        default => '../index.php'
    };
    header('Location: ' . $redirect);
    exit;
}

require_once '../config/database.php';

$error = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        try {
            // Check if users table exists and has data
            $check = $pdo->query("SELECT COUNT(*) as count FROM users");
            $userCount = $check->fetch(PDO::FETCH_ASSOC);
            
            if ($userCount['count'] == 0) {
                $error = 'No users in database. <a href="../create-test-users.php" style="color: #DAA520; text-decoration: underline; font-weight: bold;">Click here to create test users</a>';
            } else {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$user) {
                    $error = 'No account found with email: ' . htmlspecialchars($email) . '. <a href="../create-test-users.php" style="color: #DAA520; text-decoration: underline;">Create test users</a>';
                } elseif (!password_verify($password, $user['password'])) {
                    $error = 'Incorrect password for ' . htmlspecialchars($email);
                } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'] ?? ($user['first_name'] . ' ' . $user['last_name']);
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'] ?? $user['name'];
                $_SESSION['last_name'] = $user['last_name'] ?? '';
                
                // Role-based redirect
                switch ($user['role']) {
                    case 'super_admin':
                    case 'admin':
                        header('Location: ../admin/index.php');
                        break;
                    case 'mca':
                        header('Location: ../mca/index.php');
                        break;
                    case 'advisor':
                        header('Location: ../advisor/index.php');
                        break;
                    case 'user':
                    default:
                        header('Location: ../client/index.php');
                        break;
                }
                    exit();
                }
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage() . ' <br><small>Make sure your database is running and configured correctly.</small>';
        }
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
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { overflow: hidden; }
        .form-container { height: 100vh; }
        .image-container { height: 100vh; }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 form-container bg-white flex flex-col">
            <!-- Logo in Top Left -->
            <div class="p-6">
                <a href="../index.php" class="inline-block hover:opacity-80 transition-opacity">
                    <h1 class="text-2xl font-bold text-gradient">iForYoungTours</h1>
                </a>
            </div>
            
            <!-- Form Content -->
            <div class="flex-1 flex items-center justify-center px-8">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h2>
                        <p class="text-slate-600">Sign in to your account</p>
                    </div>

                    <!-- Error Message -->
                    <?php if ($error): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <div class="nextcloud-card p-8">
                        <form method="POST" class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Email Address
                                </label>
                                <input type="email" name="email" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="john@example.com">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Password
                                </label>
                                <input type="password" name="password" required 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="Enter your password">
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2 accent-golden-600">
                                    <span class="text-sm text-slate-600">Remember me</span>
                                </label>
                                <a href="#" class="text-sm text-golden-600 hover:text-golden-700 font-medium">
                                    Forgot password?
                                </a>
                            </div>

                            <button type="submit" class="w-full btn-primary text-black px-6 py-3 rounded-lg font-semibold text-lg">
                                Sign In
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-slate-600">
                                Don't have an account? 
                                <a href="register.php" class="text-golden-600 hover:text-golden-700 font-semibold">Create Account</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Tourism Image -->
        <div class="hidden lg:block lg:w-1/2 image-container relative">
            <img src="https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?w=1200" 
                 alt="African Wildlife" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                <h2 class="text-4xl font-bold mb-4">Your African Adventure Awaits</h2>
                <p class="text-xl opacity-90">Experience the magic of Africa with expertly curated tours and unforgettable memories.</p>
            </div>
        </div>
    </div>
</body>
</html>
