<?php
session_start();
require_once '../config/database.php';

$page_title = 'My Profile';
$page_subtitle = 'Manage Your Account';

$client_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$client_id]);
$user = $stmt->fetch();

include 'includes/client-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="nextcloud-card p-6 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-3xl font-bold">
            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
        <p class="text-slate-600 mb-4"><?php echo htmlspecialchars($user['email']); ?></p>
        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Active Member</span>
    </div>

    <div class="lg:col-span-2">
        <div class="nextcloud-card p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Personal Information</h2>
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">First Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Last Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Phone</label>
                    <input type="tel" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Update Profile</button>
            </form>
        </div>

        <div class="nextcloud-card p-6 mt-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Change Password</h2>
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Current Password</label>
                    <input type="password" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">New Password</label>
                    <input type="password" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Confirm New Password</label>
                    <input type="password" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Change Password</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/client-footer.php'; ?>
