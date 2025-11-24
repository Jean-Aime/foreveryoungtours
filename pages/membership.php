<?php
session_start();
require_once '../config/database.php';

// Get membership tiers
$stmt = $pdo->query("SELECT * FROM membership_tiers WHERE status = 'active' ORDER BY price ASC");
$tiers = $stmt->fetchAll();

// Get user's current membership if logged in
$current_membership = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT um.*, mt.name as tier_name, mt.discount_percentage FROM user_memberships um JOIN membership_tiers mt ON um.tier_id = mt.id WHERE um.user_id = ? AND um.status = 'active' AND um.expiry_date > NOW() ORDER BY um.expiry_date DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $current_membership = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-slate-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 mb-4">Travel Club Membership</h1>
                <p class="text-xl text-slate-600">Unlock exclusive benefits and save on every adventure</p>
            </div>

            <?php if ($current_membership): ?>
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6 mb-8 max-w-2xl mx-auto">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600">Current Membership</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo $current_membership['tier_name']; ?></p>
                        <p class="text-sm text-slate-600">Expires: <?php echo date('M j, Y', strtotime($current_membership['expiry_date'])); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-green-600"><?php echo $current_membership['discount_percentage']; ?>%</p>
                        <p class="text-sm text-slate-600">Discount</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($tiers as $index => $tier): ?>
                <?php 
                $benefits = json_decode($tier['benefits'], true);
                $is_popular = $tier['slug'] === 'gold';
                ?>
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden <?php echo $is_popular ? 'ring-4 ring-yellow-400 transform scale-105' : ''; ?>">
                    <?php if ($is_popular): ?>
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-center py-2 font-bold text-sm">
                        MOST POPULAR
                    </div>
                    <?php endif; ?>
                    
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo $tier['name']; ?></h3>
                        <div class="mb-6">
                            <span class="text-5xl font-bold text-slate-900">$<?php echo number_format($tier['price']); ?></span>
                            <span class="text-slate-600">/year</span>
                        </div>
                        
                        <ul class="space-y-3 mb-8">
                            <?php foreach ($benefits as $benefit): ?>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 mr-3 mt-1"></i>
                                <span class="text-slate-700"><?php echo $benefit; ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <a href="membership-checkout.php?tier=<?php echo $tier['id']; ?>" 
                           class="block w-full text-center py-3 rounded-lg font-bold transition-all
                           <?php echo $is_popular 
                               ? 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white hover:shadow-lg' 
                               : 'bg-slate-900 text-white hover:bg-slate-800'; ?>">
                            <?php echo $current_membership && $current_membership['tier_id'] == $tier['id'] ? 'Renew' : 'Get Started'; ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-16 bg-white rounded-2xl shadow-lg p-8 max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 text-center">Membership Benefits Comparison</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Feature</th>
                                <?php foreach ($tiers as $tier): ?>
                                <th class="text-center py-3 px-4"><?php echo $tier['name']; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="py-3 px-4">Tour Discount</td>
                                <?php foreach ($tiers as $tier): ?>
                                <td class="text-center py-3 px-4 font-semibold"><?php echo $tier['discount_percentage']; ?>%</td>
                                <?php endforeach; ?>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Priority Booking</td>
                                <?php foreach ($tiers as $tier): ?>
                                <td class="text-center py-3 px-4"><?php echo $tier['priority_booking'] ? '<i class="fas fa-check text-green-600"></i>' : '<i class="fas fa-times text-red-400"></i>'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4">Free Cancellations</td>
                                <?php foreach ($tiers as $tier): ?>
                                <td class="text-center py-3 px-4 font-semibold"><?php echo $tier['free_cancellations'] > 100 ? 'Unlimited' : $tier['free_cancellations'] . '/year'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
