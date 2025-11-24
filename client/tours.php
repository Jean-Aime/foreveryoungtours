<?php

require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

$client_id = $_SESSION['user_id'] ?? 1;

// Get all available tours with enhanced information
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, r.name as region_name,
           (SELECT COUNT(*) FROM bookings b WHERE b.tour_id = t.id AND b.user_id = ?) as my_bookings,
           (SELECT AVG(rating) FROM tour_reviews tr WHERE tr.tour_id = t.id AND tr.status = 'approved') as avg_rating,
           (SELECT COUNT(*) FROM tour_reviews tr WHERE tr.tour_id = t.id AND tr.status = 'approved') as review_count
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    WHERE t.status = 'active'
    ORDER BY t.featured DESC, t.popularity_score DESC, t.created_at DESC
");
$stmt->execute([$client_id]);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get client's wishlist (if implemented)
$wishlist_tours = [];
try {
    $stmt = $pdo->prepare("SELECT tour_id FROM wishlist WHERE user_id = ?");
    $stmt->execute([$client_id]);
    $wishlist_tours = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // Wishlist table doesn't exist yet
}

// Filter options
$category_filter = $_GET['category'] ?? '';
$country_filter = $_GET['country'] ?? '';
$price_filter = $_GET['price'] ?? '';
$duration_filter = $_GET['duration'] ?? '';
$difficulty_filter = $_GET['difficulty'] ?? '';

// Apply filters
$filtered_tours = $tours;
if ($category_filter) {
    $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['category'] == $category_filter);
}
if ($country_filter) {
    $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['country_id'] == $country_filter);
}
if ($price_filter) {
    switch ($price_filter) {
        case 'under_1000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] < 1000);
            break;
        case '1000_2000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] >= 1000 && $tour['price'] <= 2000);
            break;
        case 'over_2000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] > 2000);
            break;
    }
}
if ($duration_filter) {
    switch ($duration_filter) {
        case 'short':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['duration_days'] <= 3);
            break;
        case 'medium':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['duration_days'] >= 4 && $tour['duration_days'] <= 7);
            break;
        case 'long':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['duration_days'] > 7);
            break;
    }
}
if ($difficulty_filter) {
    $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['difficulty_level'] == $difficulty_filter);
}

// Get unique values for filters
$countries = [];
$categories = [];
$difficulties = [];
foreach ($tours as $tour) {
    if (!in_array($tour['country_name'], array_column($countries, 'name'))) {
        $countries[] = ['id' => $tour['country_id'], 'name' => $tour['country_name']];
    }
    if (!in_array($tour['category'], $categories)) {
        $categories[] = $tour['category'];
    }
    if ($tour['difficulty_level'] && !in_array($tour['difficulty_level'], $difficulties)) {
        $difficulties[] = $tour['difficulty_level'];
    }
}

$page_title = 'Explore Tours';
$page_subtitle = 'Discover incredible adventures across Africa';

