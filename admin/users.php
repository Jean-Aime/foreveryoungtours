<?php
$page_title = 'User Management';
$page_subtitle = 'Manage All System Users';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

// Handle user operations
if ($_POST) {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add_user':
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, phone, role, status, sponsor_id) VALUES (?, ?, ?, ?, ?, ?, 'active', ?)");
                    $stmt->execute([
                        $_POST['email'],
                        $password,
                        $_POST['first_name'],
                        $_POST['last_name'],
                        $_POST['phone'],
                        $_POST['role'],
                        $_POST['sponsor_id'] ?: null
                    ]);
                    $success = 'User added successfully!';
                    break;
                case 'update_status':
                    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
                    $stmt->execute([$_POST['status'], $_POST['user_id']]);
                    $success = 'User status updated successfully!';
                    break;
                case 'delete_user':
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'super_admin'");
                    $stmt->execute([$_POST['user_id']]);
                    $success = 'User deleted successfully!';
                    break;
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

// Get all users with hierarchy info
$stmt = $pdo->prepare("SELECT u.*,
                              CONCAT(s.first_name, ' ', s.last_name) as sponsor_name,
                              (SELECT COUNT(*) FROM users WHERE sponsor_id = u.id) as team_count
                       FROM users u
                       LEFT JOIN users s ON u.sponsor_id = s.id
                       ORDER BY u.role, u.created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll();

// Group users by role
$users_by_role = [];
foreach ($users as $user) {
    $users_by_role[$user['role']][] = $user;
}

// Get user statistics
$stats = $pdo->query("SELECT
    COUNT(*) as total_users,
    SUM(CASE WHEN role = 'super_admin' THEN 1 ELSE 0 END) as admins,
    SUM(CASE WHEN role = 'mca' THEN 1 ELSE 0 END) as mcas,
    SUM(CASE WHEN role = 'advisor' THEN 1 ELSE 0 END) as advisors,
    SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) as clients,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users
    FROM users")->fetch();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">User Management</h1>
                <p class="text-slate-600">Manage all system users and their roles</p>
            </div>
            <button onclick="showAddUserModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add New User
            </button>
        </div>

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

        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Users</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($stats['total_users']) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">MCAs</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($stats['mcas']) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-crown text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Advisors</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($stats['advisors']) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Clients</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($stats['clients']) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Active</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($stats['active_users']) ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users by Role -->
        <?php foreach ($users_by_role as $role => $role_users): ?>
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4"><?= ucwords(str_replace('_', ' ', $role)) ?>s (<?= count($role_users) ?>)</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Sponsor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Team</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($role_users as $user): ?>
                        <tr class="hover:bg-slate-50">
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
                                <div class="text-sm text-slate-900"><?= htmlspecialchars($user['phone'] ?: 'N/A') ?></div>
                                <div class="text-sm text-slate-500"><?= htmlspecialchars($user['country'] ?: 'N/A') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                <?= htmlspecialchars($user['sponsor_name'] ?: 'None') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                <?= $user['team_count'] ?> members
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?= $user['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <?= date('M j, Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="toggleStatus(<?= $user['id'] ?>, '<?= $user['status'] == 'active' ? 'inactive' : 'active' ?>')"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <?= $user['status'] == 'active' ? 'Deactivate' : 'Activate' ?>
                                </button>
                                <?php if ($user['role'] != 'super_admin'): ?>
                                <button onclick="deleteUser(<?= $user['id'] ?>)" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Add New User</h3>
            <button onclick="closeAddUserModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="add_user">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                    <input type="tel" name="phone" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Role *</label>
                    <select name="role" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="mca">MCA (Master Country Advisor)</option>
                        <option value="advisor">Advisor</option>
                        <option value="client">Client</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sponsor (Optional)</label>
                    <select name="sponsor_id" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                        <option value="">No Sponsor</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>">
                            <?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name']) ?: $user['email']) ?>
                            (<?= ucfirst($user['role']) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password *</label>
                    <input type="password" name="password" required minlength="6" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                    <p class="text-xs text-slate-500 mt-1">Minimum 6 characters</p>
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="closeAddUserModal()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-plus mr-2"></i>Add User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddUserModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.getElementById('addUserModal').classList.add('flex');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.getElementById('addUserModal').classList.remove('flex');
}

function toggleStatus(userId, newStatus) {
    if (confirm('Are you sure you want to ' + newStatus + ' this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="user_id" value="${userId}">
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="user_id" value="${userId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>