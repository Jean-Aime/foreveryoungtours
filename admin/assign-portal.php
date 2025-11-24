<?php
$current_page = 'company-portals';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$page_title = 'Assign Portal to Advisor';
$page_subtitle = 'Transfer company portal to advisor';

$portal_code = $_GET['code'] ?? '';

if (empty($portal_code)) {
    header('Location: company-portals.php');
    exit;
}

// Get portal details
$stmt = $pdo->prepare("SELECT * FROM client_registry WHERE portal_code = ?");
$stmt->execute([$portal_code]);
$portal = $stmt->fetch();

if (!$portal) {
    header('Location: company-portals.php');
    exit;
}

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['advisor_id'])) {
    $advisorId = $_POST['advisor_id'];
    
    // Get advisor details
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = ? AND role = 'advisor'");
    $stmt->execute([$advisorId]);
    $advisor = $stmt->fetch();
    
    if ($advisor) {
        // Update portal ownership
        $stmt = $pdo->prepare("
            UPDATE client_registry 
            SET owned_by_user_id = ?, 
                owned_by_name = ?, 
                owned_by_role = 'advisor',
                transferred_to = ?,
                transfer_date = NOW(),
                transfer_reason = ?
            WHERE portal_code = ?
        ");
        
        $advisorName = $advisor['first_name'] . ' ' . $advisor['last_name'];
        $reason = 'Assigned by admin from company portal';
        
        $stmt->execute([$advisorId, $advisorName, $advisorId, $reason, $portal_code]);
        
        // Create alert for advisor
        $pdo->prepare("
            INSERT INTO ownership_alerts (portal_code, alert_type, advisor_id, alert_message)
            VALUES (?, 'portal_viewed', ?, ?)
        ")->execute([$portal_code, $advisorId, "New client assigned to you: " . $portal['client_name']]);
        
        header('Location: company-portals.php?assigned=1');
        exit;
    }
}

// Get all advisors
$stmt = $pdo->query("SELECT id, first_name, last_name, email FROM users WHERE role = 'advisor' AND status = 'active' ORDER BY first_name");
$advisors = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-3xl mx-auto">

            <div class="mb-6">
                <a href="company-portals.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Company Portals
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Assign Portal to Advisor</h2>

                <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-6 mb-6">
                    <h3 class="font-bold text-purple-900 mb-4">Client Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-600">Name</p>
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($portal['client_name']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Email</p>
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($portal['client_email']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Phone</p>
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($portal['client_phone']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Portal Code</p>
                            <p class="font-semibold text-purple-700"><?= htmlspecialchars($portal['portal_code']) ?></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-slate-600">Interest</p>
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($portal['client_interest'] ?: 'Not specified') ?></p>
                        </div>
                    </div>
                </div>

                <form method="POST">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Advisor *</label>
                        <select name="advisor_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-3 text-slate-900 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                            <option value="">Choose an advisor...</option>
                            <?php foreach ($advisors as $advisor): ?>
                            <option value="<?= $advisor['id'] ?>">
                                <?= htmlspecialchars($advisor['first_name'] . ' ' . $advisor['last_name']) ?> 
                                (<?= htmlspecialchars($advisor['email']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Note:</strong> Once assigned, this portal will be locked to the selected advisor. 
                            They will receive all commissions from this client's bookings.
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 font-bold transition shadow-lg">
                            <i class="fas fa-user-check mr-2"></i>Assign to Advisor
                        </button>
                        <a href="company-portals.php" class="border-2 border-slate-300 px-8 py-3 rounded-lg hover:bg-slate-50 font-semibold text-slate-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
