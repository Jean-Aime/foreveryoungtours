<?php

require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Set default values
$first_name = 'Admin';
$last_name = 'User';
$email = 'admin@foreveryoungtours.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Super Admin Dashboard'; ?> - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link href="../assets/css/admin-styles.css" rel="stylesheet">
    <link href="../assets/css/download-protection.css" rel="stylesheet">
    <script src="../assets/js/download-protection.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-gold': '#DAA520',
                        'cream': '#FDF6E3',
                        'light-gray': '#F8FAFC',
                        'off-white': '#FEFEFE'
                    }
                }
            }
        }
    </script>
    <style>
        .nav-item {
            transition: all 0.3s ease;
            color: #64748b;
            text-decoration: none;
        }
        .nav-item:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #DAA520;
            transform: translateX(4px);
            text-decoration: none;
        }
        .nav-item.active {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #DAA520;
            font-weight: 600;
            border-right: 3px solid #DAA520;
            text-decoration: none;
        }
        .mobile-menu-btn {
            display: none;
        }
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            .desktop-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .desktop-sidebar.mobile-open {
                transform: translateX(0);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-slate-200 fixed top-0 left-0 right-0 z-40 h-16">
        <div class="flex justify-between items-center h-full px-4 md:pl-6 md:pr-6">
            <div class="flex items-center space-x-4">
                <button onclick="toggleMobileMenu()" id="mobile-menu-toggle" class="md:hidden text-slate-600 hover:text-primary-gold">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Super Admin</h2>
                    <p class="text-sm text-slate-600"><?php echo $page_subtitle ?? 'Dashboard'; ?></p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center space-x-2">
                <a href="notifications.php" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Notifications">
                    <i class="fas fa-bell"></i>
                </a>
                <a href="settings.php" class="p-2 text-slate-600 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors" title="Settings">
                    <i class="fas fa-cog"></i>
                </a>
                <a href="../index.php" class="p-2 text-slate-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Visit Site">
                    <i class="fas fa-globe"></i>
                </a>
                <a href="../auth/logout.php" class="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="flex pt-16 min-h-screen">