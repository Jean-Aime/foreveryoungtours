<?php

require_once 'config.php';
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Handle MCA operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'assign_country':
                $stmt = $conn->prepare("INSERT INTO mca_assignments (mca_id, country_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE status = 'active'");
                $stmt->execute([$_POST['mca_id'], $_POST['country_id']]);
                break;
            case 'remove_assignment':
                $stmt = $conn->prepare("UPDATE mca_assignments SET status = 'inactive' WHERE mca_id = ? AND country_id = ?");
                $stmt->execute([$_POST['mca_id'], $_POST['country_id']]);
                break;
        }
    }
}

// Get MCAs only with KYC status
$stmt = $conn->prepare("SELECT u.*, COUNT(ma.country_id) as assigned_countries FROM users u LEFT JOIN mca_assignments ma ON u.id = ma.mca_id AND ma.status = 'active' WHERE u.role = 'mca' GROUP BY u.id ORDER BY u.full_name");
$stmt->execute();
$mcas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all countries
$stmt = $conn->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get MCA assignments
$stmt = $conn->prepare("SELECT ma.*, u.full_name as mca_name, c.name as country_name, r.name as region_name FROM mca_assignments ma JOIN users u ON ma.mca_id = u.id JOIN countries c ON ma.country_id = c.id JOIN regions r ON c.region_id = r.id WHERE ma.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCA Management - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">MCA Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="destinations.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-map-marker-alt mr-3"></i>Destinations
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Tours & Packages
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="advisor-management.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user-tie mr-3"></i>Advisor Management
                </a>
                <a href="mca-management.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-users-cog mr-3"></i>MCA Management
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-bar mr-3"></i>Reports & Analytics
                </a>
                <a href="settings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-cog mr-3"></i>System Settings
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">MCA Management</h1>
                <button onclick="openAssignModal()" class="btn-primary px-6 py-3 rounded-lg">Assign Country</button>
            </div>

            <!-- MCA Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Total MCAs</h3>
                    <p class="text-3xl font-bold text-gradient"><?php echo count($mcas); ?></p>
                </div>
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Countries Assigned</h3>
                    <p class="text-3xl font-bold text-gradient"><?php echo count($assignments); ?></p>
                </div>
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Unassigned Countries</h3>
                    <p class="text-3xl font-bold text-gradient"><?php echo count($countries) - count($assignments); ?></p>
                </div>
            </div>

            <!-- MCA List -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">MCA Overview</h2>
                <div class="nextcloud-card overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">MCA Name</th>
                                <th class="text-left p-4">Email</th>
                                <th class="text-left p-4">Assigned Countries</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mcas as $mca): ?>
                            <tr class="border-b">
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($mca['full_name']); ?></p>
                                        <p class="text-sm text-slate-600">@<?php echo htmlspecialchars($mca['username']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4"><?php echo htmlspecialchars($mca['email']); ?></td>
                                <td class="p-4"><?php echo $mca['assigned_countries']; ?> countries</td>
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 py-1 rounded text-xs <?php echo $mca['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo ucfirst($mca['status']); ?>
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs <?php 
                                            echo match($mca['kyc_status'] ?? 'pending') {
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                default => 'bg-yellow-100 text-yellow-800'
                                            };
                                        ?>">
                                            KYC: <?php echo ucfirst($mca['kyc_status'] ?? 'Pending'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button onclick="viewMCADetails(<?php echo $mca['id']; ?>)" class="btn-secondary px-3 py-1 rounded text-sm">View Details</button>
                                        <button onclick="assignCountry(<?php echo $mca['id']; ?>)" class="btn-primary px-3 py-1 rounded text-sm">Assign Country</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Current Assignments -->
            <div>
                <h2 class="text-2xl font-bold mb-4">Current Country Assignments</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $assignments_by_region = [];
                    foreach ($assignments as $assignment) {
                        $assignments_by_region[$assignment['region_name']][] = $assignment;
                    }
                    ?>
                    <?php foreach ($assignments_by_region as $region => $region_assignments): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-lg font-bold mb-4"><?php echo htmlspecialchars($region); ?></h3>
                        <div class="space-y-3">
                            <?php foreach ($region_assignments as $assignment): ?>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="font-semibold"><?php echo htmlspecialchars($assignment['country_name']); ?></p>
                                    <p class="text-sm text-slate-600"><?php echo htmlspecialchars($assignment['mca_name']); ?></p>
                                </div>
                                <button onclick="removeAssignment(<?php echo $assignment['mca_id']; ?>, <?php echo $assignment['country_id']; ?>)" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Country Modal -->
    <div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Assign Country to MCA</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="assign_country">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Select MCA</label>
                        <select name="mca_id" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Choose MCA</option>
                            <?php foreach ($mcas as $mca): ?>
                            <option value="<?php echo $mca['id']; ?>"><?php echo htmlspecialchars($mca['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Select Country</label>
                        <select name="country_id" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Choose Country</option>
                            <?php foreach ($countries as $country): ?>
                            <option value="<?php echo $country['id']; ?>"><?php echo htmlspecialchars($country['region_name'] . ' - ' . $country['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeAssignModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Assign Country</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal() {
            document.getElementById('assignModal').classList.remove('hidden');
        }
        
        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
        
        function assignCountry(mcaId) {
            document.querySelector('select[name="mca_id"]').value = mcaId;
            openAssignModal();
        }
        
        function viewMCADetails(mcaId) {
            window.location.href = 'mca-details.php?id=' + mcaId;
        }
        
        function removeAssignment(mcaId, countryId) {
            if (confirm('Are you sure you want to remove this country assignment?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="remove_assignment">
                    <input type="hidden" name="mca_id" value="${mcaId}">
                    <input type="hidden" name="country_id" value="${countryId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>