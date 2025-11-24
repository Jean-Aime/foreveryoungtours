<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/check_auth.php';

// Check if advisor is active
$stmt = $pdo->prepare("SELECT status FROM users WHERE id = ? AND role = 'advisor'");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['status'] !== 'active') {
    session_destroy();
    header('Location: ../auth/login.php?error=account_pending');
    exit;
}

checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];
$advisor_name = $_SESSION['user_name'] ?? 'Advisor';

$base_path = '/foreveryoungtours/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Advisor Dashboard'; ?> - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            transition: all 0.3s;
            color: #64748b;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            background: #f8fafc;
            color: #0f172a;
            border-left-color: #DAA520;
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, #FDF6E3 0%, #fff 100%);
            color: #DAA520;
            border-left-color: #DAA520;
            font-weight: 600;
        }
        .sidebar-link i {
            width: 20px;
            margin-right: 12px;
        }
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
            <i class="fas fa-bars text-xl text-slate-700"></i>
        </button>
        
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 bg-white shadow-lg fixed h-full overflow-y-auto z-40 md:translate-x-0">
            <div class="p-6 border-b bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex items-center mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Advisor Portal</h2>
                        <p class="text-xs text-slate-600">Sales Dashboard</p>
                    </div>
                </div>
            </div>
            
            <div class="py-4">
                <div class="px-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Main Menu</p>
                </div>
                <a href="<?php echo $base_path; ?>advisor/index.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>Dashboard
                </a>
                <a href="<?php echo $base_path; ?>advisor/bookings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i>My Bookings
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Sales & Tours</p>
                </div>
                <a href="<?php echo $base_path; ?>advisor/tours.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'tours.php' ? 'active' : ''; ?>">
                    <i class="fas fa-route"></i>Browse Tours
                </a>
                <a href="<?php echo $base_path; ?>advisor/marketing.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'marketing.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bullhorn"></i>Marketing Tools
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Clients & Team</p>
                </div>
                <a href="<?php echo $base_path; ?>advisor/my-clients.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'my-clients.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users-lock"></i>My Clients
                </a>
                <a href="<?php echo $base_path; ?>advisor/create-client-portal.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'create-client-portal.php' ? 'active' : ''; ?>">
                    <i class="fas fa-link"></i>Create Portal
                </a>
                <a href="<?php echo $base_path; ?>advisor/team.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>My Team
                </a>
                <a href="<?php echo $base_path; ?>advisor/register-client.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'register-client.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i>Add Client
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Earnings</p>
                </div>
                <a href="<?php echo $base_path; ?>advisor/commissions.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'commissions.php' ? 'active' : ''; ?>">
                    <i class="fas fa-dollar-sign"></i>Commissions
                </a>
                <a href="<?php echo $base_path; ?>advisor/request-payout.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'request-payout.php' ? 'active' : ''; ?>">
                    <i class="fas fa-money-check-alt"></i>Request Payout
                </a>
                <a href="<?php echo $base_path; ?>advisor/pay-license.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'pay-license.php' ? 'active' : ''; ?>">
                    <i class="fas fa-certificate"></i>License Fee
                </a>
                <a href="<?php echo $base_path; ?>advisor/reports.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>Reports
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Learning</p>
                </div>
                <a href="<?php echo $base_path; ?>advisor/training-center.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'training-center.php' || basename($_SERVER['PHP_SELF']) == 'training-module.php' ? 'active' : ''; ?>">
                    <i class="fas fa-graduation-cap"></i>Training Center
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Account</p>
                </div>
                <a href="<?php echo $base_path; ?>profile/edit-profile.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'edit-profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i>Profile
                </a>
                <a href="<?php echo $base_path; ?>auth/logout.php" class="sidebar-link text-red-600 hover:text-red-700">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 md:ml-64">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b border-slate-200 px-4 md:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                        <p class="text-sm text-slate-600"><?php echo $page_subtitle ?? 'Welcome back, ' . htmlspecialchars($advisor_name); ?></p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="relative p-2 text-slate-600 hover:text-slate-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($advisor_name, 0, 1)); ?>
                            </div>
                            <div class="hidden md:block">
                                <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($advisor_name); ?></p>
                                <p class="text-xs text-slate-600">Advisor</p>
                            </div>
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

document.addEventListener('click', (e) => {
    if (window.innerWidth < 768 && !sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
        sidebar.classList.remove('active');
        menuIcon.classList.add('fa-bars');
        menuIcon.classList.remove('fa-times');
    }
});
</script>
