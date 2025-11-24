<?php
session_start();
require_once '../includes/csrf.php';
require_once '../config/database.php';

$page_title = 'My Profile';
$page_subtitle = 'Manage Your Account';

$client_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    requireCsrf();
    
    if ($_POST['action'] === 'update_profile') {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
        if ($stmt->execute([$first_name, $last_name, $email, $phone, $client_id])) {
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $_SESSION['user_email'] = $email;
            $success = 'Profile updated successfully!';
        } else {
            $error = 'Failed to update profile';
        }
    }
    
    if ($_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$client_id]);
        $user_data = $stmt->fetch();
        
        if (!password_verify($current_password, $user_data['password'])) {
            $error = 'Current password is incorrect';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } elseif (strlen($new_password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed, $client_id])) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password';
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$client_id]);
$user = $stmt->fetch();

include 'includes/client-header.php';
?>

<?php if ($success): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <?= $success ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <?= $error ?>
</div>
<?php endif; ?>

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
            <form method="POST" class="space-y-4">
                <?php echo getCsrfField(); ?>
                <input type="hidden" name="action" value="update_profile">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Phone</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Update Profile</button>
            </form>
        </div>

        <div class="nextcloud-card p-6 mt-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Change Password</h2>
            <form method="POST" class="space-y-4">
                <?php echo getCsrfField(); ?>
                <input type="hidden" name="action" value="change_password">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Current Password</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">New Password</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" required minlength="6" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Change Password</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/client-footer.php'; ?>
