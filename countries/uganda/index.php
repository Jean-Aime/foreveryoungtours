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
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-1 sm:mb-2">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-3 sm:mb-4">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-sm sm:text-base md:text-lg text-gray-300 mb-6 sm:mb-8 max-w-2xl mx-auto">
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