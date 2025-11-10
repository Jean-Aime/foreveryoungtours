<?php
session_start();
require_once '../../config/database.php';

$continent_slug = 'africa';

// Fetch continent information
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if (!$continent) {
    header('Location: ../../pages/destinations.php');
    exit();
}

// Fetch countries in this continent
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

// Fetch featured tours in this continent
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.slug as country_slug 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE t.region_id = ? AND t.status = 'active' 
    ORDER BY t.featured DESC, t.popularity_score DESC, t.average_rating DESC 
    LIMIT 12
");
$stmt->execute([$continent['id']]);
$tours = $stmt->fetchAll();

// Parse JSON data
$tourism_highlights = json_decode($continent['tourism_highlights'] ?? '[]', true);
$popular_activities = json_decode($continent['popular_activities'] ?? '[]', true);
$languages = json_decode($continent['languages_spoken'] ?? '[]', true);

$page_title = $continent['meta_title'] ?? "Explore " . $continent['name'] . " - Forever Young Tours";
$page_description = $continent['meta_description'] ?? "Discover the wonders of " . $continent['name'];
$css_path = '../../assets/css/modern-styles.css';

include '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($continent['hero_image'] ?? 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" 
             alt="<?php echo htmlspecialchars($continent['name']); ?>" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 leading-tight animate-fade-in">
                Discover
                <span class="block text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                    <?php echo htmlspecialchars($continent['name']); ?>
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-8 leading-relaxed max-w-3xl mx-auto">
                <?php echo htmlspecialchars($continent['description'] ?? 'Experience the magic of ' . $continent['name']); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#countries" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all transform hover:scale-105">
                    Explore Countries
                </a>
                <a href="#tours" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                    View Tours
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
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">About <?php echo htmlspecialchars($continent['name']); ?></h2>
                <div class="text-lg text-gray-700 leading-relaxed space-y-4">
                    <?php echo nl2br(htmlspecialchars($continent['about_text'] ?? '')); ?>
                </div>
                
                <!-- Quick Facts -->
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <?php if ($continent['best_time_to_visit']): ?>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center gap-2 text-yellow-600 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Best Time</span>
                        </div>
                        <p class="text-sm text-gray-700"><?php echo htmlspecialchars($continent['best_time_to_visit']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($languages)): ?>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center gap-2 text-blue-600 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Languages</span>
                        </div>
                        <p class="text-sm text-gray-700"><?php echo implode(', ', array_slice($languages, 0, 3)); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="relative">
                <img src="<?php echo htmlspecialchars($continent['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($continent['name']); ?>" 
                     class="rounded-2xl shadow-2xl">
                <div class="absolute -bottom-6 -right-6 bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6 rounded-xl shadow-xl">
                    <div class="text-4xl font-bold"><?php echo count($countries); ?>+</div>
                    <div class="text-sm">Countries to Explore</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tourism Highlights -->
<?php if (!empty($tourism_highlights)): ?>
<section class="py-20 bg-gradient-to-br from-yellow-50 to-orange-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tourism Highlights</h2>
            <p class="text-xl text-gray-600">Must-see attractions and experiences</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($tourism_highlights as $highlight): ?>
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all text-center group cursor-pointer transform hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900"><?php echo htmlspecialchars($highlight); ?></h3>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Countries Section -->
<section id="countries" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore Countries</h2>
            <p class="text-xl text-gray-600">Choose your destination and start your adventure</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($countries as $country): ?>
            <div class="relative rounded-2xl overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-2xl" 
                 onclick="window.location.href='http://<?php echo htmlspecialchars($country['slug']); ?>.foreveryoungtours.local'">
                <img src="<?php echo htmlspecialchars($country['hero_image'] ?? $country['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($country['name']); ?>" 
                     class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($country['name']); ?></h3>
                    <p class="text-gray-200 mb-4 text-sm line-clamp-2">
                        <?php echo htmlspecialchars(substr($country['description'] ?? 'Discover the beauty of ' . $country['name'], 0, 100)); ?>...
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-yellow-400">
                            <?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tours WHERE country_id = ? AND status = 'active'");
                            $stmt->execute([$country['id']]);
                            echo $stmt->fetchColumn(); 
                            ?> Tours Available
                        </span>
                        <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:shadow-xl transition-all">
                            Explore →
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Tours -->
<?php if (!empty($tours)): ?>
<section id="tours" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Tours in <?php echo htmlspecialchars($continent['name']); ?></h2>
            <p class="text-xl text-gray-600">Handpicked experiences for unforgettable adventures</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group cursor-pointer" 
                 onclick="window.location.href='http://<?php echo htmlspecialchars($tour['country_slug']); ?>.foreveryoungtours.local/tour-details.php?slug=<?php echo htmlspecialchars($tour['slug']); ?>'">
                <div class="relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&fit=crop&w=800&q=80'); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Featured
                    </div>
                    <?php endif; ?>
                    <?php if ($tour['discount_price']): ?>
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Save <?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>%
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($tour['country_name']); ?></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                        <?php echo htmlspecialchars($tour['name']); ?>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-2 text-sm">
                        <?php echo htmlspecialchars(substr($tour['description'], 0, 120)); ?>...
                    </p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-1">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="w-4 h-4 <?php echo $i < round($tour['average_rating']) ? 'text-yellow-500' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <?php endfor; ?>
                            <span class="text-sm text-gray-600 ml-1">(<?php echo $tour['total_reviews']; ?>)</span>
                        </div>
                        <span class="text-sm text-gray-500 capitalize"><?php echo htmlspecialchars($tour['difficulty_level']); ?></span>
                    </div>
                    
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
                        <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Start Your <?php echo htmlspecialchars($continent['name']); ?> Adventure?</h2>
        <p class="text-xl text-white/90 mb-8">Let us help you create unforgettable memories</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="../../pages/contact.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Contact Us
            </a>
            <a href="#tours" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                Browse All Tours
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
