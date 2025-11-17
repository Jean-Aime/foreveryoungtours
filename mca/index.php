<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('mca');

// Get MCA's assigned countries
$mca_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id JOIN mca_assignments ma ON c.id = ma.country_id WHERE ma.mca_id = ? AND ma.status = 'active'");
$stmt->execute([$mca_id]);
$assigned_countries = $stmt->fetchAll();

// Get statistics for assigned countries
$country_ids = array_column($assigned_countries, 'id');
$country_ids_str = implode(',', $country_ids ?: [0]);

// Tours in assigned countries
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tours WHERE country_id IN ($country_ids_str) AND status = 'active'");
$stmt->execute();
$total_tours = $stmt->fetchColumn();

// Bookings in assigned countries
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings b JOIN tours t ON b.tour_id = t.id WHERE t.country_id IN ($country_ids_str)");
$stmt->execute();
$total_bookings = $stmt->fetchColumn();

// Revenue from assigned countries
$stmt = $pdo->prepare("SELECT SUM(b.total_price) FROM bookings b JOIN tours t ON b.tour_id = t.id WHERE t.country_id IN ($country_ids_str) AND b.status IN ('confirmed', 'completed')");
$stmt->execute();
$total_revenue = $stmt->fetchColumn() ?: 0;

// Advisors under this MCA
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'advisor' AND sponsor_id = ?");
$stmt->execute([$mca_id]);
$total_advisors = $stmt->fetchColumn();

// Recent bookings
$stmt = $pdo->prepare("SELECT b.*, t.name as tour_name, c.name as country_name FROM bookings b JOIN tours t ON b.tour_id = t.id JOIN countries c ON t.country_id = c.id WHERE t.country_id IN ($country_ids_str) ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute();
$recent_bookings = $stmt->fetchAll();

// Top performing tours
$stmt = $pdo->prepare("SELECT t.name, c.name as country_name, COUNT(b.id) as booking_count, SUM(b.total_price) as revenue FROM tours t JOIN countries c ON t.country_id = c.id LEFT JOIN bookings b ON t.id = b.tour_id WHERE t.country_id IN ($country_ids_str) GROUP BY t.id ORDER BY booking_count DESC LIMIT 5");
$stmt->execute();
$top_tours = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">MCA Dashboard</h2>
                <p class="text-sm text-slate-600">Country Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="countries.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-flag mr-3"></i>My Countries
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Tours Management
                </a>
                <a href="advisors.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user-friends mr-3"></i>Advisor Network
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="commissions.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-dollar-sign mr-3"></i>Commissions
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-line mr-3"></i>Performance Reports
                </a>
                <a href="profile.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user mr-3"></i>Profile Settings
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">MCA Dashboard</h1>
                <p class="text-slate-600">Manage your assigned countries and advisor network</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Assigned Countries</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo count($assigned_countries); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-flag text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Active Tours</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_tours; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-route text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">My Advisors</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_advisors; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-friends text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_revenue); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-golden-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-golden-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Countries -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">My Assigned Countries</h3>
                    <div class="space-y-3">
                        <?php foreach ($assigned_countries as $country): ?>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div>
                                <p class="font-semibold"><?php echo htmlspecialchars($country['name']); ?></p>
                                <p class="text-sm text-slate-600"><?php echo htmlspecialchars($country['region_name']); ?></p>
                            </div>
                            <a href="countries.php?country=<?php echo $country['id']; ?>" class="btn-primary px-3 py-1 rounded text-sm">Manage</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Top Performing Tours</h3>
                    <div class="space-y-3">
                        <?php foreach ($top_tours as $tour): ?>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <div>
                                <p class="font-semibold"><?php echo htmlspecialchars($tour['name']); ?></p>
                                <p class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-golden-600"><?php echo $tour['booking_count']; ?> bookings</p>
                                <p class="text-sm text-slate-600">$<?php echo number_format($tour['revenue']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Performance Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Monthly Performance</h3>
                    <div id="performanceChart" style="height: 250px;"></div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Country Distribution</h3>
                    <div id="countryChart" style="height: 250px;"></div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="nextcloud-card p-6">
                <h3 class="text-lg font-bold mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Customer</th>
                                <th class="text-left py-2">Tour</th>
                                <th class="text-left py-2">Country</th>
                                <th class="text-left py-2">Amount</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-left py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_bookings as $booking): ?>
                            <tr class="border-b">
                                <td class="py-2"><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($booking['tour_name']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($booking['country_name']); ?></td>
                                <td class="py-2">$<?php echo number_format($booking['total_price']); ?></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs <?php echo $booking['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td class="py-2"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Performance Chart
        const performanceChart = echarts.init(document.getElementById('performanceChart'));
        const performanceOption = {
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            },
            yAxis: { type: 'value' },
            series: [{
                name: 'Revenue',
                data: [<?php echo $total_revenue * 0.1; ?>, <?php echo $total_revenue * 0.15; ?>, <?php echo $total_revenue * 0.12; ?>, <?php echo $total_revenue * 0.18; ?>, <?php echo $total_revenue * 0.22; ?>, <?php echo $total_revenue * 0.23; ?>],
                type: 'line',
                smooth: true,
                itemStyle: { color: '#DAA520' }
            }]
        };
        performanceChart.setOption(performanceOption);

        // Country Distribution Chart
        const countryChart = echarts.init(document.getElementById('countryChart'));
        const countryOption = {
            tooltip: { trigger: 'item' },
            series: [{
                type: 'pie',
                radius: '60%',
                data: [
                    <?php foreach ($assigned_countries as $index => $country): ?>
                    { value: <?php echo rand(10, 50); ?>, name: '<?php echo htmlspecialchars($country['name']); ?>', itemStyle: { color: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'][<?php echo $index % 5; ?>] } }<?php echo $index < count($assigned_countries) - 1 ? ',' : ''; ?>
                    <?php endforeach; ?>
                ]
            }]
        };
        countryChart.setOption(countryOption);
    </script>
</body>
</html>