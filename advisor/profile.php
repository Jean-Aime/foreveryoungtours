<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get advisor info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$advisor_id]);
$advisor = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    
    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, country = ?, city = ?, address = ? WHERE id = ?");
    if ($stmt->execute([$first_name, $last_name, $phone, $country, $city, $address, $advisor_id])) {
        $success = 'Profile updated successfully!';
        $advisor = array_merge($advisor, $_POST);
    } else {
        $error = 'Failed to update profile';
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    if (!password_verify($current, $advisor['password'])) {
        $error = 'Current password is incorrect';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match';
    } elseif (strlen($new) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([password_hash($new, PASSWORD_DEFAULT), $advisor_id])) {
            $success = 'Password changed successfully!';
        } else {
            $error = 'Failed to change password';
        }
    }
}

$page_title = 'Profile';
$page_subtitle = 'Manage your account settings';

include 'includes/advisor-header.php';
?>

<?php if ($success): ?>
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    <?php echo $success; ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
    <?php echo $error; ?>
</div>
<?php endif; ?>

<!-- Profile Info Card -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 mb-8">
    <div class="flex items-center gap-6">
        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white text-4xl font-bold">
            <?php echo strtoupper(substr($advisor['first_name'], 0, 1)); ?>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-900"><?php echo htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']); ?></h2>
            <p class="text-slate-600"><?php echo htmlspecialchars($advisor['email']); ?></p>
            <div class="flex gap-3 mt-2">
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">Advisor</span>
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold"><?php echo strtoupper($advisor['advisor_rank']); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Profile Information -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">Profile Information</h3>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($advisor['first_name']); ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($advisor['last_name']); ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($advisor['phone']); ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                    <input type="text" name="country" value="<?php echo htmlspecialchars($advisor['country']); ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($advisor['city']); ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($advisor['address']); ?></textarea>
            </div>
            
            <button type="submit" name="update_profile" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Update Profile
            </button>
        </form>
    </div>
    
    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900">Change Password</h3>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                <input type="password" name="confirm_password" required minlength="6" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" name="change_password" class="w-full bg-slate-900 text-white py-2 rounded-lg font-semibold hover:bg-slate-800">
                <i class="fas fa-key mr-2"></i>Change Password
            </button>
        </form>
        
        <!-- Account Info -->
        <div class="p-6 border-t border-slate-200 bg-slate-50">
            <h4 class="font-bold text-slate-900 mb-3">Account Information</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Referral Code:</span>
                    <span class="font-semibold text-slate-900"><?php echo $advisor['referral_code']; ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Member Since:</span>
                    <span class="font-semibold text-slate-900"><?php echo date('M Y', strtotime($advisor['created_at'])); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Status:</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"><?php echo ucfirst($advisor['status']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/advisor-footer.php'; ?>
