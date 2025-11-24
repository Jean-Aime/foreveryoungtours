<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/audit-logger.php';
require_once '../includes/csrf.php';
checkAuth('super_admin');

$success = '';
$error = '';

if ($_POST && isset($_POST['action'])) {
    verifyCSRF();
    try {
        if ($_POST['action'] == 'approve') {
            $stmt = $pdo->prepare("UPDATE booking_modifications SET status = 'approved', processed_by = ?, processed_at = NOW() WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $_POST['mod_id']]);
            
            $stmt = $pdo->prepare("SELECT * FROM booking_modifications WHERE id = ?");
            $stmt->execute([$_POST['mod_id']]);
            $mod = $stmt->fetch();
            
            if ($mod['modification_type'] == 'cancellation') {
                $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
                $stmt->execute([$mod['booking_id']]);
            }
            
            logAudit($_SESSION['user_id'], 'booking_modification_approved', 'booking_modification', $_POST['mod_id']);
            $success = 'Modification approved successfully!';
        } elseif ($_POST['action'] == 'reject') {
            $stmt = $pdo->prepare("UPDATE booking_modifications SET status = 'rejected', processed_by = ?, processed_at = NOW() WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $_POST['mod_id']]);
            
            logAudit($_SESSION['user_id'], 'booking_modification_rejected', 'booking_modification', $_POST['mod_id']);
            $success = 'Modification rejected!';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT bm.*, b.destination, b.travel_date, b.total_amount,
                     CONCAT(u.first_name, ' ', u.last_name) as client_name, u.email
                     FROM booking_modifications bm
                     JOIN bookings b ON bm.booking_id = b.id
                     JOIN users u ON bm.requested_by = u.id
                     ORDER BY bm.created_at DESC");
$modifications = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Booking Modifications</h1>
            
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
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php foreach ($modifications as $mod): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">#<?= $mod['booking_id'] ?></div>
                                    <div class="text-sm text-slate-500"><?= htmlspecialchars($mod['destination']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900"><?= htmlspecialchars($mod['client_name']) ?></div>
                                    <div class="text-sm text-slate-500"><?= htmlspecialchars($mod['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= ucwords(str_replace('_', ' ', $mod['modification_type'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900 max-w-xs truncate">
                                    <?= htmlspecialchars($mod['reason']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?= $mod['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($mod['status'] == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= ucfirst($mod['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <?= date('M j, Y', strtotime($mod['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($mod['status'] == 'pending'): ?>
                                    <button onclick="approveModification(<?= $mod['id'] ?>)" class="text-green-600 hover:text-green-900 mr-3">
                                        Approve
                                    </button>
                                    <button onclick="rejectModification(<?= $mod['id'] ?>)" class="text-red-600 hover:text-red-900">
                                        Reject
                                    </button>
                                    <?php else: ?>
                                    <span class="text-slate-400">Processed</span>
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
</main>

<script>
function approveModification(id) {
    if (confirm('Approve this modification request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <?= csrfField() ?>
            <input type="hidden" name="action" value="approve">
            <input type="hidden" name="mod_id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectModification(id) {
    if (confirm('Reject this modification request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <?= csrfField() ?>
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="mod_id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
