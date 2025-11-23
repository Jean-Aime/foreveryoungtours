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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', 'Inter', sans-serif;
        }
        body {
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
        }
        .nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
            transition: background 0.3s ease;
        }
        .nav-overlay:hover {
            background: rgba(0, 0, 0, 0.35);
        }
        .mobile-menu {
            display: none;
        }
        @media (max-width: 768px) {
            .desktop-menu {
                display: none !important;
            }
            .mobile-menu {
                display: block !important;
            }
            .mobile-nav-links {
                position: absolute;
                top: 64px;
                right: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.95);
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 15px;
                z-index: 40;
            }
            .mobile-nav-links a {
                color: white;
                text-decoration: none;
                font-weight: 600;
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .mobile-nav-links a:hover {
                color: #fcd34d;
            }
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="relative w-full min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0 w-full h-full">
        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2070&q=80" alt="<?= htmlspecialchars($country['name']) ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white mb-1 sm:mb-2 leading-tight drop-shadow-lg">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-gray-100 mb-3 sm:mb-4 max-w-4xl mx-auto leading-relaxed font-bold drop-shadow-md">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-sm sm:text-base md:text-lg text-gray-50 mb-6 sm:mb-8 max-w-2xl mx-auto leading-relaxed drop-shadow-md px-2">
            <?= htmlspecialchars($country['tourism_description'] ?: $country['description'] ?: 'Experience Rwanda\'s remarkable recovery and track mountain gorillas in their natural habitat. Discover stunning landscapes, vibrant culture, and unforgettable wildlife encounters.') ?>
        </p>
        <a href="#tours" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-base sm:text-lg font-bold rounded-lg sm:rounded-xl hover:shadow-2xl hover:from-yellow-600 hover:to-orange-600 transition-all transform hover:scale-105">
            <span>Explore Tours</span>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>

<!-- Why Visit Rwanda Section -->
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-3 sm:mb-4">Why Visit Rwanda?</h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 font-semibold">Experience the magic of Africa's most welcoming destination</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🦍</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Mountain Gorillas</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Track endangered mountain gorillas in Volcanoes National Park. One of the world's most incredible wildlife experiences with less than 900 gorillas remaining in the wild.</p>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🌋</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Stunning Landscapes</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Hike through misty volcanic rainforests, discover thousand hills with panoramic vistas, and visit pristine crater lakes surrounded by lush vegetation.</p>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🎭</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Rich Culture</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Immerse yourself in Rwandan traditions, visit local communities, experience traditional dance and music, and learn about the country's remarkable recovery.</p>
            </div>
            
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🦁</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Wildlife Safari</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Explore diverse ecosystems and encounter wildlife including lions, zebras, antelopes, and over 700 bird species in Akagera National Park.</p>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🌍</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Kigali City</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Visit Rwanda's modern capital city with excellent restaurants, museums, and memorials. Experience contemporary Africa with vibrant markets and nightlife.</p>
            </div>
            
            <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:shadow-xl transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🌸</div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 mb-2 sm:mb-3">Peace & Safety</h3>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">Rwanda is one of Africa's safest and most stable countries, with excellent infrastructure, friendly locals, and a welcoming atmosphere for travelers.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tours Section -->
<section id="tours" class="py-12 sm:py-16 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-3 sm:mb-4">Unforgettable Tours in <?= htmlspecialchars($country['name']) ?></h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 font-semibold">Discover carefully curated adventures tailored to your interests</p>
        </div>
        
        <?php if (empty($all_tours)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No tours available yet. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <?php foreach ($all_tours as $tour): ?>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative">
                    <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url'], 'assets/images/default-tour.jpg') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-48 sm:h-56 md:h-64 object-cover">
                    <?php if ($tour['featured']): ?>
                    <span class="absolute top-3 sm:top-4 right-3 sm:right-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-3 sm:px-4 py-1 sm:py-2 rounded-full text-xs sm:text-sm font-bold">Featured</span>
                    <?php endif; ?>
                </div>
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg sm:text-2xl font-extrabold text-gray-900 mb-2 sm:mb-3"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4 line-clamp-3 leading-relaxed"><?= htmlspecialchars(substr($tour['description'], 0, 120)) . '...' ?></p>
                    <div class="flex items-center justify-between mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-gray-200">
                        <span class="text-2xl sm:text-3xl font-extrabold text-yellow-600">$<?= number_format($tour['price'], 0) ?></span>
                        <span class="text-xs sm:text-base text-gray-600 font-semibold"><i class="fas fa-calendar-alt mr-2"></i><?= htmlspecialchars($tour['duration']) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/pages/tour-detail.php?id=<?= $tour['id'] ?>" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl font-extrabold text-sm sm:text-base text-center hover:shadow-xl transition-all transform hover:scale-105">
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
<section class="w-screen left-1/2 right-1/2 -mx-1/2 py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-3 sm:mb-4">Essential Information</h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 font-semibold">Everything you need to know before your visit</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">💰</div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-2 sm:mb-3">Currency</h3>
                <p class="text-sm sm:text-base text-gray-700 font-semibold"><?= htmlspecialchars($country['currency'] ?: 'Rwandan Franc (RWF)') ?></p>
                <p class="text-xs sm:text-sm text-gray-600 mt-2">~1 USD = 1,300 RWF</p>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🗣️</div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-2 sm:mb-3">Language</h3>
                <p class="text-sm sm:text-base text-gray-700 font-semibold"><?= htmlspecialchars($country['language'] ?: 'English, French, Kinyarwanda') ?></p>
                <p class="text-xs sm:text-sm text-gray-600 mt-2">English widely spoken in tourist areas</p>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🌤️</div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-2 sm:mb-3">Best Time</h3>
                <p class="text-sm sm:text-base text-gray-700 font-semibold"><?= htmlspecialchars($country['best_time_to_visit'] ?: 'June - September (Dry Season)') ?></p>
                <p class="text-xs sm:text-sm text-gray-600 mt-2">Year-round destination</p>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl sm:rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all">
                <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">📍</div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-2 sm:mb-3">Region</h3>
                <p class="text-sm sm:text-base text-gray-700 font-semibold"><?= htmlspecialchars($country['continent_name'] ?: 'East Africa') ?></p>
                <p class="text-xs sm:text-sm text-gray-600 mt-2">Central/East Africa</p>
            </div>
        </div>
    </div>
</section>

<!-- Visa & Travel Tips -->
<section class="py-12 sm:py-16 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4 sm:mb-6">Visa Requirements</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-yellow-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-check-circle text-green-600 mr-2"></i>E-Visa Available</h3>
                        <p class="text-sm sm:text-base text-gray-700">Easy online visa application for most nationalities through RDB website</p>
                    </div>
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-yellow-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-check-circle text-green-600 mr-2"></i>Processing Time</h3>
                        <p class="text-sm sm:text-base text-gray-700">E-Visa typically approved within 72 hours</p>
                    </div>
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-yellow-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-check-circle text-green-600 mr-2"></i>Duration</h3>
                        <p class="text-sm sm:text-base text-gray-700">Single entry visa valid for 30 days (extendable)</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4 sm:mb-6">Travel Tips</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-orange-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Best Time to Visit</h3>
                        <p class="text-sm sm:text-base text-gray-700">June-September and December-February offer the best weather for gorilla trekking</p>
                    </div>
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-orange-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-info-circle text-blue-600 mr-2"></i>What to Pack</h3>
                        <p class="text-sm sm:text-base text-gray-700">Bring rain jackets, sturdy hiking boots, insect repellent, and sunscreen for high altitude</p>
                    </div>
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 border-l-4 border-orange-500">
                        <h3 class="text-base sm:text-lg font-extrabold text-gray-900 mb-2"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Health Precautions</h3>
                        <p class="text-sm sm:text-base text-gray-700">Consult your doctor about malaria prophylaxis and recommended vaccinations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 sm:mb-6">Ready to Explore Rwanda?</h2>
        <p class="text-lg sm:text-xl md:text-2xl text-white/90 mb-6 sm:mb-8 font-bold">Book your unforgettable African adventure today</p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="<?= BASE_URL ?>/pages/packages.php" class="bg-white text-yellow-600 px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-bold rounded-lg sm:rounded-xl hover:shadow-2xl transition-all transform hover:scale-105">
                Browse All Tours
            </a>
            <a href="<?= BASE_URL ?>/pages/contact.php" class="bg-white/20 backdrop-blur-sm text-white border-2 border-white px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-bold rounded-lg sm:rounded-xl hover:bg-white/30 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<?php require_once '../../includes/footer.php'; ?>

<script>
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavLinks = document.getElementById('mobileNavLinks');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileNavLinks.classList.toggle('hidden');
        });
        
        // Close menu when a link is clicked
        const links = mobileNavLinks.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                mobileNavLinks.classList.add('hidden');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('nav')) {
                mobileNavLinks.classList.add('hidden');
            }
        });
    }
});
</script>

</body>
</html>