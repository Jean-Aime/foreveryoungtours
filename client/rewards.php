<?php
session_start();
require_once '../config/database.php';

$page_title = 'Rewards';
$page_subtitle = 'Your Loyalty Points & Benefits';

$client_id = $_SESSION['user_id'];

// Calculate points based on bookings
$stmt = $pdo->prepare("SELECT COUNT(*) as total_bookings, SUM(total_price) as total_spent FROM bookings WHERE user_id = ? AND status IN ('confirmed', 'completed')");
$stmt->execute([$client_id]);
$stats = $stmt->fetch();

$points = ($stats['total_spent'] ?? 0) * 0.1; // 10% back as points
$tier = $points < 500 ? 'Bronze' : ($points < 1000 ? 'Silver' : 'Gold');

include 'includes/client-header.php';
?>

<div class="nextcloud-card p-8 mb-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 mb-2">Your Rewards</h2>
            <p class="text-slate-600">Earn points with every booking</p>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-primary-gold"><?php echo number_format($points); ?></div>
            <div class="text-sm text-slate-600">Points Available</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-600 mb-1">Current Tier</p>
                <h3 class="text-2xl font-bold text-primary-gold"><?php echo $tier; ?> Member</h3>
            </div>
            <i class="fas fa-trophy text-5xl text-primary-gold"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="nextcloud-card p-6 text-center">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-star text-yellow-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Bronze Tier</h3>
        <p class="text-slate-600 text-sm mb-4">0-499 points</p>
        <ul class="text-left text-sm text-slate-600 space-y-2">
            <li>✓ 5% discount on tours</li>
            <li>✓ Priority support</li>
            <li>✓ Birthday bonus</li>
        </ul>
    </div>

    <div class="nextcloud-card p-6 text-center border-2 border-primary-gold">
        <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-gem text-slate-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Silver Tier</h3>
        <p class="text-slate-600 text-sm mb-4">500-999 points</p>
        <ul class="text-left text-sm text-slate-600 space-y-2">
            <li>✓ 10% discount on tours</li>
            <li>✓ Free airport transfer</li>
            <li>✓ Exclusive deals</li>
        </ul>
    </div>

    <div class="nextcloud-card p-6 text-center">
        <div class="w-16 h-16 bg-golden-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-crown text-golden-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Gold Tier</h3>
        <p class="text-slate-600 text-sm mb-4">1000+ points</p>
        <ul class="text-left text-sm text-slate-600 space-y-2">
            <li>✓ 15% discount on tours</li>
            <li>✓ VIP concierge service</li>
            <li>✓ Room upgrades</li>
        </ul>
    </div>
</div>

<?php include 'includes/client-footer.php'; ?>
