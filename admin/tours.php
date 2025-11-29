<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = 'tours';
require_once 'config.php';
require_once '../includes/csrf.php';
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');
require_once 'upload_handler.php';

$page_title = "Tours & Packages Management";
$page_subtitle = "Manage Tour Offerings";

$success = '';
$error = '';

// Handle tour operations
if ($_POST) {
    requireCsrf();
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                if (!isset($_POST['tour_id']) || !is_numeric($_POST['tour_id']) || $_POST['tour_id'] <= 0) {
                    header('Location: tours.php?error=invalid_tour_id');
                    exit;
                }
                $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                
                // Check for duplicate slug and make it unique
                $original_slug = $slug;
                $counter = 1;
                $check_stmt = $pdo->prepare("SELECT id FROM tours WHERE slug = ? AND id != ?");
                $check_stmt->execute([$slug, $_POST['tour_id']]);
                while ($check_stmt->fetch()) {
                    $slug = $original_slug . '-' . $counter;
                    $counter++;
                    $check_stmt->execute([$slug, $_POST['tour_id']]);
                }
                // Get country details
                $country_stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ?");
                $country_stmt->execute([$_POST['country_id']]);
                $country = $country_stmt->fetch();
                
                // Prepare itinerary JSON
                $itinerary = [];
                if (isset($_POST['itinerary_day'])) {
                    for ($i = 0; $i < count($_POST['itinerary_day']); $i++) {
                        $itinerary[] = [
                            'day' => $_POST['itinerary_day'][$i],
                            'title' => $_POST['itinerary_title'][$i] ?? '',
                            'activities' => $_POST['itinerary_activities'][$i] ?? ''
                        ];
                    }
                }
                
                // Prepare inclusions and exclusions
                $inclusions = !empty($_POST['inclusions']) ? array_filter(explode("\n", $_POST['inclusions'])) : [];
                $exclusions = !empty($_POST['exclusions']) ? array_filter(explode("\n", $_POST['exclusions'])) : [];
                
                // Prepare gallery JSON
                $gallery = [];
                if (!empty($_POST['gallery'])) {
                    $gallery = array_filter(explode("\n", trim($_POST['gallery'])));
                }
                
                // Handle file uploads
                $image_url = $_POST['current_image_url'] ?? '';
                $cover_image = $_POST['current_cover_image'] ?? '';
                
                try {
                    // Main image upload
                    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                        if ($image_url) deleteFile($image_url);
                        $image_url = uploadTourImage($_FILES['main_image'], $_POST['tour_id'], 'main');
                    }
                    
                    // Cover image upload
                    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                        if ($cover_image) deleteFile($cover_image);
                        $cover_image = uploadTourImage($_FILES['cover_image'], $_POST['tour_id'], 'cover');
                    }
                    
                    // Simple images array
                    $images = [];
                    if ($image_url) $images[] = $image_url;
                    if ($cover_image && $cover_image !== $image_url) $images[] = $cover_image;
                    
                    // Gallery images (legacy support)
                    $gallery = [];
                    if (!empty($_POST['current_gallery'])) {
                        $current_gallery = json_decode($_POST['current_gallery'], true);
                        if (is_array($current_gallery)) {
                            $gallery = $current_gallery;
                        }
                    }
                    
                    if (isset($_FILES['gallery_images'])) {
                        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                                $file = [
                                    'name' => $_FILES['gallery_images']['name'][$key],
                                    'type' => $_FILES['gallery_images']['type'][$key],
                                    'tmp_name' => $tmp_name,
                                    'size' => $_FILES['gallery_images']['size'][$key]
                                ];
                                $uploaded_image = uploadTourImage($file, $_POST['tour_id'], 'gallery_' . $key);
                                $gallery[] = $uploaded_image;
                                $images[] = $uploaded_image;
                            }
                        }
                    }
                } catch (Exception $e) {
                    header('Location: tours.php?error=' . urlencode($e->getMessage()));
                    exit;
                }
                
                $highlights = !empty($_POST['highlights']) ? array_filter(explode("\n", $_POST['highlights'])) : [];
                
                $stmt = $pdo->prepare("UPDATE tours SET name = ?, slug = ?, description = ?, detailed_description = ?, destination = ?, destination_country = ?, country_id = ?, category = ?, price = ?, base_price = ?, duration = ?, duration_days = ?, max_participants = ?, min_participants = ?, image_url = ?, cover_image = ?, gallery = ?, images = ?, itinerary = ?, inclusions = ?, exclusions = ?, highlights = ?, requirements = ?, difficulty_level = ?, best_time_to_visit = ?, status = ?, featured = ? WHERE id = ?");
                
                $stmt->execute([
                    $_POST['name'], 
                    $slug, 
                    $_POST['description'], 
                    $_POST['detailed_description'] ?? '',
                    $_POST['destination'], 
                    $country['name'], 
                    $_POST['country_id'], 
                    $_POST['category'], 
                    $_POST['price'], 
                    $_POST['price'], 
                    $_POST['duration_days'] . ' days', 
                    $_POST['duration_days'], 
                    $_POST['max_participants'], 
                    $_POST['min_participants'] ?? 2,
                    $image_url,
                    $cover_image, 
                    json_encode($gallery),
                    json_encode($images), // New images column
                    json_encode($itinerary), 
                    json_encode($inclusions), 
                    json_encode($exclusions), 
                    json_encode($highlights),
                    $_POST['requirements'] ?? '',
                    $_POST['difficulty_level'] ?? 'moderate',
                    $_POST['best_time_to_visit'] ?? '',
                    $_POST['status'] ?? 'active',
                    isset($_POST['featured']) ? 1 : 0,
                    $_POST['tour_id']
                ]);
                header('Location: tours.php?updated=1');
                exit;
                break;
            case 'add':
                try {
                    $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                    
                    // Check for duplicate slug and make it unique
                    $original_slug = $slug;
                    $counter = 1;
                    $check_stmt = $pdo->prepare("SELECT id FROM tours WHERE slug = ?");
                    $check_stmt->execute([$slug]);
                    while ($check_stmt->fetch()) {
                        $slug = $original_slug . '-' . $counter;
                        $counter++;
                        $check_stmt->execute([$slug]);
                    }
                    
                    // Get country details
                    $country_stmt = $pdo->prepare("SELECT name FROM countries WHERE id = ?");
                    $country_stmt->execute([$_POST['country_id']]);
                    $country = $country_stmt->fetch();
                    
                    if (!$country) {
                        throw new Exception('Invalid country selected');
                    }
                    
                    // Prepare itinerary JSON
                    $itinerary = [];
                    if (isset($_POST['itinerary_day'])) {
                        for ($i = 0; $i < count($_POST['itinerary_day']); $i++) {
                            $itinerary[] = [
                                'day' => $_POST['itinerary_day'][$i],
                                'title' => $_POST['itinerary_title'][$i] ?? '',
                                'activities' => $_POST['itinerary_activities'][$i] ?? ''
                            ];
                        }
                    }
                    
                    // Prepare inclusions and exclusions
                    $inclusions = !empty($_POST['inclusions']) ? array_filter(explode("\n", $_POST['inclusions'])) : [];
                    $exclusions = !empty($_POST['exclusions']) ? array_filter(explode("\n", $_POST['exclusions'])) : [];
                    $highlights = !empty($_POST['highlights']) ? array_filter(explode("\n", $_POST['highlights'])) : [];
                    

                    // Insert tour with minimal fields
                    $stmt = $pdo->prepare("INSERT INTO tours (name, slug, description, destination, destination_country, country_id, category, price, base_price, duration, duration_days, max_participants, min_participants, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    if (!$stmt->execute([
                        $_POST['name'], 
                        $slug, 
                        $_POST['description'] ?? 'Tour',
                        $_POST['destination'], 
                        $country['name'], 
                        $_POST['country_id'], 
                        $_POST['category'] ?? 'cultural tours', 
                        $_POST['price'] ?? 0, 
                        $_POST['price'] ?? 0, 
                        $_POST['duration_days'] . ' days', 
                        $_POST['duration_days'], 
                        $_POST['max_participants'] ?? 20, 
                        $_POST['min_participants'] ?? 2,
                        $_POST['status'] ?? 'active',
                        isset($_POST['featured']) ? 1 : 0,
                        1
                    ])) {
                        $errorInfo = $stmt->errorInfo();
                        throw new Exception('Insert error: ' . $errorInfo[2]);
                    }
                    
                    $tour_id = $pdo->lastInsertId();
                    
                    if (!$tour_id) {
                        $check = $pdo->prepare("SELECT id FROM tours WHERE name = ? AND country_id = ? LIMIT 1");
                        $check->execute([$_POST['name'], $_POST['country_id']]);
                        $row = $check->fetch();
                        $tour_id = $row['id'] ?? 0;
                    }
                    
                    if (!$tour_id) {
                        throw new Exception('Failed to create tour. Please try again.');
                    }
                    
                    // Handle file uploads AFTER getting tour ID
                    $image_url = '';
                    $cover_image = '';
                    $gallery = [];
                    $images = [];
                    
                    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                        $image_url = uploadTourImage($_FILES['main_image'], $tour_id, 'main');
                        $images[] = $image_url;
                    }
                    
                    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                        $cover_image = uploadTourImage($_FILES['cover_image'], $tour_id, 'cover');
                        $images[] = $cover_image;
                    }
                    
                    if (isset($_FILES['gallery_images'])) {
                        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                                $file = [
                                    'name' => $_FILES['gallery_images']['name'][$key],
                                    'type' => $_FILES['gallery_images']['type'][$key],
                                    'tmp_name' => $tmp_name,
                                    'size' => $_FILES['gallery_images']['size'][$key]
                                ];
                                $uploaded_image = uploadTourImage($file, $tour_id, 'gallery_' . $key);
                                $gallery[] = $uploaded_image;
                                $images[] = $uploaded_image;
                            }
                        }
                    }
                    
                    // Update tour with all details
                    $update_stmt = $pdo->prepare("UPDATE tours SET image_url = ?, cover_image = ?, gallery = ?, images = ?, itinerary = ?, inclusions = ?, exclusions = ?, highlights = ?, detailed_description = ?, requirements = ?, difficulty_level = ?, best_time_to_visit = ? WHERE id = ?");
                    $update_stmt->execute([
                        $image_url,
                        $cover_image,
                        json_encode($gallery),
                        json_encode($images),
                        json_encode($itinerary),
                        json_encode($inclusions),
                        json_encode($exclusions),
                        json_encode($highlights),
                        $_POST['detailed_description'] ?? '',
                        $_POST['requirements'] ?? '',
                        $_POST['difficulty_level'] ?? 'moderate',
                        $_POST['best_time_to_visit'] ?? '',
                        $tour_id
                    ]);
                    
                    header('Location: tours.php?added=1');
                    exit;
                } catch (Exception $e) {
                    error_log("Add tour error: " . $e->getMessage());
                    header('Location: tours.php?error=' . urlencode($e->getMessage()));
                    exit;
                }
                break;
            case 'deactivate':
                try {
                    $stmt = $pdo->prepare("UPDATE tours SET status = 'inactive' WHERE id = ?");
                    $result = $stmt->execute([$_POST['tour_id']]);
                    
                    if ($result && $stmt->rowCount() > 0) {
                        header('Location: tours.php?deactivated=1');
                    } else {
                        header('Location: tours.php?error=deactivate_failed');
                    }
                } catch (Exception $e) {
                    error_log("Deactivate tour error: " . $e->getMessage());
                    header('Location: tours.php?error=deactivate_error');
                }
                exit;
                break;
                
            case 'delete':
                try {
                    $tour_id = intval($_POST['tour_id']);
                    if (!$tour_id) {
                        throw new Exception('Invalid tour ID');
                    }
                    
                    // First, delete related records to avoid foreign key constraints
                    $pdo->beginTransaction();
                    
                    // Delete tour bookings (if any)
                    $stmt = $pdo->prepare("DELETE FROM bookings WHERE tour_id = ?");
                    $stmt->execute([$tour_id]);
                    
                    // Delete tour reviews (if any)
                    $stmt = $pdo->prepare("DELETE FROM reviews WHERE tour_id = ?");
                    $stmt->execute([$tour_id]);
                    
                    // Delete the tour itself
                    $stmt = $pdo->prepare("DELETE FROM tours WHERE id = ?");
                    $result = $stmt->execute([$tour_id]);
                    
                    if ($result && $stmt->rowCount() > 0) {
                        $pdo->commit();
                        header('Location: tours.php?deleted=1');
                    } else {
                        $pdo->rollback();
                        header('Location: tours.php?error=delete_failed');
                    }
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollback();
                    }
                    error_log("Delete tour error: " . $e->getMessage());
                    header('Location: tours.php?error=delete_error');
                }
                exit;
                break;
        }
    }
}

