<?php

require_once 'config.php';
$page_title = "African Travel Packages - iForYoungTours | Expert-Curated Adventures";
$page_description = "Explore 1200+ expertly curated African travel packages. From safaris to cultural experiences, find your perfect adventure with transparent pricing and 24/7 support.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";

// Database connection
require_once '../config/database.php';

// Get filter parameters
$country_filter = $_GET['country'] ?? '';
$region_filter = $_GET['region'] ?? '';
$category_filter = $_GET['category'] ?? '';

// Build WHERE clause
$where_conditions = ["t.status = 'active'"];
$params = [];

if ($country_filter) {
    $where_conditions[] = "c.slug = ?";
    $params[] = $country_filter;
}
if ($region_filter) {
    $where_conditions[] = "r.slug = ?";
    $params[] = $region_filter;
}
if ($category_filter) {
    $where_conditions[] = "t.category = ?";
    $params[] = $category_filter;
}

$where_clause = implode(' AND ', $where_conditions);

// Fetch tours with region and country info
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name, r.slug as region_slug FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id WHERE $where_clause ORDER BY r.name, c.name, t.featured DESC, t.price ASC");
$stmt->execute($params);
$tours = $stmt->fetchAll();

// Get all regions for filters
$stmt = $pdo->prepare("SELECT * FROM regions WHERE status = 'active' ORDER BY name");
$stmt->execute();
$regions = $stmt->fetchAll();

