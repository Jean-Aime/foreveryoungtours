<?php
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get analytics data
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM bookings");
$stmt->execute();
$total_bookings = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT SUM(total_price) as revenue FROM bookings WHERE status IN ('confirmed', 'completed')");
$stmt->execute();
$total_revenue = $stmt->fetchColumn() ?: 0;

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role IN ('mca', 'advisor')");
$stmt->execute();
$total_agents = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">Reports & Analytics</p>
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
                <a href="mca-management.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users-cog mr-3"></i>MCA Management
                </a>
                <a href="reports.php" class="nav-item active block px-6 py-3">
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">Reports & Analytics</h1>
                <p class="text-slate-600">Platform performance and business insights</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo number_format($total_bookings); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_revenue); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Active Agents</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo number_format($total_agents); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Coming Soon -->
            <div class="nextcloud-card p-12 text-center">
                <i class="fas fa-chart-line text-6xl text-slate-300 mb-6"></i>
                <h2 class="text-2xl font-bold text-slate-600 mb-4">Advanced Analytics Coming Soon</h2>
                <p class="text-slate-500 mb-6">Detailed reports, charts, and business intelligence features are being developed.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <i class="fas fa-chart-pie text-blue-600 mb-2"></i>
                        <h3 class="font-semibold">Revenue Analytics</h3>
                        <p class="text-sm text-slate-600">Detailed revenue breakdowns and trends</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <i class="fas fa-users-cog text-green-600 mb-2"></i>
                        <h3 class="font-semibold">Agent Performance</h3>
                        <p class="text-sm text-slate-600">MCA and Advisor performance metrics</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <i class="fas fa-map-marked-alt text-purple-600 mb-2"></i>
                        <h3 class="font-semibold">Destination Insights</h3>
                        <p class="text-sm text-slate-600">Popular destinations and booking patterns</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>