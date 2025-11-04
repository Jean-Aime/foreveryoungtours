<?php
require_once '../config/database.php';

$page_title = 'Client Packages';
$current_page = 'client-packages';

// Fetch all client bookings with package details
$packages = $pdo->query("
    SELECT b.*, t.name as tour_name, t.destination
    FROM bookings b
    LEFT JOIN tours t ON b.tour_id = t.id
    ORDER BY b.created_at DESC
")->fetchAll();

include 'includes/admin-header.php';
include 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Client Packages</h1>
            <div class="flex gap-3">
                <select id="statusFilter" onchange="filterPackages()" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="paid">Paid</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600">Total Packages</div>
                <div class="text-2xl font-bold text-gray-900"><?= count($packages) ?></div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600">Active Packages</div>
                <div class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($packages, fn($p) => in_array($p['status'], ['confirmed', 'paid']))) ?>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600">Pending</div>
                <div class="text-2xl font-bold text-yellow-600">
                    <?= count(array_filter($packages, fn($p) => $p['status'] === 'pending')) ?>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-600">Total Revenue</div>
                <div class="text-2xl font-bold text-blue-600">
                    $<?= number_format(array_sum(array_column($packages, 'total_amount')), 2) ?>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($packages as $package): ?>
                    <tr class="package-row" data-status="<?= $package['status'] ?>">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= $package['booking_reference'] ?></td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($package['customer_name']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($package['customer_email']) ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($package['tour_name']) ?></div>
                            <div class="text-sm text-gray-500"><?= $package['participants'] ?> participants</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900"><?= date('M d, Y', strtotime($package['travel_date'])) ?></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">$<?= number_format($package['total_amount'], 2) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= $package['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                    ($package['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($package['status'] === 'paid' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) ?>">
                                <?= ucfirst($package['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="booking-details.php?id=<?= $package['id'] ?>&source=bookings" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function filterPackages() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.package-row');
    rows.forEach(row => {
        if (!status || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php include 'includes/admin-footer.php'; ?>
