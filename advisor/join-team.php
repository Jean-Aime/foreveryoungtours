<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

$referral_code = $_GET['ref'] ?? '';
$error = '';
$success = '';

// Get referrer info
$referrer = null;
if ($referral_code) {
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, advisor_rank FROM users WHERE referral_code = ? AND role = 'advisor' AND status = 'active'");
    $stmt->execute([$referral_code]);
    $referrer = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ref_code = $_POST['referral_code'];
    
    // Verify referral code
    $stmt = $pdo->prepare("SELECT id, upline_id FROM users WHERE referral_code = ? AND role = 'advisor'");
    $stmt->execute([$ref_code]);
    $upline = $stmt->fetch();
    
    if (!$upline) {
        $error = 'Invalid referral code';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Generate new referral code
            $stmt = $pdo->prepare("SELECT MAX(id) as max_id FROM users");
            $stmt->execute();
            $max_id = $stmt->fetchColumn() + 1;
            $new_ref_code = 'ADV' . str_pad($max_id, 6, '0', STR_PAD_LEFT);
            
            // Insert new advisor (pending approval)
            $stmt = $pdo->prepare("INSERT INTO users (email, password, role, first_name, last_name, phone, upline_id, referral_code, referred_by_code, status) VALUES (?, ?, 'advisor', ?, ?, ?, ?, ?, ?, 'inactive')");
            $stmt->execute([$email, $password, $first_name, $last_name, $phone, $upline['id'], $new_ref_code, $ref_code]);
            
            $new_user_id = $pdo->lastInsertId();
            
            // Update team counts
            $stmt = $pdo->prepare("UPDATE users SET team_l2_count = team_l2_count + 1 WHERE id = ?");
            $stmt->execute([$upline['id']]);
            
            if ($upline['upline_id']) {
                $stmt = $pdo->prepare("UPDATE users SET team_l3_count = team_l3_count + 1 WHERE id = ?");
                $stmt->execute([$upline['upline_id']]);
            }
            
            $pdo->commit();
            $success = 'Registration successful! Your account is pending admin approval. You will be notified once approved.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Advisor Team - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-slate-100 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Join Our Advisor Network</h1>
                <p class="text-slate-600">Start earning commissions by selling African tours</p>
            </div>

            <?php if ($referrer): ?>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tie text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-100">You're joining the team of</p>
                        <h3 class="text-2xl font-bold"><?php echo htmlspecialchars($referrer['first_name'] . ' ' . $referrer['last_name']); ?></h3>
                        <span class="bg-yellow-400 text-slate-900 px-3 py-1 rounded-full text-xs font-bold"><?php echo strtoupper($referrer['advisor_rank']); ?> ADVISOR</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                            <input type="text" name="first_name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                        <input type="tel" name="phone" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" required minlength="6" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Referral Code</label>
                        <input type="text" name="referral_code" value="<?php echo htmlspecialchars($referral_code); ?>" required readonly class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-user-plus mr-2"></i>Join Team & Start Earning
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h4 class="font-bold text-slate-900 mb-3">Commission Structure:</h4>
                    <div class="space-y-2 text-sm text-slate-600">
                        <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Direct Sales: 30-40% commission</p>
                        <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Level 2 Team: 10% override</p>
                        <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Level 3 Team: 5% override</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
