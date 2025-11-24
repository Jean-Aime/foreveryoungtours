<?php
$page_title = 'MCA Management';
$page_subtitle = 'Master Country Advisors';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

// Handle MCA operations
if ($_POST) {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'assign_country':
                    $stmt = $pdo->prepare("INSERT INTO mca_assignments (mca_id, country_id, status) VALUES (?, ?, 'active') ON DUPLICATE KEY UPDATE status = 'active'");
                    $stmt->execute([$_POST['mca_id'], $_POST['country_id']]);
                    $success = 'Country assigned successfully!';
                    break;
                case 'remove_assignment':
                    $stmt = $pdo->prepare("UPDATE mca_assignments SET status = 'inactive' WHERE mca_id = ? AND country_id = ?");
                    $stmt->execute([$_POST['mca_id'], $_POST['country_id']]);
                    $success = 'Assignment removed successfully!';
                    break;
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

// Get MCAs with stats
$stmt = $pdo->prepare("SELECT u.*, 
                              COUNT(DISTINCT ma.country_id) as assigned_countries,
                              COUNT(DISTINCT t.id) as team_count
                       FROM users u 
                       LEFT JOIN mca_assignments ma ON u.id = ma.mca_id AND ma.status = 'active' 
                       LEFT JOIN users t ON t.sponsor_id = u.id AND t.role = 'advisor'
                       WHERE u.role = 'mca' 
                       GROUP BY u.id 
                       ORDER BY u.first_name");
$stmt->execute();
$mcas = $stmt->fetchAll();

// Get all countries
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name 
                       FROM countries c 
                       JOIN regions r ON c.region_id = r.id 
                       WHERE c.status = 'active' 
                       ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll();

// Get MCA assignments
$stmt = $pdo->prepare("SELECT ma.*, 
                              CONCAT(u.first_name, ' ', u.last_name) as mca_name, 
                              c.name as country_name, 
                              r.name as region_name 
                       FROM mca_assignments ma 
                       JOIN users u ON ma.mca_id = u.id 
                       JOIN countries c ON ma.country_id = c.id 
                       JOIN regions r ON c.region_id = r.id 
                       WHERE ma.status = 'active' 
                       ORDER BY r.name, c.name");
$stmt->execute();
$assignments = $stmt->fetchAll();

// Get statistics
$stats = [
    'total_mcas' => count($mcas),
    'active_mcas' => count(array_filter($mcas, fn($m) => $m['status'] == 'active')),
    'assigned_countries' => count($assignments),
    'unassigned_countries' => count($countries) - count($assignments)
];

$current_page = 'mca-management';
require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">MCA Management</h1>
                    <p class="text-slate-600">Manage Master Country Advisors and country assignments</p>
                </div>
                <button onclick="openAssignModal()" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Assign Country
                </button>
            </div>
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
                                <p class="text-muted mb-1 small">Total MCAs</p>
                                <h3 class="mb-0 fw-bold"><?= $stats['total_mcas'] ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-user-crown text-primary fs-4"></i>
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
                                <p class="text-muted mb-1 small">Active MCAs</p>
                                <h3 class="mb-0 fw-bold text-success"><?= $stats['active_mcas'] ?></h3>
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
                                <p class="text-muted mb-1 small">Assigned Countries</p>
                                <h3 class="mb-0 fw-bold text-info"><?= $stats['assigned_countries'] ?></h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-flag text-info fs-4"></i>
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
                                <p class="text-muted mb-1 small">Unassigned</p>
                                <h3 class="mb-0 fw-bold text-warning"><?= $stats['unassigned_countries'] ?></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-globe text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MCA List -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">MCA Overview</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($mcas)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-crown text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No MCAs found</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>MCA</th>
                                <th>Contact</th>
                                <th>Countries</th>
                                <th>Team</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mcas as $mca): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <span class="fw-bold text-primary"><?= strtoupper(substr($mca['first_name'] ?: 'M', 0, 1)) ?></span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars(trim($mca['first_name'] . ' ' . $mca['last_name']) ?: 'N/A') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($mca['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($mca['phone'] ?: 'N/A') ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($mca['country'] ?: 'N/A') ?></small>
                                </td>
                                <td><span class="badge bg-info"><?= $mca['assigned_countries'] ?> assigned</span></td>
                                <td><?= $mca['team_count'] ?> advisors</td>
                                <td>
                                    <span class="badge bg-<?= $mca['status'] == 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($mca['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="mca-dashboard.php?id=<?= $mca['id'] ?>" class="btn btn-info btn-sm" title="View Dashboard">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="assignCountry(<?= $mca['id'] ?>)" class="btn btn-primary btn-sm" title="Assign Country">
                                            <i class="fas fa-plus"></i>
                                        </button>
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

        <!-- Current Assignments -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Current Country Assignments</h5>
            </div>
            <div class="card-body">
                <?php if (empty($assignments)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-globe text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No country assignments yet. Click "Assign Country" to get started.</p>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php 
                    $assignments_by_region = [];
                    foreach ($assignments as $assignment) {
                        $assignments_by_region[$assignment['region_name']][] = $assignment;
                    }
                    ?>
                    <?php foreach ($assignments_by_region as $region => $region_assignments): ?>
                    <div class="col-md-4">
                        <div class="card border-start border-primary border-4">
                            <div class="card-body">
                                <h6 class="card-title text-primary fw-bold mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($region) ?>
                                </h6>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($region_assignments as $assignment): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($assignment['country_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($assignment['mca_name']) ?></small>
                                        </div>
                                        <button onclick="removeAssignment(<?= $assignment['mca_id'] ?>, <?= $assignment['country_id'] ?>)" class="btn btn-sm btn-danger" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
</main>

<!-- Assign Country Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Country to MCA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="assign_country">
                    <div class="mb-3">
                        <label class="form-label">Select MCA</label>
                        <select name="mca_id" id="mca_select" required class="form-select">
                            <option value="">Choose MCA</option>
                            <?php foreach ($mcas as $mca): ?>
                            <option value="<?= $mca['id'] ?>"><?= htmlspecialchars($mca['first_name'] . ' ' . $mca['last_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Country</label>
                        <select name="country_id" required class="form-select">
                            <option value="">Choose Country</option>
                            <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>"><?= htmlspecialchars($country['region_name'] . ' - ' . $country['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openAssignModal() {
    new bootstrap.Modal(document.getElementById('assignModal')).show();
}

function assignCountry(mcaId) {
    document.getElementById('mca_select').value = mcaId;
    openAssignModal();
}

function removeAssignment(mcaId, countryId) {
    if (confirm('Are you sure you want to remove this country assignment?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="remove_assignment">
            <input type="hidden" name="mca_id" value="${mcaId}">
            <input type="hidden" name="country_id" value="${countryId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
