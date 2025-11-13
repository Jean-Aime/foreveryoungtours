<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Explore Africa - Luxury Group Travel & Safari Adventures | Forever Young Tours</title>
    <meta name="description" content="Discover Africa's best destinations. Premium safaris, cultural experiences, and luxury tours across Kenya, Tanzania, Rwanda, Uganda, and more. Expert-curated adventures.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Explore Africa - Luxury Group Travel & Safari Adventures">
    <meta property="og:description" content="Discover Africa's best destinations with premium safaris and cultural experiences.">
    <meta property="og:image" content="https://iforeveryoungtours.com/assets/images/africa-og.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Explore Africa - Luxury Group Travel & Safari Adventures">
    <meta property="twitter:description" content="Discover Africa's best destinations with premium safaris and cultural experiences.">
    
    <!-- Mobile Optimization -->
    <meta name="theme-color" content="#F59E0B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-amber': '#F59E0B',
                        'brand-orange': '#EA580C',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans">
<?php
require_once __DIR__ . '/../../config/database.php';

// Get continent data from current directory
$continent_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if (!$continent) {
    header('Location: ../../pages/destinations.php');
    exit;
}

// Get countries in Africa
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

// Get top 6 featured tours
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active' AND t.featured = 1
    ORDER BY t.popularity_score DESC
    LIMIT 6
");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();

// Set paths for subdomain
$base_path = '../../';
$css_path = '../assets/css/modern-styles.css';
?>

<!-- Hero Section - Professional Parallax -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero">
    <!-- Parallax Background -->
    <div class="absolute inset-0 z-0" data-parallax>
        <img src="<?php echo htmlspecialchars($continent['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" alt="<?php echo htmlspecialchars($continent['name']); ?>" class="w-full h-full object-cover scale-110">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-amber-900/70"></div>
        
        <!-- Animated Overlay Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
        </div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Region Badge -->
        <div class="inline-flex items-center px-6 py-3 bg-amber-500/20 backdrop-blur-md border border-amber-400/30 rounded-full mb-8 animate-fade-in-down shadow-xl shadow-amber-500/10">
            <svg class="w-5 h-5 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-amber-300 font-bold text-sm uppercase tracking-wider">Explore the Continent</span>
        </div>
        
        <!-- Main Heading -->
        <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-6 leading-tight animate-fade-in">
            <span class="block mb-2">Discover</span>
            <span class="block bg-gradient-to-r from-amber-400 via-orange-400 to-amber-400 bg-clip-text text-transparent">
                <?php echo htmlspecialchars($continent['name']); ?>
            </span>
        </h1>
        
        <!-- Description -->
        <p class="text-lg sm:text-xl md:text-2xl text-gray-200 mb-12 leading-relaxed max-w-4xl mx-auto animate-fade-in-up font-light">
            <?php echo htmlspecialchars($continent['description'] ?: 'Experience the magic of diverse cultures, wildlife, and breathtaking landscapes.'); ?>
        </p>
        
        <!-- CTA Buttons -->
        <div class="flex flex-wrap gap-4 justify-center mb-8 animate-fade-in-up">
            <a href="#countries" class="group relative bg-gradient-to-r from-amber-500 to-orange-500 text-white px-10 py-4 rounded-full font-bold overflow-hidden shadow-2xl shadow-amber-500/50 hover:shadow-amber-500/70 transition-all duration-300 hover:scale-105">
                <span class="relative z-10 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Explore Countries
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
            <a href="#tours" class="group relative bg-white/10 backdrop-blur-md text-white px-10 py-4 rounded-full font-bold border-2 border-white/30 hover:bg-white/20 transition-all duration-300 hover:scale-105 shadow-xl">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"></path>
                    </svg>
                    View Tours
                </span>
            </a>
        </div>
        
        <!-- Contact Info -->
        <div class="flex flex-wrap justify-center gap-6 text-sm text-white/80 animate-fade-in-up">
            <a href="tel:+17374439646" class="flex items-center gap-2 hover:text-amber-400 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                </svg>
                +1 737 443 9646
            </a>
            <a href="mailto:info@iforeveryoungtours.com" class="flex items-center gap-2 hover:text-amber-400 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
                info@iforeveryoungtours.com
            </a>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Content Hero Section - Professional Design -->
