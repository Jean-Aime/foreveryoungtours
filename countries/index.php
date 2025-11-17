<?php

require_once 'config.php';
$page_title = "Country - iForYoungTours";
$css_path = '../../assets/css/modern-styles.css';
require_once '../../config/database.php';

// Get country slug from URL
$country_slug = basename(dirname($_SERVER['PHP_SELF']));
if ($country_slug === 'countries') {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get country data
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get all tours for this country
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, popularity_score DESC");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

include '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($country['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex items-center justify-center mb-4">
            <span class="text-6xl">🏴</span>
        </div>
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
            <?php echo htmlspecialchars($country['name']); ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-4">
            <?php echo htmlspecialchars($country['region_name']); ?>
        </p>
        <p class="text-lg text-gray-300 mb-8 max-w-3xl mx-auto">
            <?php echo htmlspecialchars($country['tourism_description'] ?: $country['description'] ?: 'Discover the wonders of ' . $country['name']); ?>
        </p>
        <a href="#tours" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all inline-block">
            Explore Tours
        </a>
    </div>
</section>

<!-- Tours Section -->
<section id="tours" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tours in <?php echo htmlspecialchars($country['name']); ?></h2>
            <p class="text-xl text-gray-600">Discover unforgettable experiences</p>
        </div>
        
        <?php if (empty($tours)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No tours available yet. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='../../pages/tour-detail.php?id=<?php echo $tour['id']; ?>'">
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                    <?php if ($tour['featured']): ?>
                    <span class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                        <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-xl transition-all">
                        View Details →
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Country Information -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">About <?php echo htmlspecialchars($country['name']); ?></h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">💰</div>
                <h3 class="font-bold text-gray-900 mb-2">Currency</h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🗣️</div>
                <h3 class="font-bold text-gray-900 mb-2">Language</h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($country['language'] ?: 'Local Language'); ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🌤️</div>
                <h3 class="font-bold text-gray-900 mb-2">Best Time to Visit</h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($country['best_time_to_visit'] ?: 'Year-round'); ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">📍</div>
                <h3 class="font-bold text-gray-900 mb-2">Region</h3>
                <p class="text-gray-600"><?php echo htmlspecialchars($country['region_name']); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?php echo htmlspecialchars($country['name']); ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Book your adventure today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="../../pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="../../pages/contact.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
