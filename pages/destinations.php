<?php
$page_title = "Discover Luxury Group Travel Across the World";
$page_description = "Explore the world's most breathtaking destinations with iForYoungTours";
$css_path = '../assets/css/modern-styles.css';
require_once '../config/database.php';

$stmt = $pdo->query("SELECT * FROM regions WHERE status = 'active' ORDER BY name");
$continents = $stmt->fetchAll();

// Get top global tours
$stmt = $pdo->query("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.status = 'active' AND t.featured = 1 ORDER BY t.popularity_score DESC, t.average_rating DESC LIMIT 6");
$global_tours = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=2072&q=80" alt="World Travel" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                Discover Luxury Group Travel
                <span class="text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent block">Across the World</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-8 leading-relaxed">
                Experience unforgettable journeys crafted for luxury and authenticity
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#continents" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                    View Continents
                </a>
                <a href="#global-tours" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                    See Top Global Tours
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Continents Section -->
<section id="continents" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore by Continent</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">At ForYoungTours, we believe every region offers unforgettable experiences. From the savannas of Africa to the ancient temples of Asia, each continent tells a unique story. Choose your destination and let us craft your perfect luxury group travel experience.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($continents as $continent): ?>
            <div class="relative rounded-2xl overflow-hidden group cursor-pointer transform hover:scale-105 transition-all duration-300" onclick="window.open('http://<?php echo $continent['slug']; ?>.foreveryoungtours.local', '_blank')">
                <img src="<?php echo htmlspecialchars($continent['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1000&q=80'); ?>" alt="<?php echo htmlspecialchars($continent['name']); ?>" class="w-full h-96 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <h3 class="text-3xl font-bold mb-3"><?php echo htmlspecialchars($continent['name']); ?></h3>
                    <p class="text-gray-200 mb-4 line-clamp-2"><?php echo htmlspecialchars($continent['description'] ?: 'Discover the wonders of ' . $continent['name']); ?></p>
                    <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                        Explore →
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Top Global Tours -->
<section id="global-tours" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top Global Tours</h2>
            <p class="text-xl text-gray-600">Discover our most popular experiences from around the world</p>
        </div>
        
        <?php if (!empty($global_tours)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($global_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='tour-detail.php?id=<?php echo $tour['id']; ?>'">
                <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                        <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($tour['country_name'] ?: 'Global'); ?></span>
                        <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:shadow-xl transition-all">
                            View Tour →
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Featured tours coming soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>



<?php include '../includes/footer.php'; ?>
