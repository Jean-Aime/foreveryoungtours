<?php

require_once 'config.php';
$page_title = "Tours in Rwanda - iForYoungTours";
require_once __DIR__ . '/../../../config/database.php';

// Get country data
$country_slug = basename(dirname(dirname(__FILE__)));
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ../index.php');
    exit;
}

// Get all tours for this country
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, popularity_score DESC");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

$base_path = '../../../';
$css_path = '../assets/css/modern-styles.css';
include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-r from-blue-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Tours in <span class="text-gradient"><?php echo htmlspecialchars($country['name']); ?></span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Discover amazing experiences and adventures in <?php echo htmlspecialchars($country['name']); ?>
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <p class="text-slate-500 mb-4">No tours available at the moment. Please check back later!</p>
            </div>
            <?php else: ?>
            <?php foreach ($tours as $tour): ?>
            <div class="package-card rounded-2xl overflow-hidden fade-in-up">
                <div class="relative">
                    <?php 
                    $image_src = $tour['cover_image'] ?: $tour['image_url'] ?: '../../../assets/images/default-tour.jpg';
                    ?>
                    <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-40 object-cover" onerror="this.src="<?= getImageUrl('assets/images/default-tour.jpg') ?>"; this.onerror=null;">
                    <div class="absolute top-4 right-4 bg-golden-500 text-black px-3 py-1 rounded-full text-sm font-semibold">
                        From $<?php echo number_format($tour['price']); ?>
                    </div>
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
                        <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="block w-full bg-slate-200 text-slate-700 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-colors text-center">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
