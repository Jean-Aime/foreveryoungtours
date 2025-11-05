<?php
$page_title = "iForYoungTours - Discover Africa's Wonders | Professional Travel Platform";
$page_description = "Explore Africa's most breathtaking destinations with expert guidance. From safaris to cultural experiences, book your dream African adventure with confidence.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "assets/css/modern-styles.css";
$js_path = "assets/js/main.js";

// Database connection
require_once 'config/database.php';

// Check if on country subdomain
$country_filter = isset($_SESSION['subdomain_country_id']) ? $_SESSION['subdomain_country_id'] : null;

if ($country_filter) {
    $page_title = "Visit " . $_SESSION['subdomain_country_name'] . " - iForYoungTours";
    $page_description = "Explore " . $_SESSION['subdomain_country_name'] . "'s best tours and destinations with iForYoungTours.";
}

// Fetch featured tours (filtered by country if on subdomain)
if ($country_filter) {
    $stmt = $pdo->prepare("SELECT t.*, c.name as country_name, r.name as region_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id WHERE t.status = 'active' AND t.country_id = ? ORDER BY t.created_at DESC LIMIT 6");
    $stmt->execute([$country_filter]);
} else {
    $stmt = $pdo->prepare("SELECT t.*, c.name as country_name, r.name as region_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id WHERE t.status = 'active' AND t.featured = 1 ORDER BY t.created_at DESC LIMIT 6");
    $stmt->execute();
}
$featured_tours = $stmt->fetchAll();

// Fetch featured destinations (filtered by country if on subdomain)
if ($country_filter) {
    $stmt = $pdo->prepare("SELECT c.*, r.name as region_name, COUNT(t.id) as tour_count FROM countries c JOIN regions r ON c.region_id = r.id LEFT JOIN tours t ON c.id = t.country_id WHERE c.id = ? AND c.status = 'active' GROUP BY c.id LIMIT 1");
    $stmt->execute([$country_filter]);
} else {
    $stmt = $pdo->prepare("SELECT c.*, r.name as region_name, COUNT(t.id) as tour_count FROM countries c JOIN regions r ON c.region_id = r.id LEFT JOIN tours t ON c.id = t.country_id WHERE c.featured = 1 AND c.status = 'active' GROUP BY c.id ORDER BY tour_count DESC LIMIT 8");
    $stmt->execute();
}
$featured_destinations = $stmt->fetchAll();

// Fetch statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as total_bookings FROM bookings");
$stmt->execute();
$stats = $stmt->fetch();
$total_bookings = $stats['total_bookings'] ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as total_users FROM users WHERE role IN ('client', 'advisor')");
$stmt->execute();
$stats = $stmt->fetch();
$total_users = $stats['total_users'] ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as total_tours FROM tours WHERE status = 'active'");
$stmt->execute();
$stats = $stmt->fetch();
$total_tours = $stats['total_tours'] ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as total_countries FROM countries WHERE status = 'active'");
$stmt->execute();
$stats = $stmt->fetch();
$total_countries = $stats['total_countries'] ?: 0;

