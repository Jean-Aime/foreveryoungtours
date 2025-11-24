<?php
session_start();
require_once '../config/database.php';
require_once '../config/stripe.php';

$session_id = $_GET['session_id'] ?? '';
$tier_id = $_GET['tier_id'] ?? '';

if ($session_id && $tier_id) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        
        if ($session->payment_status === 'paid') {
            $stmt = $pdo->prepare("SELECT * FROM membership_tiers WHERE id = ?");
            $stmt->execute([$tier_id]);
            $tier = $stmt->fetch();
            
            $start_date = date('Y-m-d H:i:s');
            $expiry_date = date('Y-m-d H:i:s', strtotime('+' . $tier['duration_months'] . ' months'));
            
            $stmt = $pdo->prepare("INSERT INTO user_memberships (user_id, tier_id, start_date, expiry_date, status, payment_amount, stripe_payment_id) VALUES (?, ?, ?, ?, 'active', ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $tier_id, $start_date, $expiry_date, $session->amount_total / 100, $session->payment_intent]);
            
            $success = true;
            $tier_name = $tier['name'];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Activated - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
            <?php if (isset($success)): ?>
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-crown text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Welcome to <?php echo $tier_name; ?>!</h1>
                <p class="text-slate-600 mb-8">Your membership is now active. Start enjoying exclusive benefits on your next adventure.</p>
                <div class="space-y-3">
                    <a href="../client/index.php" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-3 rounded-lg">
                        Go to Dashboard
                    </a>
                    <a href="../client/tours.php" class="block w-full bg-slate-900 text-white font-bold py-3 rounded-lg">
                        Browse Tours
                    </a>
                </div>
            <?php else: ?>
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-times text-4xl text-red-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Payment Failed</h1>
                <p class="text-slate-600 mb-8">There was an issue processing your payment.</p>
                <a href="membership.php" class="block w-full bg-slate-900 text-white font-bold py-3 rounded-lg">
                    Try Again
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
