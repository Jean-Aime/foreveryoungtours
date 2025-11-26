<?php
$page_title = "iForYoungTours - Discover Africa's Wonders | Premium Travel Experiences";
$page_description = "Experience Africa like never before with our premium travel packages. From wildlife safaris to cultural immersions, discover the continent's hidden gems with expert local guides.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "assets/css/modern-styles.css";
$js_path = "assets/js/main.js";

require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get featured tours
$stmt = $conn->prepare("SELECT t.*, c.name as country_name, r.name as region_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id WHERE t.status = 'active' AND t.featured = 1 ORDER BY t.created_at DESC LIMIT 6");
$stmt->execute();
$featured_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<!-- Hero Section with Background -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Video/Image -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-golden-900/80 via-emerald-900/70 to-slate-900/80 z-10"></div>
        <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2000&q=80" 
             alt="African Safari" 
             class="w-full h-full object-cover">
        <!-- Animated Elements -->
        <div class="absolute inset-0 z-5">
            <div class="floating-element absolute top-20 left-10 w-4 h-4 bg-golden-400/30 rounded-full animate-pulse"></div>
            <div class="floating-element absolute top-40 right-20 w-6 h-6 bg-emerald-400/30 rounded-full animate-bounce"></div>
            <div class="floating-element absolute bottom-40 left-20 w-3 h-3 bg-blue-400/30 rounded-full animate-ping"></div>
        </div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <!-- Main Headline -->
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
                Discover Africa's
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-golden-400 to-emerald-400">
                    Hidden Wonders
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-gray-200 mb-12 max-w-3xl mx-auto leading-relaxed">
                Experience authentic African adventures with expert local guides. From wildlife safaris to cultural immersions, 
                create memories that last a lifetime.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16">
                <a href="pages/packages-enhanced.php" 
                   class="group relative inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-golden-500 to-golden-600 text-white font-bold text-lg rounded-2xl shadow-2xl hover:shadow-golden-500/25 transform hover:scale-110 transition-all duration-300 overflow-hidden">
                    <span class="absolute inset-0 bg-gradient-to-r from-golden-600 to-golden-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <svg class="w-6 h-6 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span class="relative z-10">Explore Packages</span>
                </a>
                <a href="pages/destinations.php" 
                   class="group relative inline-flex items-center justify-center px-10 py-5 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 font-bold text-lg rounded-2xl hover:bg-white/20 hover:border-white/50 transform hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    View Destinations
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="text-white">
                    <div class="text-3xl font-bold text-golden-400">1200+</div>
                    <div class="text-sm text-gray-300">Happy Travelers</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-bold text-emerald-400">47</div>
                    <div class="text-sm text-gray-300">African Countries</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-bold text-blue-400">15+</div>
                    <div class="text-sm text-gray-300">Years Experience</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-bold text-purple-400">24/7</div>
                    <div class="text-sm text-gray-300">Support</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
        <div class="animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>
</section>

<!-- Features Section (Nextcloud-style) -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose iForYoungTours?</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We provide comprehensive travel solutions that make your African adventure seamless and unforgettable.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-golden-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-golden-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Expert Local Guides</h3>
                <p class="text-gray-600">
                    Our certified local guides provide authentic insights and ensure your safety throughout your journey.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Support</h3>
                <p class="text-gray-600">
                    Round-the-clock assistance to handle any questions or emergencies during your African adventure.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Customized Experiences</h3>
                <p class="text-gray-600">
                    Tailor-made itineraries designed to match your interests, budget, and travel preferences.
                </p>
            </div>
            
            <!-- Feature 4 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Authentic Destinations</h3>
                <p class="text-gray-600">
                    Access to hidden gems and authentic experiences across all 47 African countries.
                </p>
            </div>
            
            <!-- Feature 5 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Best Price Guarantee</h3>
                <p class="text-gray-600">
                    Competitive pricing with transparent costs and no hidden fees for all our travel packages.
                </p>
            </div>
            
            <!-- Feature 6 -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Safety First</h3>
                <p class="text-gray-600">
                    Comprehensive safety protocols and travel insurance options for peace of mind.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tours Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured African Adventures</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Handpicked experiences that showcase the best of Africa's wildlife, culture, and natural beauty.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_tours as $tour): ?>
            <div class="nextcloud-card overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=800&q=80'); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute top-4 right-4 bg-golden-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                        From $<?php echo number_format($tour['price']); ?>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-medium">
                            <?php echo htmlspecialchars($tour['region_name'] ?: 'Africa'); ?>
                        </span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                            <?php echo htmlspecialchars($tour['country_name'] ?: 'Multiple'); ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500"><?php echo $tour['duration_days']; ?> days</span>
                        <div class="flex text-yellow-400">
                            ★★★★★
                        </div>
                    </div>
                    <a href="pages/tour-detail-enhanced.php?id=<?php echo $tour['id']; ?>" 
                       class="block w-full bg-slate-900 text-white text-center py-3 rounded-lg font-semibold hover:bg-slate-800 transition-colors">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="pages/packages-enhanced.php" 
               class="group relative inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-golden-500 to-golden-600 text-white font-bold text-lg rounded-2xl shadow-xl hover:shadow-golden-500/25 transform hover:scale-110 transition-all duration-300 overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-golden-600 to-golden-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <svg class="w-6 h-6 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="relative z-10">View All Packages</span>
            </a>
        </div>
    </div>
</section>

<!-- Destinations Showcase -->
<section class="py-20 bg-gradient-to-br from-slate-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore African Regions</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                From the Sahara Desert to the Cape of Good Hope, discover Africa's diverse landscapes and cultures.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- North Africa -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="window.location.href='pages/destinations.php?region=north-africa'">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">North Africa</h3>
                <p class="text-sm text-gray-600">Ancient civilizations & desert adventures</p>
            </div>
            
            <!-- West Africa -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="window.location.href='pages/destinations.php?region=west-africa'">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">West Africa</h3>
                <p class="text-sm text-gray-600">Rich cultures & vibrant traditions</p>
            </div>
            
            <!-- East Africa -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="window.location.href='pages/destinations.php?region=east-africa'">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">East Africa</h3>
                <p class="text-sm text-gray-600">Wildlife safaris & great migration</p>
            </div>
            
            <!-- Central Africa -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="window.location.href='pages/destinations.php?region=central-africa'">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Central Africa</h3>
                <p class="text-sm text-gray-600">Rainforests & gorilla trekking</p>
            </div>
            
            <!-- Southern Africa -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="window.location.href='pages/destinations.php?region=southern-africa'">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Southern Africa</h3>
                <p class="text-sm text-gray-600">Wine routes & dramatic landscapes</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready for Your African Adventure?</h2>
        <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
            Join thousands of travelers who have discovered Africa's magic with iForYoungTours. 
            Start planning your dream journey today.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="pages/packages-enhanced.php" 
               class="group relative inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-golden-500 to-golden-600 text-white font-bold text-lg rounded-2xl shadow-xl hover:shadow-golden-500/25 transform hover:scale-110 transition-all duration-300 overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-golden-600 to-golden-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <svg class="w-6 h-6 mr-3 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="relative z-10">Browse Packages</span>
            </a>
            <a href="pages/destinations.php" 
               class="group relative inline-flex items-center justify-center px-10 py-5 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 font-bold text-lg rounded-2xl hover:bg-white/20 hover:border-white/50 transform hover:scale-105 transition-all duration-300">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Explore Destinations
            </a>
        </div>
    </div>
</section>

<style>
.floating-element {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.nextcloud-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.nextcloud-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
</style>

<?php include 'includes/footer.php'; ?>