// Get all countries for filters
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-r from-blue-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                African <span class="text-gradient">Travel Packages</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover 1200+ expertly curated travel experiences across 47 African countries. From luxury safaris to cultural immersions, find your perfect adventure.
            </p>
        </div>
        
        <!-- Search Bar -->
        <div class="max-w-4xl mx-auto">
            <div class="search-input rounded-2xl p-6 shadow-lg">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="package-search" placeholder="Search destinations, activities, or keywords..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button onclick="performSearch()" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                        Search Packages
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <div class="filter-sidebar rounded-2xl p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Filter Packages</h3>
                    
                    <!-- Experience Type -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Experience Type</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="experience-filter mr-3" value="safari" 
                                       onchange="applyFilters()">
                                <span class="text-gray-700">Safari & Wildlife</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="experience-filter mr-3" value="cultural" 
                                       onchange="applyFilters()">
                                <span class="text-gray-700">Cultural Heritage</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="experience-filter mr-3" value="beach" 
                                       onchange="applyFilters()">
                                <span class="text-gray-700">Beach & Relaxation</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="experience-filter mr-3" value="adventure" 
                                       onchange="applyFilters()">
                                <span class="text-gray-700">Adventure & Sports</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="experience-filter mr-3" value="luxury" 
                                       onchange="applyFilters()">
                                <span class="text-gray-700">Luxury Experiences</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Regions -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Regions</h4>
                        <div class="space-y-3">
                            <?php foreach ($regions as $region): ?>
                            <label class="flex items-center">
                                <input type="checkbox" class="region-filter mr-3" value="<?php echo $region['slug']; ?>" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700"><?php echo htmlspecialchars($region['name']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Countries -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Countries</h4>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            <?php foreach ($countries as $country): ?>
                            <label class="flex items-center">
                                <input type="checkbox" class="country-filter mr-3" value="<?php echo $country['slug']; ?>" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700"><?php echo htmlspecialchars($country['name']); ?></span>
                                <span class="text-xs text-gray-500 ml-2">(<?php echo htmlspecialchars($country['region_name']); ?>)</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Tour Types -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Tour Types</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="motorcoach" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Motorcoach Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="rail" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Rail Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="cruise" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Cruises</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="city" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">City Breaks</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="agro" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Agro Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="adventure" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Adventure Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="sports" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Sport Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="cultural" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Cultural Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="conference" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Conference & Expos</span>
                            </label>
                        </div>
                    </div>
                    
                    <button onclick="clearAllFilters()" class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        Clear All Filters
                    </button>
                </div>
            </div>
            
            <!-- Packages Grid -->
            <div class="lg:w-3/4">
                <!-- Sort and View Options -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Sort by:</span>
                        <select id="sort-select" onchange="applySorting()" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="popularity">Popularity</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="duration">Duration</option>
                            <option value="rating">Rating</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleView('grid')" id="grid-view" class="p-2 rounded-lg bg-blue-500 text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button onclick="toggleView('list')" id="list-view" class="p-2 rounded-lg bg-gray-200 text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Filter Info & Results Count -->
                <div class="mb-6">
                    <?php if ($region_filter || $country_filter || $category_filter): ?>
                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <?php if ($region_filter): ?>
                        <span class="bg-golden-100 text-golden-800 px-3 py-1 rounded-full text-sm font-medium">
                            Region: <?php echo htmlspecialchars(array_filter($regions, fn($r) => $r['slug'] === $region_filter)[0]['name'] ?? $region_filter); ?>
                            <button onclick="clearRegionFilter()" class="ml-2 text-golden-600 hover:text-golden-800">×</button>
                        </span>
                        <?php endif; ?>
                        <?php if ($country_filter): ?>
                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">
                            Country: <?php echo htmlspecialchars(array_filter($countries, fn($c) => $c['slug'] === $country_filter)[0]['name'] ?? $country_filter); ?>
                            <button onclick="clearCountryFilter()" class="ml-2 text-emerald-600 hover:text-emerald-800">×</button>
                        </span>
                        <?php endif; ?>
                        <?php if ($category_filter): ?>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            Category: <?php echo ucfirst($category_filter); ?>
                            <button onclick="clearCategoryFilter()" class="ml-2 text-blue-600 hover:text-blue-800">×</button>
                        </span>
                        <?php endif; ?>
                        <button onclick="clearAllFilters()" class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-red-200 transition-colors">
                            Clear All Filters
                        </button>
                    </div>
                    <?php endif; ?>
                    <p class="text-gray-600">
                        Showing <span id="results-count"><?php echo count($tours); ?></span> packages
                        <?php if ($region_filter || $country_filter || $category_filter): ?>
                        with current filters
                        <?php endif; ?>
                    </p>
                </div>
                
                <!-- Packages Grid -->
                <div id="packages-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    <?php if (empty($tours)): ?>
                    <div class="col-span-full text-center py-12">
                        <div class="text-slate-400 mb-4">
                            <i class="fas fa-search text-6xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-600 mb-2">No tours found</h3>
                        <p class="text-slate-500 mb-4">Try adjusting your filters or browse all destinations</p>
                        <button onclick="clearAllFilters()" class="btn-primary px-6 py-3 rounded-lg">
                            View All Tours
                        </button>
                    </div>
                    <?php else: ?>
                    <?php foreach ($tours as $tour): ?>
                    <div class="package-card rounded-2xl overflow-hidden fade-in-up" 
                         data-price="<?php echo $tour['price']; ?>" 
                         data-duration="<?php echo $tour['duration_days']; ?>" 
                         data-destination="<?php echo strtolower($tour['destination'] ?? ''); ?>"
                         data-category="<?php echo strtolower($tour['category'] ?? ''); ?>"
                         data-experience="<?php echo strtolower($tour['experience_type'] ?? ''); ?>">
                        <div class="relative">
                            <?php 
                            $image_src = $tour['cover_image'] ?: $tour['image_url'] ?: '../assets/images/default-tour.jpg';
                            if (strpos($image_src, 'uploads/') === 0) {
                                $image_src = '../' . $image_src;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-40 object-cover" onerror="this.src="<?= getImageUrl('assets/images/default-tour.jpg') ?>"; this.onerror=null;">

                            <?php if ($tour['featured']): ?>
                            <div class="absolute top-4 left-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Featured
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">
                                    <?php echo htmlspecialchars($tour['region_name'] . ' - ' . $tour['country_name']); ?>
                                </span>
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium ml-2">
                                    <?php echo ucfirst($tour['category']); ?>
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                            <p class="text-gray-600 mb-3 text-sm"><?php echo htmlspecialchars(substr($tour['description'], 0, 80)) . '...'; ?></p>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm text-gray-500"><?php echo $tour['duration_days']; ?> days</span>
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        ★★★★☆
                                    </div>
                                    <span class="text-sm text-gray-500 ml-2">(<?php echo rand(50, 200); ?>)</span>
                                </div>
                            </div>
                            <div>
                                <a href="../tour/<?php echo $tour['slug']; ?>" class="block w-full bg-slate-200 text-slate-700 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-colors text-center">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                
                <!-- Load More Button -->
                <div class="text-center mt-12">
                    <button onclick="loadMorePackages()" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold">
                        Load More Packages
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Tour CTA Section -->
<section class="py-20 bg-gradient-to-br from-cream via-light-gray to-off-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-golden-500 via-primary-green to-golden-600 rounded-3xl p-12 md:p-16 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-32 -mb-32"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full">
                <div class="absolute top-10 right-20 w-32 h-32 border-4 border-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-20 left-32 w-24 h-24 border-4 border-white opacity-10 rounded-full"></div>
            </div>
            
            <div class="relative z-10 text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-golden-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Can't find what you're looking for?
                </h2>
                
                <p class="text-lg md:text-xl text-slate-800 mb-8 leading-relaxed">
                    Let us create a personalized itinerary tailored to your dreams. Our travel experts will craft the perfect African adventure just for you.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-6">
                    <div class="flex items-center text-slate-900">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Custom Itineraries</span>
                    </div>
                    <div class="flex items-center text-slate-900">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Expert Guidance</span>
                    </div>
                    <div class="flex items-center text-slate-900">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">24/7 Support</span>
                    </div>
                </div>
                
                <button onclick="openInquiryModal()" class="inline-flex items-center bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-800 transition-all transform hover:scale-105 hover:shadow-2xl">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Request Custom Tour
                </button>
                
                <p class="text-slate-800 mt-6 text-sm font-medium">
                    ✨ Free consultation • No obligation • Response within 24 hours
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Package Detail Modal -->
<div id="package-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div id="modal-content">
            <!-- Modal content will be dynamically loaded here -->
        </div>
    </div>
</div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gradient">Book Your Tour</h3>
                    <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-600 mt-2">Tour: <span id="tourName"></span></p>
                <p class="text-sm text-slate-600">Price: <span id="tourPrice"></span> per person</p>
            </div>
            <form id="bookingForm" class="p-6">
                <input type="hidden" id="tourId" name="tour_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="customer_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="customer_email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                        <input type="tel" name="customer_phone" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Travel Date</label>
                        <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Participants</label>
                        <select id="participants" name="participants" required onchange="updateTotalPrice()" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="1">1 Person</option>
                            <option value="2">2 People</option>
                            <option value="3">3 People</option>
                            <option value="4">4 People</option>
                            <option value="5">5 People</option>
                        </select>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm font-medium">Total Price: <span id="totalPrice" class="text-golden-600 font-bold">$0</span></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Special Requests (Optional)</label>
                        <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeBookingModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Book Now</button>
                </div>
            </form>
        </div>
    </div>

<!-- Custom Checkbox Styling -->
<style>
/* Reset checkbox styling to default browser appearance */
.experience-filter,
.region-filter,
.country-filter,
.tour-type-filter {
    appearance: checkbox !important;
    -webkit-appearance: checkbox !important;
    -moz-appearance: checkbox !important;
    width: 16px !important;
    height: 16px !important;
    margin-right: 12px !important;
    cursor: pointer !important;
    background: white !important;
    border: 2px solid #d1d5db !important;
    border-radius: 3px !important;
    position: relative !important;
}

.experience-filter:checked,
.region-filter:checked,
.country-filter:checked,
.tour-type-filter:checked {
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
}

.experience-filter:checked::before,
.region-filter:checked::before,
.country-filter:checked::before,
.tour-type-filter:checked::before {
    content: '✓' !important;
    position: absolute !important;
    top: -2px !important;
    left: 1px !important;
    color: white !important;
    font-size: 12px !important;
    font-weight: bold !important;
}

/* Ensure labels are clickable */
label {
    cursor: pointer !important;
    user-select: none !important;
}
</style>

<!-- JavaScript -->
<script>
    // Initialize filters on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure all checkboxes start unchecked
        const allFilters = document.querySelectorAll('.experience-filter, .region-filter, .country-filter, .tour-type-filter');
        allFilters.forEach(filter => {
            filter.checked = false;
        });
        
        // Show all tours initially
        const packageCards = document.querySelectorAll('.package-card');
        packageCards.forEach(card => {
            card.style.display = 'block';
        });
        
        // Update initial count
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            resultsCount.textContent = packageCards.length;
        }
    });
    
    function clearAllFilters() {
        // Clear all filter checkboxes
        const experienceFilters = document.querySelectorAll('.experience-filter');
        const regionFilters = document.querySelectorAll('.region-filter');
        const countryFilters = document.querySelectorAll('.country-filter');
        const tourTypeFilters = document.querySelectorAll('.tour-type-filter');
        
        experienceFilters.forEach(filter => filter.checked = false);
        regionFilters.forEach(filter => filter.checked = false);
        countryFilters.forEach(filter => filter.checked = false);
        tourTypeFilters.forEach(filter => filter.checked = false);
        
        // Clear URL parameters and reload
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('category');
        currentUrl.searchParams.delete('region');
        currentUrl.searchParams.delete('country');
        window.location.href = currentUrl.toString();
    }
    
    function clearCategoryFilter() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('category');
        window.location.href = currentUrl.toString();
    }
    
    function clearRegionFilter() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('region');
        window.location.href = currentUrl.toString();
    }
    
    function clearCountryFilter() {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.delete('country');
        window.location.href = currentUrl.toString();
    }
    
    function performSearch() {
        const searchTerm = document.getElementById('package-search').value;
        if (searchTerm) {
            window.location.href = '/ForeverYoungTours/pages/packages.php?search=' + encodeURIComponent(searchTerm);
        }
    }
    
    function loadMorePackages() {
        alert('Load more functionality - coming soon!');
    }
    
    function applySorting() {
        const sortBy = document.getElementById('sort-select').value;
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('sort', sortBy);
        window.location.href = currentUrl.toString();
    }
    
    function toggleView(viewType) {
        // Toggle view functionality
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const packagesGrid = document.getElementById('packages-grid');
        
        if (viewType === 'grid') {
            gridView.classList.add('bg-blue-500', 'text-white');
            gridView.classList.remove('bg-gray-200', 'text-gray-700');
            listView.classList.add('bg-gray-200', 'text-gray-700');
            listView.classList.remove('bg-blue-500', 'text-white');
            packagesGrid.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8';
        } else {
            listView.classList.add('bg-blue-500', 'text-white');
            listView.classList.remove('bg-gray-200', 'text-gray-700');
            gridView.classList.add('bg-gray-200', 'text-gray-700');
            gridView.classList.remove('bg-blue-500', 'text-white');
            packagesGrid.className = 'grid grid-cols-1 gap-6';
        }
    }
    
    function applyFilters() {
        applyAllFilters();
    }
    
    function applyAllFilters() {
        // Add visual feedback - briefly highlight the filter area
        const filterSidebar = document.querySelector('.filter-sidebar');
        if (filterSidebar) {
            filterSidebar.style.backgroundColor = '#f8fafc';
            setTimeout(() => {
                filterSidebar.style.backgroundColor = '';
            }, 200);
        }
        
        // Get all checked filters
        const experienceFilters = document.querySelectorAll('.experience-filter:checked');
        const regionFilters = document.querySelectorAll('.region-filter:checked');
        const countryFilters = document.querySelectorAll('.country-filter:checked');
        const tourTypeFilters = document.querySelectorAll('.tour-type-filter:checked');
        
        const selectedExperiences = Array.from(experienceFilters).map(filter => filter.value);
        const selectedRegions = Array.from(regionFilters).map(filter => filter.value);
        const selectedCountries = Array.from(countryFilters).map(filter => filter.value);
        const selectedTourTypes = Array.from(tourTypeFilters).map(filter => filter.value);
        
        // Debug logging (remove in production)
        console.log('Applying filters:', {
            experiences: selectedExperiences,
            regions: selectedRegions, 
            countries: selectedCountries,
            tourTypes: selectedTourTypes
        });
        
        // Get all package cards
        const packageCards = document.querySelectorAll('.package-card');
        let visibleCount = 0;
        
        packageCards.forEach(card => {
            let shouldShow = true;
            
            // Get card data
            const cardExperience = card.dataset.experience || '';
            const cardCategory = card.dataset.category || '';
            const cardDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
            const cardTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const cardRegion = card.querySelector('.bg-slate-100')?.textContent.toLowerCase() || '';
            
            // Check experience filters
            if (selectedExperiences.length > 0) {
                const matchesExperience = selectedExperiences.some(exp => {
                    return cardExperience.includes(exp) || 
                           cardCategory.includes(exp) || 
                           cardDescription.includes(exp) ||
                           cardTitle.includes(exp) ||
                           (exp === 'safari' && (cardDescription.includes('safari') || cardDescription.includes('wildlife') || cardTitle.includes('safari') || cardTitle.includes('wildlife'))) ||
                           (exp === 'cultural' && (cardDescription.includes('cultural') || cardDescription.includes('heritage') || cardTitle.includes('cultural') || cardTitle.includes('heritage'))) ||
                           (exp === 'beach' && (cardDescription.includes('beach') || cardDescription.includes('coast') || cardTitle.includes('beach') || cardTitle.includes('coast'))) ||
                           (exp === 'adventure' && (cardDescription.includes('adventure') || cardDescription.includes('hiking') || cardTitle.includes('adventure') || cardTitle.includes('hiking'))) ||
                           (exp === 'luxury' && (cardDescription.includes('luxury') || cardDescription.includes('premium') || cardTitle.includes('luxury') || cardTitle.includes('premium')));
                });
                
                if (!matchesExperience) {
                    shouldShow = false;
                }
            }
            
            // Check region filters
            if (selectedRegions.length > 0 && shouldShow) {
                const matchesRegion = selectedRegions.some(region => {
                    return cardRegion.includes(region.toLowerCase()) || 
                           cardRegion.includes(region.replace('-', ' ').toLowerCase());
                });
                
                if (!matchesRegion) {
                    shouldShow = false;
                }
            }
            
            // Check country filters
            if (selectedCountries.length > 0 && shouldShow) {
                const matchesCountry = selectedCountries.some(country => {
                    return cardRegion.includes(country.toLowerCase()) || 
                           cardRegion.includes(country.replace('-', ' ').toLowerCase());
                });
                
                if (!matchesCountry) {
                    shouldShow = false;
                }
            }
            
            // Check tour type filters
            if (selectedTourTypes.length > 0 && shouldShow) {
                const matchesTourType = selectedTourTypes.some(type => {
                    return cardCategory.includes(type) || 
                           cardDescription.includes(type) ||
                           cardTitle.includes(type) ||
                           (type === 'motorcoach' && (cardDescription.includes('bus') || cardTitle.includes('bus'))) ||
                           (type === 'rail' && (cardDescription.includes('train') || cardTitle.includes('train'))) ||
                           (type === 'cruise' && (cardDescription.includes('cruise') || cardTitle.includes('cruise'))) ||
                           (type === 'city' && (cardDescription.includes('city') || cardTitle.includes('city'))) ||
                           (type === 'agro' && (cardDescription.includes('farm') || cardTitle.includes('farm') || cardDescription.includes('agriculture'))) ||
                           (type === 'adventure' && (cardDescription.includes('adventure') || cardTitle.includes('adventure'))) ||
                           (type === 'sports' && (cardDescription.includes('sport') || cardTitle.includes('sport'))) ||
                           (type === 'cultural' && (cardDescription.includes('cultural') || cardTitle.includes('cultural'))) ||
                           (type === 'conference' && (cardDescription.includes('conference') || cardTitle.includes('conference') || cardDescription.includes('expo')));
                });
                
                if (!matchesTourType) {
                    shouldShow = false;
                }
            }
            
            // Show/hide the card
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Update results count
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            resultsCount.textContent = visibleCount;
        }
        
        // Show/hide no results message
        const noResultsMessage = document.querySelector('.col-span-full');
        if (visibleCount === 0 && noResultsMessage) {
            noResultsMessage.style.display = 'block';
        } else if (noResultsMessage) {
            noResultsMessage.style.display = 'none';
        }
    }
</script>
<script src="<?= getImageUrl('assets/js/booking.js') ?>"></script>

<?php include 'inquiry-modal.php'; ?>
<?php include '../includes/footer.php'; ?>