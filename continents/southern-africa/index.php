<?php
$page_title = "Continent - iForYoungTours";
$css_path = '../../assets/css/modern-styles.css';
require_once '../../config/database.php';

// Get continent slug from URL
$continent_slug = basename(dirname($_SERVER['PHP_SELF']));
if ($continent_slug === 'continents') {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get continent data
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if (!$continent) {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get countries in this continent
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

// Get top 6 featured tours
$stmt = $pdo->prepare("
    SELECT t.* FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active' AND t.featured = 1
    ORDER BY t.popularity_score DESC, t.average_rating DESC
    LIMIT 6
");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();

include '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($continent['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" alt="<?php echo htmlspecialchars($continent['name']); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
            <?php echo htmlspecialchars($continent['name']); ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
            <?php echo htmlspecialchars($continent['description'] ?: 'Discover the wonders of ' . $continent['name']); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#countries" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                View Countries
            </a>
            <a href="#featured-tours" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                See Top Tours
            </a>
        </div>
    </div>
</section>

<!-- Countries Directory -->
<section id="countries" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore Countries</h2>
            <p class="text-xl text-gray-600">Choose a country to discover its tours and experiences</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($countries as $country): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='../../countries/<?php echo $country['slug']; ?>/'">
                <img src="<?php echo htmlspecialchars($country['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="text-3xl mr-3">🏴</span>
                        <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($country['name']); ?></h3>
                    </div>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($country['description'] ?: 'Discover the beauty of ' . $country['name']); ?></p>
                    <button class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                        Explore →
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Top 6 Featured Tours -->
<?php if (!empty($featured_tours)): ?>
<section id="featured-tours" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top Featured Experiences</h2>
            <p class="text-xl text-gray-600">Discover the best tours in <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='../../pages/tour-detail.php?id=<?php echo $tour['id']; ?>'">
                <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                        <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                        View Itinerary →
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why FYT For This Continent -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why FYT For <?php echo htmlspecialchars($continent['name']); ?></h2>
            <p class="text-xl text-gray-600">Your trusted partner for unforgettable experiences</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Expanding Country-by-Country</h3>
                <p class="text-gray-600">We're continuously growing our network across <?php echo htmlspecialchars($continent['name']); ?> to bring you more destinations</p>
            </div>
            
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Partnered with Local Travel Advisors</h3>
                <p class="text-gray-600">Our local experts ensure authentic experiences and insider knowledge</p>
            </div>
            
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Offering Premium, Protected Experiences</h3>
                <p class="text-gray-600">Travel with confidence knowing you're protected with comprehensive coverage</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Start Your Adventure?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of travelers exploring <?php echo htmlspecialchars($continent['name']); ?></p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="../../pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="../../pages/register.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                Join FYT Travel Club
            </a>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
