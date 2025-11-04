<?php
$page_title = 'Destination Management';
$current_page = 'destinations';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Handle operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit_country':
                $stmt = $pdo->prepare("UPDATE countries SET name = ?, description = ?, tourism_description = ?, image_url = ?, best_time_to_visit = ?, currency = ?, language = ?, featured = ? WHERE id = ?");
                $stmt->execute([$_POST['name'], $_POST['description'], $_POST['tourism_description'], $_POST['image_url'], $_POST['best_time_to_visit'], $_POST['currency'], $_POST['language'], isset($_POST['featured']) ? 1 : 0, $_POST['country_id']]);
                header('Location: destinations.php?updated=1');
                exit;
                break;
        }
    }
}

// Get regions with country count
$stmt = $pdo->prepare("SELECT r.*, COUNT(c.id) as country_count FROM regions r LEFT JOIN countries c ON r.id = c.region_id WHERE r.status = 'active' GROUP BY r.id ORDER BY r.name");
$stmt->execute();
$regions = $stmt->fetchAll();

// Get all countries grouped by region
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name, COUNT(t.id) as tour_count FROM countries c JOIN regions r ON c.region_id = r.id LEFT JOIN tours t ON c.id = t.country_id AND t.status = 'active' WHERE c.status = 'active' GROUP BY c.id ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll();

// Group countries by region
$countries_by_region = [];
foreach ($countries as $country) {
    $countries_by_region[$country['region_name']][] = $country;
}

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-white">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Destination Management</h1>
        </div>
        
        <?php if (isset($_GET['updated'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            Country information updated successfully!
        </div>
        <?php endif; ?>

        <!-- Regions Overview -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Regions Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($regions as $region): ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 cursor-pointer hover:shadow-lg transition-all" onclick="window.open('http://<?php echo $region['slug']; ?>.foreveryoungtours.local', '_blank')">
                    <img src="<?php echo $region['image_url'] ?: '../assets/images/default-region.jpg'; ?>" alt="<?php echo $region['name']; ?>" class="w-full h-32 object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($region['name']); ?></h3>
                    <p class="text-slate-600 mb-4"><?php echo htmlspecialchars(substr($region['description'], 0, 100)); ?>...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500"><?php echo $region['country_count']; ?> countries</span>
                        <?php if ($region['featured']): ?>
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Featured</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Countries by Region -->
        <div>
            <h2 class="text-2xl font-bold mb-6">Countries by Region</h2>
            <?php foreach ($countries_by_region as $region_name => $region_countries): ?>
            <div class="mb-8">
                <h3 class="text-xl font-bold text-yellow-600 mb-4"><?php echo htmlspecialchars($region_name); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($region_countries as $country): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 cursor-pointer hover:shadow-lg transition-all" onclick="window.open('http://visit-<?php echo substr($country['country_code'], 0, 2); ?>.foreveryoungtours.local', '_blank')">
                        <img src="<?php echo $country['image_url'] ?: '../assets/images/default-country.jpg'; ?>" alt="<?php echo $country['name']; ?>" class="w-full h-24 object-cover rounded-lg mb-3">
                        <h4 class="font-bold"><?php echo htmlspecialchars($country['name']); ?></h4>
                        <p class="text-xs text-slate-500 mb-2"><?php echo $country['country_code']; ?></p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs text-slate-600"><?php echo $country['tour_count']; ?> tours</span>
                            <?php if ($country['featured']): ?>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Featured</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>
