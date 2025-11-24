<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=membership-checkout.php?tier=' . ($_GET['tier'] ?? ''));
    exit;
}

$tier_id = $_GET['tier'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM membership_tiers WHERE id = ? AND status = 'active'");
$stmt->execute([$tier_id]);
$tier = $stmt->fetch();

if (!$tier) {
    header('Location: membership.php');
    exit;
}

$benefits = json_decode($tier['benefits'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo $tier['name']; ?> Membership</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-6">Complete Your Membership</h1>
                
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 mb-6 border border-yellow-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900"><?php echo $tier['name']; ?> Membership</h2>
                            <p class="text-slate-600"><?php echo $tier['duration_months']; ?> months access</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($tier['price'], 2); ?></p>
                        </div>
                    </div>
                    
                    <div class="border-t border-yellow-200 pt-4">
                        <p class="font-semibold text-slate-900 mb-2">Included Benefits:</p>
                        <ul class="space-y-2">
                            <?php foreach ($benefits as $benefit): ?>
                            <li class="flex items-start text-sm">
                                <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                                <span class="text-slate-700"><?php echo $benefit; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <button id="checkout-button" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-4 rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($tier['price'], 2); ?> Securely
                </button>
                
                <p class="text-center text-sm text-slate-500 mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>Secure payment powered by Stripe
                </p>
            </div>
        </div>
    </div>

    <script>
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        
        document.getElementById('checkout-button').addEventListener('click', async () => {
            const button = document.getElementById('checkout-button');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            try {
                const response = await fetch('../api/create-membership-checkout.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({tier_id: <?php echo $tier_id; ?>})
                });
                
                const session = await response.json();
                
                if (session.error) {
                    alert(session.error);
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($tier['price'], 2); ?> Securely';
                    return;
                }
                
                const result = await stripe.redirectToCheckout({sessionId: session.id});
                
                if (result.error) {
                    alert(result.error.message);
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($tier['price'], 2); ?> Securely';
                }
            } catch (error) {
                alert('Payment failed. Please try again.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay $<?php echo number_format($tier['price'], 2); ?> Securely';
            }
        });
    </script>
</body>
</html>
