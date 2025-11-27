<?php
require_once 'config.php';
require_once '../../../config/database.php';

// Get country data from current directory structure
$country_folder = basename(dirname(dirname(__FILE__)));
// Map folder names to correct slugs
$slug_map = [
    'visit-rw' => 'visit-rw',
    'visit-bw' => 'visit-bw',
    'visit-ke' => 'visit-ke',
    'visit-tz' => 'visit-tz',
    'visit-ug' => 'visit-ug',
    'visit-za' => 'visit-za',
    'visit-na' => 'visit-na',
    'visit-zw' => 'visit-zw',
    'visit-eg' => 'visit-eg',
    'visit-ma' => 'visit-ma',
    'visit-tn' => 'visit-tn',
    'visit-et' => 'visit-et',
    'visit-gh' => 'visit-gh',
    'visit-ng' => 'visit-ng',
    'visit-sn' => 'visit-sn',
    'visit-cm' => 'visit-cm',
    'visit-cd' => 'visit-cd'
];
$country_slug = $slug_map[$country_folder] ?? $country_folder;
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Tours in " . $country['name'] . " - iForYoungTours";
$page_description = "Explore expertly curated " . $country['name'] . " travel packages. From safaris to cultural experiences, find your perfect adventure.";

// Get filter parameters
$category_filter = $_GET['category'] ?? '';

// Build WHERE clause
$where_conditions = ["t.status = 'active'", "t.country_id = ?"];
$params = [$country['id']];

if ($category_filter) {
    $where_conditions[] = "t.category = ?";
    $params[] = $category_filter;
}

$where_clause = implode(' AND ', $where_conditions);

// Get all tours for this country
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.slug as country_slug, c.currency
    FROM tours t
    JOIN countries c ON t.country_id = c.id
    WHERE $where_clause
    ORDER BY t.featured DESC, t.popularity_score DESC
