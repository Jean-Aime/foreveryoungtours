<?php
require_once '../config/database.php';
require_once '../includes/client-portal-functions.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $portalCode = $_POST['portal_code'];
    $stmt = $pdo->prepare("DELETE FROM client_registry WHERE portal_code = ?");
    $stmt->execute([$portalCode]);
    header('Location: manage-portals.php?deleted=1');
    exit;
}

// Get all portals
$stmt = $pdo->query("
    SELECT cr.*, 
           u.first_name, u.last_name, u.email as owner_email,
           (SELECT COUNT(*) FROM portal_tours WHERE portal_code = cr.portal_code) as tour_count,
           (SELECT COUNT(*) FROM portal_messages WHERE portal_code = cr.portal_code AND is_read = 0) as unread_messages
    FROM client_registry cr
    LEFT JOIN users u ON cr.owned_by_user_id = u.id
    ORDER BY cr.created_at DESC
");
$portals = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">🛡️ Client Ownership Monitor</h1>
                <p class="text-slate-600">Monitor client ownership and prevent commission disputes</p>
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Admin Role:</strong> You can view all client portals and ownership. Only advisors can create portals to lock their clients.
                    </p>
                </div>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                ✅ Client portal deleted successfully!
            </div>
            <?php endif; ?>



            <!-- Stats -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-slate-600 text-sm mb-1">Total Portals</p>
                    <p class="text-3xl font-bold text-slate-900"><?= count($portals) ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-slate-600 text-sm mb-1">Active</p>
                    <p class="text-3xl font-bold text-green-600">
                        <?= count(array_filter($portals, fn($p) => $p['ownership_status'] === 'active')) ?>
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-slate-600 text-sm mb-1">Total Bookings</p>
                    <p class="text-3xl font-bold text-blue-600">
                        <?= array_sum(array_column($portals, 'total_bookings')) ?>
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-slate-600 text-sm mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-primary-gold">
                        $<?= number_format(array_sum(array_column($portals, 'total_revenue')), 2) ?>
                    </p>
                </div>
            </div>

            <!-- Portals Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Portal Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php foreach ($portals as $portal): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900"><?= htmlspecialchars($portal['client_name']) ?></p>
                                <p class="text-sm text-slate-600"><?= htmlspecialchars($portal['client_email']) ?></p>
                                <p class="text-sm text-slate-600"><?= htmlspecialchars($portal['client_phone']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <code class="bg-slate-100 px-2 py-1 rounded text-sm font-mono">
                                    <?= htmlspecialchars($portal['portal_code']) ?>
                                </code>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium"><?= htmlspecialchars($portal['owned_by_name']) ?></p>
                                <p class="text-sm text-slate-600"><?= htmlspecialchars($portal['owned_by_role']) ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <?= $portal['tour_count'] ?> tours
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($portal['ownership_status'] === 'active'): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    🔒 Active
                                </span>
                                <?php else: ?>
                                <span class="bg-slate-100 text-slate-800 px-3 py-1 rounded-full text-sm font-medium">
                                    <?= ucfirst($portal['ownership_status']) ?>
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm">Views: <?= $portal['portal_views'] ?></p>
                                <p class="text-sm">Bookings: <?= $portal['total_bookings'] ?></p>
                                <?php if ($portal['unread_messages'] > 0): ?>
                                <p class="text-sm text-red-600 font-medium">
                                    💬 <?= $portal['unread_messages'] ?> new messages
                                </p>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="<?= $portal['portal_url'] ?>" target="_blank"
                                       class="text-blue-600 hover:text-blue-800" title="Open Portal">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button onclick="copyPortalLink('<?= $portal['portal_url'] ?>')"
                                            class="text-green-600 hover:text-green-800" title="Copy Link">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <a href="mailto:<?= $portal['owner_email'] ?>?subject=Client Portal: <?= $portal['portal_code'] ?>"
                                       class="text-yellow-600 hover:text-yellow-800" title="Contact Owner">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <button onclick="deletePortal('<?= $portal['portal_code'] ?>', '<?= htmlspecialchars($portal['client_name']) ?>')"
                                            class="text-red-600 hover:text-red-800" title="Delete Portal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>

<script>
function copyPortalLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Portal link copied to clipboard!');
    });
}

function deletePortal(code, clientName) {
    if (confirm('Delete portal for ' + clientName + '?\n\nThis will remove the client ownership record. The advisor will lose this client.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="action" value="delete">' +
                        '<input type="hidden" name="portal_code" value="' + code + '">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
