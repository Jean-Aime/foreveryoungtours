<?php

require_once 'config.php';
$page_title = "My Dashboard - iForYoungTours";
$page_description = "Manage your African tour bookings and profile";
$css_path = "../assets/css/modern-styles.css";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Simple email-based authentication (in production, use proper sessions)
$customer_email = $_GET['email'] ?? $_POST['email'] ?? '';

if ($_POST['action'] ?? '' === 'login') {
    $customer_email = $_POST['email'];
    header("Location: client-dashboard.php?email=" . urlencode($customer_email));
    exit;
}

$bookings = [];
$customer_stats = [];

if ($customer_email) {
    // Get customer bookings
    $stmt = $conn->prepare("
        SELECT b.*, t.name as tour_name, t.destination, t.image_url, t.cover_image, c.name as country_name
        FROM bookings b
        LEFT JOIN tours t ON b.tour_id = t.id
        LEFT JOIN countries c ON t.country_id = c.id
        WHERE b.customer_email = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$customer_email]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get customer statistics
    $stats_stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_bookings,
            SUM(total_amount) as total_spent,
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_bookings,
            COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed_bookings
        FROM bookings 
        WHERE customer_email = ?
    ");
    $stats_stmt->execute([$customer_email]);
    $customer_stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
}

include './header.php';
?>

<div class="min-h-screen bg-cream pt-20">
    <?php if (!$customer_email): ?>
    <!-- Login Form -->
    <section class="py-16">
        <div class="max-w-md mx-auto px-4">
            <div class="nextcloud-card p-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Access Your Dashboard</h1>
                    <p class="text-slate-600">Enter your email to view your bookings</p>
                </div>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="login">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                    </div>
                    <button type="submit" class="w-full bg-golden-500 hover:bg-golden-600 text-black py-3 rounded-lg font-semibold transition-colors">
                        Access Dashboard
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <?php else: ?>
    <!-- Dashboard Content -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">My Dashboard</h1>
                        <p class="text-slate-600 mt-1"><?php echo htmlspecialchars($customer_email); ?></p>
                    </div>
                    <a href="packages.php" class="bg-golden-500 hover:bg-golden-600 text-black px-6 py-3 rounded-lg font-semibold transition-colors">
                        Browse Tours
                    </a>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo $customer_stats['total_bookings'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Pending</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo $customer_stats['pending_bookings'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Confirmed</p>
                            <p class="text-2xl font-bold text-slate-900"><?php echo $customer_stats['confirmed_bookings'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-golden-100">
                            <svg class="w-6 h-6 text-golden-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-600">Total Spent</p>
                            <p class="text-2xl font-bold text-slate-900">$<?php echo number_format($customer_stats['total_spent'] ?? 0); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="nextcloud-card">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-slate-900">My Bookings</h2>
                        <a href="my-bookings.php?email=<?php echo urlencode($customer_email); ?>" class="text-golden-600 hover:text-golden-700 font-medium">
                            View All
                        </a>
                    </div>
                </div>
                
                <?php if (empty($bookings)): ?>
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class=\"text-lg font-medium text-slate-900 mb-2\">No bookings yet</h3>
                    <p class=\"text-slate-500 mb-6\">Start your African adventure by booking your first tour</p>
                    <a href="packages.php" class="bg-golden-500 hover:bg-golden-600 text-black px-6 py-3 rounded-lg font-semibold transition-colors inline-block">
                        Browse Tours
                    </a>
                </div>
                <?php else: ?>
                <div class="divide-y divide-slate-200">
                    <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                    <div class="p-6 hover:bg-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <?php 
                                $image = $booking['cover_image'] ?: $booking['image_url'] ?: '../assets/images/default-tour.jpg';
                                if (strpos($image, 'uploads/') === 0) {
                                    $image = '../' . $image;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($image); ?>" 
                                     alt="<?php echo htmlspecialchars($booking['tour_name']); ?>" 
                                     class="w-16 h-16 rounded-lg object-cover"
                                     onerror="this.src="<?= getImageUrl('assets/images/default-tour.jpg') ?>"; this.onerror=null;">
                                <div>
                                    <h3 class="font-semibold text-slate-900"><?php echo htmlspecialchars($booking['tour_name']); ?></h3>
                                    <p class="text-sm text-slate-600"><?php echo htmlspecialchars($booking['destination'] . ', ' . $booking['country_name']); ?></p>
                                    <p class="text-sm text-slate-500">Ref: <?php echo htmlspecialchars($booking['booking_reference']); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full status-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                                <p class="text-sm text-slate-600 mt-1">Travel: <?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></p>
                                <p class="text-sm font-semibold text-slate-900">$<?php echo number_format($booking['total_amount'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<style>
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #d1fae5; color: #065f46; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.status-completed { background: #dbeafe; color: #1e40af; }
</style>

<?php include '../includes/footer.php'; ?>