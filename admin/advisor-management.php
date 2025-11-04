<?php
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Handle KYC and status updates
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'approve_kyc':
                $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                break;
            case 'reject_kyc':
                $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                break;
            case 'create_user':
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, role, status) VALUES (?, ?, ?, ?, ?, ?, 'inactive')");
                $stmt->execute([$_POST['username'], $_POST['email'], $password, $_POST['full_name'], $_POST['phone'], $_POST['role']]);
                break;
        }
    }
}

// Get advisors only
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'advisor' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$pending_kyc = count(array_filter($users, function($u) { return $u['status'] == 'inactive'; }));
$active_users = count(array_filter($users, function($u) { return $u['status'] == 'active'; }));
$total_advisors = count($users);
$kyc_approved = count(array_filter($users, function($u) { return $u['kyc_status'] == 'approved'; }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Management - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">Advisor Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="destinations.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-map-marker-alt mr-3"></i>Destinations
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Tours & Packages
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="advisor-management.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-user-tie mr-3"></i>Advisor Management
                </a>
                <a href="mca-management.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users-cog mr-3"></i>MCA Management
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-bar mr-3"></i>Reports & Analytics
                </a>
                <a href="settings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-cog mr-3"></i>System Settings
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gradient">Advisor Management</h1>
                    <p class="text-slate-600">Onboard and manage KYC for advisors only</p>
                </div>
                <button onclick="openCreateModal()" class="btn-primary px-6 py-3 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Onboard New User
                </button>
            </div>

            <!-- KYC Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Pending KYC</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $pending_kyc; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Active Users</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $active_users; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">KYC Approved</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $kyc_approved; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Advisors</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_advisors; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">All Advisors</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">User Details</th>
                                <th class="text-left p-4">Role</th>
                                <th class="text-left p-4">Contact</th>
                                <th class="text-left p-4">KYC Status</th>
                                <th class="text-left p-4">Joined</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="border-b">
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($user['full_name']); ?></p>
                                        <p class="text-sm text-slate-600">@<?php echo htmlspecialchars($user['username']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php echo $user['role'] == 'mca' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'; ?>">
                                        <?php echo strtoupper($user['role']); ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="text-sm"><?php echo htmlspecialchars($user['email']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($user['phone'] ?: 'No phone'); ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php 
                                        echo match($user['status']) {
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-yellow-100 text-yellow-800',
                                            'suspended' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td class="p-4"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <?php if ($user['status'] == 'inactive'): ?>
                                        <button onclick="approveKYC(<?php echo $user['id']; ?>)" class="btn-primary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                        <button onclick="rejectKYC(<?php echo $user['id']; ?>)" class="btn-secondary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                        <?php else: ?>
                                        <button onclick="viewUser(<?php echo $user['id']; ?>)" class="btn-secondary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Onboard New User</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="create_user">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="full_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
                        <input type="text" name="username" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                        <input type="tel" name="phone" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                        <select name="role" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Role</option>
                            <option value="advisor">Advisor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Temporary Password</label>
                        <input type="password" name="password" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Create User</button>
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
        
        function approveKYC(userId) {
            if (confirm('Approve KYC for this user?')) {
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
            if (confirm('Reject KYC for this user?')) {
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
        
        function viewUser(userId) {
            window.location.href = 'user-details.php?id=' + userId;
        }
    </script>
</body>
</html>