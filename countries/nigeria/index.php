<?php
session_start();
require_once '../../config.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', 'Inter', sans-serif !important; }
        h1, h2, h3, h4, h5, h6 { font-weight: 800; letter-spacing: -0.5px; }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="relative w-full min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0 w-full h-full">
        <img src="<?= BASE_URL ?>/assets/images/nigeria-hero.png" alt="<?= htmlspecialchars($country['name']) ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
        <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-4 sm:mb-6 leading-tight drop-shadow-lg">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl text-gray-100 mb-6 sm:mb-8 max-w-4xl mx-auto leading-relaxed font-bold drop-shadow-md">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-base sm:text-lg md:text-xl text-gray-50 mb-8 sm:mb-12 max-w-3xl mx-auto leading-relaxed drop-shadow-md px-2">
            <?= htmlspecialchars($country['tourism_description'] ?: $country['description'] ?: 'Experience Nigeria\'s vibrant culture, diverse landscapes, and warm hospitality. Discover the gateway to West Africa.') ?>
        </p>
        <a href="#tours" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-base sm:text-lg font-bold rounded-lg sm:rounded-xl hover:shadow-2xl hover:from-yellow-600 hover:to-orange-600 transition-all transform hover:scale-105">
            <span>Explore Tours</span>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
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