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

    <!-- New Hero Section -->
    <section class="relative h-screen overflow-hidden hero-section">
        <!-- Background Video -->
        <video id="heroVideo" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover hero-video" poster="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1920">
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
        
        <!-- Gold to Black Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-yellow-600/20 via-black/60 to-black/80 hero-overlay"></div>
        
        <!-- Content -->
        <div class="relative h-full flex items-center justify-center px-4 sm:px-6 lg:px-8" style="display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important;">
            <div class="w-full max-w-5xl mx-auto text-center hero-content" style="text-align: center !important; margin: 0 auto !important; display: block !important; width: 100% !important;">
                <!-- Headline -->
                <h1 class="hero-headline text-8xl md:text-8xl lg:text-5xl font-bold text-white mb-6 leading-tight" style="text-align: center !important; width: 100% !important; margin: 0 auto 1.5rem auto !important;">
                    Explore the World with <span class="text-yellow-400">Forever Young Tours</span>
                </h1>
                
                <!-- Subheadline -->
                <p class="hero-subheadline text-xl md:text-2xl text-gray-200 mb-12 max-w-4xl mx-auto" style="text-align: center !important; width: 100% !important; margin: 0 auto 3rem auto !important;">
                    Luxury Group Travel Experiences Across Africa, the Caribbean, Europe, and Beyond
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center" style="display: flex !important; justify-content: center !important; align-items: center !important; margin: 0 auto !important; width: 100% !important; text-align: center !important;">
                    <a href="<?php echo $base_path; ?>pages/packages.php" class="group inline-flex items-center px-10 py-5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-black font-bold rounded-2xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/25 transform hover:-translate-y-2 hover:scale-105 cta-primary">
                        <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Plan Your Journey
                        <svg class="w-5 h-5 ml-3 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="<?php echo $base_path; ?>auth/register.php" class="group inline-flex items-center px-10 py-5 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl border-2 border-white/40 hover:bg-white/20 hover:border-white/60 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 cta-secondary">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Join Our Travel Community
                    </a>
                </div>
            </div>
        </div> 
    </section>

    <!-- Partner Logos Section -->
    <section class="partner-logos-section py-6 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <!-- Continuous Sliding Partner Logos -->
            <div class="partner-logos-sliding-container overflow-hidden">
                <div class="partner-logos-sliding-track" id="partnerLogosSlider">
                    <!-- KCB Group Logo -->
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/KCB Group Plc Logo.jpg" alt="KCB Group Plc" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <!-- Visit Rwanda Logo -->
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/Visit Rwanda vector logo.jpg" alt="Visit Rwanda" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <!-- Equity ATM Logo -->
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/Equity ATM Logo.jpg" alt="Equity ATM" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <!-- Western Union Logo -->
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/western Union logo.jpg" alt="Western Union" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <!-- Duplicate set for seamless loop -->
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/KCB Group Plc Logo.jpg" alt="KCB Group Plc" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/Visit Rwanda vector logo.jpg" alt="Visit Rwanda" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/Equity ATM Logo.jpg" alt="Equity ATM" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <div class="partner-logo-item">
                        <div class="partner-logo-circle">
                            <img src="assets/images/western Union logo.jpg" alt="Western Union" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


        <!-- Statistics - Nextcloud Style -->
    <section class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="stats-grid p-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="fade-in-up stats-card-enhanced">
                        <div class="stats-icon-wrapper mb-4">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="counter text-4xl font-bold text-gray-900 mb-2" data-target="<?php echo max($total_users * 15, 1500); ?>"><?php echo number_format(max($total_users * 15, 1500)); ?>+</div>
                        <p class="text-gray-900 font-medium">Happy Travelers</p>
                    </div>
                    <div class="fade-in-up stats-card-enhanced">
                        <div class="stats-icon-wrapper mb-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="counter text-4xl font-bold text-gray-900 mb-2" data-target="<?php echo max($total_countries, 47); ?>"><?php echo max($total_countries, 47); ?></div>
                        <p class="text-gray-900 font-medium">African Countries</p>
                    </div>
                    <div class="fade-in-up stats-card-enhanced">
                        <div class="stats-icon-wrapper mb-4">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="counter text-4xl font-bold text-gray-900 mb-2" data-target="<?php echo max($total_tours, 200); ?>"><?php echo number_format(max($total_tours, 200)); ?>+</div>
                        <p class="text-gray-900 font-medium">Travel Packages</p>
                    </div>
                    <div class="fade-in-up stats-card-enhanced">
                        <div class="stats-icon-wrapper mb-4">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="counter text-4xl font-bold text-gray-900 mb-2" data-target="<?php echo max($total_bookings, 50); ?>"><?php echo number_format(max($total_bookings, 50)); ?>+</div>
                        <p class="text-gray-900 font-medium">Total Bookings</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- See it in action Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text Content -->
                <div>
                    <h2 class="text-4xl font-bold text-slate-900 mb-6">See it in action</h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        ForYoungTours is designed to offer best in class African travel experiences, and is developed at an impressive pace with new functionality becoming available every few months. We selected some videos to give you an idea of what we're up to.
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
                        <div class="absolute inset-0"></div>
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
                        <div class="absolute inset-0"></div>
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

    <!-- Our Signature Packages - Professional Design -->
    <section class="py-24 relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">SIGNATURE EXPERIENCES</span>
                <h2 class="text-4xl lg:text-4xl font-bold text-slate-900 mb-6 leading-tight">Our Signature Packages</h2>
                <p class="text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">Expertly curated African travel experiences across nine distinctive categories</p>
            </div>
            
            <!-- All 9 Signature Categories Continuous Sliding Animation -->
            <div class="relative">
                <!-- Continuous Sliding Container -->
                <div class="signature-sliding-container overflow-hidden">
                    <div class="signature-sliding-track" id="signatureSlider">
                        <!-- First Set of Categories -->
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=motorcoach'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=600" alt="Motorcoach Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Motorcoach Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">Comfortable road journeys</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=rail'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=600" alt="Rail Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Rail Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">Scenic train journeys</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=cruises'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600" alt="Cruises" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Cruises</h3>
                                        <p class="text-white/90 text-sm mt-2">Ocean adventures</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=city'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1578991624414-276ef23a534f?w=600" alt="City Breaks" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">City Breaks</h3>
                                        <p class="text-white/90 text-sm mt-2">Urban exploration</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=agro'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600" alt="Agro Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Agro Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">Farm experiences</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=adventure'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600" alt="Adventure Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Adventure Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">Thrilling activities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=sports'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600" alt="Sports Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Sports Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">Athletic events</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=cultural'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=600" alt="Cultural Tours" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 "></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Cultural Tours</h3>
                                        <p class="text-white/90 text-sm mt-2">African traditions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="signature-category-item">
                            <div class="group cursor-pointer" onclick="window.location.href='pages/packages.php?category=conference'">
                                <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600" alt="Conference & Expos" class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0"></div>
                                    <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-4">
                                        <h3 class="text-xl font-bold text-white">Conference & Expos</h3>
                                        <p class="text-white/90 text-sm mt-2">Business events</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
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

    <!-- Compact Tour Calendar Section -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Upcoming Departures</h2>
                <p class="text-lg text-gray-600">Quick access to available tour dates</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Compact Calendar Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button onclick="previousMonth()" class="p-2 rounded-lg bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h3 id="current-month" class="text-lg font-semibold text-gray-900"></h3>
                            <button onclick="nextMonth()" class="p-2 rounded-lg bg-white hover:bg-gray-50 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span>Available Tours</span>
                        </div>
                    </div>
                </div>
                
                <!-- Compact Calendar Grid -->
                <div class="p-4">
                    <div class="compact-calendar-grid bg-gray-50 rounded-lg overflow-hidden">
                        <div class="compact-calendar-header">
                            <div class="compact-day-header">Sun</div>
                            <div class="compact-day-header">Mon</div>
                            <div class="compact-day-header">Tue</div>
                            <div class="compact-day-header">Wed</div>
                            <div class="compact-day-header">Thu</div>
                            <div class="compact-day-header">Fri</div>
                            <div class="compact-day-header">Sat</div>
                        </div>
                        <div id="compact-calendar-days" class="compact-calendar-body"></div>
                    </div>
                </div>
                
                <!-- Selected Date Tours -->
                <div id="selectedDateTours" class="px-6 pb-6">
                    <div class="text-center text-gray-500 py-8">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>Click on a highlighted date to view available tours</p>
                    </div>
                </div>
            </div>
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
            html += `<div class="compact-calendar-day other-month">${daysInPrevMonth - i}</div>`;
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const hasTours = tourDates.includes(dateStr);
            html += `<div class="enhanced-calendar-day ${hasTours ? 'has-tours' : ''}" onclick="selectCompactDate('${dateStr}')">${day}${hasTours ? '<div class="enhanced-tour-indicator"></div>' : ''}</div>`;
        }
        
        document.getElementById('compact-calendar-days').innerHTML = html;
    }
    
    function previousMonth() { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); }
    function nextMonth() { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); }
    
    function selectCompactDate(date) {
        document.querySelectorAll('.enhanced-calendar-day').forEach(d => d.classList.remove('selected'));
        event.target.classList.add('selected');
        fetch(`admin/get_scheduled_tours.php?date=${date}`)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('selectedDateTours');
                if (data.tours && data.tours.length > 0) {
                    container.innerHTML = `
                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Tours on ${new Date(date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</h4>
                            <div class="space-y-3">
                                ${data.tours.map(tour => `
                                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all cursor-pointer" onclick="openCalendarInquiry('${date}', ${tour.tour_id}, '${tour.tour_name}')">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900 mb-1">${tour.tour_name}</h5>
                                                <p class="text-sm text-gray-600 mb-2">${tour.destination}</p>
                                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                    <span>${tour.duration_days} days</span>
                                                    <span>${tour.available_slots - tour.booked_slots} slots left</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-yellow-600">$${tour.price}</div>
                                                <button class="text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600 transition-colors">Book Now</button>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Tours on ${new Date(date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</h4>
                            <div class="text-center text-gray-500 py-6">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>No tours scheduled for this date</p>
                            </div>
                        </div>
                    `;
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
    
    // Signature Categories JavaScript-Powered Sliding Animation
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Starting JavaScript-powered sliding animation...');
        
        const slidingTrack = document.getElementById('signatureSlider');
        const container = document.querySelector('.signature-sliding-container');
        
        if (slidingTrack) {
            console.log('Sliding track found, initializing movement...');
            slidingTrack.style.animation = 'none';
            const originalItems = Array.from(slidingTrack.children);
            originalItems.forEach(item => {
                const clone = item.cloneNode(true);
                slidingTrack.appendChild(clone);
            });
            
            let position = 0;
            const speed = 0.9; 
            const itemWidth = 320 + 32; 
            const totalItems = originalItems.length;
            const resetPoint = itemWidth * totalItems; 
            
            function moveCategories() {
                position += speed;
                
                // Reset position seamlessly when original items are off screen
                if (position >= resetPoint) {
                    position = 0;
                }
                
                slidingTrack.style.transform = `translateX(-${position}px)`;
                requestAnimationFrame(moveCategories);
            }
            
            // Start the animation
            moveCategories();
            console.log('JavaScript sliding animation started!');
            
            // Pause on hover
            container.addEventListener('mouseenter', function() {
                slidingTrack.style.animationPlayState = 'paused';
                console.log('Animation paused on hover');
            });
            
            container.addEventListener('mouseleave', function() {
                slidingTrack.style.animationPlayState = 'running';
                console.log('Animation resumed');
            });
            
        } else {
            console.error('Sliding track element not found!');
        }
        
        // Add click tracking
        const categoryItems = document.querySelectorAll('.signature-category-item');
        console.log(`Found ${categoryItems.length} category items`);
        
        categoryItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                console.log(`Category ${index + 1} clicked`);
            });
        });
    });

    // Partner Logos Continuous Sliding Animation
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Starting partner logos sliding animation...');
        
        const partnerSlidingTrack = document.getElementById('partnerLogosSlider');
        const partnerContainer = document.querySelector('.partner-logos-sliding-container');
        
        if (partnerSlidingTrack) {
            console.log('Partner logos sliding track found, initializing movement...');
            
            const originalPartnerItems = Array.from(partnerSlidingTrack.children);
            const halfLength = originalPartnerItems.length / 2;
            
            for (let i = 0; i < 2; i++) {
                originalPartnerItems.slice(0, halfLength).forEach(item => {
                    const clone = item.cloneNode(true);
                    partnerSlidingTrack.appendChild(clone);
                });
            }
            
            let partnerPosition = 0;
            const partnerSpeed = 1.0;
            const partnerItemWidth = 120 + 48; 
            const partnerResetPoint = partnerItemWidth * halfLength; 
            
            function movePartnerLogos() {
                partnerPosition += partnerSpeed;
                
                // Reset position seamlessly when original items are off screen
                if (partnerPosition >= partnerResetPoint) {
                    partnerPosition = 0;
                }
                
                partnerSlidingTrack.style.transform = `translateX(-${partnerPosition}px)`;
                requestAnimationFrame(movePartnerLogos);
            }
            
            // Start the animation
            movePartnerLogos();
            console.log('Partner logos sliding animation started!');
            
        } else {
            console.error('Partner logos sliding track element not found!');
        }
    });
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
                                <img src="<?php echo htmlspecialchars($dest_image); ?>" alt="<?php echo htmlspecialchars($destination['name']); ?>" class="w-full h-80 object-cover" onerror="this.src='assets/images/Nairobi kenya.jpg'; this.onerror=null;">
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

    <!-- Quick Links -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Essential Quick Links</h2>
            <p class="text-xl text-gray-600">Quick access to the most important travel information</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('visas')">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Visa Requirements</h3>
                <p class="text-gray-600 mb-6">Check visa requirements for all 47 African countries</p>
                <div class="text-blue-600 font-semibold">Check Requirements →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('safety')">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Travel Safety</h3>
                <p class="text-gray-600 mb-6">Essential safety tips and health precautions</p>
                <div class="text-green-600 font-semibold">Safety Guide →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('packing')">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Packing Lists</h3>
                <p class="text-gray-600 mb-6">Complete packing guides for different African destinations</p>
                <div class="text-yellow-600 font-semibold">View Lists →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('culture')">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Cultural Guide</h3>
                <p class="text-gray-600 mb-6">Local customs, etiquette, and cultural insights</p>
                <div class="text-purple-600 font-semibold">Cultural Tips →</div>
            </div>
        </div>
    </div>
</section>

    <!-- Testimonials Section - Modern Card Design -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Left Side Content -->
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Section Header -->
                <div class="lg:col-span-1">
                    <div class="inline-block px-4 py-2 bg-yellow-500 text-black rounded-full text-sm font-medium mb-4">
                        Testimonials
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        What our clients are saying about us?
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Discover how our travelers experience unforgettable African adventures and support the sustainable tourism initiatives practiced by our operators worldwide.
                    </p>
                </div>
                
                <!-- Right: Testimonial Cards -->
                <div class="lg:col-span-2">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Testimonial Card 1 -->
                        <div class="testimonial-card-modern bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-6">
                                    <img src="https://kimi-web-img.moonshot.cn/img/govolunteerafrica.org/1935ad8b4e285447eb446b26ed39c7572e161b11.png" alt="Sarah M." class="w-12 h-12 rounded-full object-cover mr-4">
                                <div>
                                    <h4 class="font-bold text-gray-900">Sara Mohamed</h4>
                                    <p class="text-sm text-gray-500">Jakarta</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-4">
                                ★★★★★
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                I've been using Forever Young Tours for several years now, and it's become my go-to platform for planning my African adventures. The interface is user-friendly, and I appreciate the detailed information and real-time availability of tours.
                            </p>
                        </div>
                        
                        <!-- Testimonial Card 2 -->
                        <div class="testimonial-card-modern bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-6">
                                <img 
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face" 
                                    alt="Atend John" 
                                    class="w-12 h-12 rounded-full object-cover mr-4"
                                >
                                <div>
                                    <h4 class="font-bold text-gray-900">Atend John</h4>
                                    <p class="text-sm text-gray-500">California</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 mb-4">
                                ★★★★★
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                I had a last-minute travel opportunity, and Forever Young Tours came to the rescue. I was able to find high-quality African tours in no time and get a great deal on the package. The confirmation process was straightforward, and I received all the necessary information promptly.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section - Modern Design -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Content -->
                <div class="newsletter-content">
                    <div class="inline-block px-4 py-2 bg-yellow-500 text-black rounded-full text-sm font-semibold mb-6">
                        Join our newsletter
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        Get exclusive travel tips, destination guides, and special offers delivered to your inbox. Join our community of adventurous travelers!
                    </h2>
                    
                    <form class="newsletter-form mt-8">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input 
                                type="email" 
                                placeholder="Your Email" 
                                class="newsletter-input-modern flex-1 px-6 py-4 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                                required
                            >
                            <button 
                                type="submit" 
                                class="newsletter-button-modern px-8 py-4 bg-yellow-500 text-black font-semibold rounded-xl hover:bg-yellow-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                            >
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right: Image -->
                <div class="newsletter-image">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img 
                            src="assets/images/beach and sunset.jpg" 
                            alt="Beautiful tropical resort with pool and palm trees" 
                            class="w-full h-96 object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include 'includes/footer.php';
?>