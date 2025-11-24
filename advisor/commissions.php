<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get commission summary
$stmt = $pdo->prepare("
    SELECT 
        commission_type,
        COUNT(*) as count,
        SUM(commission_amount) as total,
        SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as paid,
        SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END) as pending
    FROM commissions 
    WHERE user_id = ?
    GROUP BY commission_type
");
$stmt->execute([$advisor_id]);
$summary = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

// Get recent commissions
$stmt = $pdo->prepare("
    SELECT c.*, b.booking_reference, b.customer_name, b.total_amount, t.name as tour_name
    FROM commissions c
    JOIN bookings b ON c.booking_id = b.id
    JOIN tours t ON b.tour_id = t.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
    LIMIT 50
");
$stmt->execute([$advisor_id]);
$commissions = $stmt->fetchAll();

$total_earned = array_sum(array_column($summary, 'total'));
$total_paid = array_sum(array_column($summary, 'paid'));
$total_pending = array_sum(array_column($summary, 'pending'));

$page_title = 'Commissions';
$page_subtitle = 'Track your earnings from direct sales and team overrides';

include 'includes/advisor-header.php';
?>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Total Earned</p>
        <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_earned, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Paid Out</p>
        <p class="text-3xl font-bold text-green-600">$<?php echo number_format($total_paid, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Pending</p>
        <p class="text-3xl font-bold text-yellow-600">$<?php echo number_format($total_pending, 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Available</p>
        <p class="text-3xl font-bold text-blue-600">$<?php echo number_format($total_pending, 2); ?></p>
    </div>
</div>

<!-- Commission Breakdown -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900">Direct Sales (L1)</h3>
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">30-40%</span>
        </div>
        <p class="text-2xl font-bold text-green-600 mb-2">$<?php echo number_format($summary['direct']['total'] ?? 0, 2); ?></p>
        <p class="text-sm text-slate-600"><?php echo $summary['direct']['count'] ?? 0; ?> transactions</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900">Level 2 Override</h3>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">10%</span>
        </div>
        <p class="text-2xl font-bold text-blue-600 mb-2">$<?php echo number_format($summary['level2']['total'] ?? 0, 2); ?></p>
        <p class="text-sm text-slate-600"><?php echo $summary['level2']['count'] ?? 0; ?> transactions</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900">Level 3 Override</h3>
            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-bold">5%</span>
        </div>
        <p class="text-2xl font-bold text-purple-600 mb-2">$<?php echo number_format($summary['level3']['total'] ?? 0, 2); ?></p>
        <p class="text-sm text-slate-600"><?php echo $summary['level3']['count'] ?? 0; ?> transactions</p>
    </div>
</div>

<!-- Commission History -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-900">Commission History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sale Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($commissions as $comm): ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo date('M j, Y', strtotime($comm['created_at'])); ?></td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo $comm['booking_reference']; ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo htmlspecialchars($comm['tour_name']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php 
                            echo match($comm['commission_type']) {
                                'direct' => 'bg-green-100 text-green-700',
                                'level2' => 'bg-blue-100 text-blue-700',
                                'level3' => 'bg-purple-100 text-purple-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        ?>">
                            <?php echo strtoupper(str_replace('_', ' ', $comm['commission_type'])); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700">$<?php echo number_format($comm['total_amount'], 2); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $comm['commission_rate']; ?>%</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-900">$<?php echo number_format($comm['commission_amount'], 2); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php 
                            echo match($comm['status']) {
                                'paid' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        ?>">
                            <?php echo ucfirst($comm['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/advisor-footer.php'; ?>
