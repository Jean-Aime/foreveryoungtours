<?php
require_once 'config/database.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $portalCode = trim($_POST['portal_code']);
    
    // Verify client credentials
    $stmt = $pdo->prepare("SELECT * FROM client_registry WHERE client_email = ? AND portal_code = ? AND ownership_status = 'active'");
    $stmt->execute([$email, $portalCode]);
    $client = $stmt->fetch();
    
    if ($client) {
        // Set client session
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_portal_code'] = $client['portal_code'];
        $_SESSION['client_name'] = $client['client_name'];
        $_SESSION['client_email'] = $client['client_email'];
        $_SESSION['is_client'] = true;
        
        // Log activity
        $pdo->prepare("INSERT INTO portal_activity (portal_code, activity_type, activity_data) VALUES (?, 'login', ?)")
            ->execute([$portalCode, json_encode(['email' => $email])]);
        
        header('Location: client-dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or portal code';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">🌍 Forever Young Tours</h1>
            <p class="text-slate-600">Client Portal Login</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Access Your Travel Portal</h2>
            
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" required
                           class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                           placeholder="your@email.com">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Portal Code</label>
                    <input type="text" name="portal_code" required
                           class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                           placeholder="JD-2025-001">
                    <p class="text-xs text-slate-500 mt-1">Check your email or WhatsApp for your portal code</p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i>Access My Portal
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600">
                    Don't have a portal code? 
                    <a href="pages/contact.php" class="text-blue-600 hover:text-blue-800 font-semibold">Contact Us</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
