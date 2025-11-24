<?php

require_once 'config.php';
if (!isset($_SESSION)) {
    session_start();
}
require_once '../auth/check_auth.php';
checkAuth('client');

$client_id = $_SESSION['user_id'] ?? null;
$client_name = $_SESSION['client_name'] ?? $_SESSION['user_name'] ?? 'Traveler';
$client_email = $_SESSION['client_email'] ?? $_SESSION['user_email'] ?? '';

// Auto-detect base path
$server_port = $_SERVER['SERVER_PORT'] ?? 80;
if ($server_port == 8000) {
    $base_path = '/';
} else {
    $base_path = '/foreveryoungtours/';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Client Dashboard'; ?> - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link href="../assets/css/client-dashboard.css" rel="stylesheet">
    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-link i {
            width: 20px;
            margin-right: 12px;
        }
        .bg-golden-500 { background-color: #DAA520; }
        .bg-golden-600 { background-color: #B8860B; }
        .hover\:bg-golden-600:hover { background-color: #B8860B; }
        .text-golden-600 { color: #B8860B; }
        .text-golden-500 { color: #DAA520; }
        .ring-golden-500 { --tw-ring-color: #DAA520; }
        .border-golden-500 { border-color: #DAA520; }
        .bg-golden-50 { background-color: #FDF6E3; }
        .border-golden-200 { border-color: #F4E4BC; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden fixed top-4 left-4 z-50 bg-white p-3 rounded-lg shadow-lg">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 bg-white shadow-lg fixed h-full overflow-y-auto z-40 md:translate-x-0">
            <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex items-center mb-2">
                    <img src="<?= getImageUrl('assets/images/logo.png') ?>" alt="Logo" class="w-10 h-10 mr-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Travel Hub</h2>
                        <p class="text-xs text-slate-600">Client Portal</p>
                    </div>
                </div>
            </div>
            
            <div class="py-4">
                <div class="px-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Main Menu</p>
                </div>
                <a href="<?php echo $base_path; ?>client/index.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>Dashboard
                </a>
                <a href="<?php echo $base_path; ?>client/bookings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-suitcase-rolling"></i>My Bookings
                </a>
                <a href="<?php echo $base_path; ?>client/booking-engine-orders.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'booking-engine-orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-plane-departure"></i>Engine Orders
                </a>
                <a href="<?php echo $base_path; ?>client/tours.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'tours.php' ? 'active' : ''; ?>">
                    <i class="fas fa-compass"></i>Explore Tours
                </a>
                <a href="<?php echo $base_path; ?>client/destinations.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'destinations.php' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marked-alt"></i>Destinations
                </a>
                <a href="<?php echo $base_path; ?>client/wishlist.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'wishlist.php' ? 'active' : ''; ?>">
                    <i class="fas fa-heart"></i>Wishlist
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Premium Services</p>
                </div>
                <a href="<?php echo $base_path; ?>client/visa-services.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'visa-services.php' ? 'active' : ''; ?>">
                    <i class="fas fa-passport"></i>Visa Services
                </a>
                <a href="<?php echo $base_path; ?>client/vip-services.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'vip-services.php' ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>VIP Services
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Resources</p>
                </div>
                <a href="<?php echo $base_path; ?>client/my-stories.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'my-stories.php' ? 'active' : ''; ?>">
                    <i class="fas fa-pen-fancy"></i>My Stories
                </a>
                <a href="<?php echo $base_path; ?>client/travel-guide.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'travel-guide.php' ? 'active' : ''; ?>">
                    <i class="fas fa-book-open"></i>Travel Guide
                </a>
                <a href="<?php echo $base_path; ?>client/rewards.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'rewards.php' ? 'active' : ''; ?>">
                    <i class="fas fa-gift"></i>Rewards
                </a>
                <a href="<?php echo $base_path; ?>client/support.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'support.php' ? 'active' : ''; ?>">
                    <i class="fas fa-headset"></i>Support
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Account</p>
                </div>
                <a href="<?php echo $base_path; ?>client/profile.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i>Profile
                </a>
                <a href="<?php echo $base_path; ?>client/settings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i>Settings
                </a>
                <a href="<?php echo $base_path; ?>auth/logout.php" class="sidebar-link text-red-600 hover:text-red-700">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 md:ml-64">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-slate-200 px-4 md:px-8 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                    <p class="text-sm text-slate-600"><?php echo $page_subtitle ?? 'Welcome back, ' . htmlspecialchars($client_name); ?></p>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-600 hover:text-slate-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo strtoupper(substr($client_name, 0, 1)); ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($client_name); ?></p>
                            <p class="text-xs text-slate-600">Client</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-4 md:p-8">
<script>
const sidebar = document.getElementById('sidebar');
const menuBtn = document.getElementById('mobile-menu-btn');
const menuIcon = menuBtn.querySelector('i');

menuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    menuIcon.classList.toggle('fa-bars');
    menuIcon.classList.toggle('fa-times');
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', (e) => {
    if (window.innerWidth < 768 && !sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
        sidebar.classList.remove('active');
        menuIcon.classList.add('fa-bars');
        menuIcon.classList.remove('fa-times');
    }
});
</script>
