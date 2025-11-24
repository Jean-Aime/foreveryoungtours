<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$period = $_GET['period'] ?? '30';
$date_from = date('Y-m-d', strtotime("-$period days"));

// Revenue metrics
$revenue = $pdo->prepare("SELECT DATE(created_at) as date, SUM(total_amount) as revenue 
                          FROM bookings WHERE created_at >= ? AND status = 'confirmed' 
                          GROUP BY DATE(created_at) ORDER BY date");
$revenue->execute([$date_from]);
$revenue_data = $revenue->fetchAll();

// Booking trends
$bookings = $pdo->prepare("SELECT DATE(created_at) as date, COUNT(*) as count 
                           FROM bookings WHERE created_at >= ? 
                           GROUP BY DATE(created_at) ORDER BY date");
$bookings->execute([$date_from]);
$booking_data = $bookings->fetchAll();

// Forecasting (simple linear regression)
$forecast_days = 30;
$forecast_data = [];
if (count($revenue_data) > 5) {
    $n = count($revenue_data);
    $sum_x = $sum_y = $sum_xy = $sum_x2 = 0;
    foreach ($revenue_data as $i => $row) {
        $sum_x += $i;
        $sum_y += $row['revenue'];
        $sum_xy += $i * $row['revenue'];
        $sum_x2 += $i * $i;
    }
    $slope = ($n * $sum_xy - $sum_x * $sum_y) / ($n * $sum_x2 - $sum_x * $sum_x);
    $intercept = ($sum_y - $slope * $sum_x) / $n;
    
    for ($i = $n; $i < $n + $forecast_days; $i++) {
        $forecast_data[] = [
            'date' => date('Y-m-d', strtotime("+$i days", strtotime($date_from))),
            'forecast' => max(0, $slope * $i + $intercept)
        ];
    }
}

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Advanced Analytics</h1>
                <select onchange="location.href='?period='+this.value" class="border border-slate-300 rounded-lg px-4 py-2">
                    <option value="7" <?= $period == 7 ? 'selected' : '' ?>>Last 7 Days</option>
                    <option value="30" <?= $period == 30 ? 'selected' : '' ?>>Last 30 Days</option>
                    <option value="90" <?= $period == 90 ? 'selected' : '' ?>>Last 90 Days</option>
                    <option value="365" <?= $period == 365 ? 'selected' : '' ?>>Last Year</option>
                </select>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Revenue Trend</h2>
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Booking Trend</h2>
                    <canvas id="bookingChart" height="300"></canvas>
                </div>
            </div>
            
            <?php if (!empty($forecast_data)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">30-Day Revenue Forecast</h2>
                <canvas id="forecastChart" height="200"></canvas>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueData = <?= json_encode($revenue_data) ?>;
const bookingData = <?= json_encode($booking_data) ?>;
const forecastData = <?= json_encode($forecast_data) ?>;

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueData.map(d => d.date),
        datasets: [{
            label: 'Revenue ($)',
            data: revenueData.map(d => d.revenue),
            borderColor: '#DAA520',
            backgroundColor: 'rgba(218, 165, 32, 0.1)',
            tension: 0.4
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels: bookingData.map(d => d.date),
        datasets: [{
            label: 'Bookings',
            data: bookingData.map(d => d.count),
            backgroundColor: '#3b82f6'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

<?php if (!empty($forecast_data)): ?>
new Chart(document.getElementById('forecastChart'), {
    type: 'line',
    data: {
        labels: [...revenueData.map(d => d.date), ...forecastData.map(d => d.date)],
        datasets: [{
            label: 'Actual',
            data: [...revenueData.map(d => d.revenue), ...Array(forecastData.length).fill(null)],
            borderColor: '#DAA520',
            backgroundColor: 'rgba(218, 165, 32, 0.1)'
        }, {
            label: 'Forecast',
            data: [...Array(revenueData.length).fill(null), ...forecastData.map(d => d.forecast)],
            borderColor: '#ef4444',
            borderDash: [5, 5],
            backgroundColor: 'rgba(239, 68, 68, 0.1)'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
<?php endif; ?>
</script>

<?php require_once 'includes/admin-footer.php'; ?>
