<?php
$page_title = 'Pending Approvals';
$page_subtitle = 'Review New Registrations';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    
    try {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                $success = 'User approved successfully!';
            }
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                $success = 'User rejected!';
            }
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get pending advisors
$stmt = $pdo->query("
    SELECT u.*, 
           CONCAT(u2.first_name, ' ', u2.last_name) as recruiter_name, 
           u2.referral_code as recruiter_code
    FROM users u
    LEFT JOIN users u2 ON u.sponsor_id = u2.id
    WHERE u.role = 'advisor' AND u.status = 'inactive'
    ORDER BY u.created_at DESC
");
$pending_advisors = $stmt->fetchAll();

// Get pending MCAs
$stmt = $pdo->query("
    SELECT * FROM users 
    WHERE role = 'mca' AND status = 'inactive'
    ORDER BY created_at DESC
");
$pending_mcas = $stmt->fetchAll();

$current_page = 'pending-approvals';
require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Pending Approvals</h1>
            <p class="text-slate-600">Review and approve new advisor and MCA registrations</p>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Pending Advisors -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Pending Advisors (<?= count($pending_advisors) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pending_advisors)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-check text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No pending advisors</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Advisor</th>
                                <th>Contact</th>
                                <th>Recruited By</th>
                                <th>Referral Code</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_advisors as $advisor): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($advisor['email']) ?></small>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($advisor['phone'] ?: 'N/A') ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($advisor['country'] ?: 'N/A') ?></small>
                                </td>
                                <td>
                                    <?php if ($advisor['recruiter_name']): ?>
                                        <div><?= htmlspecialchars($advisor['recruiter_name']) ?></div>
                                        <span class="badge bg-secondary"><?= $advisor['recruiter_code'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Direct</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-primary"><?= $advisor['referral_code'] ?></span></td>
                                <td><?= date('M j, Y', strtotime($advisor['created_at'])) ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $advisor['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this advisor?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" onclick="return confirm('Reject this advisor?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pending MCAs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Pending MCAs (<?= count($pending_mcas) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pending_mcas)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-crown text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No pending MCAs</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>MCA</th>
                                <th>Contact</th>
                                <th>Country</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_mcas as $mca): ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($mca['first_name'] . ' ' . $mca['last_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($mca['email']) ?></small>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($mca['phone'] ?: 'N/A') ?></div>
                                </td>
                                <td><?= htmlspecialchars($mca['country'] ?: 'N/A') ?></td>
                                <td><?= date('M j, Y', strtotime($mca['created_at'])) ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $mca['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this MCA?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" onclick="return confirm('Reject this MCA?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once 'includes/admin-footer.php'; ?>
