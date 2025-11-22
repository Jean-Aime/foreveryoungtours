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

// Get latest tour for hero section
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active'
    ORDER BY t.created_at DESC
    LIMIT 1
");
$stmt->execute([$continent['id']]);
$latest_tour = $stmt->fetch();

$page_title = $continent['name'] . " - Discover Amazing Destinations - iForYoungTours";

// Function to get proper image URL
function getTourImage($tour) {
    $base_url = 'http://localhost/foreveryoungtours';
    $default_image = $base_url . '/assets/images/default-tour.jpg';
    
    // Check cover_image first
    if (!empty($tour['cover_image'])) {
        $image_path = $tour['cover_image'];
        if (strpos($image_path, 'http') === 0) {
            return $image_path; // Full URL already
        } else {
            return $base_url . '/' . ltrim($image_path, '/');
        }
    }
    
    // Fallback to image_url
    if (!empty($tour['image_url'])) {
        $image_path = $tour['image_url'];
        if (strpos($image_path, 'http') === 0) {
            return $image_path; // Full URL already
        } else {
            return $base_url . '/' . ltrim($image_path, '/');
        }
    }
    
    return $default_image;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
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
<body>

<!-- Navigation -->
<nav class="nav-overlay w-full">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="<?= BASE_URL ?>" class="text-2xl font-black text-white hover:text-yellow-300 transition-colors drop-shadow-md">iForYoungTours</a>
            
            <!-- Desktop Menu -->
            <div class="desktop-menu flex gap-6">
                <a href="<?= BASE_URL ?>/pages/packages.php" class="text-white hover:text-yellow-300 font-bold transition-colors drop-shadow-sm">Tours</a>
                <a href="<?= BASE_URL ?>/pages/destinations.php" class="text-white hover:text-yellow-300 font-bold transition-colors drop-shadow-sm">Destinations</a>
                <a href="<?= BASE_URL ?>/pages/contact.php" class="text-white hover:text-yellow-300 font-bold transition-colors drop-shadow-sm">Contact</a>
            </div>
            
            <!-- Mobile Hamburger Menu Button -->
            <button class="mobile-menu text-white text-3xl focus:outline-none" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Menu Links -->
        <div class="mobile-nav-links hidden" id="mobileNavLinks">
            <a href="<?= BASE_URL ?>/pages/packages.php">Tours</a>
            <a href="<?= BASE_URL ?>/pages/destinations.php">Destinations</a>
            <a href="<?= BASE_URL ?>/pages/contact.php">Contact</a>
        </div>
    </div>
</nav>
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

<!-- Gateway Section -->
<section class="bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 px-4 py-2 rounded-full text-sm font-semibold text-white mb-6 shadow-sm">
                    🌍 <?php echo htmlspecialchars($continent['name']); ?>'s Leading Travel Platform
                </div>
                
                <h2 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Your gateway to
                    <span class="bg-gradient-to-r from-yellow-500 to-yellow-600 bg-clip-text text-transparent"><?php echo htmlspecialchars($continent['name']); ?> adventures</span>
                </h2>
                
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Experience the magic of <?php echo htmlspecialchars($continent['name']); ?> with our expertly curated travel packages. From safaris to cultural immersions, we make your dreams come true.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= BASE_URL ?>/pages/packages.php" class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg inline-flex items-center justify-center hover:shadow-xl transition-all">
                        Explore Tours
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="<?= BASE_URL ?>/pages/destinations.php" class="bg-white text-gray-700 border-2 border-yellow-500 px-8 py-4 rounded-2xl font-semibold text-lg inline-flex items-center justify-center hover:bg-yellow-50 transition-all">
                        View Destinations
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <?php if (!empty($latest_tour)): ?>
                <div class="bg-white p-8 rounded-3xl shadow-2xl">
                    <?php
                    $latest_tour_image = getTourImage($latest_tour);
                    ?>
                    <img src="<?php echo htmlspecialchars($latest_tour_image); ?>" 
                         alt="<?= htmlspecialchars($latest_tour['name']) ?>" 
                         class="w-full h-80 object-cover rounded-2xl mb-6"
                         onerror="this.src='http://localhost/foreveryoungtours/assets/images/default-tour.jpg'; this.onerror=null;">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($latest_tour['name']) ?></h3>
                            <p class="text-gray-600"><?= htmlspecialchars($latest_tour['duration'] ?: $latest_tour['duration_days'] . ' days') ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-600">$<?= number_format($latest_tour['price']) ?></div>
                            <div class="text-sm text-gray-500">per person</div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white p-8 rounded-3xl shadow-2xl">
                    <img src="<?= BASE_URL ?>/assets/images/default-tour.jpg" 
                         alt="Adventure" 
                         class="w-full h-80 object-cover rounded-2xl mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Adventure Awaits</h3>
                            <p class="text-gray-600">Explore <?= htmlspecialchars($continent['name']) ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-600">Coming Soon</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Countries Grid -->
<section id="countries" class="py-20 bg-white">
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
<section id="tours" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top <?php echo htmlspecialchars($continent['name']); ?> Tours</h2>
            <p class="text-xl text-gray-600">Discover our most popular experiences</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                <?php
                $tour_image = getTourImage($tour);
                ?>
                <img src="<?php echo htmlspecialchars($tour_image); ?>" 
                     alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                     class="w-full h-56 object-cover"
                     onerror="this.src='http://localhost/foreveryoungtours/assets/images/default-tour.jpg'; this.onerror=null;">
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
<section class="py-20 bg-gradient-to-r from-yellow-500 to-yellow-600">
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
<?php include '../../includes/footer.php'; ?>

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