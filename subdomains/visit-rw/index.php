<?php
session_start();
require_once '../../config/database.php';

$country_slug = 'visit-rw';

// Fetch country information
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name, r.slug as region_slug FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../../pages/destinations.php');
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
$css_path = '../../assets/css/modern-styles.css';

include '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($country['hero_image'] ?? $country['image_url'] ?? 'https://images.unsplash.com/photo-1609198092357-8e51c4b1d9f9?auto=format&fit=crop&w=2072&q=80'); ?>" 
             alt="<?php echo htmlspecialchars($country['name']); ?>" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto text-center">
            <div class="mb-6">
                <span class="inline-block bg-white/10 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold border border-white/20">
                    <?php echo htmlspecialchars($country['region_name']); ?>
                </span>
            </div>
            <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 leading-tight animate-fade-in">
                Visit
                <span class="block text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                    <?php echo htmlspecialchars($country['name']); ?>
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-12 leading-relaxed max-w-3xl mx-auto">
                <?php echo htmlspecialchars($country['description'] ?? 'Experience the beauty and culture of ' . $country['name']); ?>
            </p>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12 max-w-4xl mx-auto">
                <?php if ($country['capital']): ?>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-3xl font-bold text-yellow-400"><?php echo htmlspecialchars($country['capital']); ?></div>
                    <div class="text-sm text-gray-300">Capital City</div>
                </div>
                <?php endif; ?>
                
                <?php if ($country['population']): ?>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-3xl font-bold text-yellow-400"><?php echo htmlspecialchars($country['population']); ?></div>
                    <div class="text-sm text-gray-300">Population</div>
                </div>
                <?php endif; ?>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-3xl font-bold text-yellow-400"><?php echo count($tours); ?>+</div>
                    <div class="text-sm text-gray-300">Tours Available</div>
                </div>
                
                <?php if ($country['currency']): ?>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-3xl font-bold text-yellow-400"><?php echo htmlspecialchars($country['currency']); ?></div>
                    <div class="text-sm text-gray-300">Currency</div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#tours" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all transform hover:scale-105">
                    Explore Tours
                </a>
                <a href="#about" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                    Learn More
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
                    
                    <?php if ($country['visa_requirements']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Visa Requirements</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($country['visa_requirements']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($country['timezone']): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Timezone</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($country['timezone']); ?></div>
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

<!-- Tourism Highlights -->
<?php if (!empty($tourism_highlights)): ?>
<section class="py-20 bg-gradient-to-br from-yellow-50 to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top Attractions</h2>
            <p class="text-xl text-gray-600">Must-visit places in <?php echo htmlspecialchars($country['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($tourism_highlights as $highlight): ?>
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all text-center group cursor-pointer transform hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-sm"><?php echo htmlspecialchars($highlight); ?></h3>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Available Tours -->
<section id="tours" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Tours in <?php echo htmlspecialchars($country['name']); ?></h2>
            <p class="text-xl text-gray-600">Choose your perfect adventure</p>
        </div>
        
        <?php if (!empty($tours)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group cursor-pointer" 
                 onclick="window.location.href='tour-details.php?slug=<?php echo htmlspecialchars($tour['slug']); ?>'">
                <div class="relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Featured
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['discount_price']): ?>
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        -<?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>%
                    </div>
                    <?php endif; ?>
                    
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="flex gap-2">
                            <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-xs font-semibold">
                                <?php echo htmlspecialchars($tour['duration']); ?>
                            </span>
                            <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                <?php echo htmlspecialchars($tour['difficulty_level']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                        <?php echo htmlspecialchars($tour['name']); ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                        <?php echo htmlspecialchars(substr($tour['description'], 0, 120)); ?>...
                    </p>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-1">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="w-4 h-4 <?php echo $i < round($tour['average_rating']) ? 'text-yellow-500' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm text-gray-600"><?php echo number_format($tour['average_rating'], 1); ?> (<?php echo $tour['total_reviews']; ?> reviews)</span>
                    </div>
                    
                    <!-- Price and CTA -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <?php if ($tour['discount_price']): ?>
                            <div class="text-sm text-gray-500 line-through">$<?php echo number_format($tour['price'], 0); ?></div>
                            <div class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['discount_price'], 0); ?></div>
                            <?php else: ?>
                            <div class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500">per person</div>
                        </div>
                        <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all flex items-center gap-2">
                            Book Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
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
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include '../../includes/footer.php'; ?>
