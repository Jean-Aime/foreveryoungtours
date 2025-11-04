<?php
$page_title = 'Super Admin Dashboard';
$page_subtitle = 'System Overview & Management';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Get statistics
$total_bookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('advisor', 'mca')")->fetchColumn();
$total_tours = $pdo->query("SELECT COUNT(*) FROM tours WHERE status = 'active'")->fetchColumn();
$total_revenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM bookings WHERE status IN ('confirmed', 'paid')")->fetchColumn();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Super Admin Dashboard</h1>
            <p class="text-slate-600">Complete system overview and management tools</p>
        </div>
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($total_bookings) ?></p>
                        <p class="text-xs text-green-600 mt-1">+12% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Team Members</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($total_users) ?></p>
                        <p class="text-xs text-green-600 mt-1">+5% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Active Tours</p>
                        <p class="text-2xl font-bold text-slate-900"><?= number_format($total_tours) ?></p>
                        <p class="text-xs text-blue-600 mt-1">+8% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-slate-900">$<?= number_format($total_revenue, 2) ?></p>
                        <p class="text-xs text-green-600 mt-1">+18% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Management Modules -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Booking Management</h3>
                        <p class="text-sm text-slate-600">Manage all bookings and confirmations</p>
                    </div>
                </div>
                <a href="bookings.php" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">MCA Management</h3>
                        <p class="text-sm text-slate-600">Master Country Agent oversight</p>
                    </div>
                </div>
                <a href="mca-dashboard.php" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Advisor Management</h3>
                        <p class="text-sm text-slate-600">Travel advisor oversight</p>
                    </div>
                </div>
                <a href="advisor-dashboard.php" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Tours Management</h3>
                        <p class="text-sm text-slate-600">Manage packages and destinations</p>
                    </div>
                </div>
                <a href="tours.php" class="inline-flex items-center text-yellow-600 hover:text-yellow-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Commission Management</h3>
                        <p class="text-sm text-slate-600">Track and manage commissions</p>
                    </div>
                </div>
                <a href="commission-management.php" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-bar text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Analytics & Reports</h3>
                        <p class="text-sm text-slate-600">Performance insights and reports</p>
                    </div>
                </div>
                <a href="analytics.php" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                    Access Module <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">Recent Activity</h2>
            <div class="space-y-3">
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900">New booking confirmed</p>
                        <p class="text-xs text-slate-500">2 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900">New advisor registered</p>
                        <p class="text-xs text-slate-500">15 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900">Commission payment processed</p>
                        <p class="text-xs text-slate-500">1 hour ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>