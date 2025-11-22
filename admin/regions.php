<?php

require_once 'config.php';
require_once '../config/database.php';
require_once '../includes/theme-generator.php';
require_once 'auto-clone-subdomain.php';

$db = new Database();
$conn = $db->getConnection();

// Handle region operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_region':
                $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                $stmt = $conn->prepare("INSERT INTO regions (name, slug, description, image_url, featured, status) VALUES (?, ?, ?, ?, ?, 'active')");
                $stmt->execute([$_POST['name'], $slug, $_POST['description'], $_POST['image_url'], isset($_POST['featured']) ? 1 : 0]);
                
                // Auto-clone continent folder
                cloneContinentFolder($slug);
                break;
            case 'add_country':
                try {
                    $slug = 'visit-' . strtolower($_POST['country_code']);

                    // Insert country into database
                    $stmt = $conn->prepare("INSERT INTO countries (region_id, name, slug, country_code, description, tourism_description, image_url, best_time_to_visit, currency, language, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                    $stmt->execute([$_POST['region_id'], $_POST['name'], $slug, $_POST['country_code'], $_POST['description'], $_POST['tourism_description'], $_POST['image_url'], $_POST['best_time_to_visit'], $_POST['currency'], $_POST['language'], isset($_POST['featured']) ? 1 : 0]);

                    $country_id = $conn->lastInsertId();

                    // Generate folder name from slug
                    $folder_name = generateFolderName($slug);

                    // Automatically generate Rwanda theme for new country
                    $theme_result = generateCountryTheme([
                        'id' => $country_id,
                        'name' => $_POST['name'],
                        'slug' => $slug,
                        'country_code' => $_POST['country_code'],
                        'folder' => $folder_name,
                        'currency' => $_POST['currency'],
                        'description' => $_POST['description']
                    ]);

                    // Update subdomain handler
                    updateSubdomainHandler($_POST['country_code'], $slug, $folder_name);
                    
                    // Auto-clone country folder
                    cloneCountryFolder($slug);

                } catch (Exception $e) {
                    error_log("Error adding country: " . $e->getMessage());
                }
                break;
        }
    }
}

// Get regions with country count
$stmt = $conn->prepare("SELECT r.*, COUNT(c.id) as country_count FROM regions r LEFT JOIN countries c ON r.id = c.region_id WHERE r.status = 'active' GROUP BY r.id ORDER BY r.name");
$stmt->execute();
$regions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all countries
$stmt = $conn->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Regions & Countries - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">Regions & Countries</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">Dashboard</a>
                <a href="destinations.php" class="nav-item active block px-6 py-3">Destinations</a>
                <a href="tours.php" class="nav-item block px-6 py-3">Tours & Packages</a>
                <a href="bookings.php" class="nav-item block px-6 py-3">Bookings</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">Manage Destinations</h1>
                <div class="flex gap-4">
                    <button onclick="openRegionModal()" class="btn-primary px-6 py-3 rounded-lg">Add Region</button>
                    <button onclick="openCountryModal()" class="btn-secondary px-6 py-3 rounded-lg">Add Country</button>
                </div>
            </div>

            <!-- Regions Grid -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Regions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($regions as $region): ?>
                    <div class="nextcloud-card p-6">
                        <img src="<?php echo $region['image_url'] ?: '../assets/images/default-region.jpg'; ?>" alt="<?php echo $region['name']; ?>" class="w-full h-32 object-cover rounded-lg mb-4">
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($region['name']); ?></h3>
                        <p class="text-slate-600 mb-4"><?php echo htmlspecialchars(substr($region['description'], 0, 100)); ?>...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500"><?php echo $region['country_count']; ?> countries</span>
                            <?php if ($region['featured']): ?>
                            <span class="bg-golden-100 text-golden-800 px-2 py-1 rounded text-xs">Featured</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Countries Grid -->
            <div>
                <h2 class="text-2xl font-bold mb-6">Countries</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($countries as $country): ?>
                    <div class="nextcloud-card p-4">
                        <img src="<?php echo $country['image_url'] ?: '../assets/images/default-country.jpg'; ?>" alt="<?php echo $country['name']; ?>" class="w-full h-24 object-cover rounded-lg mb-3">
                        <h4 class="font-bold"><?php echo htmlspecialchars($country['name']); ?></h4>
                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($country['region_name']); ?></p>
                        <p class="text-xs text-slate-500"><?php echo $country['country_code']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Region Modal -->
    <div id="regionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Add New Region</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="add_region">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Region Name</label>
                        <input type="text" name="name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Image URL</label>
                        <input type="url" name="image_url" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="featured" class="mr-2">
                            <span class="text-sm">Featured Region</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeRegionModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Add Region</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Country Modal -->
    <div id="countryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Add New Country</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="add_country">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country Name</label>
                        <input type="text" name="name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country Code</label>
                        <input type="text" name="country_code" required maxlength="3" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Region</label>
                        <select name="region_id" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">Select Region</option>
                            <?php foreach ($regions as $region): ?>
                            <option value="<?php echo $region['id']; ?>"><?php echo htmlspecialchars($region['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Currency</label>
                        <input type="text" name="currency" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Language</label>
                        <input type="text" name="language" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Best Time to Visit</label>
                        <input type="text" name="best_time_to_visit" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tourism Description</label>
                        <textarea name="tourism_description" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Image URL</label>
                        <input type="url" name="image_url" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="featured" class="mr-2">
                            <span class="text-sm">Featured Country</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeCountryModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Add Country</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRegionModal() { document.getElementById('regionModal').classList.remove('hidden'); }
        function closeRegionModal() { document.getElementById('regionModal').classList.add('hidden'); }
        function openCountryModal() { document.getElementById('countryModal').classList.remove('hidden'); }
        function closeCountryModal() { document.getElementById('countryModal').classList.add('hidden'); }
    </script>
</body>
</html>