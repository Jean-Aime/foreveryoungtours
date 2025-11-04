<?php
session_start();
require_once '../config/database.php';

// No authentication required

// Get filter parameters
$status_filter = $_GET['status'] ?? '';
$user_filter = $_GET['user'] ?? '';
$type_filter = $_GET['type'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$where_conditions = [];
$params = [];

if ($status_filter) {
    $where_conditions[] = "c.status = ?";
    $params[] = $status_filter;
}
if ($user_filter) {
    $where_conditions[] = "c.user_id = ?";
    $params[] = $user_filter;
}
if ($type_filter) {
    $where_conditions[] = "c.commission_type = ?";
    $params[] = $type_filter;
}
if ($date_from) {
    $where_conditions[] = "DATE(c.created_at) >= ?";
    $params[] = $date_from;
}
if ($date_to) {
    $where_conditions[] = "DATE(c.created_at) <= ?";
    $params[] = $date_to;
}

$where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get commissions with related data
$query = "
    SELECT 
        c.*,
        CONCAT(u.first_name, ' ', u.last_name) as user_name,
        u.email as user_email,
        u.role as user_role,
        b.booking_reference,
        b.customer_name,
        b.total_amount as booking_amount,
        t.name as tour_name
    FROM commissions c
    JOIN users u ON c.user_id = u.id
    JOIN bookings b ON c.booking_id = b.id
    JOIN tours t ON b.tour_id = t.id
    $where_clause
    ORDER BY c.created_at DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$commissions = $stmt->fetchAll();

// Get summary statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_commissions,
        SUM(commission_amount) as total_amount,
        SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END) as pending_amount,
        SUM(CASE WHEN status = 'approved' THEN commission_amount ELSE 0 END) as approved_amount,
        SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as paid_amount
    FROM commissions c
    $where_clause
";

$stats_stmt = $pdo->prepare($stats_query);
$stats_stmt->execute($params);
$stats = $stats_stmt->fetch();

// Get users for filter
$users = $pdo->query("
    SELECT id, CONCAT(first_name, ' ', last_name) as name, role 
    FROM users 
    WHERE role IN ('advisor', 'mca') 
    ORDER BY first_name
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Management | iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Commission Management</h1>
                    <div class="flex items-center space-x-4">
                        <a href="superadmin-bookings.php" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
                        </a>

                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-percentage text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Commissions</p>
                            <p class="text-2xl font-bold text-gray-900">$<?= number_format($stats['total_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">$<?= number_format($stats['pending_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">$<?= number_format($stats['approved_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Paid</p>
                            <p class="text-2xl font-bold text-gray-900">$<?= number_format($stats['paid_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">All Status</option>
                                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="paid" <?= $status_filter === 'paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                            <select name="user" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">All Users</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= $user_filter == $user['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['name']) ?> (<?= ucfirst($user['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">All Types</option>
                                <option value="direct" <?= $type_filter === 'direct' ? 'selected' : '' ?>>Direct</option>
                                <option value="level1" <?= $type_filter === 'level1' ? 'selected' : '' ?>>Level 1</option>
                                <option value="level2" <?= $type_filter === 'level2' ? 'selected' : '' ?>>Level 2</option>
                                <option value="level3" <?= $type_filter === 'level3' ? 'selected' : '' ?>>Level 3</option>
                                <option value="override" <?= $type_filter === 'override' ? 'selected' : '' ?>>Override</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="date_from" value="<?= $date_from ?>" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="date_to" value="<?= $date_to ?>" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Bulk Actions</h3>
                        <div class="flex space-x-3">
                            <button onclick="bulkApprove()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-check mr-2"></i>Approve Selected
                            </button>
                            <button onclick="bulkPay()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                <i class="fas fa-dollar-sign mr-2"></i>Mark as Paid
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commissions Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commission</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($commissions as $commission): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="commission-checkbox" value="<?= $commission['id'] ?>">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($commission['user_name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($commission['user_email']) ?></div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                <?= $commission['user_role'] === 'advisor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                                <?= ucfirst($commission['user_role']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= $commission['booking_reference'] ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($commission['customer_name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($commission['tour_name']) ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">$<?= number_format($commission['commission_amount'], 2) ?></div>
                                            <div class="text-sm text-gray-500"><?= $commission['commission_rate'] ?>% of $<?= number_format($commission['booking_amount'], 2) ?></div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <?= ucfirst($commission['commission_type']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $status_colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'paid' => 'bg-green-100 text-green-800'
                                        ];
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $status_colors[$commission['status']] ?>">
                                            <?= ucfirst($commission['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($commission['created_at'])) ?></div>
                                        <?php if ($commission['paid_date']): ?>
                                            <div class="text-sm text-gray-500">Paid: <?= date('M j, Y', strtotime($commission['paid_date'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <?php if ($commission['status'] === 'pending'): ?>
                                                <button onclick="updateCommissionStatus(<?= $commission['id'] ?>, 'approved')" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($commission['status'] === 'approved'): ?>
                                                <button onclick="updateCommissionStatus(<?= $commission['id'] ?>, 'paid')" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="viewCommissionDetails(<?= $commission['id'] ?>)" class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.commission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function getSelectedCommissions() {
            const checkboxes = document.querySelectorAll('.commission-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function bulkApprove() {
            const selected = getSelectedCommissions();
            if (selected.length === 0) {
                alert('Please select commissions to approve');
                return;
            }
            
            if (confirm(`Approve ${selected.length} selected commissions?`)) {
                bulkUpdateStatus(selected, 'approved');
            }
        }

        function bulkPay() {
            const selected = getSelectedCommissions();
            if (selected.length === 0) {
                alert('Please select commissions to mark as paid');
                return;
            }
            
            if (confirm(`Mark ${selected.length} selected commissions as paid?`)) {
                bulkUpdateStatus(selected, 'paid');
            }
        }

        function bulkUpdateStatus(ids, status) {
            fetch('update-commission-status.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ids: ids, status: status, bulk: true})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating commission status');
                }
            });
        }

        function updateCommissionStatus(id, status) {
            if (confirm(`Are you sure you want to mark this commission as ${status}?`)) {
                fetch('update-commission-status.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ids: [id], status: status})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating commission status');
                    }
                });
            }
        }

        function viewCommissionDetails(id) {
            // Implementation for viewing commission details
            alert('Commission details view - to be implemented');
        }
    </script>
</body>
</html>