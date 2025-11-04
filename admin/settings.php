<?php
$page_title = 'System Settings';
$page_subtitle = 'Platform Configuration';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

if ($_POST) {
    // Handle settings updates here
    $success_message = "Settings updated successfully!";
}

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">System Settings</h1>
                <p class="text-slate-600">Configure platform settings and preferences</p>
            </div>

            <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <!-- Settings Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Platform Settings -->
                <div class="nextcloud-card p-6">
                    <h2 class="text-xl font-bold mb-4">Platform Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Platform Name</label>
                            <input type="text" value="iForYoungTours" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Support Email</label>
                            <input type="email" value="support@foreveryoungtours.com" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Support Phone</label>
                            <input type="tel" value="+1 (234) 567-890" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Update Settings</button>
                    </form>
                </div>

                <!-- Commission Settings -->
                <div class="nextcloud-card p-6">
                    <h2 class="text-xl font-bold mb-4">Commission Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Advisor Commission (%)</label>
                            <input type="number" value="10" min="0" max="100" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">MCA Override (%)</label>
                            <input type="number" value="5" min="0" max="100" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Platform Fee (%)</label>
                            <input type="number" value="15" min="0" max="100" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Update Commission</button>
                    </form>
                </div>

                <!-- KYC Settings -->
                <div class="nextcloud-card p-6">
                    <h2 class="text-xl font-bold mb-4">KYC Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span>Auto-approve KYC</span>
                            <input type="checkbox" class="toggle">
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Require document upload</span>
                            <input type="checkbox" checked class="toggle">
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Email notifications</span>
                            <input type="checkbox" checked class="toggle">
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="nextcloud-card p-6">
                    <h2 class="text-xl font-bold mb-4">System Status</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span>Database Connection</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Connected</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Email Service</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Active</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Payment Gateway</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Pending</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Backup Status</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Up to date</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>