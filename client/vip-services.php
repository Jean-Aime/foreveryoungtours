<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('client');

$success = $error = '';

if ($_POST) {
    try {
        $stmt = $pdo->prepare("INSERT INTO vip_services (user_id, service_type, service_date, location, details, price) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['service_type'],
            $_POST['service_date'],
            $_POST['location'],
            $_POST['details'],
            $_POST['price'] ?? 0
        ]);
        $success = 'VIP service requested successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$services = $pdo->prepare("SELECT * FROM vip_services WHERE user_id = ? ORDER BY created_at DESC");
$services->execute([$_SESSION['user_id']]);
$vip_services = $services->fetchAll();

require_once 'includes/client-header.php';
?>

<main class="flex-1 min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">VIP Services</h1>
                <button onclick="showModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600">
                    <i class="fas fa-star mr-2"></i>Request VIP Service
                </button>
            </div>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                    <i class="fas fa-car text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Airport Transfer</h3>
                    <p class="text-sm text-slate-600">Luxury vehicle pickup and drop-off</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                    <i class="fas fa-concierge-bell text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Concierge Service</h3>
                    <p class="text-sm text-slate-600">24/7 personal assistance</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border border-blue-200">
                    <i class="fas fa-route text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Private Tour</h3>
                    <p class="text-sm text-slate-600">Exclusive guided experiences</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                    <i class="fas fa-couch text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Lounge Access</h3>
                    <p class="text-sm text-slate-600">Premium airport lounge entry</p>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-4">My VIP Requests</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($vip_services as $service): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                <?= ucwords(str_replace('_', ' ', $service['service_type'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                <?= date('M j, Y H:i', strtotime($service['service_date'])) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= htmlspecialchars($service['location']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                $<?= number_format($service['price'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $service['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($service['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($service['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-900">Request VIP Service</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Service Type *</label>
                <select name="service_type" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    <option value="airport_transfer">Airport Transfer</option>
                    <option value="concierge">Concierge Service</option>
                    <option value="private_tour">Private Tour</option>
                    <option value="meet_greet">Meet & Greet</option>
                    <option value="lounge_access">Lounge Access</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Service Date & Time *</label>
                <input type="datetime-local" name="service_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Location *</label>
                <input type="text" name="location" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Details</label>
                <textarea name="details" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                    Submit Request
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
