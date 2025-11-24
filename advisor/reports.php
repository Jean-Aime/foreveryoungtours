<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Date range filter
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

// Sales summary
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        COALESCE(SUM(total_amount), 0) as total_sales,
        COALESCE(AVG(total_amount), 0) as avg_sale
    FROM bookings 
    WHERE advisor_id = ? AND status IN ('confirmed','completed')
    AND created_at BETWEEN ? AND ?
");
$stmt->execute([$advisor_id, $start_date, $end_date]);
$sales = $stmt->fetch();

// Commission summary
$stmt = $pdo->prepare("
    SELECT 
        commission_type,
        COUNT(*) as count,
        SUM(commission_amount) as total
    FROM commissions 
    WHERE user_id = ? AND created_at BETWEEN ? AND ?
    GROUP BY commission_type
");
$stmt->execute([$advisor_id, $start_date, $end_date]);
$commissions = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Top tours
$stmt = $pdo->prepare("
    SELECT t.name, COUNT(b.id) as sales, SUM(b.total_amount) as revenue
    FROM bookings b
    JOIN tours t ON b.tour_id = t.id
    WHERE b.advisor_id = ? AND b.status IN ('confirmed','completed')
    AND b.created_at BETWEEN ? AND ?
    GROUP BY t.id
    ORDER BY sales DESC
    LIMIT 5
");
$stmt->execute([$advisor_id, $start_date, $end_date]);
$top_tours = $stmt->fetchAll();

// Monthly trend
$stmt = $pdo->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as bookings,
        SUM(total_amount) as revenue
    FROM bookings
    WHERE advisor_id = ? AND status IN ('confirmed','completed')
    AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY month
    ORDER BY month
");
$stmt->execute([$advisor_id]);
$monthly = $stmt->fetchAll();

$page_title = 'Reports';
$page_subtitle = 'Analyze your sales performance';

include 'includes/advisor-header.php';
?>

<!-- Date Filter -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200 mb-6">
    <form method="GET" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-slate-700 mb-2">Start Date</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-slate-700 mb-2">End Date</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Total Bookings</p>
        <p class="text-3xl font-bold text-slate-900"><?php echo $sales['total_bookings']; ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Total Sales</p>
        <p class="text-3xl font-bold text-green-600">$<?php echo number_format($sales['total_sales'], 2); ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <p class="text-sm text-slate-600 mb-1">Average Sale</p>
        <p class="text-3xl font-bold text-blue-600">$<?php echo number_format($sales['avg_sale'], 2); ?></p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Commission Breakdown</h3>
        <div id="commissionChart" style="height: 300px;"></div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Monthly Trend</h3>
        <div id="trendChart" style="height: 300px;"></div>
    </div>
</div>

<!-- Top Tours -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-900">Top Selling Tours</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($top_tours as $tour): ?>
                <tr>
                    <td class="px-6 py-4 text-sm text-slate-900"><?php echo htmlspecialchars($tour['name']); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $tour['sales']; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">$<?php echo number_format($tour['revenue'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
<script>
// Commission Chart
const commChart = echarts.init(document.getElementById('commissionChart'));
commChart.setOption({
    tooltip: { trigger: 'item' },
    series: [{
        type: 'pie',
        radius: '60%',
        data: [
            { value: <?php echo $commissions['direct'] ?? 0; ?>, name: 'Direct', itemStyle: { color: '#10B981' } },
            { value: <?php echo $commissions['level2'] ?? 0; ?>, name: 'Level 2', itemStyle: { color: '#3B82F6' } },
            { value: <?php echo $commissions['level3'] ?? 0; ?>, name: 'Level 3', itemStyle: { color: '#8B5CF6' } }
        ]
    }]
});

// Trend Chart
const trendChart = echarts.init(document.getElementById('trendChart'));
trendChart.setOption({
    tooltip: { trigger: 'axis' },
    xAxis: {
        type: 'category',
        data: <?php echo json_encode(array_column($monthly, 'month')); ?>
    },
    yAxis: { type: 'value' },
    series: [{
        data: <?php echo json_encode(array_column($monthly, 'revenue')); ?>,
        type: 'line',
        smooth: true,
        itemStyle: { color: '#3B82F6' }
    }]
});
</script>

<?php include 'includes/advisor-footer.php'; ?>