");
$stmt->execute($params);
$tours = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/modern-styles.css">
    <style>
        body { background-color: #f8fafc; color: #1e293b; }
        .text-golden-600 { color: #d97706; }
        .bg-golden-500 { background-color: #f59e0b; }
        .bg-golden-600 { background-color: #d97706; }
        .border-golden-500 { border-color: #f59e0b; }
        .text-slate-900 { color: #0f172a !important; }
        .text-slate-600 { color: #475569 !important; }
        .text-slate-700 { color: #334155 !important; }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-r from-blue-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                <?php echo htmlspecialchars($country['name']); ?> <span class="text-gradient">Travel Packages</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover expertly curated travel experiences in <?php echo htmlspecialchars($country['name']); ?>. From luxury safaris to cultural immersions, find your perfect adventure.
            </p>
        </div>
        
        <!-- Search Bar -->
        <div class="max-w-4xl mx-auto">
            <div class="search-input rounded-2xl p-6 shadow-lg bg-white">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="package-search" placeholder="Search destinations, activities, or keywords..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button onclick="performSearch()" class="bg-golden-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-golden-600 transition-colors">
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
                <div class="bg-white rounded-2xl p-6 sticky top-24 shadow-sm border">
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
                    
                    <!-- Tour Types -->
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 mb-4">Tour Types</h4>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="safari" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Safari Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="cultural" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Cultural Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="adventure" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Adventure Tours</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="tour-type-filter mr-3" value="luxury" 
                                       onchange="applyAllFilters()">
                                <span class="text-gray-700">Luxury Tours</span>
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
                
                <!-- Results Count -->
                <div class="mb-6">
                    <p class="text-gray-600">
                        Showing <span id="results-count"><?php echo count($tours); ?></span> packages
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
                        <button onclick="clearAllFilters()" class="bg-golden-500 text-white px-6 py-3 rounded-lg">
                            View All Tours
                        </button>
                    </div>
                    <?php else: ?>
                    <?php foreach ($tours as $tour): ?>
                    <div class="package-card bg-white rounded-2xl overflow-hidden shadow-sm border hover:shadow-lg transition-shadow" 
                         data-price="<?php echo $tour['price']; ?>" 
                         data-duration="<?php echo $tour['duration_days']; ?>" 
                         data-destination="<?php echo strtolower($tour['destination'] ?? ''); ?>"
                         data-category="<?php echo strtolower($tour['category'] ?? ''); ?>"
                         data-experience="<?php echo strtolower($tour['experience_type'] ?? ''); ?>"
                         data-country="<?php echo $tour['country_slug']; ?>">
                        <div class="relative">
                            <?php 
                            $image_path = $tour['cover_image'] ?: $tour['image_url'];
                            if ($image_path && !empty(trim($image_path))) {
                                $image_src = 'http://localhost/foreveryoungtours/' . ltrim($image_path, '/');
                            } else {
                                $image_src = 'http://localhost/foreveryoungtours/assets/images/default-tour.jpg';
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-40 object-cover" onerror="this.src='http://localhost/foreveryoungtours/assets/images/default-tour.jpg'; this.onerror=null;" loading="lazy">
                            <?php if ($tour['featured']): ?>
                            <div class="absolute top-4 left-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Featured
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-medium">
                                    <?php echo htmlspecialchars($country['name']); ?>
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
            </div>
        </div>
    </div>
</section>

<!-- Custom Tour CTA Section -->
<section class="py-20 bg-gradient-to-br from-cream via-light-gray to-off-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-golden-500 via-primary-green to-golden-600 rounded-3xl p-12 md:p-16 shadow-2xl relative overflow-hidden">
            <div class="relative z-10 text-center max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Can't find what you're looking for?
                </h2>
                
                <p class="text-lg md:text-xl text-slate-800 mb-8 leading-relaxed">
                    Let us create a personalized itinerary tailored to your dreams. Our travel experts will craft the perfect <?php echo htmlspecialchars($country['name']); ?> adventure just for you.
                </p>
                
                <button onclick="openInquiryModal()" class="inline-flex items-center bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-800 transition-all transform hover:scale-105 hover:shadow-2xl">
                    Request Custom Tour
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function clearAllFilters() {
        const experienceFilters = document.querySelectorAll('.experience-filter');
        const tourTypeFilters = document.querySelectorAll('.tour-type-filter');
        
        experienceFilters.forEach(filter => filter.checked = false);
        tourTypeFilters.forEach(filter => filter.checked = false);
        
        applyAllFilters();
    }
    
    function performSearch() {
        const searchTerm = document.getElementById('package-search').value;
        if (searchTerm) {
            alert('Search functionality - coming soon!');
        }
    }
    
    function applySorting() {
        alert('Sort functionality - coming soon!');
    }
    
    function applyFilters() {
        applyAllFilters();
    }
    
    function toggleView(viewType) {
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
    
    function applyAllFilters() {
        const experienceFilters = document.querySelectorAll('.experience-filter:checked');
        const tourTypeFilters = document.querySelectorAll('.tour-type-filter:checked');
        
        const selectedExperiences = Array.from(experienceFilters).map(filter => filter.value);
        const selectedTourTypes = Array.from(tourTypeFilters).map(filter => filter.value);
        
        const packageCards = document.querySelectorAll('.package-card');
        let visibleCount = 0;
        
        packageCards.forEach(card => {
            let shouldShow = true;
            
            const cardCategory = card.dataset.category || '';
            const cardDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
            const cardTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
            
            if (selectedExperiences.length > 0) {
                const matchesExperience = selectedExperiences.some(exp => {
                    return cardCategory.includes(exp) || 
                           cardDescription.includes(exp) ||
                           cardTitle.includes(exp);
                });
                
                if (!matchesExperience) {
                    shouldShow = false;
                }
            }
            
            if (selectedTourTypes.length > 0 && shouldShow) {
                const matchesTourType = selectedTourTypes.some(type => {
                    return cardCategory.includes(type) || 
                           cardDescription.includes(type) ||
                           cardTitle.includes(type);
                });
                
                if (!matchesTourType) {
                    shouldShow = false;
                }
            }
            
            if (shouldShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            resultsCount.textContent = visibleCount;
        }
    }
    
    function openInquiryModal() {
        alert('Inquiry modal - coming soon!');
    }
</script>

<!-- Footer removed -->

</body>
</html>