<section class="relative py-24 bg-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-blue-50 opacity-60"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-5 gap-12 items-center">
            <!-- Left Content - 3 columns -->
            <div class="lg:col-span-3">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500/10 to-green-500/10 rounded-full mb-6 border border-yellow-500/20">
                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-4a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-slate-700">Africa's Leading Travel Platform</span>
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-extrabold text-slate-900 mb-6 leading-tight">
                    Discover Africa's
                    <span class="block mt-2 bg-gradient-to-r from-yellow-600 via-green-600 to-yellow-600 bg-clip-text text-transparent">Hidden Treasures</span>
                </h1>
                
                <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-2xl">
                    Embark on extraordinary journeys across the African continent. From thrilling safaris to immersive cultural experiences, we craft unforgettable adventures tailored to your dreams.
                </p>
                
                <div class="flex flex-wrap gap-4 mb-8">
                    <a href="#countries" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-bold rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Explore Tours
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="#tours" class="inline-flex items-center px-8 py-4 bg-white text-slate-700 font-bold rounded-xl border-2 border-slate-200 hover:border-yellow-500 hover:text-yellow-600 transition-all shadow-sm hover:shadow-md">
                        View Destinations
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="font-semibold">4.9/5 Rating</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Verified Tours</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                        <span class="font-semibold">24/7 Support</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Featured Tour Card - 2 columns -->
            <div class="lg:col-span-2">
                <?php if (!empty($featured_tours)): ?>
                <?php 
                $hero_tour = $featured_tours[0];
                $hero_image = $hero_tour['cover_image'] ?: $hero_tour['image_url'] ?: '../../assets/images/default-tour.jpg';
                ?>
                <div class="group relative bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 hover:shadow-3xl transition-all duration-500 transform hover:-translate-y-2">
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-4 py-2 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg">FEATURED</span>
                    </div>
                    <div class="relative h-64 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($hero_image); ?>" alt="<?php echo htmlspecialchars($hero_tour['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($hero_tour['name']); ?></h3>
                        <p class="text-slate-600 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <?php echo htmlspecialchars($hero_tour['duration']); ?>
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-sm text-slate-500">Starting from</p>
                                <p class="text-3xl font-bold text-yellow-600">$<?php echo number_format($hero_tour['price']); ?></p>
                            </div>
                            <a href="/pages/tour-detail.php?id=<?php echo $hero_tour['id']; ?>" class="px-6 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
                    <div class="h-64 bg-gradient-to-br from-slate-100 to-slate-200"></div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Amazing Tours Coming Soon</h3>
                        <p class="text-slate-600 mb-4">Stay tuned for incredible adventures</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Countries Grid - Enhanced -->
<section id="countries" class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold mb-4 uppercase tracking-wide">Destinations</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Explore by Country</h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">From the pyramids of Egypt to the wildlife of Kenya, discover the diverse beauty of <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($countries as $country): ?>
            <?php
            // Map country slug to subdomain code
            $country_codes = [
                'visit-rw' => 'rw',
                'rwanda' => 'rw',
                'kenya' => 'ke',
                'tanzania' => 'tz',
                'uganda' => 'ug',
                'egypt' => 'eg',
                'morocco' => 'ma',
                'south-africa' => 'za',
                'botswana' => 'bw',
                'namibia' => 'na',
                'zimbabwe' => 'zw'
            ];
            $country_code = $country_codes[$country['slug']] ?? strtolower(substr($country['country_code'], 0, 2));
            
            // Detect environment based on current host
            $current_host = $_SERVER['HTTP_HOST'];
            if (strpos($current_host, 'iforeveryoungtours.com') !== false) {
                // Production environment
                $country_url = "https://visit-{$country_code}.iforeveryoungtours.com";
            } else {
                // Local environment
                $country_url = "http://visit-{$country_code}.foreveryoungtours.local";
            }
            ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2" onclick="window.open('<?php echo $country_url; ?>', '_blank')">
                <div class="relative h-64 sm:h-72 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($country['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800'); ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-lg">EXPLORE</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2 group-hover:text-amber-400 transition-colors"><?php echo htmlspecialchars($country['name']); ?></h3>
                        <p class="text-sm text-gray-200 mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($country['description'] ?: 'Discover the beauty and culture', 0, 80)); ?>...</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-300"><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></span>
                            <svg class="w-5 h-5 text-amber-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Tours -->
