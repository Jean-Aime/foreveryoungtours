<?php
require_once '../config/database.php';

// Get dashboard statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_bookings,
        SUM(total_amount) as total_revenue,
        COUNT(DISTINCT advisor_id) as total_advisors,
        COUNT(DISTINCT DATE(created_at)) as active_days
    FROM bookings
";
$stats = $pdo->query($stats_query)->fetch();

// Get recent bookings
$recent_bookings = $pdo->query("
    SELECT b.*, t.name as tour_name 
    FROM bookings b 
    LEFT JOIN tours t ON b.tour_id = t.id 
    ORDER BY b.created_at DESC 
    LIMIT 5
")->fetchAll();

// Get commission stats
$commission_stats = $pdo->query("
    SELECT 
        COUNT(*) as total_commissions,
        SUM(commission_amount) as total_amount,
        SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END) as pending_amount,
        SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as paid_amount
    FROM commissions
")->fetch();

// Get content stats
$tours_count = $pdo->query("SELECT COUNT(*) FROM tours WHERE status = 'active'")->fetchColumn();
$destinations_count = $pdo->query("SELECT COUNT(*) FROM countries WHERE status = 'active'")->fetchColumn();
$regions_count = $pdo->query("SELECT COUNT(*) FROM regions WHERE status = 'active'")->fetchColumn();
$blog_posts_count = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status = 'published'")->fetchColumn();
$client_stories_count = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status = 'published' AND user_id IS NOT NULL")->fetchColumn();
$inquiries_count = $pdo->query("SELECT COUNT(*) FROM booking_inquiries")->fetchColumn();
$users_count = $pdo->query("SELECT COUNT(DISTINCT customer_email) FROM bookings")->fetchColumn();
$mcas_count = 0; // Will be populated when users table is created
$advisors_count = $pdo->query("SELECT COUNT(DISTINCT advisor_id) FROM bookings WHERE advisor_id IS NOT NULL")->fetchColumn();

$page_title = 'Dashboard';
include 'includes/admin-header.php';
?>

<?php include 'includes/admin-sidebar.php'; ?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <div class="text-sm text-gray-600">
                Welcome to iForYoungTours Admin
            </div>
        </div>

        <!-- Main Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_bookings']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">$<?= number_format($stats['total_revenue'], 2) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Advisors</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_advisors']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-percentage text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Commissions</p>
                        <p class="text-2xl font-bold text-gray-900">$<?= number_format($commission_stats['total_amount'], 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Management Stats -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Content Overview</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?= $tours_count ?></div>
                        <div class="text-sm text-gray-600 mt-1">Active Tours</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600"><?= $destinations_count ?></div>
                        <div class="text-sm text-gray-600 mt-1">Destinations</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600"><?= $regions_count ?></div>
                        <div class="text-sm text-gray-600 mt-1">Regions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600"><?= $blog_posts_count ?></div>
                        <div class="text-sm text-gray-600 mt-1">Blog Posts</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User & Operations Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $users_count ?></p>
                    </div>
                    <i class="fas fa-users text-3xl text-blue-400"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Client Stories</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $client_stories_count ?></p>
                    </div>
                    <i class="fas fa-star text-3xl text-yellow-400"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending Inquiries</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $inquiries_count ?></p>
                    </div>
                    <i class="fas fa-envelope text-3xl text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                </div>
                <div class="p-6">
                    <?php if (empty($recent_bookings)): ?>
                        <p class="text-gray-500 text-center py-4">No recent bookings</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_bookings as $booking): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($booking['customer_name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($booking['tour_name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= $booking['booking_reference'] ?></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900">$<?= number_format($booking['total_amount'], 2) ?></div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                ($booking['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="bookings.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All Bookings →
                </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-4">
                    <a href="bookings.php" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Manage Bookings
                    </a>
                    <a href="commission-management.php" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 flex items-center justify-center">
                        <i class="fas fa-percentage mr-2"></i>
                        Manage Commissions
                    </a>
                    <a href="mca-dashboard.php" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg hover:bg-purple-700 flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>
                        MCA Dashboard
                    </a>
                    <a href="advisor-dashboard.php" class="w-full bg-yellow-600 text-white py-3 px-4 rounded-lg hover:bg-yellow-700 flex items-center justify-center">
                        <i class="fas fa-user-tie mr-2"></i>
                        Advisor Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Commission Overview -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Commission Overview</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">$<?= number_format($commission_stats['total_amount'], 2) ?></div>
                        <div class="text-sm text-gray-600">Total Commissions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">$<?= number_format($commission_stats['pending_amount'], 2) ?></div>
                        <div class="text-sm text-gray-600">Pending Commissions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">$<?= number_format($commission_stats['paid_amount'], 2) ?></div>
                        <div class="text-sm text-gray-600">Paid Commissions</div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="commission-management.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Manage Commissions →
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/admin-footer.php'; ?>