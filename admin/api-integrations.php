<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/csrf.php';
checkAuth('super_admin');

$success = $error = '';

if ($_POST && isset($_POST['action'])) {
    verifyCSRF();
    try {
        if ($_POST['action'] == 'add') {
            $stmt = $pdo->prepare("INSERT INTO api_integrations (provider, api_type, api_key, api_secret, endpoint_url, is_active, config) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['provider'],
                $_POST['api_type'],
                $_POST['api_key'],
                $_POST['api_secret'],
                $_POST['endpoint_url'],
                isset($_POST['is_active']) ? 1 : 0,
                json_encode($_POST['config'] ?? [])
            ]);
            $success = 'Integration added successfully!';
        } elseif ($_POST['action'] == 'toggle') {
            $stmt = $pdo->prepare("UPDATE api_integrations SET is_active = NOT is_active WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $success = 'Status updated!';
        }
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$integrations = $pdo->query("SELECT * FROM api_integrations ORDER BY created_at DESC")->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">API Integrations</h1>
                <button onclick="showModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-plus mr-2"></i>Add Integration
                </button>
            </div>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($integrations as $int): ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($int['provider']) ?></h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= ucwords(str_replace('_', ' ', $int['api_type'])) ?>
                            </span>
                        </div>
                        <form method="POST" class="inline">
                            <?= csrfField() ?>
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= $int['id'] ?>">
                            <button type="submit" class="text-sm">
                                <span class="px-2 py-1 rounded-full <?= $int['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $int['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </button>
                        </form>
                    </div>
                    <div class="text-sm text-slate-600 space-y-2">
                        <div><strong>Endpoint:</strong> <?= htmlspecialchars($int['endpoint_url']) ?></div>
                        <div><strong>Added:</strong> <?= date('M j, Y', strtotime($int['created_at'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Add API Integration</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="add">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Provider *</label>
                <input type="text" name="provider" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">API Type *</label>
                <select name="api_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                    <option value="car_rental">Car Rental</option>
                    <option value="activity">Activity</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">API Key *</label>
                <input type="text" name="api_key" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">API Secret</label>
                <input type="password" name="api_secret" class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Endpoint URL *</label>
                <input type="url" name="endpoint_url" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" checked class="mr-2 rounded border-slate-300">
                    <span class="text-sm font-medium text-slate-700">Active</span>
                </label>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                    Add Integration
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

<?php require_once 'includes/admin-footer.php'; ?>
