<?php
$page_title = "My Dashboard - iForYoungTours | Travel Hub";
$page_description = "Manage your bookings, view travel history, and plan your next African adventure.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";
include '../includes/header.php';
?>

<div class="pt-16 min-h-screen bg-gray-50">
    <div class="flex">
        <!-- User Sidebar -->
        <div class="w-64 bg-white shadow-sm h-screen fixed left-0 top-16 overflow-y-auto">
            <nav class="p-6">
                <div class="mb-6 text-center">
                    <img src="../assets/images/logo.png" alt="User" class="w-16 h-16 rounded-full mx-auto mb-2">
                    <h3 class="font-semibold text-gray-900">Welcome Back!</h3>
                    <p class="text-sm text-gray-600">Travel Explorer</p>
                </div>
                
                <div class="space-y-2">
                    <a href="#overview" class="nav-item active flex items-center px-4 py-3 text-sm font-medium rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Overview
                    </a>
                    
                    <a href="#bookings" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Bookings
                    </a>
                    
                    <a href="#wishlist" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Wishlist
                    </a>
                    
                    <a href="#profile" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>
                    
                    <a href="#support" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Support
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 p-8">
            <!-- Overview Section -->
            <div id="overview-section" class="dashboard-section">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
                    <p class="text-gray-600">Here's an overview of your travel journey with us</p>
                </div>

                <!-- User KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">12</div>
                        <div class="text-gray-600">Total Bookings</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">8</div>
                        <div class="text-gray-600">Confirmed Trips</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">$15,600</div>
                        <div class="text-gray-600">Total Spent</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">3</div>
                        <div class="text-gray-600">Upcoming Trips</div>
                    </div>
                </div>

                <!-- Recent Bookings & Upcoming Trips -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Recent Bookings -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Recent Bookings</h3>
                            <a href="#bookings" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View All</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                                <img src="../assets/images/africa.png" alt="Kenya Safari" class="w-12 h-12 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Kenya Safari Adventure</h4>
                                    <p class="text-sm text-gray-600">Kenya</p>
                                    <p class="text-xs text-gray-500">Aug 15, 2024</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">$2,499</div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">confirmed</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                                <img src="../assets/images/africa.png" alt="Morocco Desert" class="w-12 h-12 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Morocco Desert Experience</h4>
                                    <p class="text-sm text-gray-600">Morocco</p>
                                    <p class="text-xs text-gray-500">Sep 20, 2024</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">$1,899</div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <button onclick="window.location.href='packages.php'" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg font-medium transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Browse Tours
                            </button>
                            
                            <button onclick="handleUserAction('viewBookings')" class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-3 rounded-lg font-medium transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                My Bookings
                            </button>
                            
                            <button onclick="handleUserAction('updateProfile')" class="w-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-4 py-3 rounded-lg font-medium transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Update Profile
                            </button>
                            
                            <button onclick="handleUserAction('contactSupport')" class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg font-medium transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Contact Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Section -->
            <div id="bookings-section" class="dashboard-section hidden">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h2>
                            <p class="text-gray-600">Manage your travel bookings and reservations</p>
                        </div>
                        <a href="packages.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                            Book New Trip
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Bookings</h3>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <img src="../assets/images/africa.png" alt="Kenya Safari" class="w-20 h-20 rounded-lg object-cover">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Kenya Safari Adventure</h3>
                                        <p class="text-gray-600">Kenya</p>
                                        <p class="text-sm text-gray-500">Booking ID: BK2024001</p>
                                        <p class="text-sm text-gray-500">Travel Date: August 15, 2024</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-bold text-gray-900">$2,499</div>
                                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">confirmed</span>
                                    <div class="mt-2 space-x-2">
                                        <button class="text-blue-600 hover:text-blue-700 text-sm">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other sections -->
            <div id="wishlist-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">My Wishlist</h2>
                <div class="bg-white rounded-2xl p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No saved tours yet</h3>
                    <p class="text-gray-600 mb-4">Start exploring our amazing destinations and save your favorites</p>
                    <a href="packages.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                        Explore Tours
                    </a>
                </div>
            </div>

            <div id="profile-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <p class="text-gray-600">Profile management features coming soon...</p>
                </div>
            </div>

            <div id="support-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Support Center</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <p class="text-gray-600">Support features coming soon...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/dashboard-modules.js"></script>
<script>
// User-specific functions
function handleUserAction(action) {
    switch(action) {
        case 'viewBookings':
            document.querySelector('a[href="#bookings"]').click();
            break;
        case 'updateProfile':
            document.querySelector('a[href="#profile"]').click();
            break;
        case 'contactSupport':
            document.querySelector('a[href="#support"]').click();
            break;
        default:
            alert('Feature coming soon!');
    }
}
</script>

<?php include '../includes/footer.php'; ?>