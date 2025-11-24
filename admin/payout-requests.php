<?php
$page_title = 'Payout Requests';
$page_subtitle = 'Manage Commission Payouts';
session_start();
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    
    $payout_id = intval($_POST['payout_id']);
    $action = $_POST['action'];
    $admin_id = $_SESSION['user_id'];
    
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE payout_requests SET status = 'approved', processed_by = ?, processed_at = NOW() WHERE id = ?");
        $stmt->execute([$admin_id, $payout_id]);
        $success = 'Payout approved!';
    } elseif ($action === 'complete') {
        $ref = $_POST['transaction_reference'] ?? '';
        $stmt = $pdo->prepare("UPDATE payout_requests SET status = 'completed', transaction_reference = ?, processed_by = ?, processed_at = NOW() WHERE id = ?");
        $stmt->execute([$ref, $admin_id, $payout_id]);
        $success = 'Payout marked as completed!';
    } elseif ($action === 'reject') {
        $reason = $_POST['rejection_reason'] ?? '';
        $stmt = $pdo->prepare("UPDATE payout_requests SET status = 'rejected', rejection_reason = ?, processed_by = ?, processed_at = NOW() WHERE id = ?");
        $stmt->execute([$reason, $admin_id, $payout_id]);
        $success = 'Payout rejected!';
    }
}

$stmt = $pdo->query("SELECT pr.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email FROM payout_requests pr JOIN users u ON pr.user_id = u.id ORDER BY pr.requested_at DESC");
$payouts = $stmt->fetchAll();

$current_page = 'payout-requests';
require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Payout Requests</h1>
            <p class="text-slate-600">Manage advisor commission payouts</p>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Advisor</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Account Details</th>
                                <th>Requested</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payouts as $payout): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($payout['user_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($payout['email']) ?></small>
                                </td>
                                <td class="fw-bold">$<?= number_format($payout['amount'], 2) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $payout['payout_method'])) ?></td>
                                <td>
                                    <?php if ($payout['payout_method'] === 'bank_transfer'): ?>
                                    <small><?= htmlspecialchars($payout['bank_name']) ?><br><?= htmlspecialchars($payout['account_number']) ?></small>
                                    <?php else: ?>
                                    <small><?= htmlspecialchars($payout['account_details']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($payout['requested_at'])) ?></td>
                                <td><span class="badge bg-<?= match($payout['status']) {
                                    'completed' => 'success',
                                    'processing' => 'info',
                                    'approved' => 'primary',
                                    'rejected' => 'danger',
                                    default => 'warning'
                                } ?>"><?= ucfirst($payout['status']) ?></span></td>
                                <td>
                                    <?php if ($payout['status'] === 'pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="payout_id" value="<?= $payout['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" onclick="return confirm('Approve this payout?')"><i class="fas fa-check"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="rejectPayout(<?= $payout['id'] ?>)"><i class="fas fa-times"></i></button>
                                    </form>
                                    <?php elseif ($payout['status'] === 'approved'): ?>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="completePayout(<?= $payout['id'] ?>)">Mark Completed</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <?= csrfField() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Reject Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="payout_id" id="reject_payout_id">
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <?= csrfField() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Complete Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="payout_id" id="complete_payout_id">
                    <input type="hidden" name="action" value="complete">
                    <div class="mb-3">
                        <label class="form-label">Transaction Reference</label>
                        <input type="text" name="transaction_reference" class="form-control" placeholder="Enter transaction ID or reference">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Mark as Completed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function rejectPayout(id) {
    document.getElementById('reject_payout_id').value = id;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
function completePayout(id) {
    document.getElementById('complete_payout_id').value = id;
    new bootstrap.Modal(document.getElementById('completeModal')).show();
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