include 'includes/client-header.php';
?>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold" style="color: #333;">Find Your Perfect Adventure</h3>
        <span class="text-sm" style="color: #666;"><?php echo count($filtered_tours); ?> tours found</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2" style="color: #333;">Category</label>
            <select onchange="applyFilters()" id="categoryFilter" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>" <?php echo $category_filter == $category ? 'selected' : ''; ?>>
                    <?php echo ucfirst($category); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2" style="color: #333;">Country</label>
            <select onchange="applyFilters()" id="countryFilter" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                <option value="">All Countries</option>
                <?php foreach ($countries as $country): ?>
                <option value="<?php echo $country['id']; ?>" <?php echo $country_filter == $country['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($country['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2" style="color: #333;">Price Range</label>
            <select onchange="applyFilters()" id="priceFilter" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                <option value="">All Prices</option>
                <option value="under_1000" <?php echo $price_filter == 'under_1000' ? 'selected' : ''; ?>>Under $1,000</option>
                <option value="1000_2000" <?php echo $price_filter == '1000_2000' ? 'selected' : ''; ?>>$1,000 - $2,000</option>
                <option value="over_2000" <?php echo $price_filter == 'over_2000' ? 'selected' : ''; ?>>Over $2,000</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2" style="color: #333;">Duration</label>
            <select onchange="applyFilters()" id="durationFilter" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                <option value="">Any Duration</option>
                <option value="short" <?php echo $duration_filter == 'short' ? 'selected' : ''; ?>>1-3 days</option>
                <option value="medium" <?php echo $duration_filter == 'medium' ? 'selected' : ''; ?>>4-7 days</option>
                <option value="long" <?php echo $duration_filter == 'long' ? 'selected' : ''; ?>>8+ days</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2" style="color: #333;">Difficulty</label>
            <select onchange="applyFilters()" id="difficultyFilter" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                <option value="">Any Level</option>
                <?php foreach ($difficulties as $difficulty): ?>
                <option value="<?php echo $difficulty; ?>" <?php echo $difficulty_filter == $difficulty ? 'selected' : ''; ?>>
                    <?php echo ucfirst($difficulty); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="flex justify-between items-center mt-4">
        <button onclick="clearFilters()" class="btn-secondary px-4 py-2 rounded-lg">
            <i class="fas fa-times mr-2"></i>Clear Filters
        </button>
        <div class="flex gap-2">
            <button onclick="toggleView('grid')" id="gridViewBtn" class="p-2 rounded" style="background: #DAA520; color: #fff;">
                <i class="fas fa-th-large"></i>
            </button>
            <button onclick="toggleView('list')" id="listViewBtn" class="p-2 rounded" style="background: #e5e7eb; color: #666;">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
</div>

<!-- Tours Grid -->
<div id="gridView" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($filtered_tours as $tour): ?>
    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
        <div class="h-48 bg-cover bg-center relative" style="background-image: url('<?php echo htmlspecialchars($tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>');">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <?php if ($tour['featured']): ?>
            <span class="absolute top-4 left-4 px-2 py-1 rounded text-xs font-bold" style="background: #DAA520; color: #000;">Featured</span>
            <?php endif; ?>
            <div class="absolute bottom-4 left-4 image-overlay-text">
                <h3 class="font-bold text-lg"><?php echo htmlspecialchars($tour['name']); ?></h3>
                <p class="text-sm opacity-90"><?php echo htmlspecialchars($tour['country_name']); ?></p>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-2xl font-bold" style="color: #DAA520;">$<?php echo number_format($tour['price']); ?></span>
                <div class="text-right">
                    <span class="text-sm" style="color: #666;"><?php echo $tour['duration_days']; ?> days</span>
                    <?php if ($tour['avg_rating'] > 0): ?>
                    <div class="flex items-center text-sm">
                        <div class="flex mr-1" style="color: #DAA520;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star text-xs <?php echo $i <= $tour['avg_rating'] ? '' : 'text-gray-300'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span style="color: #666;">(<?php echo $tour['review_count']; ?>)</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <p class="text-sm mb-4" style="color: #666;"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
            
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                <div>
                    <span style="color: #999;">Category:</span>
                    <span class="font-semibold" style="color: #333;"><?php echo ucfirst($tour['category']); ?></span>
                </div>
                <div>
                    <span style="color: #999;">Group Size:</span>
                    <span class="font-semibold" style="color: #333;"><?php echo $tour['min_participants']; ?>-<?php echo $tour['max_participants']; ?></span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button onclick="openMultiStepBooking(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>', <?php echo $tour['price']; ?>)" class="flex-1 btn-primary text-center py-2 rounded text-sm">
                    <i class="fas fa-calendar-plus mr-1"></i>Book Now
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- List View -->
<div id="listView" class="hidden space-y-4">
    <?php foreach ($filtered_tours as $tour): ?>
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-lg transition-shadow">
        <div class="flex gap-6">
            <div class="w-48 h-32 bg-cover bg-center rounded-lg flex-shrink-0" style="background-image: url('<?php echo htmlspecialchars($tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>');"></div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-xl font-bold" style="color: #333;"><?php echo htmlspecialchars($tour['name']); ?></h3>
                        <p style="color: #666;"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold" style="color: #DAA520;">$<?php echo number_format($tour['price']); ?></span>
                        <p class="text-sm" style="color: #666;"><?php echo $tour['duration_days']; ?> days</p>
                    </div>
                </div>
                
                <p class="mb-3" style="color: #666;"><?php echo htmlspecialchars(substr($tour['description'], 0, 200)); ?>...</p>
                
                <div class="flex justify-between items-center">
                    <div class="flex gap-4 text-sm">
                        <span class="px-2 py-1 rounded" style="background: #FDF6E3; color: #DAA520;"><?php echo ucfirst($tour['category']); ?></span>
                    </div>
                    <button onclick="openMultiStepBooking(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>', <?php echo $tour['price']; ?>)" class="btn-primary px-4 py-2 rounded text-sm">
                        Book Now
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($filtered_tours)): ?>
<div class="text-center py-12">
    <i class="fas fa-search text-6xl mb-4" style="color: #ccc;"></i>
    <h3 class="text-xl font-semibold mb-2" style="color: #666;">No tours found</h3>
    <p class="mb-4" style="color: #999;">Try adjusting your filters to see more results</p>
    <button onclick="clearFilters()" class="btn-primary px-6 py-3 rounded-lg">Clear All Filters</button>
