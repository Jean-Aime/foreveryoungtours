<?php
$page_title = "Travel Packages - iForYoungTours | Premium African Adventures";
$page_description = "Discover premium African travel packages with expert guides, luxury accommodations, and authentic experiences. Book your dream African adventure today.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get filter parameters
$country_filter = $_GET['country'] ?? '';
$region_filter = $_GET['region'] ?? '';
$category_filter = $_GET['category'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$duration_filter = $_GET['duration'] ?? '';
$search = $_GET['search'] ?? '';

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
if ($price_min) {
    $where_conditions[] = "t.price >= ?";
    $params[] = $price_min;
}
if ($price_max) {
    $where_conditions[] = "t.price <= ?";
    $params[] = $price_max;
}
if ($duration_filter) {
    $where_conditions[] = "t.duration_days = ?";
    $params[] = $duration_filter;
}
if ($search) {
    $where_conditions[] = "(t.name LIKE ? OR t.description LIKE ? OR c.name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = implode(' AND ', $where_conditions);

// Get sort parameter
$sort = $_GET['sort'] ?? 'popularity';
$order_clause = match($sort) {
    'price-low' => 'ORDER BY t.price ASC',
    'price-high' => 'ORDER BY t.price DESC',
    'duration' => 'ORDER BY t.duration_days ASC',
    'rating' => 'ORDER BY t.rating DESC',
    default => 'ORDER BY t.featured DESC, t.created_at DESC'
};

// Fetch tours with enhanced data
$stmt = $conn->prepare("
    SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name, r.slug as region_slug,
           AVG(tr.rating) as avg_rating, COUNT(tr.id) as review_count
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    LEFT JOIN tour_reviews tr ON t.id = tr.tour_id AND tr.status = 'approved'
    WHERE $where_clause 
    GROUP BY t.id
    $order_clause
    LIMIT 50
");
$stmt->execute($params);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get regions and countries for filters
$stmt = $conn->prepare("SELECT * FROM regions WHERE status = 'active' ORDER BY name");
$stmt->execute();
$regions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-r from-golden-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Premium African <span class="text-gradient">Travel Packages</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover handcrafted travel experiences across Africa. From luxury safaris to cultural immersions, 
                each package is designed to create unforgettable memories with expert local guides.
            </p>
        </div>
        
        <!-- Enhanced Search Bar -->
        <div class="max-w-6xl mx-auto">
            <div class="search-input rounded-2xl p-6 shadow-lg bg-white">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search destinations..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                            <select name="region" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                                <option value="">All Regions</option>
                                <?php foreach ($regions as $region): ?>
                                <option value="<?php echo $region['slug']; ?>" <?php echo $region_filter === $region['slug'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($region['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                            <select name="duration" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                                <option value="">Any Duration</option>
                                <option value="3" <?php echo $duration_filter === '3' ? 'selected' : ''; ?>>3 Days</option>
                                <option value="7" <?php echo $duration_filter === '7' ? 'selected' : ''; ?>>1 Week</option>
                                <option value="14" <?php echo $duration_filter === '14' ? 'selected' : ''; ?>>2 Weeks</option>
                                <option value="21" <?php echo $duration_filter === '21' ? 'selected' : ''; ?>>3 Weeks</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                                Search Packages
                            </button>
                        </div>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Price ($)</label>
                            <input type="number" name="price_min" value="<?php echo htmlspecialchars($price_min); ?>" 
                                   placeholder="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Price ($)</label>
                            <input type="number" name="price_max" value="<?php echo htmlspecialchars($price_max); ?>" 
                                   placeholder="10000" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">All Categories</option>
                                <option value="wildlife" <?php echo $category_filter === 'wildlife' ? 'selected' : ''; ?>>Wildlife Safari</option>
                                <option value="cultural" <?php echo $category_filter === 'cultural' ? 'selected' : ''; ?>>Cultural Tours</option>
                                <option value="adventure" <?php echo $category_filter === 'adventure' ? 'selected' : ''; ?>>Adventure</option>
                                <option value="luxury" <?php echo $category_filter === 'luxury' ? 'selected' : ''; ?>>Luxury</option>
                                <option value="beach" <?php echo $category_filter === 'beach' ? 'selected' : ''; ?>>Beach & Islands</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    <?php echo count($tours); ?> Packages Found
                </h2>
                <?php if ($search || $region_filter || $country_filter || $category_filter): ?>
                <div class="flex flex-wrap gap-2 mt-2">
                    <?php if ($search): ?>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Search: "<?php echo htmlspecialchars($search); ?>"</span>
                    <?php endif; ?>
                    <?php if ($region_filter): ?>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Region: <?php echo htmlspecialchars($region_filter); ?></span>
                    <?php endif; ?>
                    <?php if ($category_filter): ?>
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">Category: <?php echo ucfirst($category_filter); ?></span>
                    <?php endif; ?>
                    <a href="packages-enhanced.php" class="text-red-600 hover:text-red-800 text-sm">Clear all filters</a>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Sort by:</span>
                <form method="GET" class="inline">
                    <?php foreach ($_GET as $key => $value): ?>
                        <?php if ($key !== 'sort'): ?>
                        <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="popularity" <?php echo $sort === 'popularity' ? 'selected' : ''; ?>>Popularity</option>
                        <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="duration" <?php echo $sort === 'duration' ? 'selected' : ''; ?>>Duration</option>
                        <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Rating</option>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Packages Grid -->
        <?php if (empty($tours)): ?>
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-600 mb-4">No packages found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all destinations</p>
            <a href="packages-enhanced.php" class="btn-primary px-8 py-3 rounded-lg">View All Packages</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="package-card rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($tour['cover_image'] ?: $tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                         class="w-full h-64 object-cover">
                    
                    <!-- Price Badge -->
                    <div class="absolute top-4 right-4 bg-golden-500 text-black px-4 py-2 rounded-full font-bold">
                        From $<?php echo number_format($tour['price']); ?>
                    </div>
                    
                    <!-- Featured Badge -->
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-4 left-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Featured
                    </div>
                    <?php endif; ?>
                    
                    <!-- Rating Overlay -->
                    <?php if ($tour['avg_rating']): ?>
                    <div class="absolute bottom-4 left-4 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                        ★ <?php echo number_format($tour['avg_rating'], 1); ?> (<?php echo $tour['review_count']; ?>)
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <!-- Location Tags -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-medium">
                            <?php echo htmlspecialchars($tour['region_name']); ?>
                        </span>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                            <?php echo htmlspecialchars($tour['country_name']); ?>
                        </span>
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-medium">
                            <?php echo ucfirst($tour['category']); ?>
                        </span>
                    </div>
                    
                    <!-- Title and Description -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars(substr($tour['description'], 0, 120)) . '...'; ?></p>
                    
                    <!-- Tour Details -->
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo $tour['duration_days']; ?> days
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Max <?php echo $tour['max_participants'] ?: '12'; ?>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <?php echo ucfirst($tour['difficulty_level'] ?: 'moderate'); ?>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <?php echo $tour['includes_accommodation'] ? 'Accommodation' : 'Day Trip'; ?>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <a href="tour-detail-enhanced.php?id=<?php echo $tour['id']; ?>" 
                           class="flex-1 bg-slate-200 text-slate-700 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-colors text-center">
                            View Details
                        </a>
                        <button onclick="openBookingModal(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>', <?php echo $tour['price']; ?>)" 
                                class="flex-1 btn-primary py-3 rounded-lg font-semibold text-white">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button onclick="loadMorePackages()" class="btn-secondary px-8 py-4 rounded-lg font-semibold">
                Load More Packages
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gradient">Book Your Adventure</h3>
                <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-slate-600 mt-2">Package: <span id="tourName" class="font-medium"></span></p>
            <p class="text-sm text-slate-600">Price: <span id="tourPrice" class="font-medium text-golden-600"></span> per person</p>
        </div>
        <form id="bookingForm" class="p-6">
            <input type="hidden" id="tourId" name="tour_id">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                        <input type="text" name="first_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Travel Date</label>
                        <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Participants</label>
                        <select id="participants" name="participants" required onchange="updateTotalPrice()" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="1">1 Person</option>
                            <option value="2">2 People</option>
                            <option value="3">3 People</option>
                            <option value="4">4 People</option>
                            <option value="5">5+ People</option>
                        </select>
                    </div>
                </div>
                <div class="bg-golden-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Total Price:</span>
                        <span id="totalPrice" class="text-2xl font-bold text-golden-600">$0</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Special Requests (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Dietary requirements, accessibility needs, etc."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="closeBookingModal()" class="btn-secondary px-6 py-3 rounded-lg">Cancel</button>
                <button type="submit" class="btn-primary px-6 py-3 rounded-lg">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/booking.js"></script>
<script>
function loadMorePackages() {
    // Implementation for loading more packages
    alert('Load more functionality - coming soon!');
}
</script>

<?php include '../includes/footer.php'; ?>