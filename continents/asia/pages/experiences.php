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

$page_title = "Experiences in " . $region['name'] . " - iForYoungTours";

// Get experience categories
$experiences = [
    ['name' => 'Wildlife Safari', 'icon' => 'fas fa-binoculars', 'description' => 'Encounter amazing wildlife in their natural habitat'],
    ['name' => 'Cultural Tours', 'icon' => 'fas fa-users', 'description' => 'Immerse yourself in local cultures and traditions'],
    ['name' => 'Adventure Sports', 'icon' => 'fas fa-mountain', 'description' => 'Thrilling outdoor activities and adventures'],
    ['name' => 'Beach & Islands', 'icon' => 'fas fa-umbrella-beach', 'description' => 'Relax on pristine beaches and tropical islands'],
    ['name' => 'City Breaks', 'icon' => 'fas fa-city', 'description' => 'Explore vibrant cities and urban attractions'],
    ['name' => 'Nature & Hiking', 'icon' => 'fas fa-tree', 'description' => 'Discover natural wonders and scenic landscapes']
];

$base_path = '../../';
$css_path = '../assets/css/modern-styles.css';
include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
    <div class="absolute inset-0 bg-[url('../../assets/images/pattern.svg')] opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Experiences in <?php echo htmlspecialchars($region['name']); ?>
            </h1>
            <p class="text-xl text-slate-300 mb-8">
                Discover unique experiences and adventures
            </p>
        </div>
    </div>
</section>

<!-- Experiences Grid -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($experiences as $experience): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-golden-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="<?php echo $experience['icon']; ?> text-2xl text-gold"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4"><?php echo $experience['name']; ?></h3>
                <p class="text-slate-600 mb-6"><?php echo $experience['description']; ?></p>
                <a href="packages.php?experience=<?php echo urlencode(strtolower($experience['name'])); ?>" class="inline-block bg-gold text-white px-6 py-3 rounded-lg font-semibold hover:bg-gold-dark transition-colors">
                    Explore Tours
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Experiences -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Featured Experiences</h2>
            <p class="text-slate-600">Handpicked adventures for unforgettable memories</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="relative rounded-xl overflow-hidden h-96">
                <img src="<?= getImageUrl('assets/images/adventure.jpg') ?>" 
                     alt="Adventure Experience" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=800&q=80';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">Adventure Expeditions</h3>
                    <p class="mb-4">Thrilling adventures across diverse landscapes</p>
                    <a href="packages.php?category=adventure" class="bg-gold text-white px-6 py-2 rounded-lg hover:bg-gold-dark transition-colors">
                        Explore Adventures
                    </a>
                </div>
            </div>
            
            <div class="relative rounded-xl overflow-hidden h-96">
                <img src="<?= getImageUrl('assets/images/landscape.jpg') ?>" 
                     alt="Cultural Experience" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">Cultural Immersion</h3>
                    <p class="mb-4">Connect with local communities and traditions</p>
                    <a href="packages.php?category=cultural" class="bg-gold text-white px-6 py-2 rounded-lg hover:bg-gold-dark transition-colors">
                        Discover Culture
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/footer.php'; ?>