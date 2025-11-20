<?php
// Get current subdomain context
$is_continent = defined('CURRENT_CONTINENT');
$is_country = defined('CURRENT_COUNTRY_NAME');
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <?php if ($is_country): ?>
                <img src="<?= htmlspecialchars($country['flag_url'] ?? '') ?>" alt="<?= CURRENT_COUNTRY_NAME ?>" class="w-8 h-6 mr-3">
                <span class="text-xl font-bold text-gray-800"><?= CURRENT_COUNTRY_NAME ?> Tours</span>
                <?php elseif ($is_continent): ?>
                <span class="text-xl font-bold text-gray-800"><?= CURRENT_CONTINENT ?> Adventures</span>
                <?php else: ?>
                <span class="text-xl font-bold text-gray-800">iForYoungTours</span>
                <?php endif; ?>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-8">
                <a href="/" class="text-gray-700 hover:text-blue-600 <?= $current_page == 'index' ? 'text-blue-600 font-semibold' : '' ?>">
                    Home
                </a>
                
                <?php if ($is_country): ?>
                <a href="/tours.php" class="text-gray-700 hover:text-blue-600 <?= $current_page == 'tours' ? 'text-blue-600 font-semibold' : '' ?>">
                    All Tours
                </a>
                <a href="/experiences.php" class="text-gray-700 hover:text-blue-600">
                    Experiences
                </a>
                <?php elseif ($is_continent): ?>
                <a href="/tours.php" class="text-gray-700 hover:text-blue-600 <?= $current_page == 'tours' ? 'text-blue-600 font-semibold' : '' ?>">
                    All Tours
                </a>
                <a href="/countries.php" class="text-gray-700 hover:text-blue-600">
                    Countries
                </a>
                <?php endif; ?>
                
                <a href="/about.php" class="text-gray-700 hover:text-blue-600">
                    About
                </a>
                <a href="/contact.php" class="text-gray-700 hover:text-blue-600">
                    Contact
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="relative group">
                    <button class="flex items-center text-gray-700 hover:text-blue-600">
                        <i class="fas fa-user mr-2"></i>
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <a href="/client-dashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/admin/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                        <?php endif; ?>
                        <a href="/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="/login.php" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="/register.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <div class="space-y-2">
                <a href="/" class="block py-2 text-gray-700 hover:text-blue-600">Home</a>
                <?php if ($is_country): ?>
                <a href="/tours.php" class="block py-2 text-gray-700 hover:text-blue-600">All Tours</a>
                <a href="/experiences.php" class="block py-2 text-gray-700 hover:text-blue-600">Experiences</a>
                <?php elseif ($is_continent): ?>
                <a href="/tours.php" class="block py-2 text-gray-700 hover:text-blue-600">All Tours</a>
                <a href="/countries.php" class="block py-2 text-gray-700 hover:text-blue-600">Countries</a>
                <?php endif; ?>
                <a href="/about.php" class="block py-2 text-gray-700 hover:text-blue-600">About</a>
                <a href="/contact.php" class="block py-2 text-gray-700 hover:text-blue-600">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="border-t pt-2 mt-2">
                    <a href="/client-dashboard.php" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/admin/" class="block py-2 text-gray-700 hover:text-blue-600">Admin Panel</a>
                    <?php endif; ?>
                    <a href="/logout.php" class="block py-2 text-gray-700 hover:text-blue-600">Logout</a>
                </div>
                <?php else: ?>
                <div class="border-t pt-2 mt-2">
                    <a href="/login.php" class="block py-2 text-gray-700 hover:text-blue-600">Login</a>
                    <a href="/register.php" class="block py-2 text-gray-700 hover:text-blue-600">Register</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script>