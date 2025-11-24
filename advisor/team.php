<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get advisor info
$stmt = $pdo->prepare("SELECT referral_code, advisor_rank, total_sales FROM users WHERE id = ?");
$stmt->execute([$advisor_id]);
$advisor = $stmt->fetch();

// Get L2 team (direct recruits)
$stmt = $pdo->prepare("
    SELECT u.*, 
           (SELECT COUNT(*) FROM users WHERE upline_id = u.id) as downline_count,
           (SELECT COUNT(*) FROM bookings WHERE advisor_id = u.id AND status IN ('confirmed','completed')) as total_bookings,
           (SELECT COALESCE(SUM(total_amount), 0) FROM bookings WHERE advisor_id = u.id AND status IN ('confirmed','completed')) as total_sales
    FROM users u 
    WHERE u.upline_id = ? AND u.role = 'advisor'
    ORDER BY u.created_at DESC
");
$stmt->execute([$advisor_id]);
$l2_team = $stmt->fetchAll();

// Get L3 team (recruits of L2)
$stmt = $pdo->prepare("
    SELECT u.*, u2.first_name as recruiter_name,
           (SELECT COUNT(*) FROM bookings WHERE advisor_id = u.id AND status IN ('confirmed','completed')) as total_bookings,
           (SELECT COALESCE(SUM(total_amount), 0) FROM bookings WHERE advisor_id = u.id AND status IN ('confirmed','completed')) as total_sales
    FROM users u
    JOIN users u2 ON u.upline_id = u2.id
    WHERE u2.upline_id = ? AND u.role = 'advisor'
    ORDER BY u.created_at DESC
");
$stmt->execute([$advisor_id]);
$l3_team = $stmt->fetchAll();

$l2_count = count($l2_team);
$l3_count = count($l3_team);
$total_team_sales = array_sum(array_column($l2_team, 'total_sales')) + array_sum(array_column($l3_team, 'total_sales'));

// Get commission earnings
$stmt = $pdo->prepare("SELECT commission_type, SUM(commission_amount) as total FROM commissions WHERE user_id = ? AND status = 'approved' GROUP BY commission_type");
$stmt->execute([$advisor_id]);
$commissions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$page_title = 'My Team';
$page_subtitle = 'Build and manage your advisor network';

include 'includes/advisor-header.php';
?>

<!-- Referral Section -->
<div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-2">Your Referral Code</h2>
            <p class="text-blue-100 mb-4">Share this code to build your team and earn L2/L3 commissions</p>
            <div class="flex items-center gap-4">
                <div class="bg-white/20 px-6 py-3 rounded-lg">
                    <span class="text-3xl font-bold tracking-wider"><?php echo $advisor['referral_code']; ?></span>
                </div>
                <button onclick="copyReferralCode('<?php echo $advisor['referral_code']; ?>')" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50">
                    <i class="fas fa-copy mr-2"></i>Copy Code
                </button>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm text-blue-100 mb-1">Your Rank</p>
            <span class="bg-yellow-400 text-slate-900 px-4 py-2 rounded-lg font-bold text-lg"><?php echo strtoupper($advisor['advisor_rank']); ?></span>
        </div>
    </div>
</div>

<!-- Commission Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Direct Sales (L1)</p>
        <p class="text-2xl font-bold text-green-600">$<?php echo number_format($commissions['direct'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Level 2 Override</p>
        <p class="text-2xl font-bold text-blue-600">$<?php echo number_format($commissions['level2'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Level 3 Override</p>
        <p class="text-2xl font-bold text-purple-600">$<?php echo number_format($commissions['level3'] ?? 0); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Total Team Sales</p>
        <p class="text-2xl font-bold text-slate-900">$<?php echo number_format($total_team_sales); ?></p>
    </div>
</div>

<!-- Team Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">Level 2 Team</h3>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold"><?php echo $l2_count; ?></span>
        </div>
        <p class="text-sm text-slate-600">Direct recruits - Earn 10% override</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">Level 3 Team</h3>
            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-bold"><?php echo $l3_count; ?></span>
        </div>
        <p class="text-sm text-slate-600">L2 recruits - Earn 5% override</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">Total Network</h3>
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold"><?php echo $l2_count + $l3_count; ?></span>
        </div>
        <p class="text-sm text-slate-600">Complete team size</p>
    </div>
</div>

<!-- L2 Team Members -->
<?php if (!empty($l2_team)): ?>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-900">Level 2 Team (Direct Recruits)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Advisor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Downline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Your Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($l2_team as $member): ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></p>
                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars($member['email']); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $member['downline_count']; ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $member['total_bookings']; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">$<?php echo number_format($member['total_sales']); ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-blue-600">$<?php echo number_format($member['total_sales'] * 0.10); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            <?php echo ucfirst($member['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- L3 Team Members -->
<?php if (!empty($l3_team)): ?>
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-900">Level 3 Team (Indirect Recruits)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Advisor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Recruited By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Your Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($l3_team as $member): ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></p>
                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars($member['email']); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo htmlspecialchars($member['recruiter_name']); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $member['total_bookings']; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">$<?php echo number_format($member['total_sales']); ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-purple-600">$<?php echo number_format($member['total_sales'] * 0.05); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            <?php echo ucfirst($member['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (empty($l2_team) && empty($l3_team)): ?>
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
    <i class="fas fa-users text-6xl text-slate-300 mb-4"></i>
    <h3 class="text-xl font-bold text-slate-900 mb-2">No Team Members Yet</h3>
    <p class="text-slate-600 mb-6">Start building your team by sharing your referral code</p>
    <button onclick="copyReferralCode('<?php echo $advisor['referral_code']; ?>')" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
        <i class="fas fa-copy mr-2"></i>Copy Referral Code
    </button>
</div>
<?php endif; ?>

<script>
function copyReferralCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert('✓ Referral code copied: ' + code);
    });
}
</script>

<?php include 'includes/advisor-footer.php'; ?>