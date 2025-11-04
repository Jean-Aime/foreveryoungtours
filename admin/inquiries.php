<?php
$page_title = 'Tour Inquiries';
$page_subtitle = 'Manage Custom Tour Inquiries';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Get filters
$status = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$where = [];
$params = [];

if ($status) {
    $where[] = "status = ?";
    $params[] = $status;
}
if ($date_from) {
    $where[] = "created_at >= ?";
    $params[] = $date_from;
}
if ($date_to) {
    $where[] = "created_at <= ?";
    $params[] = $date_to . ' 23:59:59';
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Get inquiries
$sql = "SELECT * FROM booking_inquiries $whereClause ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();

// Get statistics
$stats_sql = "SELECT
    COUNT(*) as total_inquiries,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM booking_inquiries";
$stats = $pdo->query($stats_sql)->fetch();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Tour Inquiries</h1>
            <p class="text-slate-600">Manage custom tour inquiries and requests</p>
        </div>
        
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Inquiries</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['total_inquiries'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600"><?= $stats['pending'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Confirmed</h3>
                <p class="text-3xl font-bold text-green-600"><?= $stats['confirmed'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Cancelled</h3>
                <p class="text-3xl font-bold text-red-600"><?= $stats['cancelled'] ?></p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= $status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <input type="date" name="date_from" value="<?= $date_from ?>" class="border rounded px-3 py-2" placeholder="From Date">
                <input type="date" name="date_to" value="<?= $date_to ?>" class="border rounded px-3 py-2" placeholder="To Date">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <!-- Inquiries Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($inquiries as $inquiry): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INQ-<?= $inquiry['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($inquiry['client_name']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($inquiry['email']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($inquiry['tour_name'] ?: 'Custom Tour') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($inquiry['travel_dates']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($inquiry['budget']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                switch($inquiry['status']) {
                                    case 'confirmed': echo 'bg-green-100 text-green-800'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($inquiry['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewInquiry(<?= $inquiry['id'] ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                            <?php if ($inquiry['status'] == 'pending'): ?>
                                <button onclick="confirmInquiry(<?= $inquiry['id'] ?>)" class="text-green-600 hover:text-green-900">Confirm</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</main>

<script>
function viewInquiry(id) {
    window.open('booking-details.php?id=' + id + '&source=inquiry', '_blank', 'width=800,height=600');
}

function confirmInquiry(id) {
    if (confirm('Confirm this inquiry?')) {
        fetch('booking-actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'confirm', booking_id: id, source: 'inquiry'})
        }).then(() => location.reload());
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
