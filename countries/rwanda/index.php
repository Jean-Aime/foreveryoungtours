<?php
$page_title = "Visit Rwanda - iForYoungTours";
require_once __DIR__ . '/../../config/database.php';

// Get country data from current directory
$country_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get all tours for this country only
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

// Set paths for subdomain
$base_path = '../../';
$css_path = 'assets/css/modern-styles.css';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative h-screen overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('assets/images/rwanda-gorilla-hero.png');"></div>
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
    
    <!-- Content -->
    <div class="relative h-full flex items-center justify-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center">
            <div class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm border border-white/30 rounded-full mb-5">
                <span class="text-white font-semibold text-sm uppercase tracking-wider"><?php echo htmlspecialchars($country['continent_name']); ?></span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-5 leading-tight">
                Discover <span style="color: #D4AF37;"><?php echo htmlspecialchars($country['name']); ?></span>
            </h1>
            <p class="text-base md:text-lg text-gray-200 mb-10">The Land of a Thousand Hills - Experience Mountain Gorillas, Stunning Landscapes & Rich Culture</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#tours" class="group inline-flex items-center justify-center px-8 py-3 text-white text-base font-semibold rounded-xl shadow-xl transition-all transform hover:scale-105" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);">
                    Explore Tours
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="#info" class="inline-flex items-center justify-center px-8 py-3 bg-white/10 backdrop-blur-sm text-white border-2 border-white text-base font-semibold rounded-xl hover:bg-white/20 transition-all transform hover:scale-105">
                    Country Info
                </a>
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







<!-- Tours Section -->
<section id="tours" class="py-20 bg-gradient-to-b from-white via-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block px-5 py-2 rounded-full text-sm font-semibold mb-5 shadow-md uppercase tracking-wider" style="background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%); color: #1a1a1a;">Featured Experiences</div>
            <h2 class="text-3xl md:text-4xl font-bold mb-5" style="color: #D4AF37;">Tours in <?php echo htmlspecialchars($country['name']); ?></h2>
            <div class="w-24 h-1 mx-auto mb-5 rounded-full" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);"></div>
            <p class="text-base text-gray-700 max-w-3xl mx-auto">Discover unforgettable experiences and adventures</p>
        </div>
        
        <?php if (!empty($tours)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 border border-gray-200" onclick="window.location.href='pages/tour-detail.php?id=<?php echo $tour['id']; ?>'" style="border-top: 3px solid #D4AF37;">
                <div class="relative h-56 overflow-hidden">
                    <?php 
                    // Get image - priority: cover_image > image_url > default
                    $final_image = '../../assets/images/africa.png'; // Default fallback
                    
                    if (!empty($tour['cover_image'])) {
                        $img = $tour['cover_image'];
                        if (strpos($img, 'http') === 0) {
                            $final_image = $img;
                        } elseif (strpos($img, 'uploads/') === 0) {
                            $final_image = '../../' . $img;
                        }
                    } elseif (!empty($tour['image_url'])) {
                        $img = $tour['image_url'];
                        if (strpos($img, 'http') === 0) {
                            $final_image = $img;
                        } elseif (strpos($img, 'uploads/') === 0) {
                            $final_image = '../../' . $img;
                        }
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($final_image); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" 
                         onerror="this.src='../../assets/images/africa.png'; this.onerror=null;" 
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold shadow-lg uppercase tracking-wide" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%); color: white;">Featured</div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed"><?php echo htmlspecialchars(substr($tour['description'] ?: 'Discover amazing experiences', 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                        <span class="text-2xl font-bold" style="color: #D4AF37;">$<?php echo number_format($tour['price'], 0); ?></span>
                        <span class="text-sm text-gray-600 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" style="color: #D4AF37;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <?php echo htmlspecialchars($tour['duration']); ?>
                        </span>
                    </div>
                    <button class="w-full text-white px-6 py-3 rounded-lg font-semibold hover:shadow-xl transition-all transform hover:scale-105 text-base" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);">
                        View Details →
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Tours coming soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Country Info -->
<section id="info" class="py-20" style="background: linear-gradient(135deg, #F4E5C3 0%, #ffffff 50%, #F4E5C3 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-5" style="color: #D4AF37;">Essential Travel Info</h2>
            <div class="w-24 h-1 mx-auto mb-5 rounded-full" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);"></div>
            <p class="text-base text-gray-700">Everything you need to know about <?php echo htmlspecialchars($country['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-xl border-t-3 transform hover:scale-105 transition-all" style="border-color: #D4AF37;">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3 text-center">Currency</h3>
                <p class="text-gray-700 text-center text-base font-medium"><?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-xl border-t-3 transform hover:scale-105 transition-all" style="border-color: #D4AF37;">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3 text-center">Language</h3>
                <p class="text-gray-700 text-center text-base font-medium"><?php echo htmlspecialchars($country['language'] ?: 'English'); ?></p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-xl border-t-3 transform hover:scale-105 transition-all" style="border-color: #D4AF37;">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3 text-center">Best Time to Visit</h3>
                <p class="text-gray-700 text-center text-base font-medium"><?php echo htmlspecialchars($country['best_time_to_visit'] ?: 'Year-round'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose FYT -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-5" style="color: #D4AF37;">Why Choose iForYoungTours</h2>
            <div class="w-24 h-1 mx-auto mb-5 rounded-full" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);"></div>
            <p class="text-base text-gray-700">Your trusted travel partner for <?php echo htmlspecialchars($country['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 rounded-2xl shadow-lg transform hover:scale-105 transition-all" style="background: linear-gradient(135deg, #F4E5C3 0%, #ffffff 100%);">
                <div class="w-20 h-20 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Local Experts</h3>
                <p class="text-sm text-gray-700 leading-relaxed">Partnered with local guides for authentic experiences</p>
            </div>
            
            <div class="text-center p-8 rounded-2xl shadow-lg transform hover:scale-105 transition-all" style="background: linear-gradient(135deg, #F4E5C3 0%, #ffffff 100%);">
                <div class="w-20 h-20 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Safe & Secure</h3>
                <p class="text-sm text-gray-700 leading-relaxed">Travel with confidence and comprehensive coverage</p>
            </div>
            
            <div class="text-center p-8 rounded-2xl shadow-lg transform hover:scale-105 transition-all" style="background: linear-gradient(135deg, #F4E5C3 0%, #ffffff 100%);">
                <div class="w-20 h-20 rounded-xl flex items-center justify-center mx-auto mb-5 shadow-md" style="background: linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">24/7 Support</h3>
                <p class="text-sm text-gray-700 leading-relaxed">Round-the-clock assistance for your peace of mind</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('assets/images/volucano.png');"></div>
    <!-- Gold Overlay -->
    <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(212,175,55,0.85) 0%, rgba(244,229,195,0.75) 50%, rgba(212,175,55,0.85) 100%);"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-5" style="color: #1a1a1a;">Ready to Explore <?php echo htmlspecialchars($country['name']); ?>?</h2>
        <p class="text-base mb-10" style="color: #2d2d2d;">Book your dream adventure today and create memories that last forever</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="../../pages/packages.php" class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-xl shadow-xl transition-all transform hover:scale-105" style="background: #1a1a1a; color: white;">
                Browse All Tours
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            <a href="../../pages/contact.php" class="inline-flex items-center justify-center bg-white text-gray-900 border-2 border-gray-900 px-8 py-3 text-base font-semibold rounded-xl hover:bg-gray-50 shadow-lg transition-all transform hover:scale-105">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