// Filter by country if specified or by subdomain context
$country_filter = $_GET['country_id'] ?? '';
$where_conditions = [];
$params = [];

// Add subdomain filtering
if (defined('CURRENT_COUNTRY_ID')) {
    $where_conditions[] = "t.country_id = ?";
    $params[] = CURRENT_COUNTRY_ID;
} elseif (isset($_SESSION['continent_filter'])) {
    $where_conditions[] = "r.name = ?";
    $params[] = $_SESSION['continent_filter'];
}

// Add manual country filter
if ($country_filter) {
    $where_conditions[] = "t.country_id = ?";
    $params[] = $country_filter;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "WHERE 1=1";

// Get tours with country, region and category info
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name, r.name as region_name, t.category as category_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id $where_clause ORDER BY r.name, c.name, t.created_at DESC");
$stmt->execute($params);
$tours = $stmt->fetchAll();

// Get all countries for dropdown
$stmt = $pdo->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll();

// Get all categories - not needed since we use enum
$categories = [];

// Get tour for editing if edit parameter is present
$edit_tour = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_tour = $stmt->fetch();
}

// Group tours by region and country
$tours_by_region = [];
foreach ($tours as $tour) {
    $tours_by_region[$tour['region_name']][$tour['country_name']][] = $tour;
}

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Tours & Packages Management</h1>
                <p class="text-slate-600">Manage all tour offerings across Africa</p>
            </div>
            <div class="flex gap-4">
                <?php if (isset($_GET['edit'])): ?>
                <a href="tours.php" class="px-6 py-3 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel Edit
                </a>
                <?php endif; ?>
                <button onclick="openAddModal()" class="bg-primary-gold text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i><?= isset($_GET['edit']) ? 'Edit Tour' : 'Add New Tour' ?>
                </button>
            </div>
        </div>
            
            <?php if (isset($_GET['added'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Tour added successfully!
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['updated'])): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                Tour updated successfully!
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deactivated'])): ?>
            <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-pause-circle mr-2"></i>Tour deactivated successfully! (Status set to inactive)
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-trash mr-2"></i>Tour permanently deleted successfully!
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?php 
                switch($_GET['error']) {
                    case 'invalid_tour_id':
                        echo 'Invalid tour ID. Please try again.';
                        break;
                    case 'deactivate_failed':
                        echo 'Failed to deactivate tour. Tour may not exist.';
                        break;
                    case 'deactivate_error':
                        echo 'An error occurred while deactivating the tour. Please try again.';
                        break;
                    case 'delete_failed':
                        echo 'Failed to delete tour. Tour may not exist.';
                        break;
                    case 'delete_error':
                        echo 'An error occurred while deleting the tour. Please try again.';
                        break;
                    default:
                        echo htmlspecialchars($_GET['error']);
                }
                ?>
            </div>
            <?php endif; ?>

            <!-- Filter Controls -->
            <div class="mb-8 flex gap-4 items-center">
                <select onchange="filterByCountry(this.value)" class="border border-slate-300 rounded-lg px-4 py-2">
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $country): ?>
                    <option value="<?php echo $country['id']; ?>" <?php echo $country_filter == $country['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($country['region_name'] . ' - ' . $country['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="text-slate-600">Total Tours: <?php echo count($tours); ?></span>
            </div>

            <!-- Tours List -->
            <?php if (empty($tours)): ?>
            <div class="text-center py-12">
                <p class="text-slate-500 text-lg">No tours found. Start by adding tours to your destinations.</p>
            </div>
            <?php else: ?>
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">All Tours & Packages</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4 w-1/4">Tour Details</th>
                                <th class="text-left p-4 w-24">Destination</th>
                                <th class="text-left p-4">Category</th>
                                <th class="text-left p-4">Price</th>
                                <th class="text-left p-4">Duration</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4 w-1/4">
                                    <div class="flex items-center">
                                        <?php 
                                        $display_image = $tour['image_url'] ?: $tour['cover_image'] ?: '../assets/images/default-tour.jpg';
                                        if (strpos($display_image, 'uploads/') === 0) {
                                            $display_image = '../' . $display_image;
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($display_image); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-16 h-16 object-cover rounded-lg mr-4" onerror="this.src="<?= getImageUrl('assets/images/default-tour.jpg') ?>"; this.onerror=null;">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($tour['name']); ?></h4>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 w-24">
                                    <p class="text-sm font-semibold"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo htmlspecialchars($tour['category_name'] ?: 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold text-golden-600">$<?php echo number_format($tour['price']); ?></p>
                                        <p class="text-xs text-slate-500">per person</p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo $tour['duration_days']; ?> days</p>
                                        <p class="text-xs text-slate-500">Max: <?php echo $tour['max_participants']; ?> people</p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php echo $tour['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($tour['status']); ?>
                                    </span>
                                    <?php if ($tour['featured']): ?>
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button onclick="editTour(<?php echo $tour['id']; ?>)" class="btn-secondary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" class="inline" onsubmit="return confirm('⚠️ PERMANENTLY DELETE tour: <?php echo addslashes($tour['name']); ?>?\n\n🚨 WARNING: This will completely remove the tour and all related data (bookings, reviews, etc.) from the database.\n\nThis action CANNOT be undone!\n\nAre you absolutely sure?')">
                                            <?php echo getCsrfField(); ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm transition-colors duration-200">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>

    <!-- Add Tour Modal -->
    <div id="addTourModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient"><?php echo $edit_tour ? 'Edit Tour' : 'Add New Tour'; ?></h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <?php echo getCsrfField(); ?>
                <input type="hidden" name="action" value="<?php echo $edit_tour ? 'edit' : 'add'; ?>">
                <?php if ($edit_tour): ?>
                <input type="hidden" name="tour_id" value="<?php echo $edit_tour['id']; ?>">
                <?php endif; ?>
                <!-- Basic Information -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tour Name *</label>
                            <input type="text" name="name" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['name']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Country *</label>
                            <select name="country_id" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country['id']; ?>" <?php echo ($edit_tour && $edit_tour['country_id'] == $country['id']) || ($_GET['country_id'] ?? '') == $country['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country['region_name'] . ' - ' . $country['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Destination/City *</label>
                            <input type="text" name="destination" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['destination']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                            <select name="category" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="">Select Category</option>
                                <option value="motorcoach tours" <?php echo ($edit_tour && $edit_tour['category'] == 'motorcoach tours') ? 'selected' : ''; ?>>Motorcoach Tours</option>
                                <option value="rail tours" <?php echo ($edit_tour && $edit_tour['category'] == 'rail tours') ? 'selected' : ''; ?>>Rail Tours</option>
                                <option value="cruises tours" <?php echo ($edit_tour && $edit_tour['category'] == 'cruises tours') ? 'selected' : ''; ?>>Cruises Tours</option>
                                <option value="city_break tours" <?php echo ($edit_tour && $edit_tour['category'] == 'city_break tours') ? 'selected' : ''; ?>>City Break Tours</option>
                                <option value="agro tours" <?php echo ($edit_tour && $edit_tour['category'] == 'agro tours') ? 'selected' : ''; ?>>Agro Tours</option>
                                <option value="adventure tours" <?php echo ($edit_tour && $edit_tour['category'] == 'adventure tours') ? 'selected' : ''; ?>>Adventure Tours</option>
                                <option value="sports tours" <?php echo ($edit_tour && $edit_tour['category'] == 'sports tours') ? 'selected' : ''; ?>>Sports Tours</option>
                                <option value="cultural tours" <?php echo ($edit_tour && $edit_tour['category'] == 'cultural tours') ? 'selected' : ''; ?>>Cultural Tours</option>
                                <option value="conference tours" <?php echo ($edit_tour && $edit_tour['category'] == 'conference tours') ? 'selected' : ''; ?>>Conference Tours</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                        <textarea name="description" rows="3" placeholder="Brief tour overview for listings" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo $edit_tour ? htmlspecialchars($edit_tour['description']) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Pricing & Duration -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Pricing & Duration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Price per Person ($) *</label>
                            <input type="number" name="price" required min="0" step="0.01" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? $edit_tour['price'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Duration (days) *</label>
                            <input type="number" name="duration_days" required min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? $edit_tour['duration_days'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Max Participants</label>
                            <input type="number" name="max_participants" value="<?php echo $edit_tour ? $edit_tour['max_participants'] : '20'; ?>" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Min Participants</label>
                            <input type="number" name="min_participants" value="<?php echo $edit_tour ? $edit_tour['min_participants'] : '2'; ?>" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="featured" value="1" <?php echo ($edit_tour && $edit_tour['featured']) ? 'checked' : ''; ?> class="mr-2">
                            <label class="text-sm font-medium text-slate-700">Featured Tour</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                            <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="active" <?php echo ($edit_tour && $edit_tour['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($edit_tour && $edit_tour['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="draft" <?php echo ($edit_tour && $edit_tour['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Images & Gallery</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Main Image</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center cursor-pointer hover:border-golden-600 transition-colors" onclick="document.getElementById('main_image_input').click()">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600">Click to upload or drag & drop</p>
                                <p class="text-xs text-slate-500">JPG, PNG, GIF, WebP (Max 5MB)</p>
                            </div>
                            <input type="file" id="main_image_input" name="main_image" accept="image/*" class="hidden" onchange="previewImage(this, 'main_preview')">
                            <div id="main_preview" class="mt-2"></div>
                            <?php if ($edit_tour && $edit_tour['image_url']): ?>
                            <div class="mt-2">
                                <p class="text-xs text-slate-500 mb-1">Current image:</p>
                                <img src="<?php echo htmlspecialchars($edit_tour['image_url']); ?>" alt="Current main image" class="w-20 h-20 object-cover rounded">
                                <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($edit_tour['image_url']); ?>">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Cover Image</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center cursor-pointer hover:border-golden-600 transition-colors" onclick="document.getElementById('cover_image_input').click()">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-2"></i>
                                <p class="text-sm text-slate-600">Click to upload or drag & drop</p>
                                <p class="text-xs text-slate-500">JPG, PNG, GIF, WebP (Max 5MB)</p>
                            </div>
                            <input type="file" id="cover_image_input" name="cover_image" accept="image/*" class="hidden" onchange="previewImage(this, 'cover_preview')">
                            <div id="cover_preview" class="mt-2"></div>
                            <?php if ($edit_tour && $edit_tour['cover_image']): ?>
                            <div class="mt-2">
                                <p class="text-xs text-slate-500 mb-1">Current image:</p>
                                <img src="<?php echo htmlspecialchars($edit_tour['cover_image']); ?>" alt="Current cover image" class="w-20 h-20 object-cover rounded">
                                <input type="hidden" name="current_cover_image" value="<?php echo htmlspecialchars($edit_tour['cover_image']); ?>">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Gallery Images (Multiple files)</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center cursor-pointer hover:border-golden-600 transition-colors" onclick="document.getElementById('gallery_images_input').click()">
                            <i class="fas fa-images text-3xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-600">Click to upload or drag & drop</p>
                            <p class="text-xs text-slate-500">Select multiple images (JPG, PNG, GIF, WebP - Max 5MB each)</p>
                        </div>
                        <input type="file" id="gallery_images_input" name="gallery_images[]" accept="image/*" multiple class="hidden" onchange="previewGalleryImages(this)">
                        <div id="gallery_preview" class="mt-4"></div>
                        <?php if ($edit_tour): ?>
                        <div class="mt-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Current Images:</p>
                            <?php 
                            $display_images = [];
                            if ($edit_tour['image_url']) $display_images[] = $edit_tour['image_url'];
                            if ($edit_tour['cover_image']) $display_images[] = $edit_tour['cover_image'];
                            if ($edit_tour['gallery']) {
                                $gallery_data = json_decode($edit_tour['gallery'], true);
                                if ($gallery_data) $display_images = array_merge($display_images, $gallery_data);
                            }
                            $display_images = array_unique(array_filter($display_images));
                            ?>
                            
                            <?php if (!empty($display_images)): ?>
                            <div class="grid grid-cols-4 gap-2 mb-4">
                                <?php foreach ($display_images as $image): ?>
                                <?php 
                                // Fix relative paths
                                $image_src = $image;
                                if (strpos($image, 'uploads/') === 0) {
                                    $image_src = '../' . $image;
                                }
                                ?>
                                <div class="relative">
                                    <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Tour image" class="w-full h-16 object-cover rounded" onerror="this.src="<?= getImageUrl('assets/images/default-tour.jpg') ?>"; this.onerror=null;">
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">
                                        <?php echo basename($image); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-gray-500 mb-4">No images found.</p>
                            <?php endif; ?>
                            
                            <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($edit_tour['image_url'] ?: ''); ?>">
                            <input type="hidden" name="current_cover_image" value="<?php echo htmlspecialchars($edit_tour['cover_image'] ?: ''); ?>">
                            <input type="hidden" name="current_gallery" value="<?php echo htmlspecialchars($edit_tour['gallery'] ?: '[]'); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tour Details -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Tour Details</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Detailed Description</label>
                            <textarea name="detailed_description" rows="3" placeholder="Comprehensive tour description with highlights" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php echo $edit_tour ? htmlspecialchars($edit_tour['detailed_description']) : ''; ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Difficulty Level</label>
                                <select name="difficulty_level" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="easy" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'easy') ? 'selected' : ''; ?>>Easy</option>
                                    <option value="moderate" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'moderate') ? 'selected' : ''; ?>>Moderate</option>
                                    <option value="challenging" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'challenging') ? 'selected' : ''; ?>>Challenging</option>
                                    <option value="extreme" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'extreme') ? 'selected' : ''; ?>>Extreme</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Best Time to Visit</label>
                                <input type="text" name="best_time_to_visit" placeholder="e.g., June to October" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['best_time_to_visit']) : ''; ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Tour Highlights (one per line)</label>
                            <textarea name="highlights" rows="3" placeholder="Expert local guides\nSmall group experience\nAuthentic cultural interactions" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php 
                            if ($edit_tour && $edit_tour['highlights']) {
                                $highlights = json_decode($edit_tour['highlights'], true);
                                echo is_array($highlights) ? implode("\n", $highlights) : '';
                            }
                            ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Inclusions (one per line)</label>
                                <textarea name="inclusions" rows="3" placeholder="Accommodation\nMeals\nTransportation\nGuide" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php 
                                if ($edit_tour && $edit_tour['inclusions']) {
                                    $inclusions = json_decode($edit_tour['inclusions'], true);
                                    echo is_array($inclusions) ? implode("\n", $inclusions) : '';
                                }
                                ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Exclusions (one per line)</label>
                                <textarea name="exclusions" rows="3" placeholder="International flights\nVisa fees\nPersonal expenses" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php 
                                if ($edit_tour && $edit_tour['exclusions']) {
                                    $exclusions = json_decode($edit_tour['exclusions'], true);
                                    echo is_array($exclusions) ? implode("\n", $exclusions) : '';
                                }
                                ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Itinerary</h4>
                    <div id="itinerary-container">
                        <?php 
                        $itinerary_items = [];
                        if ($edit_tour && $edit_tour['itinerary']) {
                            $itinerary_items = json_decode($edit_tour['itinerary'], true);
                        }
                        if (empty($itinerary_items)) {
                            $itinerary_items = [['day' => 1, 'title' => '', 'activities' => '']];
                        }
                        foreach ($itinerary_items as $index => $item):
                        ?>
                        <div class="itinerary-day mb-4 p-4 border border-slate-200 rounded-lg">
                            <?php if ($index > 0): ?>
                            <div class="flex justify-between items-start mb-4">
                                <h5 class="font-medium">Day <?= $item['day'] ?></h5>
                                <button type="button" onclick="removeItineraryDay(this)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                            </div>
                            <?php endif; ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Day</label>
                                    <input type="number" name="itinerary_day[]" value="<?= htmlspecialchars($item['day']) ?>" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                                    <input type="text" name="itinerary_title[]" value="<?= htmlspecialchars($item['title']) ?>" placeholder="Day <?= $item['day'] ?>: Activity Title" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Activities</label>
                                <textarea name="itinerary_activities[]" rows="3" placeholder="Describe the day's activities and highlights" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?= htmlspecialchars($item['activities']) ?></textarea>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addItineraryDay()" class="btn-secondary px-4 py-2 rounded-lg text-sm">Add Another Day</button>
                </div>

                <!-- Requirements -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Requirements & Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Requirements</label>
                            <textarea name="requirements" rows="3" placeholder="Valid passport\nYellow fever certificate\nFitness level required" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php echo $edit_tour ? htmlspecialchars($edit_tour['requirements']) : ''; ?></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Additional Notes</label>
                            <textarea name="notes" rows="3" placeholder="Weather information\nPacking suggestions\nCultural tips" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"><?php echo $edit_tour ? htmlspecialchars($edit_tour['notes'] ?? '') : ''; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeAddModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg"><?php echo $edit_tour ? 'Update Tour' : 'Add Tour'; ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addTourModal').classList.remove('hidden');
        }
        function closeAddModal() {
            document.getElementById('addTourModal').classList.add('hidden');
        }
        function editTour(id) {
            // Redirect to edit page with tour ID
            window.location.href = 'tours.php?edit=' + id;
        }
        function filterByCountry(countryId) {
            if (countryId) {
                window.location.href = 'tours.php?country_id=' + countryId;
            } else {
                window.location.href = 'tours.php';
            }
        }
        
        function addItineraryDay() {
            const container = document.getElementById('itinerary-container');
            const dayCount = container.children.length + 1;
            const dayHtml = `
                <div class="itinerary-day mb-4 p-4 border border-slate-200 rounded-lg">
                    <div class="flex justify-between items-start mb-4">
                        <h5 class="font-medium">Day ${dayCount}</h5>
                        <button type="button" onclick="removeItineraryDay(this)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Day</label>
                            <input type="number" name="itinerary_day[]" value="${dayCount}" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                            <input type="text" name="itinerary_title[]" placeholder="Day ${dayCount}: Activity Title" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Activities</label>
                        <textarea name="itinerary_activities[]" rows="3" placeholder="Describe the day's activities and highlights" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', dayHtml);
        }
        
        function removeItineraryDay(button) {
            button.closest('.itinerary-day').remove();
        }
        
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded';
                    
                    const container = document.createElement('div');
                    container.className = 'flex items-center gap-2';
                    container.innerHTML = '<p class="text-xs text-slate-500">New:</p>';
                    container.appendChild(img);
                    
                    preview.appendChild(container);
                };
                reader.readAsDataURL(file);
            }
        }
        
        function previewGalleryImages(input) {
            const preview = document.getElementById('gallery_preview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                const container = document.createElement('div');
                container.className = 'grid grid-cols-4 gap-2';
                
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-16 object-cover rounded">
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">
                                ${file.name}
                            </div>
                        `;
                        container.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
                
                preview.appendChild(container);
            }
        }
        
        // Auto-open modal if edit parameter is present
        <?php if (isset($_GET['edit']) || isset($_GET['add_tour'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openAddModal();
        });
        <?php endif; ?>
    </script>
    </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>