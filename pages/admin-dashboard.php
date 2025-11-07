<?php
$page_title = "Admin Dashboard - iForYoungTours | Management Hub";
$page_description = "Comprehensive admin dashboard for managing tours, bookings, users, and business analytics.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";
include './header.php';
?>

<div class="pt-16 min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Admin Sidebar -->
        <div class="w-64 bg-white shadow-sm h-screen fixed left-0 top-16 overflow-y-auto">
            <nav class="p-6">
                <div class="space-y-2">
                    <a href="#overview" class="nav-item active flex items-center px-4 py-3 text-sm font-medium rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Overview
                    </a>
                    
                    <a href="#tours" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Tours & Packages
                    </a>
                    
                    <a href="#bookings" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Bookings
                    </a>
                    
                    <a href="#users" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Users
                    </a>
                    
                    <a href="#analytics" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 p-8">
            <!-- Overview Section -->
            <div id="overview-section" class="dashboard-section">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h2>
                    <p class="text-gray-600">Monitor and manage your tourism business</p>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <span class="text-sm text-green-600 font-medium">+12.5%</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">$245,800</div>
                        <div class="text-gray-600">Total Revenue</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-green-600 font-medium">+8.2%</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">1,247</div>
                        <div class="text-gray-600">Total Bookings</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-green-600 font-medium">+15.3%</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">3,456</div>
                        <div class="text-gray-600">Total Users</div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 kpi-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-green-600 font-medium">+5.1%</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">89</div>
                        <div class="text-gray-600">Active Partners</div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Revenue Trends</h3>
                            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option>Last 6 Months</option>
                                <option>Last Year</option>
                            </select>
                        </div>
                        <div id="revenue-chart" style="height: 300px;"></div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Booking Status</h3>
                        </div>
                        <div id="bookings-chart" style="height: 300px;"></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <button onclick="window.location.href='packages.php'" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-6 py-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Tour
                        </button>
                        
                        <button onclick="handleQuickAction('manageUsers')" class="bg-green-50 hover:bg-green-100 text-green-700 px-6 py-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Manage Users
                        </button>
                        
                        <button onclick="handleQuickAction('viewReports')" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-6 py-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            View Reports
                        </button>
                        
                        <button onclick="handleQuickAction('systemSettings')" class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-6 py-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tours Section -->
            <div id="tours-section" class="dashboard-section hidden">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Tours & Packages</h2>
                            <p class="text-gray-600">Manage your tour offerings</p>
                        </div>
                        <button onclick="showCreateTourModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                            Add New Tour
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tour Management</h3>
                    <div id="tours-list">
                        <!-- Tours will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Other sections -->
            <div id="bookings-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Bookings Management</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <p class="text-gray-600">Booking management features coming soon...</p>
                </div>
            </div>

            <div id="users-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">User Management</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <p class="text-gray-600">User management features coming soon...</p>
                </div>
            </div>

            <div id="analytics-section" class="dashboard-section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Analytics & Reports</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <p class="text-gray-600">Advanced analytics coming soon...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/dashboard-modules.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
<script>
// Admin-specific functions
function showCreateTourModal() {
    alert('Create Tour Modal - Feature coming soon!');
}

function handleQuickAction(action) {
    switch(action) {
        case 'manageUsers':
            document.querySelector('a[href="#users"]').click();
            break;
        case 'viewReports':
            document.querySelector('a[href="#analytics"]').click();
            break;
        case 'systemSettings':
            alert('System Settings - Coming soon!');
            break;
        default:
            alert('Feature coming soon!');
    }
}
</script>

<?php include '../includes/footer.php'; ?>