<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';

require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id']; // Replace with actual session

// Get advisor's statistics
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE referred_by = ? OR user_id = ?");
$stmt->execute([$advisor_id, $advisor_id]);
$total_bookings = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(total_price) FROM bookings WHERE (referred_by = ? OR user_id = ?) AND status IN ('confirmed', 'completed')");
$stmt->execute([$advisor_id, $advisor_id]);
$total_sales = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'client' AND sponsor_id = ?");
$stmt->execute([$advisor_id]);
$total_clients = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(commission_amount) FROM commissions WHERE user_id = ? AND status = 'paid'");
$stmt->execute([$advisor_id]);
$total_commissions = $stmt->fetchColumn() ?: 0;

// Recent bookings
$stmt = $pdo->prepare("SELECT b.*, t.name as tour_name, c.name as country_name FROM bookings b JOIN tours t ON b.tour_id = t.id LEFT JOIN countries c ON t.country_id = c.id WHERE b.user_id = ? ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute([$advisor_id]);
$recent_bookings = $stmt->fetchAll();

// Monthly performance
$stmt = $pdo->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as bookings, SUM(total_price) as revenue FROM bookings WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY month ORDER BY month");
$stmt->execute([$advisor_id]);
$monthly_data = $stmt->fetchAll();

// Top selling tours
$stmt = $pdo->prepare("SELECT t.name, c.name as country_name, COUNT(b.id) as sales FROM bookings b JOIN tours t ON b.tour_id = t.id LEFT JOIN countries c ON t.country_id = c.id WHERE b.user_id = ? GROUP BY t.id ORDER BY sales DESC LIMIT 5");
$stmt->execute([$advisor_id]);
$top_tours = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Advisor Dashboard</h2>
                <p class="text-sm text-slate-600">Sales & Client Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>My Bookings
                </a>
                <a href="team.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users mr-3"></i>My Team
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Available Tours
                </a>
                <a href="commissions.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-dollar-sign mr-3"></i>Commissions
                </a>
                <a href="marketing.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-bullhorn mr-3"></i>Marketing Tools
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-bar mr-3"></i>Sales Reports
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
                <h1 class="text-3xl font-bold text-gradient">Advisor Dashboard</h1>
                <p class="text-slate-600">Track your sales performance and manage clients</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_bookings; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Sales</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_sales); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">My Clients</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_clients; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Commissions Earned</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_commissions); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-golden-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-golden-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Monthly Sales Performance</h3>
                    <div id="salesChart" style="height: 250px;"></div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Top Selling Tours</h3>
                    <div class="space-y-3">
                        <?php foreach ($top_tours as $tour): ?>
                        <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <div>
                                <p class="font-semibold"><?php echo htmlspecialchars($tour['name']); ?></p>
                                <p class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                            </div>
                            <span class="font-bold text-golden-600"><?php echo $tour['sales']; ?> sales</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Sales Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Commission Trends</h3>
                    <div id="commissionChart" style="height: 250px;"></div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-4">Booking Status</h3>
                    <div id="statusChart" style="height: 250px;"></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-plus-circle text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">New Booking</h3>
                    <p class="text-slate-600 mb-4">Create a new booking for your clients</p>
                    <a href="bookings.php?action=new" class="btn-primary px-4 py-2 rounded-lg">Create Booking</a>
                </div>
                
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-user-plus text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Add Client</h3>
                    <p class="text-slate-600 mb-4">Register a new client to your network</p>
                    <a href="clients.php?action=add" class="btn-primary px-4 py-2 rounded-lg">Add Client</a>
                </div>
                
                <div class="nextcloud-card p-6 text-center">
                    <i class="fas fa-share-alt text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-lg font-bold mb-2">Share Tours</h3>
                    <p class="text-slate-600 mb-4">Get marketing materials and links</p>
                    <a href="marketing.php" class="btn-primary px-4 py-2 rounded-lg">Get Materials</a>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="nextcloud-card p-6">
                <h3 class="text-lg font-bold mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Customer</th>
                                <th class="text-left py-2">Tour</th>
                                <th class="text-left py-2">Destination</th>
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
        // Sales Chart
        const salesChart = echarts.init(document.getElementById('salesChart'));
        const salesOption = {
            xAxis: {
                type: 'category',
                data: <?php echo json_encode(array_column($monthly_data, 'month')); ?>
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                data: <?php echo json_encode(array_column($monthly_data, 'revenue')); ?>,
                type: 'bar',
                itemStyle: {
                    color: '#DAA520'
                }
            }]
        };
        salesChart.setOption(salesOption);

        // Commission Trends Chart
        const commissionChart = echarts.init(document.getElementById('commissionChart'));
        const commissionOption = {
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: <?php echo json_encode(array_column($monthly_data, 'month')); ?>
            },
            yAxis: { type: 'value' },
            series: [{
                name: 'Commission',
                data: <?php echo json_encode(array_map(function($r) { return $r * 0.1; }, array_column($monthly_data, 'revenue'))); ?>,
                type: 'bar',
                itemStyle: { color: '#10B981' }
            }]
        };
        commissionChart.setOption(commissionOption);

        // Booking Status Chart
        const statusChart = echarts.init(document.getElementById('statusChart'));
        const statusOption = {
            tooltip: { trigger: 'item' },
            series: [{
                type: 'pie',
                radius: '60%',
                data: [
                    { value: <?php echo $total_bookings * 0.6; ?>, name: 'Confirmed', itemStyle: { color: '#10B981' } },
                    { value: <?php echo $total_bookings * 0.3; ?>, name: 'Pending', itemStyle: { color: '#F59E0B' } },
                    { value: <?php echo $total_bookings * 0.1; ?>, name: 'Cancelled', itemStyle: { color: '#EF4444' } }
                ]
            }]
        };
        statusChart.setOption(statusOption);
    </script>
</body>
</html>