include 'includes/header.php';
?>

    <!-- Video Hero Section -->
    <section class="relative h-screen overflow-hidden">
        <!-- Background Video -->
        <video id="heroVideo" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover" poster="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1920">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-giraffes-walking-in-the-savanna-3618-large.mp4" type="video/mp4">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-man-climbing-up-a-mountain-1120-large.mp4" type="video/mp4">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-wild-zebras-in-the-savanna-3736-large.mp4" type="video/mp4">
        </video>
        
        <!-- Fallback Background Image -->
        <div id="videoFallback" class="absolute inset-0 w-full h-full bg-cover bg-center hidden" style="background-image: url('https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1920');"></div>
        
        <script>
        // Show fallback if video fails to load
        document.getElementById('heroVideo').addEventListener('error', function() {
            document.getElementById('videoFallback').classList.remove('hidden');
            this.style.display = 'none';
        });
        </script>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
        
        <!-- Content - Centered Search Box -->
        <div class="relative h-full flex items-center justify-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                    <?php if ($country_filter): ?>
                    Discover <span class="text-yellow-500"><?php echo $_SESSION['subdomain_country_name']; ?></span>
                    <?php else: ?>
                    Explore <span class="text-yellow-500">Africa</span>
                    <?php endif; ?>
                </h1>
                
                <p class="text-xl text-gray-200 mb-10">Find your perfect adventure</p>
                
                <!-- Search Box -->
                <div class="max-w-3xl mx-auto">
                    <form action="<?php echo $base_path; ?>pages/packages.php" method="GET" class="bg-white rounded-2xl shadow-2xl p-3">
                        <div class="flex gap-3">
                            <input type="text" name="search" placeholder="Search tours, destinations, experiences..." required class="flex-1 px-6 py-4 rounded-xl border-0 focus:ring-2 focus:ring-yellow-500 text-gray-900">
                            <button type="submit" class="px-8 py-4 bg-yellow-500 text-black font-bold rounded-xl hover:bg-yellow-400 transition-all flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>



    
    <!-- Features Section - Modern Design -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Explore Our Services</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Everything you need for an unforgettable African adventure</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Travel Packages -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/packages.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Travel Packages</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Curated safari and cultural experiences across 47 African countries</p>
                        <div class="flex items-center text-yellow-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Browse packages</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Destinations -->
                <!-- <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/destinations.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Destinations</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Explore detailed guides for every African destination</p>
                        <div class="flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Discover places</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div> -->

                <!-- Experiences -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/experiences.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Experiences</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Safari, cultural, adventure and luxury travel experiences</p>
                        <div class="flex items-center text-purple-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>View experiences</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Travel Calendar -->
                <!-- <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/calendar.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Travel Calendar</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Plan your trip with departure dates and seasonal information</p>
                        <div class="flex items-center text-green-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Check calendar</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div> -->

                <!-- Travel Resources -->
                <!-- <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/resources.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Travel Resources</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Visa guides, safety tips, and essential travel information</p>
                        <div class="flex items-center text-red-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Get resources</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div> -->

                <!-- Partner Network -->
                <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='<?php echo $base_path; ?>pages/partners.php'">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative p-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Partner Network</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Join our network of travel advisors and local partners</p>
                        <div class="flex items-center text-indigo-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Become partner</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Signature Packages - Professional Design -->
    <section class="py-24 relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">SIGNATURE EXPERIENCES</span>
                <h2 class="text-4xl lg:text-6xl font-bold text-slate-900 mb-6 leading-tight">Our Signature Packages</h2>
                <p class="text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">Expertly curated African travel experiences across nine distinctive categories</p>
            </div>
            
            <!-- All 9 Signature Categories in 3x3 Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Motorcoach Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=motorcoach'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600" alt="Motorcoach Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/70 to-blue-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Motorcoach Tours</h3>
                            <p class="text-white/90 text-sm mt-2">Comfortable road journeys</p>
                        </div>
                    </div>
                </div>
                
                <!-- Rail Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=rail'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=600" alt="Rail Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-600/70 to-green-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Rail Tours</h3>
                            <p class="text-white/90 text-sm mt-2">Scenic train journeys</p>
                        </div>
                    </div>
                </div>
                
                <!-- Cruises -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=cruises'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600" alt="Cruises" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-600/70 to-cyan-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Cruises</h3>
                            <p class="text-white/90 text-sm mt-2">Ocean adventures</p>
                        </div>
                    </div>
                </div>
                
                <!-- City Breaks -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=city'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1578991624414-276ef23a534f?w=600" alt="City Breaks" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/70 to-purple-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">City Breaks</h3>
                            <p class="text-white/90 text-sm mt-2">Urban exploration</p>
                        </div>
                    </div>
                </div>
                
                <!-- Agro Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=agro'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600" alt="Agro Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-600/70 to-yellow-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Agro Tours</h3>
                            <p class="text-white/90 text-sm mt-2">Farm experiences</p>
                        </div>
                    </div>
                </div>
                
                <!-- Adventure Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=adventure'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600" alt="Adventure Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/70 to-orange-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Adventure Tours</h3>
                            <p class="text-white/90 text-sm mt-2">Thrilling activities</p>
                        </div>
                    </div>
                </div>
                
                <!-- Sports Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=sports'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600" alt="Sports Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-600/70 to-red-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Sports Tours</h3>
                            <p class="text-white/90 text-sm mt-2">Athletic events</p>
                        </div>
                    </div>
                </div>
                
                <!-- Cultural Tours -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=cultural'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=600" alt="Cultural Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/70 to-indigo-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Cultural Tours</h3>
                            <p class="text-white/90 text-sm mt-2">African traditions</p>
                        </div>
                    </div>
                </div>
                
                <!-- Conference & Expos -->
                <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=conference'">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600" alt="Conference & Expos" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-600/70 to-pink-800/70"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                            <h3 class="text-xl font-bold text-white">Conference & Expos</h3>
                            <p class="text-white/90 text-sm mt-2">Business events</p>
                        </div>
                    </div>
                </div>
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="pages/packages.php" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl font-semibold hover:from-yellow-600 hover:to-orange-600 transition-all shadow-lg hover:shadow-xl">
                    View All Packages
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
<!-- See it in action Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text Content -->
                <div>
                    <h2 class="text-5xl font-bold text-slate-900 mb-6">See it in action</h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        iForYoungTours is designed to offer best in class African travel experiences, and is developed at an impressive pace with new functionality becoming available every few months. We selected some videos to give you an idea of what we're up to.
                    </p>
                    <a href="<?php echo $base_path; ?>pages/about.php" class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                        Learn more
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Right: Video Cards -->
                <div class="space-y-6">
                    <!-- Video Card 1 -->
                    <div class="relative rounded-2xl overflow-hidden shadow-xl cursor-pointer group" onclick="window.open('https://www.youtube.com/watch?v=example1', '_blank')">
                        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800" alt="African Safari" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">African Safari Experience</h3>
                            <p class="text-sm">Discover the magic of African wildlife and landscapes</p>
                        </div>
                    </div>
                    
                    <!-- Video Card 2 -->
                    <div class="relative rounded-2xl overflow-hidden shadow-xl cursor-pointer group" onclick="window.open('https://www.youtube.com/watch?v=example2', '_blank')">
                        <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=800" alt="Cultural Tours" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-20 h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Cultural Immersion</h3>
                            <p class="text-sm">Experience authentic African cultures and traditions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Tour Calendar Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tour Departure Calendar</h2>
                <p class="text-xl text-gray-600">Select a date to book your African adventure</p>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                <div class="flex items-center space-x-4">
                    <button onclick="previousMonth()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h2 id="current-month" class="text-2xl font-bold text-gray-900"></h2>
                    <button onclick="nextMonth()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                

            </div>
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="calendar-grid bg-gray-50">
                    <div class="p-4 text-center font-semibold text-gray-700">Sun</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Mon</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Tue</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Wed</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Thu</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Fri</div>
                    <div class="p-4 text-center font-semibold text-gray-700">Sat</div>
                </div>
                <div id="calendar-days" class="calendar-grid"></div>
            </div>
            
            <div id="upcomingTours" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </div>
    </section>

    
    <script>
    let currentDate = new Date();
    const tourDates = <?php 
    $stmt = $pdo->query("SELECT DISTINCT scheduled_date as date FROM tour_schedules WHERE scheduled_date >= CURDATE() AND status = 'active' ORDER BY scheduled_date LIMIT 90");
    echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
    ?>;
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        document.getElementById('current-month').textContent = new Date(year, month).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        
        let html = '';
        for (let i = firstDay - 1; i >= 0; i--) {
            html += `<div class="calendar-day other-month">${daysInPrevMonth - i}</div>`;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const hasTours = tourDates.includes(dateStr);
            html += `<div class="calendar-day ${hasTours ? 'has-tours' : ''}" onclick="selectDate('${dateStr}')">${day}${hasTours ? '<div style="width:8px;height:8px;background:#22c55e;border-radius:50%;margin:4px auto 0"></div>' : ''}</div>`;
        }
        
        document.getElementById('calendar-days').innerHTML = html;
    }
    
    function previousMonth() { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); }
    function nextMonth() { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); }
    
    function selectDate(date) {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        event.target.classList.add('selected');
        fetch(`admin/get_scheduled_tours.php?date=${date}`)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('upcomingTours');
                if (data.tours && data.tours.length > 0) {
                    container.innerHTML = data.tours.map(tour => `
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-all cursor-pointer" onclick="openCalendarInquiry('${date}', ${tour.tour_id}, '${tour.tour_name}')">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">${tour.tour_name}</h4>
                            <p class="text-gray-600 mb-4">${tour.destination}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">${tour.duration_days} days • ${tour.available_slots - tour.booked_slots} slots left</span>
                                <span class="text-2xl font-bold text-yellow-600">$${tour.price}</span>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<p class="text-gray-500 col-span-full text-center">No tours scheduled for this date</p>';
                }
            });
    }
    
    function openCalendarInquiry(date, tourId, tourName) {
        openInquiryModal(tourId, tourName);
        document.getElementById('inquiry_tour_id').value = tourId;
        document.getElementById('inquiry_tour_name').value = tourName;
        document.querySelector('input[name="travel_dates"]').value = date;
    }
    
    renderCalendar();
    
    // Video Hero Section - Ensure video plays
    const video = document.getElementById('heroVideo');
    const fallback = document.getElementById('videoFallback');
    
    if (video) {
        video.addEventListener('loadeddata', function() {
            fallback.style.display = 'none';
            video.style.display = 'block';
        });
        
        video.addEventListener('error', function() {
            video.style.display = 'none';
            fallback.style.display = 'block';
        });
        
        video.play().catch(function() {
            video.style.display = 'none';
            fallback.style.display = 'block';
        });
    }
    </script>
    
    <?php include 'pages/inquiry-modal.php'; ?>

    <!-- Featured Destinations -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Destinations</h2>
                <p class="text-xl text-gray-600">Discover the most captivating destinations across Africa</p>
            </div>
            
            <div class="splide" id="destinations-carousel">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php if (!empty($featured_destinations)): ?>
                        <?php foreach ($featured_destinations as $destination): ?>
                        <li class="splide__slide">
                            <div class="relative rounded-2xl overflow-hidden card-hover cursor-pointer" onclick="window.location.href='pages/country.php?slug=<?php echo $destination['slug']; ?>'">
                                <?php 
                                $dest_image = $destination['image_url'] ?: 'assets/images/default-destination.jpg';
                                if (strpos($dest_image, 'uploads/') === 0) {
                                    $dest_image = $dest_image;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($dest_image); ?>" alt="<?php echo htmlspecialchars($destination['name']); ?>" class="w-full h-80 object-cover" onerror="this.src='assets/images/default-destination.jpg'; this.onerror=null;">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <h3 class="text-2xl font-bold mb-2 image-overlay-text"><?php echo htmlspecialchars($destination['name']); ?></h3>
                                    <p class="mb-4 image-overlay-text"><?php echo htmlspecialchars(substr($destination['tourism_description'], 0, 80)) . '...'; ?></p>
                                    <div class="flex items-center space-x-4">
                                        <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold"><?php echo $destination['tour_count']; ?> Tours</span>
                                        <span class="text-sm image-overlay-text"><?php echo htmlspecialchars($destination['region_name']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <li class="splide__slide">
                            <div class="relative rounded-2xl overflow-hidden card-hover">
                                <img src="assets/images/default-destination.jpg" alt="Coming Soon" class="w-full h-80 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <h3 class="text-2xl font-bold mb-2 image-overlay-text">Coming Soon</h3>
                                    <p class="mb-4 image-overlay-text">Amazing destinations are being added</p>
                                    <div class="flex items-center space-x-4">
                                        <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">Stay Tuned</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics - Nextcloud Style -->
    <section class="section-padding bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="stats-grid p-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="fade-in-up">
                        <div class="counter text-4xl font-bold text-primary-gold mb-2" data-target="<?php echo max($total_users * 15, 1500); ?>"><?php echo number_format(max($total_users * 15, 1500)); ?>+</div>
                        <p class="text-slate-600 font-medium">Happy Travelers</p>
                    </div>
                    <div class="fade-in-up">
                        <div class="counter text-4xl font-bold text-primary-gold mb-2" data-target="<?php echo max($total_countries, 47); ?>"><?php echo max($total_countries, 47); ?></div>
                        <p class="text-slate-600 font-medium">African Countries</p>
                    </div>
                    <div class="fade-in-up">
                        <div class="counter text-4xl font-bold text-primary-gold mb-2" data-target="<?php echo max($total_tours, 200); ?>"><?php echo number_format(max($total_tours, 200)); ?>+</div>
                        <p class="text-slate-600 font-medium">Travel Packages</p>
                    </div>
                    <div class="fade-in-up">
                        <div class="counter text-4xl font-bold text-primary-gold mb-2" data-target="<?php echo max($total_bookings, 50); ?>"><?php echo number_format(max($total_bookings, 50)); ?>+</div>
                        <p class="text-slate-600 font-medium">Total Bookings</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Travelers Say</h2>
                <p class="text-xl text-gray-600">Real experiences from our amazing community</p>
            </div>
            
            <div class="splide" id="testimonials-carousel">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="testimonial-card p-8 rounded-2xl mx-4">
                                <div class="flex items-center mb-6">
                                    <img src="https://kimi-web-img.moonshot.cn/img/govolunteerafrica.org/1935ad8b4e285447eb446b26ed39c7572e161b11.png" alt="Sarah M." class="w-16 h-16 rounded-full object-cover mr-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900">Sarah M.</h4>
                                        <p class="text-gray-600">Adventure Traveler</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-lg mb-6">"The Kenya safari exceeded all expectations. Our guide was incredibly knowledgeable, and we saw the Big Five within the first two days. The booking process was seamless and the support team was always available."</p>
                                <div class="flex text-yellow-400">
                                    ★★★★★
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="testimonial-card p-8 rounded-2xl mx-4">
                                <div class="flex items-center mb-6">
                                    <img src="https://kimi-web-img.moonshot.cn/img/www.cuisinenoir.com/02ec9c166503b6d6f0c577beac1a87758ed659b7.jpg" alt="Michael R." class="w-16 h-16 rounded-full object-cover mr-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900">Michael R.</h4>
                                        <p class="text-gray-600">Cultural Explorer</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-lg mb-6">"Morocco was a dream come true. From the Sahara desert camp to the bustling medinas, every moment was perfectly planned. The local insights and cultural experiences made it unforgettable."</p>
                                <div class="flex text-yellow-400">
                                    ★★★★★
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="testimonial-card p-8 rounded-2xl mx-4">
                                <div class="flex items-center mb-6">
                                    <img src="https://kimi-web-img.moonshot.cn/img/www.bigwildadventures.com/325488bf48b16a7ae0819256219df6db64a9ad7b.jpg" alt="Emma L." class="w-16 h-16 rounded-full object-cover mr-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900">Emma L.</h4>
                                        <p class="text-gray-600">Solo Traveler</p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-lg mb-6">"As a solo female traveler, I felt completely safe and supported throughout my journey. The Zanzibar beach extension was the perfect way to end my African adventure. Highly recommended!"</p>
                                <div class="flex text-yellow-400">
                                    ★★★★★
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter - Nextcloud Style -->
    <section class="section-padding bg-gradient-to-br from-slate-900 to-blue-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="newsletter-card">
                <h2 class="text-4xl font-bold image-overlay-text mb-4">Stay connected with African adventures</h2>
                <p class="text-xl image-overlay-text mb-8">Get exclusive travel deals, destination insights, and expert tips delivered to your inbox.</p>
                
                <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                    <input type="email" placeholder="Enter your email address" class="newsletter-input">
                    <button type="submit" class="newsletter-button">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

<?php
include 'includes/footer.php';
?>