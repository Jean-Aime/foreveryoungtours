<?php
$current_page = 'company-portals';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$page_title = 'Company Portals';
$page_subtitle = 'Manage portals from social media leads';

// Get all company portals (owned by admin)
$stmt = $pdo->prepare("
    SELECT cr.*, 
           (SELECT COUNT(*) FROM portal_tours WHERE portal_code = cr.portal_code) as tour_count,
           (SELECT COUNT(*) FROM portal_messages WHERE portal_code = cr.portal_code AND is_read = 0) as unread_messages,
           u.first_name, u.last_name
    FROM client_registry cr
    LEFT JOIN users u ON cr.owned_by_user_id = u.id
    WHERE cr.owned_by_role = 'admin' AND cr.portal_code LIKE 'CO-%'
    ORDER BY cr.created_at DESC
");
$stmt->execute();
$portals = $stmt->fetchAll();

// Statistics
$total_portals = count($portals);
$active_portals = count(array_filter($portals, fn($p) => $p['ownership_status'] == 'active'));
$total_bookings = array_sum(array_column($portals, 'total_bookings'));
$total_revenue = array_sum(array_column($portals, 'total_revenue'));

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">

            <?php if (isset($_GET['created'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>Company portal created! Code: <strong><?= htmlspecialchars($_GET['created']) ?></strong>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['assigned'])): ?>
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                <i class="fas fa-user-check mr-2"></i>Portal assigned to advisor successfully!
            </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Total Portals</p>
                            <p class="text-3xl font-bold text-slate-900"><?= $total_portals ?></p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-link text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Active Portals</p>
                            <p class="text-3xl font-bold text-slate-900"><?= $active_portals ?></p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
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
                <button onclick="openCreateModal()" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-plus mr-2"></i>Create Company Portal
                </button>
            </div>

            <!-- Portals Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900">All Company Portals</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Portal Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Source</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php if (empty($portals)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                    No company portals yet. Create your first portal from social media leads!
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($portals as $portal): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900"><?= htmlspecialchars($portal['client_name']) ?></p>
                                        <p class="text-sm text-slate-600"><?= htmlspecialchars($portal['client_email']) ?></p>
                                        <p class="text-sm text-slate-600"><?= htmlspecialchars($portal['client_phone']) ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="bg-purple-100 px-2 py-1 rounded text-sm font-mono text-purple-700"><?= htmlspecialchars($portal['portal_code']) ?></code>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        <?= ucfirst($portal['source']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700"><?= $portal['tour_count'] ?> tours</td>
                                <td class="px-6 py-4 text-sm text-slate-700"><?= $portal['portal_views'] ?> views</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                        🏢 Company
                                    </span>
                                    <?php if ($portal['unread_messages'] > 0): ?>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                        💬 <?= $portal['unread_messages'] ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="company-chat.php?code=<?= $portal['portal_code'] ?>"
                                           class="text-purple-600 hover:text-purple-800" title="Chat">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        <a href="<?= htmlspecialchars($portal['portal_url']) ?>" target="_blank"
                                           class="text-blue-600 hover:text-blue-800" title="Open Portal">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button onclick="copyLink('<?= htmlspecialchars($portal['portal_url']) ?>', '<?= htmlspecialchars($portal['client_name']) ?>')"
                                                class="text-green-600 hover:text-green-800" title="Copy Link">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <a href="assign-portal.php?code=<?= $portal['portal_code'] ?>"
                                           class="text-orange-600 hover:text-orange-800" title="Assign to Advisor">
                                            <i class="fas fa-user-plus"></i>
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

        </div>
    </div>
</main>

<!-- Create Portal Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-purple-50 to-pink-50 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-slate-900">Create Company Portal</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="create_portal">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Client Name *</label>
                    <input type="text" name="client_name" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Client Email *</label>
                    <input type="email" name="client_email" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Client Phone *</label>
                    <input type="text" name="client_phone" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="+250788712679">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Country</label>
                    <input type="text" name="client_country" class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Client Interest</label>
                <textarea name="client_interest" rows="2" class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200" placeholder="e.g., Rwanda Gorilla Trekking, 2 people, March 2024"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Lead Source</label>
                <select name="source" class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200">
                    <option value="instagram">Instagram</option>
                    <option value="facebook">Facebook</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="website">Website</option>
                    <option value="email">Email</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-3 border-2 border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus-circle mr-2"></i>Create Portal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function copyLink(url, clientName) {
    navigator.clipboard.writeText(url).then(() => {
        alert('✅ Portal link copied!\n\nLink: ' + url + '\n\nSend this link to ' + clientName);
    }).catch(() => {
        prompt('Copy this link:', url);
    });
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
