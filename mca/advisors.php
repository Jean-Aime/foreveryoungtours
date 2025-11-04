<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Demo MCA advisors data
$advisors = [
    ['id' => 5, 'name' => 'Mary Wanjiku', 'email' => 'advisor1@foreveryoungtours.com', 'phone' => '+254704000000', 'country' => 'Kenya', 'total_bookings' => 15, 'total_sales' => 25000, 'status' => 'active', 'joined_date' => '2024-01-15'],
    ['id' => 6, 'name' => 'James Mutua', 'email' => 'advisor2@foreveryoungtours.com', 'phone' => '+254705000000', 'country' => 'Kenya', 'total_bookings' => 12, 'total_sales' => 18000, 'status' => 'active', 'joined_date' => '2024-02-01'],
    ['id' => 7, 'name' => 'Sarah Njeri', 'email' => 'advisor3@foreveryoungtours.com', 'phone' => '+254706000000', 'country' => 'Tanzania', 'total_bookings' => 8, 'total_sales' => 12000, 'status' => 'active', 'joined_date' => '2024-02-15']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Advisors - MCA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gradient">MCA Dashboard</h2>
                <p class="text-slate-600">Demo Mode</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="advisors.php" class="nav-item active block px-6 py-3">My Advisors</a>
                <a href="team_hierarchy.php" class="nav-item block px-6 py-3">Team Structure</a>
                <a href="commissions.php" class="nav-item block px-6 py-3">Commission Reports</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">My Advisors</h1>
                <button onclick="openAddAdvisorModal()" class="btn-primary px-6 py-3 rounded-lg">Add New Advisor</button>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Advisors</h3>
                    <p class="text-3xl font-bold text-golden-600"><?php echo count($advisors); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Active Advisors</h3>
                    <p class="text-3xl font-bold text-primary-green"><?php echo count(array_filter($advisors, fn($a) => $a['status'] === 'active')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Bookings</h3>
                    <p class="text-3xl font-bold text-slate-700"><?php echo array_sum(array_column($advisors, 'total_bookings')); ?></p>
                </div>
                <div class="kpi-card p-6">
                    <h3 class="text-lg font-semibold text-slate-700">Total Sales</h3>
                    <p class="text-3xl font-bold text-golden-700">$<?php echo number_format(array_sum(array_column($advisors, 'total_sales'))); ?></p>
                </div>
            </div>
            
            <!-- Advisors Table -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-6">Advisor Performance</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-4">Advisor</th>
                                <th class="text-left py-3 px-4">Contact</th>
                                <th class="text-left py-3 px-4">Country</th>
                                <th class="text-left py-3 px-4">Bookings</th>
                                <th class="text-left py-3 px-4">Sales</th>
                                <th class="text-left py-3 px-4">Status</th>
                                <th class="text-left py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($advisors as $advisor): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <div>
                                        <div class="font-medium"><?php echo htmlspecialchars($advisor['name']); ?></div>
                                        <div class="text-sm text-slate-500">Joined: <?php echo date('M j, Y', strtotime($advisor['joined_date'])); ?></div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm">
                                        <div><?php echo htmlspecialchars($advisor['email']); ?></div>
                                        <div class="text-slate-500"><?php echo htmlspecialchars($advisor['phone']); ?></div>
                                    </div>
                                </td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($advisor['country']); ?></td>
                                <td class="py-3 px-4 font-bold text-slate-700"><?php echo $advisor['total_bookings']; ?></td>
                                <td class="py-3 px-4 font-bold text-golden-600">$<?php echo number_format($advisor['total_sales']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <?php echo ucfirst($advisor['status']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button onclick="viewAdvisor(<?php echo $advisor['id']; ?>)" class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                                        <button onclick="editAdvisor(<?php echo $advisor['id']; ?>)" class="text-golden-600 hover:text-golden-800 text-sm">Edit</button>
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

    <!-- Add Advisor Modal -->
    <div id="addAdvisorModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Add New Advisor</h3>
            </div>
            <form class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                        <input type="text" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                        <input type="tel" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                        <select required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Country</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Tanzania">Tanzania</option>
                            <option value="Uganda">Uganda</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeAddAdvisorModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Add Advisor</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddAdvisorModal() { document.getElementById('addAdvisorModal').classList.remove('hidden'); }
        function closeAddAdvisorModal() { document.getElementById('addAdvisorModal').classList.add('hidden'); }
        function viewAdvisor(id) { alert('View advisor details - ID: ' + id); }
        function editAdvisor(id) { alert('Edit advisor - ID: ' + id); }
    </script>
</body>
</html>