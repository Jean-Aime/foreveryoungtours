<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('client');

$success = $error = '';

if ($_POST) {
    try {
        $stmt = $pdo->prepare("INSERT INTO visa_services (user_id, destination_country, visa_type, passport_number, passport_expiry, travel_date, fee_amount, notes) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['destination_country'],
            $_POST['visa_type'],
            $_POST['passport_number'],
            $_POST['passport_expiry'],
            $_POST['travel_date'],
            $_POST['fee_amount'] ?? 0,
            $_POST['notes']
        ]);
        $success = 'Visa application submitted successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$applications = $pdo->prepare("SELECT * FROM visa_services WHERE user_id = ? ORDER BY created_at DESC");
$applications->execute([$_SESSION['user_id']]);
$apps = $applications->fetchAll();

require_once 'includes/client-header.php';
?>

<main class="flex-1 min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Visa Services</h1>
                <button onclick="showModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-plus mr-2"></i>Apply for Visa
                </button>
            </div>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($apps as $app): ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($app['destination_country']) ?></h3>
                            <p class="text-sm text-slate-600"><?= htmlspecialchars($app['visa_type']) ?></p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            <?= $app['application_status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                               ($app['application_status'] == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <?= ucfirst($app['application_status']) ?>
                        </span>
                    </div>
                    <div class="text-sm text-slate-600 space-y-2">
                        <div><strong>Travel Date:</strong> <?= date('M j, Y', strtotime($app['travel_date'])) ?></div>
                        <div><strong>Fee:</strong> $<?= number_format($app['fee_amount'], 2) ?></div>
                        <div><strong>Applied:</strong> <?= date('M j, Y', strtotime($app['created_at'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Apply for Visa</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Destination Country *</label>
                <input type="text" name="destination_country" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Visa Type *</label>
                <select name="visa_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    <option value="Tourist">Tourist</option>
                    <option value="Business">Business</option>
                    <option value="Transit">Transit</option>
                    <option value="Student">Student</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Passport Number *</label>
                <input type="text" name="passport_number" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Passport Expiry *</label>
                <input type="date" name="passport_expiry" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Travel Date *</label>
                <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Additional Notes</label>
                <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}
</script>

<?php require_once 'includes/client-footer.php'; ?>
