<?php
$page_title = 'Audit Logs';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/audit-logger.php';
checkAuth('super_admin');

$filters = [
    'user_id' => $_GET['user_id'] ?? null,
    'entity_type' => $_GET['entity_type'] ?? null,
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null,
    'limit' => 500
];

$logs = getAuditLogs($filters);
$users = $pdo->query("SELECT id, first_name, last_name, email FROM users ORDER BY first_name")->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Audit Logs</h1>
            
            <!-- Filters -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">User</label>
                        <select name="user_id" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">All Users</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $filters['user_id'] == $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Entity Type</label>
                        <select name="entity_type" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">All Types</option>
                            <option value="user" <?= $filters['entity_type'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="booking" <?= $filters['entity_type'] == 'booking' ? 'selected' : '' ?>>Booking</option>
                            <option value="payment" <?= $filters['entity_type'] == 'payment' ? 'selected' : '' ?>>Payment</option>
                            <option value="payout" <?= $filters['entity_type'] == 'payout' ? 'selected' : '' ?>>Payout</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="<?= $filters['date_from'] ?>" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="<?= $filters['date_to'] ?>" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-4 flex justify-end space-x-4">
                        <a href="audit-logs.php" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Reset</a>
                        <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Logs Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Entity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    <?= date('M j, Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-medium text-slate-900"><?= htmlspecialchars($log['user_name'] ?: 'System') ?></div>
                                    <div class="text-slate-500"><?= htmlspecialchars($log['email'] ?: '') ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($log['action']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    <?= htmlspecialchars($log['entity_type']) ?> 
                                    <?= $log['entity_id'] ? '#' . $log['entity_id'] : '' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?= htmlspecialchars($log['ip_address']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button onclick="showDetails(<?= htmlspecialchars(json_encode($log)) ?>)" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Audit Log Details</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detailsContent" class="p-6"></div>
    </div>
</div>

<script>
function showDetails(log) {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailsContent');
    
    let html = `
        <div class="space-y-4">
            <div><strong>Action:</strong> ${log.action}</div>
            <div><strong>Entity:</strong> ${log.entity_type} ${log.entity_id ? '#' + log.entity_id : ''}</div>
            <div><strong>User:</strong> ${log.user_name || 'System'} (${log.email || 'N/A'})</div>
            <div><strong>IP Address:</strong> ${log.ip_address}</div>
            <div><strong>Timestamp:</strong> ${log.created_at}</div>
    `;
    
    if (log.old_values) {
        html += `<div><strong>Old Values:</strong><pre class="bg-slate-100 p-4 rounded mt-2 overflow-auto">${JSON.stringify(JSON.parse(log.old_values), null, 2)}</pre></div>`;
    }
    
    if (log.new_values) {
        html += `<div><strong>New Values:</strong><pre class="bg-slate-100 p-4 rounded mt-2 overflow-auto">${JSON.stringify(JSON.parse(log.new_values), null, 2)}</pre></div>`;
    }
    
    html += '</div>';
    content.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    document.getElementById('detailsModal').classList.add('hidden');
    document.getElementById('detailsModal').classList.remove('flex');
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
