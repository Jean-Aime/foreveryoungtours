<?php
session_start();
$base_dir = dirname(dirname(dirname(__DIR__)));
require_once $base_dir . '/config/database.php';

$country_slug = 'visit-cm';
$stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ? AND status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

$page_title = "About " . $country['name'] . " - Forever Young Tours";
$base_path = '../../../';
include $base_dir . '/includes/header.php';
?>

<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About <?php echo htmlspecialchars($country['name']); ?></h1>
        </div>
        
        <div class="prose prose-lg mx-auto">
            <img src="<?php echo htmlspecialchars($country['image_url'] ?? 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=800'); ?>" 
                 alt="<?php echo htmlspecialchars($country['name']); ?>" 
                 class="w-full h-96 object-cover rounded-xl mb-8">
            
            <div class="text-lg text-gray-700 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($country['about_text'] ?? 'Discover the beauty and culture of ' . $country['name'] . '.')); ?>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                <?php if ($country['capital']): ?>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="font-bold text-gray-900 mb-2">Capital</h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($country['capital']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($country['currency']): ?>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="font-bold text-gray-900 mb-2">Currency</h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($country['currency']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($country['best_time_to_visit']): ?>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="font-bold text-gray-900 mb-2">Best Time to Visit</h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($country['best_time_to_visit']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($country['timezone']): ?>
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="font-bold text-gray-900 mb-2">Timezone</h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($country['timezone']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include $base_dir . '/includes/footer.php'; ?>
