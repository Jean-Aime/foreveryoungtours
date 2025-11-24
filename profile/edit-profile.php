<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/audit-logger.php';
checkAuth();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_POST) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $old_data = $stmt->fetch();
        
        $stmt = $pdo->prepare("UPDATE users SET 
            first_name = ?, last_name = ?, phone = ?, country = ?, 
            bio = ?, address = ?, city = ?, state = ?, postal_code = ?,
            date_of_birth = ?, emergency_contact_name = ?, emergency_contact_phone = ?
            WHERE id = ?");
        
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['phone'],
            $_POST['country'],
            $_POST['bio'],
            $_POST['address'],
            $_POST['city'],
            $_POST['state'],
            $_POST['postal_code'],
            $_POST['date_of_birth'] ?: null,
            $_POST['emergency_contact_name'],
            $_POST['emergency_contact_phone'],
            $user_id
        ]);
        
        logAudit($user_id, 'profile_update', 'user', $user_id, $old_data, $_POST);
        $success = 'Profile updated successfully!';
    } catch (PDOException $e) {
        $error = 'Error updating profile: ' . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$role = $_SESSION['role'];
if ($role == 'super_admin') {
    require_once '../admin/includes/admin-header.php';
    require_once '../admin/includes/admin-sidebar.php';
} elseif ($role == 'advisor') {
    require_once '../advisor/includes/advisor-header.php';
} else {
    require_once '../client/includes/client-header.php';
}
?>

<main class="<?= $role == 'super_admin' ? 'flex-1 overflow-auto ml-64 w-[calc(100%-16rem)]' : 'flex-1' ?> min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Edit Profile</h1>
            
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
            
            <form method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 bg-slate-100">
                        <p class="text-xs text-slate-500 mt-1">Email cannot be changed</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                        <input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Bio</label>
                        <textarea name="bio" rows="3" 
                                  class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold"><?= htmlspecialchars($user['bio']) ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">State/Province</label>
                        <input type="text" name="state" value="<?= htmlspecialchars($user['state']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Postal Code</label>
                        <input type="text" name="postal_code" value="<?= htmlspecialchars($user['postal_code']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 mt-4">Emergency Contact</h3>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="<?= htmlspecialchars($user['emergency_contact_name']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" value="<?= htmlspecialchars($user['emergency_contact_phone']) ?>" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="javascript:history.back()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
if ($role == 'super_admin') {
    require_once '../admin/includes/admin-footer.php';
} elseif ($role == 'advisor') {
    require_once '../advisor/includes/advisor-footer.php';
} else {
    require_once '../client/includes/client-footer.php';
}
?>