</div>
<?php endif; ?>

<!-- Multi-Step Booking Modal -->
<div id="multiStepBookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">Book Your African Adventure</h3>
                    <p class="text-sm text-slate-600 mt-1">Tour: <span id="modalTourName" class="font-semibold"></span> - <span id="modalTourPrice" class="text-golden-600 font-bold"></span> per person</p>
                </div>
                <button onclick="closeMultiStepBooking()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center mt-6 space-x-4">
                <div class="flex items-center">
                    <div id="stepIndicator1" class="w-8 h-8 rounded-full bg-golden-500 text-black flex items-center justify-center text-sm font-bold">1</div>
                    <span class="ml-2 text-sm font-medium text-slate-700">Personal Info</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div id="stepIndicator2" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">2</div>
                    <span class="ml-2 text-sm font-medium text-slate-700">Travel Details</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div id="stepIndicator3" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">3</div>
                    <span class="ml-2 text-sm font-medium text-slate-700">Review & Payment</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div id="stepIndicator4" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">4</div>
                    <span class="ml-2 text-sm font-medium text-slate-700">Confirmation</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div id="step1" class="space-y-6">
                <h4 class="text-lg font-bold text-slate-900 mb-4">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name *</label>
                        <input type="text" id="customerName" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent" value="<?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?> <?php echo htmlspecialchars($_SESSION['last_name'] ?? ''); ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email Address *</label>
                        <input type="email" id="customerEmail" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number *</label>
                        <input type="tel" id="customerPhone" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Emergency Contact</label>
                        <input type="tel" id="emergencyContact" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                    </div>
                </div>
            </div>
            <div id="step2" class="space-y-6 hidden">
                <h4 class="text-lg font-bold text-slate-900 mb-4">Travel Details & Preferences</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Travel Date *</label>
                        <input type="date" id="travelDate" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Number of Participants *</label>
                        <select id="participants" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                            <option value="1">1 Person</option>
                            <option value="2">2 People</option>
                            <option value="3">3 People</option>
                            <option value="4">4 People</option>
                            <option value="5">5 People</option>
                            <option value="6">6+ People</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Accommodation Preference</label>
                        <select id="accommodation" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                            <option value="standard">Standard (+$0)</option>
                            <option value="premium">Premium (+$100/night)</option>
                            <option value="luxury">Luxury (+$200/night)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transport Preference</label>
                        <select id="transport" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                            <option value="shared">Shared Transport (+$0)</option>
                            <option value="premium">Premium Vehicle (+$75)</option>
                            <option value="private">Private Vehicle (+$150)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Special Requests or Dietary Requirements</label>
                    <textarea id="specialRequests" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent" placeholder="Any special requirements, dietary restrictions, or requests..."></textarea>
                </div>
            </div>
            <div id="step3" class="space-y-6 hidden">
                <h4 class="text-lg font-bold text-slate-900 mb-4">Review Your Booking</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-slate-50 p-6 rounded-lg">
                        <h5 class="font-bold text-slate-900 mb-4">Booking Summary</h5>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span>Participants:</span><span id="summaryParticipants" class="font-semibold">1</span></div>
                            <div class="flex justify-between"><span>Base Price (per person):</span><span id="summaryBasePrice" class="font-semibold">$0</span></div>
                            <div class="flex justify-between"><span>Accommodation Upgrade:</span><span id="summaryAccommodation" class="font-semibold">$0</span></div>
                            <div class="flex justify-between"><span>Transport Upgrade:</span><span id="summaryTransport" class="font-semibold">$0</span></div>
                            <hr class="my-3">
                            <div class="flex justify-between"><span>Subtotal:</span><span id="summarySubtotal" class="font-semibold">$0</span></div>
                            <div class="flex justify-between"><span>Tax (10%):</span><span id="summaryTax" class="font-semibold">$0</span></div>
                            <hr class="my-3">
                            <div class="flex justify-between text-lg font-bold text-golden-600"><span>Total:</span><span id="summaryTotal">$0</span></div>
                        </div>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-900 mb-4">Payment Method</h5>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="payment_method" value="card" checked class="mr-3">
                                <span class="font-medium">Credit/Debit Card</span>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="payment_method" value="paypal" class="mr-3">
                                <span class="font-medium">PayPal</span>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                                <span class="font-medium">Bank Transfer</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="step4" class="space-y-6 hidden">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-2">Confirm Your Booking</h4>
                    <p class="text-slate-600 mb-6">Please review all details before confirming your African adventure booking.</p>
                </div>
                <div class="bg-golden-50 border border-golden-200 rounded-lg p-6">
                    <h5 class="font-bold text-slate-900 mb-3">Important Information</h5>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li>• A confirmation email will be sent to your provided email address</li>
                        <li>• Our team will contact you within 24 hours to finalize details</li>
                        <li>• Payment will be processed securely through our payment partner</li>
                        <li>• Cancellation policy applies as per our terms and conditions</li>
                    </ul>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="termsAccept" required class="mr-3">
                    <label for="termsAccept" class="text-sm text-slate-600">I agree to the Terms and Conditions and Privacy Policy</label>
                </div>
            </div>
        </div>
        <div class="p-6 border-t bg-gray-50 flex justify-between">
            <button id="prevBtn" onclick="prevStep()" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" style="display: none;">Previous</button>
            <div class="flex space-x-4">
                <button onclick="closeMultiStepBooking()" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">Cancel</button>
                <button id="nextBtn" onclick="nextStep()" class="px-8 py-3 bg-golden-500 hover:bg-golden-600 text-black rounded-lg font-semibold transition-colors">Next Step</button>
                <button id="submitBtn" onclick="submitBooking()" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors" style="display: none;">Confirm Booking</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= getImageUrl('assets/js/multi-step-booking.js') ?>"></script>
<script>
function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const country = document.getElementById('countryFilter').value;
    const price = document.getElementById('priceFilter').value;
    const duration = document.getElementById('durationFilter').value;
    const difficulty = document.getElementById('difficultyFilter').value;
    
    const params = new URLSearchParams();
    if (category) params.append('category', category);
    if (country) params.append('country', country);
    if (price) params.append('price', price);
    if (duration) params.append('duration', duration);
    if (difficulty) params.append('difficulty', difficulty);
    
    window.location.href = 'tours.php?' + params.toString();
}

function clearFilters() {
    window.location.href = 'tours.php';
}

function toggleView(view) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.style.background = '#DAA520';
        gridBtn.style.color = '#fff';
        listBtn.style.background = '#e5e7eb';
        listBtn.style.color = '#666';
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.style.background = '#DAA520';
        listBtn.style.color = '#fff';
        gridBtn.style.background = '#e5e7eb';
        gridBtn.style.color = '#666';
    }
}
</script>

<?php include 'includes/client-footer.php'; ?>
