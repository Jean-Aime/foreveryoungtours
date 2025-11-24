<?php
require_once 'config.php';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/csrf.php';

checkAuth('advisor');

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle payout request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request_payout') {
    requireCsrf();
    
    $amount = floatval($_POST['amount']);
    $payment_method = $_POST['payment_method'];
    $payment_details = trim($_POST['payment_details']);
    
    // Get available balance
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount), 0) as total_earned, COALESCE(SUM(CASE WHEN pr.status = 'completed' THEN pr.amount ELSE 0 END), 0) as total_withdrawn FROM bookings b LEFT JOIN payout_requests pr ON b.advisor_id = pr.user_id WHERE b.advisor_id = ? AND b.status IN ('confirmed', 'completed')");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetch();
    $available = $balance['total_earned'] - $balance['total_withdrawn'];
    
    if ($amount < 50) {
        $error = 'Minimum payout amount is $50';
    } elseif ($amount > $available) {
        $error = 'Insufficient balance';
    } else {
        $stmt = $pdo->prepare("INSERT INTO payout_requests (user_id, amount, payment_method, payment_details, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $amount, $payment_method, $payment_details]);
        $success = 'Payout request submitted successfully';
    }
}

// Get commission stats
$stmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount), 0) as total_earned FROM bookings WHERE advisor_id = ? AND status IN ('confirmed', 'completed')");
$stmt->execute([$user_id]);
$total_earned = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM payout_requests WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$total_withdrawn = $stmt->fetchColumn();

$available_balance = $total_earned - $total_withdrawn;

// Get payout history
$stmt = $pdo->prepare("SELECT * FROM payout_requests WHERE user_id = ? ORDER BY requested_date DESC");
$stmt->execute([$user_id]);
$payouts = $stmt->fetchAll();

$page_title = 'Commission Payouts';
$page_subtitle = 'Request and track your earnings';

include 'includes/advisor-header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <p class="text-sm text-slate-600 mb-1">Total Earned</p>
        <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_earned, 2); ?></p>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <p class="text-sm text-slate-600 mb-1">Total Withdrawn</p>
        <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_withdrawn, 2); ?></p>
    </div>
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 shadow-sm border border-green-200">
        <p class="text-sm text-green-700 mb-1">Available Balance</p>
        <p class="text-3xl font-bold text-green-600">$<?php echo number_format($available_balance, 2); ?></p>
    </div>
</div>

<?php if ($success): ?>
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold mb-4">Request Payout</h3>
            <form method="POST">
                <?php echo getCsrfField(); ?>
                <input type="hidden" name="action" value="request_payout">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Amount (USD)</label>
                    <input type="number" name="amount" step="0.01" min="50" max="<?php echo $available_balance; ?>" required class="w-full px-4 py-2 border rounded-lg" placeholder="50.00">
                    <p class="text-xs text-slate-500 mt-1">Minimum: $50</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method</label>
                    <select name="payment_method" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Select method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="paypal">PayPal</option>
                        <option value="mobile_money">Mobile Money</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Payment Details</label>
                    <textarea name="payment_details" required rows="3" class="w-full px-4 py-2 border rounded-lg" placeholder="Account number, PayPal email, or mobile money number"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Request
                </button>
            </form>
        </div>
    </div>
    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Payout History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($payouts)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No payout requests yet</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($payouts as $payout): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm"><?php echo date('M j, Y', strtotime($payout['requested_date'])); ?></td>
                            <td class="px-6 py-4 text-sm font-semibold">$<?php echo number_format($payout['amount'], 2); ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo ucwords(str_replace('_', ' ', $payout['payment_method'])); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    <?php 
                                    echo match($payout['status']) {
                                        'completed' => 'bg-green-100 text-green-800',
                                        'approved', 'processing' => 'bg-blue-100 text-blue-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    ?>">
                                    <?php echo ucfirst($payout['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/advisor-footer.php'; ?>
