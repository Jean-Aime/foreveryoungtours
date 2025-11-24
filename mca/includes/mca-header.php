<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/check_auth.php';
checkAuth('mca');

$mca_id = $_SESSION['user_id'];
$mca_name = $_SESSION['user_name'] ?? 'MCA';
$base_path = '/foreveryoungtours/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'MCA Dashboard'; ?> - Forever Young Tours</title>
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
                        <i class="fas fa-user-shield text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">MCA Portal</h2>
                        <p class="text-xs text-slate-600">Country Management</p>
                    </div>
                </div>
            </div>
            
            <div class="py-4">
                <div class="px-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Main Menu</p>
                </div>
                <a href="<?php echo $base_path; ?>mca/index.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>Dashboard
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Management</p>
                </div>
                <a href="<?php echo $base_path; ?>mca/countries.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'countries.php' ? 'active' : ''; ?>">
                    <i class="fas fa-flag"></i>My Countries
                </a>
                <a href="<?php echo $base_path; ?>mca/tours.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'tours.php' ? 'active' : ''; ?>">
                    <i class="fas fa-route"></i>Tours
                </a>
                <a href="<?php echo $base_path; ?>mca/advisors.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'advisors.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-friends"></i>Advisors
                </a>
                <a href="<?php echo $base_path; ?>mca/kyc-management.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'kyc-management.php' ? 'active' : ''; ?>">
                    <i class="fas fa-id-card"></i>KYC Management
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Training</p>
                </div>
                <a href="<?php echo $base_path; ?>mca/training.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'training.php' ? 'active' : ''; ?>">
                    <i class="fas fa-graduation-cap"></i>Training Center
                </a>
                
                <div class="px-6 mt-6 mb-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase">Account</p>
                </div>
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
                        <p class="text-sm text-slate-600"><?php echo $page_subtitle ?? 'Welcome back, ' . htmlspecialchars($mca_name); ?></p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="relative p-2 text-slate-600 hover:text-slate-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($mca_name, 0, 1)); ?>
                            </div>
                            <div class="hidden md:block">
                                <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($mca_name); ?></p>
                                <p class="text-xs text-slate-600">MCA</p>
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
