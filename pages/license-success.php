<?php
session_start();
require_once '../config/database.php';
require_once '../config/stripe.php';

$session_id = $_GET['session_id'] ?? '';
$license_id = $_GET['license_id'] ?? '';

if ($session_id && $license_id) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        
        if ($session->payment_status === 'paid') {
            // Update license fee record
            $stmt = $pdo->prepare("UPDATE license_fees SET payment_status = 'paid', payment_method = 'stripe', stripe_payment_id = ?, paid_date = NOW() WHERE id = ?");
            $stmt->execute([$session->payment_intent, $license_id]);
            
            // Update user license status
            $expiry_date = date('Y-m-d H:i:s', strtotime('+12 months'));
            $stmt = $pdo->prepare("UPDATE users SET license_status = 'active', license_expiry_date = ?, license_paid_amount = ? WHERE id = ?");
            $stmt->execute([$expiry_date, $session->amount_total / 100, $_SESSION['user_id']]);
            
            $success = true;
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
    <title>License Activated - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
            <?php if (isset($success)): ?>
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-4xl text-green-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-4">License Activated!</h1>
                <p class="text-slate-600 mb-8">Your professional license is now active for 12 months.</p>
                <a href="../<?php echo $_SESSION['user_role']; ?>/index.php" class="btn-primary px-8 py-3 rounded-lg inline-block">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-times text-4xl text-red-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Payment Failed</h1>
                <p class="text-slate-600 mb-8">There was an issue processing your payment.</p>
                <a href="license-payment.php" class="btn-primary px-8 py-3 rounded-lg inline-block">
                    Try Again
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
