<?php
/**
 * Universal Subdomain Fix Script
 * Fixes all continent and country subdomain pages
 */

$base_dir = __DIR__;

// ============================================
// FIX 1: Update all continent index.php files
// ============================================

$continents = ['africa', 'asia', 'europe', 'north-america', 'south-america', 'oceania', 'caribbean'];

$continent_template = <<<'PHP'
<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';

$continent_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if (!$continent) {
    header('Location: ' . BASE_URL . '/pages/destinations.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.country_code FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active'
    ORDER BY t.featured DESC, t.popularity_score DESC
    LIMIT 6
");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();

$page_title = $continent['name'] . " - Discover Amazing Destinations - iForYoungTours";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="<?= BASE_URL ?>" class="text-2xl font-bold text-yellow-600">iForYoungTours</a>
            <div class="flex gap-6">
                <a href="<?= BASE_URL ?>/pages/packages.php" class="text-gray-700 hover:text-yellow-600">Tours</a>
                <a href="<?= BASE_URL ?>/pages/destinations.php" class="text-gray-700 hover:text-yellow-600">Destinations</a>
                <a href="<?= BASE_URL ?>/pages/contact.php" class="text-gray-700 hover:text-yellow-600">Contact</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo getImageUrl($continent['image_url'], 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" alt="<?php echo htmlspecialchars($continent['name']); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-6xl md:text-8xl font-extrabold text-white mb-6 leading-tight">
            <?php echo htmlspecialchars($continent['name']); ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-4xl mx-auto leading-relaxed">
            <?php echo htmlspecialchars($continent['description']); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="#countries" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-lg font-bold rounded-xl hover:shadow-2xl transition-all">
                Explore Countries
            </a>
            <a href="#tours" class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm text-white border-2 border-white text-lg font-bold rounded-xl hover:bg-white/20 transition-all">
                View Tours
            </a>
        </div>
    </div>
</section>

<!-- Countries Grid -->
<section id="countries" class="py-20 bg-gradient-to-b from-white to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gray-900 mb-4">Explore by Country</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover the diverse beauty of <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($countries as $country): ?>
            <?php
            $country_code = strtolower(substr($country['country_code'], 0, 2));
            $country_url = BASE_URL . '/countries/' . $country['slug'];
            ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer transform hover:-translate-y-2" onclick="window.location.href='<?php echo $country_url; ?>'">
                <div class="relative h-72 overflow-hidden">
                    <img src="<?= getImageUrl($country['image_url'], 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800') ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($country['name']); ?></h3>
                        <p class="text-sm text-gray-200 mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($country['description'] ?: 'Discover the beauty and culture', 0, 80)); ?>...</p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Tours -->
<?php if (!empty($featured_tours)): ?>
<section id="tours" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top <?php echo htmlspecialchars($continent['name']); ?> Tours</h2>
            <p class="text-xl text-gray-600">Discover our most popular experiences</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url'], 'assets/images/default-tour.jpg') ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'] ?: 'Discover amazing experiences', 0, 100)) . '...'; ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                        <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/pages/tour-detail.php?id=<?php echo $tour['id']; ?>" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-full text-center font-semibold hover:shadow-xl transition-all">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?php echo htmlspecialchars($continent['name']); ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of travelers discovering the magic</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>/pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-xl hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="<?= BASE_URL ?>/pages/contact.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-xl hover:bg-white/20 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p>&copy; 2025 iForYoungTours. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
PHP;

echo "Fixing continent subdomains...\n";
foreach ($continents as $continent) {
    $continent_path = "$base_dir/continents/$continent";
    if (is_dir($continent_path)) {
        file_put_contents("$continent_path/index.php", $continent_template);
        echo "✓ Fixed: continents/$continent/index.php\n";
    }
}

// ============================================
// FIX 2: Update all country config.php files
// ============================================

$country_config_template = <<<'PHP'
<?php
/**
 * Country Configuration File
 * Includes main config and ensures proper BASE_URL handling
 */

