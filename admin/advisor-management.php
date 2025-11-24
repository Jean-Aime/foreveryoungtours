<?php
$page_title = 'Advisor Management';
$page_subtitle = 'Manage Travel Advisors';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

// Handle advisor operations
if ($_POST) {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'approve_kyc':
                    $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
                    $stmt->execute([$_POST['user_id']]);
                    $success = 'Advisor approved successfully!';
                    break;
                case 'reject_kyc':
                    $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
                    $stmt->execute([$_POST['user_id']]);
                    $success = 'Advisor rejected!';
                    break;
                case 'update_rank':
                    $stmt = $pdo->prepare("UPDATE users SET advisor_rank = ? WHERE id = ?");
                    $stmt->execute([$_POST['rank'], $_POST['user_id']]);
                    $success = 'Advisor rank updated!';
                    break;
                case 'toggle_status':
                    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
                    $stmt->execute([$_POST['status'], $_POST['user_id']]);
                    $success = 'Status updated successfully!';
                    break;
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

// Get advisors with their stats
$stmt = $pdo->prepare("SELECT u.*,
                              CONCAT(s.first_name, ' ', s.last_name) as sponsor_name,
                              (SELECT COUNT(*) FROM users WHERE sponsor_id = u.id) as team_count,
                              (SELECT COUNT(*) FROM bookings WHERE advisor_id = u.id AND status = 'confirmed') as total_bookings,
                              (SELECT COALESCE(SUM(total_amount), 0) FROM bookings WHERE advisor_id = u.id AND status = 'confirmed') as total_sales
                       FROM users u
                       LEFT JOIN users s ON u.sponsor_id = s.id
                       WHERE u.role = 'advisor'
                       ORDER BY u.created_at DESC");
$stmt->execute();
$advisors = $stmt->fetchAll();

// Get statistics
$stats = $pdo->query("SELECT
    COUNT(*) as total_advisors,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN advisor_rank = 'certified' THEN 1 ELSE 0 END) as certified,
    SUM(CASE WHEN advisor_rank = 'senior' THEN 1 ELSE 0 END) as senior,
    SUM(CASE WHEN advisor_rank = 'executive' THEN 1 ELSE 0 END) as executive
    FROM users WHERE role = 'advisor'")->fetch();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Advisor Management</h1>
            <p class="text-slate-600">Manage travel advisors, ranks, and KYC approvals</p>
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

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Advisors</p>
                                <h3 class="mb-0 fw-bold"><?= number_format($stats['total_advisors']) ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-user-tie text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Active</p>
                                <h3 class="mb-0 fw-bold text-success"><?= number_format($stats['active']) ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Pending KYC</p>
                                <h3 class="mb-0 fw-bold text-warning"><?= number_format($stats['pending']) ?></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Executive Rank</p>
                                <h3 class="mb-0 fw-bold text-info"><?= number_format($stats['executive']) ?></h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-crown text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advisors Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">All Advisors</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($advisors)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-tie text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No advisors found</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Advisor</th>
                                <th>Contact</th>
                                <th>Rank</th>
                                <th>Team</th>
                                <th>Sales</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($advisors as $advisor): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <span class="fw-bold text-warning"><?= strtoupper(substr($advisor['first_name'] ?: 'A', 0, 1)) ?></span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars(trim($advisor['first_name'] . ' ' . $advisor['last_name']) ?: 'N/A') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($advisor['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($advisor['phone'] ?: 'N/A') ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($advisor['country'] ?: 'N/A') ?></small>
                                </td>
                                <td>
                                    <?php
                                    $rank_colors = [
                                        'certified' => 'primary',
                                        'senior' => 'info',
                                        'executive' => 'success'
                                    ];
                                    $rank = $advisor['advisor_rank'] ?: 'certified';
                                    $color = $rank_colors[$rank] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= ucfirst($rank) ?></span>
                                </td>
                                <td><?= $advisor['team_count'] ?> members</td>
                                <td>
                                    <div class="fw-semibold">$<?= number_format($advisor['total_sales'], 2) ?></div>
                                    <small class="text-muted"><?= $advisor['total_bookings'] ?> bookings</small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $advisor['status'] == 'active' ? 'success' : ($advisor['status'] == 'inactive' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($advisor['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y', strtotime($advisor['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($advisor['status'] == 'inactive'): ?>
                                        <button onclick="approveKYC(<?= $advisor['id'] ?>)" class="btn btn-success btn-sm" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectKYC(<?= $advisor['id'] ?>)" class="btn btn-danger btn-sm" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php else: ?>
                                        <a href="advisor-dashboard.php?id=<?= $advisor['id'] ?>" class="btn btn-info btn-sm" title="View Dashboard">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="toggleStatus(<?= $advisor['id'] ?>, '<?= $advisor['status'] == 'active' ? 'inactive' : 'active' ?>')" class="btn btn-outline-secondary btn-sm" title="Toggle Status">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <button onclick="changeRank(<?= $advisor['id'] ?>, '<?= $advisor['advisor_rank'] ?>')" class="btn btn-outline-primary btn-sm" title="Change Rank">
                                            <i class="fas fa-medal"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
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

<!-- Change Rank Modal -->
<div class="modal fade" id="rankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Advisor Rank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_rank">
                    <input type="hidden" name="user_id" id="rank_user_id">
                    <div class="mb-3">
                        <label class="form-label">Select Rank</label>
                        <select name="rank" id="rank_select" class="form-select" required>
                            <option value="certified">Certified (30% commission)</option>
                            <option value="senior">Senior (35% commission)</option>
                            <option value="executive">Executive (40% commission)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Rank</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function approveKYC(userId) {
    if (confirm('Approve this advisor?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="approve_kyc">
            <input type="hidden" name="user_id" value="${userId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectKYC(userId) {
    if (confirm('Reject this advisor? They will be suspended.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="reject_kyc">
            <input type="hidden" name="user_id" value="${userId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleStatus(userId, newStatus) {
    if (confirm('Change advisor status to ' + newStatus + '?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="toggle_status">
            <input type="hidden" name="user_id" value="${userId}">
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function changeRank(userId, currentRank) {
    document.getElementById('rank_user_id').value = userId;
    document.getElementById('rank_select').value = currentRank || 'certified';
    new bootstrap.Modal(document.getElementById('rankModal')).show();
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
