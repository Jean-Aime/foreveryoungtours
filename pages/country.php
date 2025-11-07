<?php
$page_title = "Country Details";
$css_path = '../assets/css/modern-styles.css';
// $base_path will be auto-detected in header.php based on server port

// Database connection
require_once '../config/database.php';

// Get country slug from URL
$country_slug = $_GET['country'] ?? '';

if (!$country_slug) {
    header('Location: destinations.php');
    exit;
}

// Get country details
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name, r.slug as region_slug FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: destinations.php');
    exit;
}

// Get tours for this country
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY category, name");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

// Get related countries in same region
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND id != ? AND status = 'active' LIMIT 6");
$stmt->execute([$country['region_id'], $country['id']]);
$related_countries = $stmt->fetchAll();

$page_title = $country['name'] . " - Discover " . $country['region_name'];
$page_description = $country['description'] ?: "Explore " . $country['name'] . " with our expertly curated tours and experiences.";

include './header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php 
        $hero_image = $country['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80';
        if (strpos($hero_image, 'uploads/') === 0) {
            $hero_image = '../' . $hero_image;
        }
        ?>
        <img src="<?php echo htmlspecialchars($hero_image); ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'; this.onerror=null;">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-semibold"><?php echo htmlspecialchars($country['region_name']); ?></span>
            </div>
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                Discover
                <span class="text-gradient bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent"><?php echo htmlspecialchars($country['name']); ?></span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-8 leading-relaxed">
                <?php echo htmlspecialchars($country['description'] ?: 'Experience the beauty, culture, and adventures that ' . $country['name'] . ' has to offer'); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#packages" class="btn-primary px-8 py-4 text-lg font-semibold rounded-full">
                    View Packages
                </a>
                <a href="#about" class="btn-secondary px-8 py-4 text-lg font-semibold rounded-full">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Country Information -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">About <?php echo htmlspecialchars($country['name']); ?></h2>
                <div class="prose prose-lg text-gray-600 mb-8">
                    <?php if ($country['tourism_description']): ?>
                        <p><?php echo nl2br(htmlspecialchars($country['tourism_description'])); ?></p>
                    <?php else: ?>
                        <p><?php echo htmlspecialchars($country['name']); ?> offers incredible experiences for travelers seeking adventure, culture, and natural beauty. From bustling cities to pristine wilderness, this destination provides unforgettable memories.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Quick Facts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Region</h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($country['region_name']); ?></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Currency</h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Language</h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($country['language'] ?: 'Local Language'); ?></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl">
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Best Time to Visit</h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($country['best_time_to_visit'] ?: 'Year Round'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <?php 
                $about_image = $country['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80';
                if (strpos($about_image, 'uploads/') === 0) {
                    $about_image = '../' . $about_image;
                }
                ?>
                <img src="<?php echo htmlspecialchars($about_image); ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-96 object-cover rounded-2xl shadow-lg" onerror="this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80'; this.onerror=null;">
            </div>
        </div>
    </div>
</section>

<!-- Detailed Tourism Information -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Climate & Weather -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Climate & Weather</h3>
                <p class="text-gray-600 mb-3"><?php echo htmlspecialchars($country['name']); ?> enjoys a diverse climate with distinct seasons.</p>
                <p class="text-sm text-golden-600 font-semibold">Best Time: <?php echo htmlspecialchars($country['best_time_to_visit'] ?: 'Year Round'); ?></p>
            </div>
            
            <!-- Culture & People -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Culture & People</h3>
                <p class="text-gray-600 mb-3">Rich cultural heritage with warm, welcoming people and diverse traditions.</p>
                <p class="text-sm text-golden-600 font-semibold">Language: <?php echo htmlspecialchars($country['language'] ?: 'Local Language'); ?></p>
            </div>
            
            <!-- Currency & Economy -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Currency & Economy</h3>
                <p class="text-gray-600 mb-3">Stable economy with growing tourism sector and modern banking facilities.</p>
                <p class="text-sm text-golden-600 font-semibold">Currency: <?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></p>
            </div>
            
            <!-- Transportation -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Transportation</h3>
                <p class="text-gray-600">Well-developed transport network including airports, roads, and public transport systems for easy travel.</p>
            </div>
            
            <!-- Safety & Health -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Safety & Health</h3>
                <p class="text-gray-600">Generally safe for tourists with good healthcare facilities. Follow standard travel precautions and health guidelines.</p>
            </div>
            
            <!-- Cuisine & Food -->
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Cuisine & Food</h3>
                <p class="text-gray-600">Diverse culinary traditions featuring local ingredients, spices, and cooking methods that reflect the country's rich heritage.</p>
            </div>
        </div>
    </div>
</section>

<!-- Travel Requirements -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Travel Requirements & Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-blue-50 p-8 rounded-2xl">
                <h3 class="text-2xl font-bold mb-4 text-blue-900">Entry Requirements</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Valid passport (6+ months validity)
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Visa requirements (check with embassy)
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Yellow fever certificate (if required)
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Return/onward ticket
                    </li>
                </ul>
            </div>
            
            <div class="bg-green-50 p-8 rounded-2xl">
                <h3 class="text-2xl font-bold mb-4 text-green-900">Health & Safety</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Travel insurance recommended
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Malaria prophylaxis (if applicable)
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Standard vaccinations up to date
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Emergency contacts available
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Available Packages -->
<section id="packages" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Packages</h2>
                <p class="text-xl text-gray-600">Explore our curated tours and experiences in <?php echo htmlspecialchars($country['name']); ?></p>
            </div>
            <a href="packages.php?country=<?php echo $country['slug']; ?>" class="btn-primary px-6 py-3 rounded-lg">
                View All Packages
            </a>
        </div>
        
        <?php if (empty($tours)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg mb-6">No packages available for <?php echo htmlspecialchars($country['name']); ?> yet.</p>
            <a href="packages.php" class="btn-secondary px-6 py-3 rounded-lg">Browse Other Destinations</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice($tours, 0, 6) as $tour): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <?php 
                $tour_image = $tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=400&q=80';
                if (strpos($tour_image, 'uploads/') === 0) {
                    $tour_image = '../' . $tour_image;
                }
                ?>
                <img src="<?php echo htmlspecialchars($tour_image); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-48 object-cover" onerror="this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=400&q=80'; this.onerror=null;">
                <div class="p-6">
                    <div class="mb-2">
                        <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs"><?php echo ucfirst($tour['category']); ?></span>
                    </div>
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></span>
                        <span class="text-sm text-gray-500"><?php echo $tour['duration_days']; ?> days</span>
                    </div>
                    <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="btn-primary w-full py-3 rounded-lg text-center">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($tours) > 6): ?>
        <div class="text-center mt-12">
            <a href="packages.php?country=<?php echo $country['slug']; ?>" class="btn-secondary px-8 py-4 rounded-lg">
                View All <?php echo count($tours); ?> Packages
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Related Destinations -->
<?php if (!empty($related_countries)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore More in <?php echo htmlspecialchars($country['region_name']); ?></h2>
            <p class="text-xl text-gray-600">Discover other amazing destinations in the region</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($related_countries as $related): ?>
            <div class="relative rounded-2xl overflow-hidden destination-card cursor-pointer group" onclick="window.location.href='country.php?country=<?php echo $related['slug']; ?>'">
                <?php 
                $related_image = $related['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=400&q=80';
                if (strpos($related_image, 'uploads/') === 0) {
                    $related_image = '../' . $related_image;
                }
                ?>
                <img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=400&q=80'; this.onerror=null;">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($related['name']); ?></h3>
                    <p class="text-gray-200 text-sm"><?php echo htmlspecialchars(substr($related['description'] ?: 'Discover ' . $related['name'], 0, 80)); ?>...</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-r from-golden-600 to-golden-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?php echo htmlspecialchars($country['name']); ?>?</h2>
        <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
            Start planning your adventure today. Our expert team is ready to help you create unforgettable memories.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="packages.php?country=<?php echo $country['slug']; ?>" class="bg-white text-golden-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                Browse Packages
            </a>
            <a href="../contact.php" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-golden-600 transition-colors">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>