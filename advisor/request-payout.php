<?php
$page_title = 'Request Payout';
$page_subtitle = 'Withdraw Your Commissions';
session_start();
require_once '../config/database.php';
require_once '../config/stripe-config.php';
require_once '../includes/csrf.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle payout request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    
    $amount = floatval($_POST['amount']);
    $method = $_POST['payout_method'];
    $account_details = $_POST['account_details'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $account_holder = $_POST['account_holder_name'] ?? '';
    
    if ($amount < MINIMUM_PAYOUT_AMOUNT) {
        $error = 'Minimum payout amount is $' . MINIMUM_PAYOUT_AMOUNT;
    } else {
        $stmt = $pdo->prepare("INSERT INTO payout_requests (user_id, amount, payout_method, account_details, bank_name, account_number, account_holder_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $amount, $method, $account_details, $bank_name, $account_number, $account_holder])) {
            $success = 'Payout request submitted successfully!';
        } else {
            $error = 'Failed to submit payout request.';
        }
    }
}

// Get available balance
$stmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount), 0) as total_earned FROM commissions WHERE user_id = ? AND status = 'approved'");
$stmt->execute([$user_id]);
$total_earned = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) as total_paid FROM payout_requests WHERE user_id = ? AND status IN ('completed', 'processing')");
$stmt->execute([$user_id]);
$total_paid = $stmt->fetchColumn();

$available_balance = $total_earned - $total_paid;

// Get payout history
$stmt = $pdo->prepare("SELECT * FROM payout_requests WHERE user_id = ? ORDER BY requested_at DESC LIMIT 10");
$stmt->execute([$user_id]);
$payouts = $stmt->fetchAll();

require_once 'includes/advisor-header.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Request Payout</h1>
            <p class="text-slate-600">Withdraw your earned commissions</p>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Available Balance</p>
                        <h3 class="mb-0 fw-bold text-success">$<?= number_format($available_balance, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Earned</p>
                        <h3 class="mb-0 fw-bold">$<?= number_format($total_earned, 2) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1 small">Total Paid</p>
                        <h3 class="mb-0 fw-bold text-primary">$<?= number_format($total_paid, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">New Payout Request</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?= csrfField() ?>
                            <div class="mb-3">
                                <label class="form-label">Amount *</label>
                                <input type="number" name="amount" step="0.01" min="<?= MINIMUM_PAYOUT_AMOUNT ?>" max="<?= $available_balance ?>" class="form-control" required>
                                <small class="text-muted">Minimum: $<?= MINIMUM_PAYOUT_AMOUNT ?> | Available: $<?= number_format($available_balance, 2) ?></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payout Method *</label>
                                <select name="payout_method" class="form-select" required onchange="togglePayoutFields(this.value)">
                                    <option value="">Select Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="mobile_money">Mobile Money</option>
                                </select>
                            </div>
                            <div id="bank_fields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="account_number" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" class="form-control">
                                </div>
                            </div>
                            <div id="other_fields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Account Details</label>
                                    <textarea name="account_details" class="form-control" rows="3" placeholder="Enter your PayPal email or Mobile Money number"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Payout History</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($payouts)): ?>
                        <div class="text-center py-4"><p class="text-muted">No payout requests yet</p></div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($payouts as $payout): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">$<?= number_format($payout['amount'], 2) ?></h6>
                                        <small class="text-muted"><?= ucfirst(str_replace('_', ' ', $payout['payout_method'])) ?></small><br>
                                        <small class="text-muted"><?= date('M d, Y', strtotime($payout['requested_at'])) ?></small>
                                    </div>
                                    <span class="badge bg-<?= match($payout['status']) {
                                        'completed' => 'success',
                                        'processing' => 'info',
                                        'approved' => 'primary',
                                        'rejected' => 'danger',
                                        default => 'warning'
                                    } ?>"><?= ucfirst($payout['status']) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePayoutFields(method) {
    document.getElementById('bank_fields').style.display = method === 'bank_transfer' ? 'block' : 'none';
    document.getElementById('other_fields').style.display = (method === 'paypal' || method === 'mobile_money') ? 'block' : 'none';
}
</script>

<?php require_once 'includes/advisor-footer.php'; ?>
