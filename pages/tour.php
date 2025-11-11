<?php
require_once '../config/database.php';

$tour_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM tours WHERE id = ? AND status = 'active'");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$tour) {
        header('Location: packages.php');
        exit();
    }
} catch (Exception $e) {
    header('Location: packages.php');
    exit();
}

$page_title = $tour['name'] . " - iForYoungTours";
$page_description = $tour['description'];
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
include '../includes/header.php';
?>

<main class="pt-16">
    <!-- Hero Section -->
    <section class="relative h-96 bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
            <div class="text-white">
                <h1 class="text-5xl font-bold mb-4"><?php echo htmlspecialchars($tour['name']); ?></h1>
                <p class="text-xl mb-6"><?php echo htmlspecialchars($tour['destination']); ?></p>
                <div class="flex items-center space-x-6">
                    <div class="text-3xl font-bold">$<?php echo number_format($tour['price'], 0); ?></div>
                    <div class="text-lg"><?php echo $tour['duration']; ?> days</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour Details -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                        <h2 class="text-3xl font-bold mb-6">Tour Overview</h2>
                        <p class="text-gray-700 text-lg leading-relaxed mb-6">
                            <?php echo nl2br(htmlspecialchars($tour['description'])); ?>
                        </p>
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="font-semibold text-lg mb-4">What's Included</h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Professional guide</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Transportation</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Accommodation</li>
                                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Meals as specified</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="font-semibold text-lg mb-4">Tour Highlights</h3>
                                <ul class="space-y-2 text-gray-700">
                                    <li class="flex items-center"><i class="fas fa-star text-yellow-500 mr-2"></i> Authentic experiences</li>
                                    <li class="flex items-center"><i class="fas fa-star text-yellow-500 mr-2"></i> Local culture immersion</li>
                                    <li class="flex items-center"><i class="fas fa-star text-yellow-500 mr-2"></i> Scenic landscapes</li>
                                    <li class="flex items-center"><i class="fas fa-star text-yellow-500 mr-2"></i> Photo opportunities</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-blue-600 mb-2">$<?php echo number_format($tour['price'], 0); ?></div>
                            <div class="text-gray-600">per person</div>
                        </div>
                        
                        <form id="bookingForm" class="space-y-4">
                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Travel Date</label>
                                <input type="date" name="travel_date" required class="w-full p-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Participants</label>
                                <select name="participants" class="w-full p-3 border border-gray-300 rounded-lg">
                                    <option value="1">1 Person</option>
                                    <option value="2">2 People</option>
                                    <option value="3">3 People</option>
                                    <option value="4">4 People</option>
                                    <option value="5">5+ People</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="customer_name" required class="w-full p-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="customer_email" required class="w-full p-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" name="customer_phone" class="w-full p-3 border border-gray-300 rounded-lg">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Book Now
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center text-sm text-gray-600">
                            <p><i class="fas fa-shield-alt mr-1"></i> Secure booking</p>
                            <p><i class="fas fa-calendar-alt mr-1"></i> Free cancellation up to 24h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../api/book_tour.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Booking successful! We will contact you soon.');
            this.reset();
        } else {
            alert('Booking failed: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
});
</script>

<?php include '../includes/footer.php'; ?>