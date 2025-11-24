<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';

// Check if user is advisor or mca
if (!in_array($_SESSION['user_role'] ?? '', ['advisor', 'mca'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Get license info
$stmt = $pdo->prepare("SELECT license_status, license_expiry_date, license_paid_amount FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Set license amount based on role
$license_amount = $user_role === 'mca' ? 959 : 59;
$license_name = $user_role === 'mca' ? 'MCA License' : 'Advisor License';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Payment - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-certificate text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2"><?php echo $license_name; ?></h1>
                <p class="text-slate-600">Activate your professional license</p>
            </div>

            <?php if ($user['license_status'] === 'active' && strtotime($user['license_expiry_date']) > time()): ?>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800 font-semibold"><i class="fas fa-check-circle mr-2"></i>License Active</p>
                    <p class="text-sm text-green-700 mt-1">Expires: <?php echo date('M j, Y', strtotime($user['license_expiry_date'])); ?></p>
                </div>
                <a href="../<?php echo $user_role; ?>/index.php" class="btn-primary w-full text-center py-3 rounded-lg">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 mb-6 border border-yellow-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-700 font-medium">License Fee</span>
                        <span class="text-3xl font-bold text-slate-900">$<?php echo number_format($license_amount, 2); ?></span>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>12 months validity</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Full platform access</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Commission earnings</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Training & support</li>
                    </ul>
                </div>

                <button id="checkout-button" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-4 rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($license_amount, 2); ?> Now
                </button>

                <p class="text-xs text-center text-slate-500 mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>Secure payment powered by Stripe
                </p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        
        document.getElementById('checkout-button')?.addEventListener('click', async () => {
            const button = document.getElementById('checkout-button');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            try {
                const response = await fetch('../api/create-license-checkout.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        license_type: '<?php echo $user_role; ?>',
                        amount: <?php echo $license_amount; ?>
                    })
                });
                
                const session = await response.json();
                
                if (session.error) {
                    alert(session.error);
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($license_amount, 2); ?> Now';
                    return;
                }
                
                const result = await stripe.redirectToCheckout({sessionId: session.id});
                
                if (result.error) {
                    alert(result.error.message);
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($license_amount, 2); ?> Now';
                }
            } catch (error) {
                alert('Payment failed. Please try again.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($license_amount, 2); ?> Now';
            }
        });
    </script>
</body>
</html>
