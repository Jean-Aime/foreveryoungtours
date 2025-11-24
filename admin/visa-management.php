<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/csrf.php';
checkAuth('super_admin');

$success = $error = '';

if ($_POST && isset($_POST['action'])) {
    verifyCSRF();
    try {
        $stmt = $pdo->prepare("UPDATE visa_services SET application_status = ?, assigned_to = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_SESSION['user_id'], $_POST['id']]);
        $success = 'Status updated successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$applications = $pdo->query("SELECT vs.*, CONCAT(u.first_name, ' ', u.last_name) as client_name, u.email 
                             FROM visa_services vs 
                             JOIN users u ON vs.user_id = u.id 
                             ORDER BY vs.created_at DESC")->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Visa Applications</h1>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Destination</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Visa Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Travel Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($applications as $app): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900"><?= htmlspecialchars($app['client_name']) ?></div>
                                <div class="text-sm text-slate-500"><?= htmlspecialchars($app['email']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                <?= htmlspecialchars($app['destination_country']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                <?= htmlspecialchars($app['visa_type']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                <?= date('M j, Y', strtotime($app['travel_date'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $app['application_status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($app['application_status'] == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($app['application_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if ($app['application_status'] == 'pending'): ?>
                                <form method="POST" class="inline-flex space-x-2">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                    <button type="submit" name="status" value="processing" class="text-blue-600 hover:text-blue-900">Process</button>
                                    <button type="submit" name="status" value="approved" class="text-green-600 hover:text-green-900">Approve</button>
                                    <button type="submit" name="status" value="rejected" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
