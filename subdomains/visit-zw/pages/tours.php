<?php
session_start();
$base_dir = dirname(dirname(dirname(__DIR__)));
require_once $base_dir . '/config/database.php';

$country_slug = 'visit-zw';
$stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ? AND status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../../../pages/tours.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

$page_title = "Tours in " . $country['name'] . " - Forever Young Tours";
$base_path = '../../../';
include $base_dir . '/includes/header.php';
?>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tours in <?php echo htmlspecialchars($country['name']); ?></h1>
            <p class="text-xl text-gray-600">Discover amazing experiences</p>
        </div>
        
        <?php if (!empty($tours)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tours as $tour): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all">
                <img src="<?php echo htmlspecialchars($tour['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=800'); ?>" 
                     alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                     class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price']); ?></span>
                        <button class="bg-yellow-500 text-white px-6 py-2 rounded-full hover:bg-yellow-600 transition-colors">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tours Coming Soon</h3>
            <p class="text-gray-600">We're adding tours for <?php echo htmlspecialchars($country['name']); ?>. Check back soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include $base_dir . '/includes/footer.php'; ?>
