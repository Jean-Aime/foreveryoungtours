<?php
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../config/database.php';

$continent_folder = basename(dirname(dirname(__FILE__)));
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_folder]);
$region = $stmt->fetch();

if (!$region) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Book Your Tour - " . $region['name'] . " - iForYoungTours";

// Get tour details if tour_id is provided
$tour = null;
if (isset($_GET['tour_id'])) {
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.currency
        FROM tours t
        JOIN countries c ON t.country_id = c.id
        WHERE t.id = ? AND c.region_id = ? AND t.status = 'active'
    ");
    $stmt->execute([$_GET['tour_id'], $region['id']]);
    $tour = $stmt->fetch();
}

$base_path = '../../';
$css_path = '../assets/css/modern-styles.css';
include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Book Your Adventure
            </h1>
            <p class="text-xl text-slate-300 mb-8">
                Secure your spot on an unforgettable journey
            </p>
        </div>
    </div>
</section>

<!-- Booking Form Section -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <?php if ($tour): ?>
            <!-- Selected Tour Info -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="lg:w-1/3">
                        <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url'], 'assets/images/default-tour.jpg') ?>"
                             alt="<?php echo htmlspecialchars($tour['name']); ?>"
                             class="w-full h-48 object-cover rounded-lg"
                             onerror="this.src='<?= getImageUrl('assets/images/africa.png') ?>';">
                    </div>
                    <div class="lg:w-2/3">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm text-gold font-semibold"><?php echo htmlspecialchars($tour['country_name']); ?></span>
                            <span class="text-sm text-slate-500">•</span>
                            <span class="text-sm text-slate-500"><?php echo $tour['duration_days']; ?> days</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-3"><?php echo htmlspecialchars($tour['name']); ?></h2>
                        <p class="text-slate-600 mb-4"><?php echo htmlspecialchars($tour['description'] ?? ''); ?></p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-3xl font-bold text-gold"><?php echo $tour['currency'] ?? '$'; ?><?php echo number_format($tour['price'], 0); ?></span>
                                <span class="text-slate-500">/person</span>
                            </div>
                            <a href="packages.php" class="text-gold hover:text-golden-700">
                                Change Tour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Booking Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-8">Booking Information</h2>
                
                <form id="booking-form" class="space-y-8">
                    
                    <?php if (!$tour): ?>
                    <!-- Tour Selection -->
                    <div class="border-b border-slate-200 pb-8">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Select Tour</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Destination</label>
                                <select name="destination" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                                    <option value="">Select destination</option>
                                    <!-- Options would be populated from database -->
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tour Package</label>
                                <select name="tour_id" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                                    <option value="">Select tour</option>
                                    <!-- Options would be populated based on destination -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                    <?php endif; ?>
                    
                    <!-- Travel Dates -->
                    <div class="border-b border-slate-200 pb-8">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Travel Dates</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Departure Date</label>
                                <input type="date" name="departure_date" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Travelers</label>
                                <select name="travelers" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                                    <option value="">Select travelers</option>
                                    <option value="1">1 Traveler</option>
                                    <option value="2">2 Travelers</option>
                                    <option value="3">3 Travelers</option>
                                    <option value="4">4 Travelers</option>
                                    <option value="5+">5+ Travelers</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information -->
                    <div class="border-b border-slate-200 pb-8">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phone" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                                <input type="text" name="address" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Special Requirements -->
                    <div class="border-b border-slate-200 pb-8">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Special Requirements</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Dietary Requirements</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="dietary[]" value="vegetarian" class="mr-2">
                                        <span class="text-gray-700">Vegetarian</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="dietary[]" value="vegan" class="mr-2">
                                        <span class="text-gray-700">Vegan</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="dietary[]" value="halal" class="mr-2">
                                        <span class="text-gray-700">Halal</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="dietary[]" value="gluten-free" class="mr-2">
                                        <span class="text-gray-700">Gluten-free</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
                                <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500" placeholder="Any special requests, accessibility needs, or other information..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Summary -->
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Booking Summary</h3>
                        <div class="space-y-2">
                            <?php if ($tour): ?>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Tour Package:</span>
                                <span class="font-semibold"><?php echo htmlspecialchars($tour['name']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Price per person:</span>
                                <span class="font-semibold"><?php echo $tour['currency'] ?? '$'; ?><?php echo number_format($tour['price'], 0); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Number of travelers:</span>
                                <span class="font-semibold" id="travelers-display">-</span>
                            </div>
                            <div class="border-t border-slate-300 pt-2 mt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total Amount:</span>
                                    <span class="text-gold" id="total-amount">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-gold text-white px-12 py-4 rounded-lg font-semibold text-lg hover:bg-gold-dark transition-colors">
                            Proceed to Payment
                        </button>
                        <p class="text-sm text-slate-500 mt-4">
                            By proceeding, you agree to our <a href="#" class="text-gold hover:text-golden-700">Terms & Conditions</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Update booking summary when form changes
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const travelersSelect = form.querySelector('select[name="travelers"]');
    const travelersDisplay = document.getElementById('travelers-display');
    const totalAmountDisplay = document.getElementById('total-amount');
    
    <?php if ($tour): ?>
    const pricePerPerson = <?php echo $tour['price']; ?>;
    const currency = '<?php echo $tour['currency'] ?? '$'; ?>';
    <?php else: ?>
    const pricePerPerson = 0;
    const currency = '$';
    <?php endif; ?>
    
    function updateSummary() {
        const travelers = travelersSelect.value;
        if (travelers && travelers !== '5+') {
            const numTravelers = parseInt(travelers);
            const total = pricePerPerson * numTravelers;
            
            travelersDisplay.textContent = travelers;
            totalAmountDisplay.textContent = currency + total.toLocaleString();
        } else if (travelers === '5+') {
            travelersDisplay.textContent = '5+';
            totalAmountDisplay.textContent = 'Contact for quote';
        } else {
            travelersDisplay.textContent = '-';
            totalAmountDisplay.textContent = '-';
        }
    }
    
    travelersSelect.addEventListener('change', updateSummary);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Booking submitted! You will be redirected to payment processing.');
        // In real implementation, this would submit to payment gateway
    });
});
</script>

<?php include '../../../includes/footer.php'; ?>