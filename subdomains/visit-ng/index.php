<?php
session_start();

// Subdomain structure - adjust path based on your setup
$base_dir = dirname(dirname(__DIR__)); // Go up to main directory
require_once $base_dir . '/config/database.php';

$country_slug = 'visit-ng';

// Fetch country information
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name, r.slug as region_slug FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: http://foreveryoungtours.local/pages/destinations.php');
    exit();
}

// Fetch tours in this country
$stmt = $pdo->prepare("
    SELECT * FROM tours 
    WHERE country_id = ? AND status = 'active' 
    ORDER BY featured DESC, popularity_score DESC, average_rating DESC
");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

// Parse JSON data
$tourism_highlights = json_decode($country['tourism_highlights'] ?? '[]', true);
$popular_destinations = json_decode($country['popular_destinations'] ?? '[]', true);
$languages = json_decode($country['languages_spoken'] ?? '[]', true);

$page_title = $country['meta_title'] ?? "Visit " . $country['name'] . " - Forever Young Tours";
$page_description = $country['meta_description'] ?? "Discover " . $country['name'];

// Base path for subdomain structure
$base_path = '../../';
$css_path = '../../assets/css/modern-styles.css';

include $base_dir . '/includes/header.php';
?>

<!-- Professional Hero Section with Parallax -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero">
    <!-- Parallax Background -->
    <div class="absolute inset-0 z-0" data-parallax>
        <img src="<?php echo htmlspecialchars($country['hero_image'] ?? $country['image_url'] ?? 'https://images.unsplash.com/photo-1609198092357-8e51c4b1d9f9?auto=format&fit=crop&w=2072&q=80'); ?>" 
             alt="<?php echo htmlspecialchars($country['name']); ?>" 
             class="w-full h-full object-cover scale-110">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-amber-900/70"></div>
        
        <!-- Animated Overlay Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
        </div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
            <!-- Region Badge with Animation -->
            <div class="mb-8 animate-fade-in-down">
                <span class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500/20 to-orange-500/20 backdrop-blur-md text-amber-300 px-8 py-3 rounded-full text-sm font-bold border-2 border-amber-400/30 shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <?php echo htmlspecialchars($country['region_name']); ?>
                </span>
            </div>
            
            <!-- Main Heading with Gradient -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-tight animate-fade-in">
                <span class="block mb-2 text-2xl sm:text-3xl md:text-4xl font-light text-amber-300">Discover</span>
                <span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    <?php echo htmlspecialchars($country['name']); ?>
                </span>
            </h1>
            
            <!-- Description -->
            <p class="text-lg sm:text-xl md:text-2xl text-gray-200 mb-12 leading-relaxed max-w-4xl mx-auto animate-fade-in-up font-light">
                <?php echo htmlspecialchars($country['description'] ?? 'Experience the beauty, culture, and adventure of ' . $country['name']); ?>
            </p>
            
            <!-- Enhanced Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-12 max-w-5xl mx-auto animate-fade-in-up">
                <?php if ($country['capital']): ?>
                <div class="group bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/30 hover:border-amber-400/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/20">
                    <div class="flex items-center justify-center w-12 h-12 bg-amber-500/20 rounded-xl mb-3 mx-auto group-hover:bg-amber-500/30 transition-colors">
                        <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-amber-300 mb-1"><?php echo htmlspecialchars($country['capital']); ?></div>
                    <div class="text-xs sm:text-sm text-gray-300 font-medium">Capital City</div>
                </div>
                <?php endif; ?>
                
                <div class="group bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/30 hover:border-amber-400/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/20">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-500/20 rounded-xl mb-3 mx-auto group-hover:bg-blue-500/30 transition-colors">
                        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-blue-300 mb-1"><?php echo count($tours); ?>+</div>
                    <div class="text-xs sm:text-sm text-gray-300 font-medium">Tours Available</div>
                </div>
                
                <?php if ($country['currency']): ?>
                <div class="group bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/30 hover:border-amber-400/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/20">
                    <div class="flex items-center justify-center w-12 h-12 bg-purple-500/20 rounded-xl mb-3 mx-auto group-hover:bg-purple-500/30 transition-colors">
                        <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-purple-300 mb-1"><?php echo htmlspecialchars($country['currency']); ?></div>
                    <div class="text-xs sm:text-sm text-gray-300 font-medium">Currency</div>
                </div>
                <?php endif; ?>
                
                <?php if ($country['population']): ?>
                <div class="group bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/30 hover:border-amber-400/50 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-amber-500/20">
                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-500/20 rounded-xl mb-3 mx-auto group-hover:bg-emerald-500/30 transition-colors">
                        <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-300 mb-1"><?php echo htmlspecialchars($country['population']); ?></div>
                    <div class="text-xs sm:text-sm text-gray-300 font-medium">Population</div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Enhanced CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center animate-fade-in-up">
                <a href="#tours" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white px-10 py-5 text-base sm:text-lg font-bold rounded-full overflow-hidden shadow-2xl shadow-amber-500/50 hover:shadow-amber-500/70 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1">
                    <span class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <svg class="w-6 h-6 relative z-10 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <span class="relative z-10">Explore Tours</span>
                </a>
                <a href="#about" class="group inline-flex items-center justify-center gap-3 bg-white/10 backdrop-blur-md text-white border-2 border-white/50 px-10 py-5 text-base sm:text-lg font-bold rounded-full hover:bg-white/20 hover:border-white transition-all duration-300 transform hover:scale-105 hover:-translate-y-1">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Learn More</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">About <?php echo htmlspecialchars($country['name']); ?></h2>
                <div class="text-lg text-gray-700 leading-relaxed space-y-4 mb-8">
                    <?php echo nl2br(htmlspecialchars($country['about_text'] ?? '')); ?>
                </div>
                
                <!-- Travel Information -->
                <div class="space-y-4">
                    <?php if ($country['best_time_to_visit']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Best Time to Visit</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($country['best_time_to_visit']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($languages)): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Languages</div>
                            <div class="text-gray-600"><?php echo implode(', ', $languages); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="relative">
                <img src="<?php echo htmlspecialchars($country['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($country['name']); ?>" 
                     class="rounded-2xl shadow-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Available Tours -->
<section id="tours" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Tours in <?php echo htmlspecialchars($country['name']); ?></h2>
            <p class="text-xl text-gray-600">Choose your perfect adventure</p>
        </div>
        
        <?php if (!empty($tours)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group cursor-pointer">
                <div class="relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Featured
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-yellow-600 transition-colors">
                        <?php echo htmlspecialchars($tour['name']); ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 text-sm">
                        <?php echo htmlspecialchars(substr($tour['description'], 0, 120)); ?>...
                    </p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></div>
                        <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tours Coming Soon</h3>
            <p class="text-gray-600 mb-8">We're working on adding amazing tours to <?php echo htmlspecialchars($country['name']); ?>. Check back soon!</p>
            <a href="../../pages/contact.php" class="inline-block bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 rounded-full font-semibold hover:shadow-xl transition-all">
                Contact Us for Custom Tours
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?php echo htmlspecialchars($country['name']); ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Book your dream tour today or contact us for a custom itinerary</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="../../pages/contact.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Get in Touch
            </a>
            <a href="#tours" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                View All Tours
            </a>
        </div>
    </div>
</section>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-fade-in-down {
    animation: fade-in-down 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out 0.2s both;
}

html {
    scroll-behavior: smooth;
}
</style>

<?php include $base_dir . '/includes/footer.php'; ?>
