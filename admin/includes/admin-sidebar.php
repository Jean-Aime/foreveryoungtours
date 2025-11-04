<!-- Sidebar -->
<aside class="desktop-sidebar w-64 bg-white shadow-lg border-r border-slate-200 flex flex-col fixed left-0 top-16 bottom-0 z-30">
    <div class="p-6 border-b border-slate-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-gold to-yellow-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-globe text-white"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">iForYoungTours</h2>
                <p class="text-sm text-slate-600">Admin Panel</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4">
        <div class="px-4 space-y-1">
            <!-- MAIN -->
            <a href="index.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'index' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-center"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <!-- OPERATIONS -->
        <div class="px-4 mt-6">
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Operations</h3>
            <div class="space-y-1">
                <a href="bookings.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'bookings' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check w-5 h-5 mr-3 text-center"></i>
                    <span>Bookings</span>
                </a>
                <a href="inquiries.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'inquiries' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope w-5 h-5 mr-3 text-center"></i>
                    <span>Inquiries</span>
                </a>
                <a href="client-packages.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'client-packages' ? 'active' : ''; ?>">
                    <i class="fas fa-box w-5 h-5 mr-3 text-center"></i>
                    <span>Client Packages</span>
                </a>
                <a href="commission-management.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'commission-management' ? 'active' : ''; ?>">
                    <i class="fas fa-percentage w-5 h-5 mr-3 text-center"></i>
                    <span>Commissions</span>
                </a>
                <a href="booking-engine-management.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'booking-engine-management' ? 'active' : ''; ?>">
                    <i class="fas fa-plane-departure w-5 h-5 mr-3 text-center"></i>
                    <span>Booking Engine</span>
                </a>
                <a href="booking-engine-orders.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'booking-engine-orders' ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt w-5 h-5 mr-3 text-center"></i>
                    <span>Engine Orders</span>
                </a>
            </div>
        </div>
        
        <!-- CONTENT -->
        <div class="px-4 mt-6">
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Content</h3>
            <div class="space-y-1">
                <a href="tours.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'tours' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marked-alt w-5 h-5 mr-3 text-center"></i>
                    <span>Tours</span>
                </a>
                <a href="manage-continents.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'manage-continents' ? 'active' : ''; ?>">
                    <i class="fas fa-globe w-5 h-5 mr-3 text-center"></i>
                    <span>Continents</span>
                </a>
                <a href="manage-countries.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'manage-countries' ? 'active' : ''; ?>">
                    <i class="fas fa-flag w-5 h-5 mr-3 text-center"></i>
                    <span>Countries</span>
                </a>
                <a href="destinations.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'destinations' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marker-alt w-5 h-5 mr-3 text-center"></i>
                    <span>Destinations</span>
                </a>
                <a href="blog-management.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'blog-management' ? 'active' : ''; ?>">
                    <i class="fas fa-blog w-5 h-5 mr-3 text-center"></i>
                    <span>Blog / Experiences</span>
                </a>
                <a href="client-stories.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'client-stories' ? 'active' : ''; ?>">
                    <i class="fas fa-star w-5 h-5 mr-3 text-center"></i>
                    <span>Client Stories</span>
                </a>
            </div>
        </div>
        
        <!-- USERS -->
        <div class="px-4 mt-6">
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Users</h3>
            <div class="space-y-1">
                <a href="users.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-users w-5 h-5 mr-3 text-center"></i>
                    <span>All Users</span>
                </a>
                <a href="mca-management.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'mca-management' ? 'active' : ''; ?>">
                    <i class="fas fa-user-crown w-5 h-5 mr-3 text-center"></i>
                    <span>MCAs</span>
                </a>
                <a href="advisor-management.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'advisor-management' ? 'active' : ''; ?>">
                    <i class="fas fa-user-tie w-5 h-5 mr-3 text-center"></i>
                    <span>Advisors</span>
                </a>
            </div>
        </div>
        
        <!-- ANALYTICS -->
        <div class="px-4 mt-6">
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Analytics</h3>
            <div class="space-y-1">
                <a href="analytics.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'analytics' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar w-5 h-5 mr-3 text-center"></i>
                    <span>Analytics</span>
                </a>
                <a href="reports.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'reports' ? 'active' : ''; ?>">
                    <i class="fas fa-file-chart w-5 h-5 mr-3 text-center"></i>
                    <span>Reports</span>
                </a>
            </div>
        </div>
        
        <!-- SYSTEM -->
        <div class="px-4 mt-6">
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">System</h3>
            <div class="space-y-1">
                <a href="tour-scheduler.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'tour-scheduler' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-center"></i>
                    <span>Tour Scheduler</span>
                </a>
                <a href="partners.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'partners' ? 'active' : ''; ?>">
                    <i class="fas fa-handshake w-5 h-5 mr-3 text-center"></i>
                    <span>Partners</span>
                </a>
                <a href="training-modules.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'training-modules' ? 'active' : ''; ?>">
                    <i class="fas fa-graduation-cap w-5 h-5 mr-3 text-center"></i>
                    <span>Training</span>
                </a>
                <a href="notifications.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'notifications' ? 'active' : ''; ?>">
                    <i class="fas fa-bell w-5 h-5 mr-3 text-center"></i>
                    <span>Notifications</span>
                </a>
                <a href="settings.php" class="nav-item flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                    <i class="fas fa-cog w-5 h-5 mr-3 text-center"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Admin Profile -->
    <div class="p-4 border-t border-slate-200 mt-auto">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-primary-gold rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">A</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 truncate">Admin</p>
                <p class="text-xs text-slate-500 truncate">admin@foreveryoungtours.com</p>
            </div>
        </div>
    </div>
</aside>

<script>
function toggleMobileMenu() {
    const sidebar = document.querySelector('.desktop-sidebar');
    sidebar.classList.toggle('mobile-open');
}
</script>