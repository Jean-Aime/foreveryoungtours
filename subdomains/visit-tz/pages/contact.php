<?php
session_start();
$base_dir = dirname(dirname(dirname(__DIR__)));
require_once $base_dir . '/config/database.php';

$country_slug = 'visit-tz';
$stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ? AND status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

$page_title = "Contact Us - " . $country['name'] . " Tours";
$base_path = '../../../';
include $base_dir . '/includes/header.php';
?>

<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-xl text-gray-600">Get in touch for <?php echo htmlspecialchars($country['name']); ?> tours</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interested in</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option>Tours in <?php echo htmlspecialchars($country['name']); ?></option>
                        <option>Custom Itinerary</option>
                        <option>Group Booking</option>
                        <option>General Inquiry</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-4 rounded-lg font-semibold hover:shadow-xl transition-all">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>

<?php include $base_dir . '/includes/footer.php'; ?>
