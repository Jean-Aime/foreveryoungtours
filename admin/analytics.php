<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Reports - Admin | iForYoungTours</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body>

<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Admin Sidebar -->
        <div class="w-64 bg-white shadow-sm h-screen fixed left-0 top-0 overflow-y-auto">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gray-900">Admin Panel</h2>
                <p class="text-sm text-gray-600">Management Dashboard</p>
            </div>
            <nav class="p-6">
                <div class="space-y-2">
                    <a href="index.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Overview</a>
                    <a href="tours.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Tours & Packages</a>
                    <a href="bookings.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Bookings</a>
                    <a href="users.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Users</a>
                    <a href="partners.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">Partners</a>
                    <a href="analytics.php" class="nav-item active flex items-center px-4 py-3 text-sm font-medium rounded-lg">Analytics</a>
                    <a href="../pages/dashboard.php" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">User Dashboard</a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Analytics & Reports</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Monthly Revenue</h3>
                    <div id="monthly-revenue-chart" style="height: 300px;"></div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Popular Destinations</h3>
                    <div id="destinations-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const revenueChart = echarts.init(document.getElementById('monthly-revenue-chart'));
    revenueChart.setOption({
        xAxis: { type: 'category', data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
        yAxis: { type: 'value' },
        series: [{ data: [65000, 72000, 68000, 89000, 95000, 89420], type: 'line', smooth: true }]
    });

    // Destinations Chart
    const destChart = echarts.init(document.getElementById('destinations-chart'));
    destChart.setOption({
        series: [{
            type: 'pie',
            data: [
                { value: 35, name: 'Kenya' },
                { value: 25, name: 'Tanzania' },
                { value: 20, name: 'Morocco' },
                { value: 20, name: 'South Africa' }
            ]
        }]
    });
});
</script>

</body>
</html>