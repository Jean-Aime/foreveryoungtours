<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get advisor's statistics
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE advisor_id = ?");
$stmt->execute([$advisor_id]);
$total_bookings = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(total_amount), 0) FROM bookings WHERE advisor_id = ? AND status IN ('confirmed', 'completed')");
$stmt->execute([$advisor_id]);
$total_sales = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'client' AND sponsor_id = ?");
$stmt->execute([$advisor_id]);
$total_clients = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(commission_amount), 0) FROM bookings WHERE advisor_id = ? AND status IN ('confirmed', 'completed')");
$stmt->execute([$advisor_id]);
$total_commissions = $stmt->fetchColumn();

// Recent bookings
$stmt = $pdo->prepare("SELECT b.*, t.name as tour_name, c.name as country_name FROM bookings b JOIN tours t ON b.tour_id = t.id LEFT JOIN countries c ON t.country_id = c.id WHERE b.advisor_id = ? ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute([$advisor_id]);
$recent_bookings = $stmt->fetchAll();

// Monthly performance
$stmt = $pdo->prepare("SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as bookings, COALESCE(SUM(total_amount), 0) as revenue FROM bookings WHERE advisor_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY MONTH(created_at) ORDER BY created_at");
$stmt->execute([$advisor_id]);
$monthly_data = $stmt->fetchAll();

$months = array_column($monthly_data, 'month') ?: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$revenues = array_column($monthly_data, 'revenue') ?: [0, 0, 0, 0, 0, 0];

// Top selling tours
$stmt = $pdo->prepare("SELECT t.name, c.name as country_name, COUNT(b.id) as sales FROM bookings b JOIN tours t ON b.tour_id = t.id LEFT JOIN countries c ON t.country_id = c.id WHERE b.advisor_id = ? GROUP BY t.id ORDER BY sales DESC LIMIT 5");
$stmt->execute([$advisor_id]);
$top_tours = $stmt->fetchAll();

$page_title = 'Dashboard';
$page_subtitle = 'Track your sales performance and manage clients';

include 'includes/advisor-header.php';
?>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Bookings</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo $total_bookings; ?></p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>12% this month</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Sales</p>
                <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_sales); ?></p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>8% this month</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-line text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">My Clients</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo $total_clients; ?></p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>5 new clients</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Commissions</p>
                <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_commissions); ?></p>
                <p class="text-xs text-green-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>15% this month</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-dollar-sign text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Monthly Sales Performance</h3>
        <div id="salesChart" style="height: 300px;"></div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Top Selling Tours</h3>
        <div class="space-y-3">
            <?php if (empty($top_tours)): ?>
            <p class="text-center text-slate-500 py-8">No sales data yet</p>
            <?php else: ?>
            <?php foreach ($top_tours as $index => $tour): ?>
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-white rounded-lg border border-slate-100 hover:shadow-sm transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center text-white font-bold">
                        <?php echo $index + 1; ?>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900"><?php echo htmlspecialchars($tour['name']); ?></p>
                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold"><?php echo $tour['sales']; ?> sales</span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="register-client.php" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-user-plus text-4xl opacity-80"></i>
            <i class="fas fa-arrow-right text-xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Add New Client</h3>
        <p class="text-blue-100">Register a new client to your network</p>
    </a>
    
    <a href="tours.php" class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-route text-4xl opacity-80"></i>
            <i class="fas fa-arrow-right text-xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Browse Tours</h3>
        <p class="text-green-100">Explore available tours to sell</p>
    </a>
    
    <a href="payouts.php" class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <i class="fas fa-money-check-alt text-4xl opacity-80"></i>
            <i class="fas fa-arrow-right text-xl"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Request Payout</h3>
        <p class="text-orange-100">Withdraw your earned commissions</p>
    </a>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Recent Bookings</h3>
            <a href="bookings.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Destination</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($recent_bookings)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No bookings yet</td>
                </tr>
                <?php else: ?>
                <?php foreach ($recent_bookings as $booking): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo htmlspecialchars($booking['tour_name']); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo htmlspecialchars($booking['country_name']); ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">$<?php echo number_format($booking['total_amount']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?php echo match($booking['status']) {
                                'confirmed' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-700'
                            }; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
<script>
const salesChart = echarts.init(document.getElementById('salesChart'));
const salesOption = {
    tooltip: { trigger: 'axis' },
    grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
    xAxis: {
        type: 'category',
        data: <?php echo json_encode($months); ?>,
        axisLine: { lineStyle: { color: '#e2e8f0' } },
        axisLabel: { color: '#64748b' }
    },
    yAxis: {
        type: 'value',
        axisLine: { lineStyle: { color: '#e2e8f0' } },
        axisLabel: { color: '#64748b' },
        splitLine: { lineStyle: { color: '#f1f5f9' } }
    },
    series: [{
        data: <?php echo json_encode($revenues); ?>,
        type: 'bar',
        itemStyle: {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: '#fbbf24' },
                { offset: 1, color: '#f59e0b' }
            ])
        },
        barWidth: '60%'
    }]
};
salesChart.setOption(salesOption);

window.addEventListener('resize', () => {
    salesChart.resize();
});
</script>

<?php include 'includes/advisor-footer.php'; ?>
