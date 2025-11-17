<?php

require_once 'config.php';
require_once __DIR__ . '/../../config/database.php';

// Get continent/region data from folder structure
$continent_folder = basename(dirname(dirname(__FILE__)));
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_folder]);
$region = $stmt->fetch();

if (!$region) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Tours in " . $region['name'] . " - iForYoungTours";

// Get all tours for countries in this continent/region
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.slug as country_slug, c.currency
    FROM tours t
    JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active'
    ORDER BY c.name, t.featured DESC, t.popularity_score DESC
");
$stmt->execute([$region['id']]);
$tours = $stmt->fetchAll();

// Get all countries in this region for filtering
$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$region['id']]);
$countries = $stmt->fetchAll();

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
                Explore Tours in <?php echo htmlspecialchars($region['name']); ?>
            </h1>
            <p class="text-xl text-slate-300 mb-8">
                Discover amazing destinations across <?php echo count($countries); ?> countries
            </p>
        </div>
    </div>
</section>

<!-- Tours Section -->
<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-4">
        <!-- Country Filter -->
        <?php if (count($countries) > 1): ?>
        <div class="mb-8">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterByCountry('all')" class="country-filter-btn active px-4 py-2 rounded-lg bg-golden-500 text-white font-semibold">
                    All Countries
                </button>
                <?php foreach ($countries as $country): ?>
                <button onclick="filterByCountry('<?php echo $country['slug']; ?>')" class="country-filter-btn px-4 py-2 rounded-lg bg-white text-slate-700 font-semibold hover:bg-golden-100">
                    <?php echo htmlspecialchars($country['name']); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tours Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($tours)): ?>
            <div class="col-span-full text-center py-12">
                <i class="fas fa-map-marked-alt text-6xl text-slate-300 mb-4"></i>
                <p class="text-xl text-slate-600">No tours available yet for <?php echo htmlspecialchars($region['name']); ?>.</p>
                <p class="text-slate-500 mt-2">Check back soon for exciting new tours!</p>
            </div>
            <?php else: ?>
            <?php foreach ($tours as $tour): ?>
            <div class="tour-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300" data-country="<?php echo $tour['country_slug']; ?>">
                <div class="relative h-64">
                    <?php if ($tour['featured']): ?>
                    <div class="absolute top-4 left-4 bg-golden-500 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                        Featured
                    </div>
                    <?php endif; ?>
                    <img src="<?php echo $base_path . 'assets/images/tours/' . ($tour['image'] ?? 'default-tour.jpg'); ?>"
                         alt="<?php echo htmlspecialchars($tour['name']); ?>"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-golden-600 font-semibold"><?php echo htmlspecialchars($tour['country_name']); ?></span>
                        <span class="text-sm text-slate-500"><?php echo $tour['duration_days']; ?> days</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                    <p class="text-slate-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($tour['description'] ?? ''); ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-2xl font-bold text-golden-600"><?php echo $tour['currency'] ?? '$'; ?><?php echo number_format($tour['price'], 0); ?></span>
                            <span class="text-sm text-slate-500">/person</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="flex-1 bg-slate-200 text-slate-700 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-colors text-center">
                            View Details
                        </a>
                        <button onclick="openBookingModal(<?php echo $tour['id']; ?>, '<?php echo htmlspecialchars($tour['name'], ENT_QUOTES); ?>', <?php echo $tour['price']; ?>)" class="flex-1 bg-golden-500 text-white py-3 rounded-lg font-semibold hover:bg-golden-600 transition-colors">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Booking Modal -->
<?php include 'enhanced-booking-modal.php'; ?>

<!-- Inquiry Modal -->
<?php include 'inquiry-modal.php'; ?>

<script src="<?php echo $base_path; ?>assets/js/booking.js"></script>

<script>
// Country filter functionality
function filterByCountry(countrySlug) {
    const tourCards = document.querySelectorAll('.tour-card');
    const filterBtns = document.querySelectorAll('.country-filter-btn');

    // Update button states
    filterBtns.forEach(btn => {
        btn.classList.remove('active', 'bg-golden-500', 'text-white');
        btn.classList.add('bg-white', 'text-slate-700');
    });
    event.target.classList.add('active', 'bg-golden-500', 'text-white');
    event.target.classList.remove('bg-white', 'text-slate-700');

    // Filter tours
    tourCards.forEach(card => {
        if (countrySlug === 'all' || card.dataset.country === countrySlug) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php include '../includes/footer.php'; ?>