require_once __DIR__ . '/../../config.php';
?>
PHP;

echo "\nFixing country configs...\n";
$countries_dir = "$base_dir/countries";
if (is_dir($countries_dir)) {
    $country_folders = array_diff(scandir($countries_dir), ['.', '..', 'index.php', 'template-country.php']);
    foreach ($country_folders as $country) {
        $country_path = "$countries_dir/$country";
        if (is_dir($country_path)) {
            file_put_contents("$country_path/config.php", $country_config_template);
            echo "✓ Fixed: countries/$country/config.php\n";
        }
    }
}

// ============================================
// FIX 3: Create universal country index template
// ============================================

$country_index_template = <<<'PHP'
<?php
session_start();
require_once 'config.php';
require_once '../../config/database.php';

$country_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name, r.slug as continent_slug FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ' . BASE_URL . '/pages/destinations.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$all_tours = $stmt->fetchAll();

$page_title = "Discover " . $country['name'] . " | Forever Young Tours";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="<?= BASE_URL ?>" class="text-2xl font-bold text-yellow-600">iForYoungTours</a>
            <div class="flex gap-6">
                <a href="<?= BASE_URL ?>/pages/packages.php" class="text-gray-700 hover:text-yellow-600">Tours</a>
                <a href="<?= BASE_URL ?>/pages/destinations.php" class="text-gray-700 hover:text-yellow-600">Destinations</a>
                <a href="<?= BASE_URL ?>/pages/contact.php" class="text-gray-700 hover:text-yellow-600">Contact</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?= getImageUrl($country['image_url'], 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80') ?>" alt="<?= htmlspecialchars($country['name']) ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-4">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-lg text-gray-300 mb-8 max-w-3xl mx-auto">
            <?= htmlspecialchars($country['tourism_description'] ?: $country['description'] ?: 'Discover the wonders of ' . $country['name']) ?>
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
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tours in <?= htmlspecialchars($country['name']) ?></h2>
            <p class="text-xl text-gray-600">Discover unforgettable experiences</p>
        </div>
        
        <?php if (empty($all_tours)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No tours available yet. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($all_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative">
                    <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url'], 'assets/images/default-tour.jpg') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-56 object-cover">
                    <?php if ($tour['featured']): ?>
                    <span class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars(substr($tour['description'], 0, 100)) . '...' ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?= number_format($tour['price'], 0) ?></span>
                        <span class="text-gray-500"><?= htmlspecialchars($tour['duration']) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/pages/tour-detail.php?id=<?= $tour['id'] ?>" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold text-center hover:shadow-xl transition-all">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Country Info -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">About <?= htmlspecialchars($country['name']) ?></h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">💰</div>
                <h3 class="font-bold text-gray-900 mb-2">Currency</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['currency'] ?: 'Local Currency') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🗣️</div>
                <h3 class="font-bold text-gray-900 mb-2">Language</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['language'] ?: 'Local Language') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🌤️</div>
                <h3 class="font-bold text-gray-900 mb-2">Best Time</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['best_time_to_visit'] ?: 'Year-round') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">📍</div>
                <h3 class="font-bold text-gray-900 mb-2">Region</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['continent_name']) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?= htmlspecialchars($country['name']) ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Book your adventure today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>/pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="<?= BASE_URL ?>/pages/contact.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p>&copy; 2025 iForYoungTours. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
PHP;

echo "\nCreating country index template...\n";
file_put_contents("$base_dir/countries/template-country.php", $country_index_template);
echo "✓ Created: countries/template-country.php\n";

echo "\n✅ ALL SUBDOMAIN FIXES COMPLETE!\n";
echo "\nNext steps:\n";
echo "1. Test continent pages: http://localhost/foreveryoungtours/continents/africa/\n";
echo "2. Test country pages: http://localhost/foreveryoungtours/countries/rwanda/\n";
echo "3. All images should now load correctly using BASE_URL\n";
echo "4. Navigation links should work properly\n";
?>
