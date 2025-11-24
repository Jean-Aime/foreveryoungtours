<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/audit-logger.php';
require_once '../includes/csrf.php';
checkAuth('super_admin');

$success = '';
$error = '';

if ($_POST && isset($_POST['bulk_action'])) {
    verifyCSRF();
    try {
        $user_ids = $_POST['user_ids'] ?? [];
        $action = $_POST['bulk_action'];
        
        if (empty($user_ids)) {
            $error = 'No users selected';
        } else {
            $placeholders = str_repeat('?,', count($user_ids) - 1) . '?';
            
            switch ($action) {
                case 'activate':
                    $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id IN ($placeholders)");
                    $stmt->execute($user_ids);
                    logAudit($_SESSION['user_id'], 'bulk_activate_users', 'user', null, null, ['user_ids' => $user_ids]);
                    $success = count($user_ids) . ' users activated';
                    break;
                    
                case 'deactivate':
                    $stmt = $pdo->prepare("UPDATE users SET status = 'inactive' WHERE id IN ($placeholders)");
                    $stmt->execute($user_ids);
                    logAudit($_SESSION['user_id'], 'bulk_deactivate_users', 'user', null, null, ['user_ids' => $user_ids]);
                    $success = count($user_ids) . ' users deactivated';
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id IN ($placeholders) AND role != 'super_admin'");
                    $stmt->execute($user_ids);
                    logAudit($_SESSION['user_id'], 'bulk_delete_users', 'user', null, null, ['user_ids' => $user_ids]);
                    $success = $stmt->rowCount() . ' users deleted';
                    break;
                    
                case 'export':
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id IN ($placeholders)");
                    $stmt->execute($user_ids);
                    $users = $stmt->fetchAll();
                    
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
                    
                    $output = fopen('php://output', 'w');
                    fputcsv($output, ['ID', 'Email', 'First Name', 'Last Name', 'Phone', 'Role', 'Status', 'Created At']);
                    
                    foreach ($users as $user) {
                        fputcsv($output, [
                            $user['id'],
                            $user['email'],
                            $user['first_name'],
                            $user['last_name'],
                            $user['phone'],
                            $user['role'],
                            $user['status'],
                            $user['created_at']
                        ]);
                    }
                    
                    fclose($output);
                    logAudit($_SESSION['user_id'], 'bulk_export_users', 'user', null, null, ['user_ids' => $user_ids]);
                    exit();
            }
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT u.*, CONCAT(s.first_name, ' ', s.last_name) as sponsor_name
                     FROM users u
                     LEFT JOIN users s ON u.sponsor_id = s.id
                     ORDER BY u.created_at DESC");
$users = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Bulk Operations</h1>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" id="bulkForm">
                <?= csrfField() ?>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="mr-2 rounded border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Select All</span>
                            </label>
                            <span id="selectedCount" class="text-sm text-slate-600">0 selected</span>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <select name="bulk_action" required class="border border-slate-300 rounded-lg px-4 py-2">
                                <option value="">Select Action</option>
                                <option value="activate">Activate</option>
                                <option value="deactivate">Deactivate</option>
                                <option value="export">Export CSV</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" class="rounded border-slate-300" disabled>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sponsor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="user_ids[]" value="<?= $user['id'] ?>" 
                                               class="user-checkbox rounded border-slate-300"
                                               <?= $user['role'] == 'super_admin' ? 'disabled' : '' ?>>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-primary-gold rounded-full flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($user['first_name'] ?: 'U', 0, 1)) ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">
                                                    <?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name']) ?: 'N/A') ?>
                                                </div>
                                                <div class="text-sm text-slate-500"><?= htmlspecialchars($user['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?= ucwords(str_replace('_', ' ', $user['role'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        <?= htmlspecialchars($user['sponsor_name'] ?: 'None') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            <?= $user['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.user-checkbox');
const selectedCount = document.getElementById('selectedCount');
const bulkForm = document.getElementById('bulkForm');

selectAll.addEventListener('change', function() {
    checkboxes.forEach(cb => {
        if (!cb.disabled) cb.checked = this.checked;
    });
    updateCount();
});

checkboxes.forEach(cb => {
    cb.addEventListener('change', updateCount);
});

function updateCount() {
    const count = document.querySelectorAll('.user-checkbox:checked').length;
    selectedCount.textContent = count + ' selected';
}

bulkForm.addEventListener('submit', function(e) {
    const action = document.querySelector('[name="bulk_action"]').value;
    const count = document.querySelectorAll('.user-checkbox:checked').length;
    
    if (count === 0) {
        e.preventDefault();
        alert('Please select at least one user');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${count} user(s)? This cannot be undone.`)) {
            e.preventDefault();
        }
    }
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