<section id="tours" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top <?php echo htmlspecialchars($continent['name']); ?> Tours</h2>
            <p class="text-xl text-gray-600">Discover our most popular experiences from <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <?php if (!empty($featured_tours)): ?>
        <div id="toursCarousel" class="relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $visible_tours = array_slice($featured_tours, 0, 3);
                foreach ($visible_tours as $tour): 
                ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="window.location.href='../../pages/tour-detail.php?id=<?php echo $tour['id']; ?>'">
                    <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                        <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'] ?: 'Discover amazing experiences', 0, 100)) . '...'; ?></p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                            <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><?php echo htmlspecialchars($tour['country_name'] ?: 'Multiple Countries'); ?></span>
                            <button class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:shadow-xl transition-all">
                                View Tour →
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        

        
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Featured tours coming soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
#toursCarousel .grid > div {
    transition: all 0.5s ease-in-out;
}
</style>

<!-- Why FYT For Africa -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose FYT for Africa</h2>
            <p class="text-xl text-gray-600">Your trusted partner for African adventures</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Expanding Country-by-Country</h3>
                <p class="text-gray-600">Growing our network across Africa to bring you more destinations</p>
            </div>
            
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Local Travel Advisors</h3>
                <p class="text-gray-600">Partnered with experts for authentic experiences</p>
            </div>
            
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Protected Experiences</h3>
                <p class="text-gray-600">Travel with confidence and comprehensive coverage</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Explore Africa?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of travelers discovering the magic of Africa</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="http://foreveryoungtours.local/pages/packages.php" class="bg-white text-amber-600 px-10 py-4 text-lg font-bold rounded-full hover:shadow-2xl transition-all hover:scale-105">
                Browse All Tours
            </a>
            <a href="https://wa.me/17374439646?text=Hi!%20I%20want%20to%20explore%20Africa" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-10 py-4 text-lg font-bold rounded-full hover:bg-white/20 transition-all hover:scale-105">
                <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us
            </a>
        </div>
    </div>
</section>

<!-- Floating WhatsApp Button -->
<div class="fixed bottom-6 right-6 z-50">
    <!-- Dropdown Card -->
    <div id="whatsappDropup" class="hidden mb-4 bg-white rounded-2xl shadow-2xl w-80 animate-slide-up">
        <div class="bg-green-500 text-white px-6 py-4 rounded-t-2xl">
            <h3 class="font-bold text-lg">WhatsApp Support</h3>
            <p class="text-sm text-white/90">We're here to help!</p>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4 text-sm">Get instant answers about Africa tours, pricing, and availability.</p>
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>24/7 Support</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>Expert Advisors</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>Quick Response</span>
                </div>
            </div>
            <a href="https://wa.me/17374439646?text=Hi!%20I%20need%20help%20with%20Africa%20travel" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 rounded-xl font-bold transition text-sm">
                <i class="fab fa-whatsapp mr-2"></i>Start Chat
            </a>
            <p class="text-center text-gray-500 text-xs mt-3">+1 (737) 443-9646</p>
        </div>
    </div>
    
    <!-- WhatsApp Button -->
    <button onclick="toggleWhatsAppDropup()" class="bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 transition transform hover:scale-110">
        <i class="fab fa-whatsapp text-3xl"></i>
    </button>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}
</style>

<script>
function toggleWhatsAppDropup() {
    const dropup = document.getElementById('whatsappDropup');
    dropup.classList.toggle('hidden');
}
// Close when clicking outside
document.addEventListener('click', function(e) {
    const dropup = document.getElementById('whatsappDropup');
    const button = e.target.closest('button[onclick="toggleWhatsAppDropup()"]');
    if (!button && !dropup.contains(e.target)) {
        dropup.classList.add('hidden');
    }
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

<style>
/* Professional Tourism Theme Animations */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

.animate-fade-in-down {
    animation: fade-in-down 1s ease-out forwards;
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: #1e293b;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #f59e0b, #ea580c);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #d97706, #c2410c);
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Touch-Friendly Improvements */
@media (hover: none) and (pointer: coarse) {
    .hover\:scale-105:hover {
        transform: scale(1.02);
    }
    
    button, a {
        -webkit-tap-highlight-color: rgba(245, 158, 11, 0.3);
    }
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden;
}

/* Ensure images don't overflow */
img {
    max-width: 100%;
    height: auto;
}

/* Fix for iOS Safari viewport height */
@supports (-webkit-touch-callout: none) {
    .min-h-screen {
        min-height: -webkit-fill-available;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gradient-to-br {
        border: 2px solid currentColor;
    }
}
</style>

<script>
// Parallax Scrolling Effect
document.addEventListener('DOMContentLoaded', function() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(function(element) {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = 'translateY(' + yPos + 'px)';
        });
    });
    
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.observe-fade').forEach(function(el) {
        observer.observe(el);
    });
});
</script>

</body>
</html>
