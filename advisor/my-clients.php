<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../includes/client-portal-functions.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get advisor's clients
$stmt = $pdo->prepare("
    SELECT cr.*, 
           (SELECT COUNT(*) FROM portal_tours WHERE portal_code = cr.portal_code) as tour_count,
           (SELECT COUNT(*) FROM portal_messages WHERE portal_code = cr.portal_code AND is_read = 0) as unread_messages
    FROM client_registry cr
    WHERE cr.owned_by_user_id = ?
    ORDER BY cr.created_at DESC
");
$stmt->execute([$advisor_id]);
$clients = $stmt->fetchAll();

// Statistics
$total_clients = count($clients);
$active_clients = count(array_filter($clients, fn($c) => $c['ownership_status'] == 'active'));
$total_bookings = array_sum(array_column($clients, 'total_bookings'));
$total_revenue = array_sum(array_column($clients, 'total_revenue'));

$page_title = 'My Clients';
$page_subtitle = 'Manage your protected clients and their portals';

include 'includes/advisor-header.php';
?>

<?php if (isset($_GET['created'])): ?>
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>Client portal created! Code: <strong><?= htmlspecialchars($_GET['created']) ?></strong> - Client is now locked to you. Commission protected! 🔒
</div>
<?php endif; ?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Clients</p>
                <p class="text-3xl font-bold text-slate-900"><?= $total_clients ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Active Clients</p>
                <p class="text-3xl font-bold text-slate-900"><?= $active_clients ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-user-check text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Bookings</p>
                <p class="text-3xl font-bold text-slate-900"><?= $total_bookings ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Revenue</p>
                <p class="text-3xl font-bold text-slate-900">$<?= number_format($total_revenue) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-dollar-sign text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Action Button -->
<div class="mb-6">
    <a href="create-client-portal.php" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all inline-block">
        <i class="fas fa-plus mr-2"></i>Add New Client
    </a>
</div>

<!-- Clients Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-900">All Clients</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Portal Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Interest</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tours</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Views</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-users text-4xl mb-3 block"></i>
                        No clients yet. Create your first client portal!
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($clients as $client): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($client['client_name']) ?></p>
                            <p class="text-sm text-slate-600"><?= htmlspecialchars($client['client_email']) ?></p>
                            <p class="text-sm text-slate-600"><?= htmlspecialchars($client['client_phone']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <code class="bg-slate-100 px-2 py-1 rounded text-sm font-mono"><?= htmlspecialchars($client['portal_code']) ?></code>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?= htmlspecialchars($client['client_interest'] ?: 'Not specified') ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?= $client['tour_count'] ?> tours</td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?= $client['portal_views'] ?> views</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            🔒 Protected
                        </span>
                        <?php if ($client['unread_messages'] > 0): ?>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                            💬 <?= $client['unread_messages'] ?>
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="client-chat.php?code=<?= $client['portal_code'] ?>"
                               class="text-purple-600 hover:text-purple-800" title="Chat">
                                <i class="fas fa-comments"></i>
                            </a>
                            <a href="<?= htmlspecialchars($client['portal_url']) ?>" target="_blank"
                               class="text-blue-600 hover:text-blue-800" title="Open Portal">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <button onclick="copyLink('<?= htmlspecialchars($client['portal_url']) ?>', '<?= htmlspecialchars($client['client_name']) ?>')"
                                    class="text-green-600 hover:text-green-800" title="Copy Link">
                                <i class="fas fa-copy"></i>
                            </button>
                            <a href="../pages/tour-booking.php?portal=<?= $client['portal_code'] ?>"
                               class="text-yellow-600 hover:text-yellow-800" title="Create Booking">
                                <i class="fas fa-calendar-check"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function copyLink(url, clientName) {
    navigator.clipboard.writeText(url).then(() => {
        alert('✅ Portal link copied!\n\nLink: ' + url + '\n\nSend this secure link to ' + clientName);
    }).catch(() => {
        prompt('Copy this link:', url);
    });
}
</script>

<?php include 'includes/advisor-footer.php'; ?>
