<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Handle tour operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                // Get country details
                $country_stmt = $conn->prepare("SELECT name FROM countries WHERE id = ?");
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
                
                // Prepare highlights JSON
                $highlights = [];
                if (!empty($_POST['highlights'])) {
                    $highlights = array_filter(explode("\n", trim($_POST['highlights'])));
                }
                
                // Prepare languages JSON
                $languages = [];
                if (!empty($_POST['languages'])) {
                    $languages = array_filter(array_map('trim', explode(",", trim($_POST['languages']))));
                }
                
                // Prepare tour tags JSON
                $tour_tags = [];
                if (!empty($_POST['tour_tags'])) {
                    $tour_tags = array_filter(array_map('trim', explode(",", trim($_POST['tour_tags']))));
                }
                
                // Prepare media galleries
                $media_gallery = [];
                if (!empty($_POST['media_gallery'])) {
                    $media_gallery = array_filter(explode("\n", trim($_POST['media_gallery'])));
                }
                
                $accommodation_gallery = [];
                if (!empty($_POST['accommodation_gallery'])) {
                    $accommodation_gallery = array_filter(explode("\n", trim($_POST['accommodation_gallery'])));
                }
                
                $video_gallery = [];
                if (!empty($_POST['video_gallery'])) {
                    $video_gallery = array_filter(explode("\n", trim($_POST['video_gallery'])));
                }
                
                $stmt = $conn->prepare("UPDATE tours SET name = ?, slug = ?, description = ?, detailed_description = ?, destination = ?, destination_country = ?, country_id = ?, category = ?, price = ?, base_price = ?, duration = ?, duration_days = ?, max_participants = ?, min_participants = ?, image_url = ?, cover_image = ?, gallery = ?, highlights = ?, itinerary = ?, inclusions = ?, exclusions = ?, requirements = ?, difficulty_level = ?, best_time_to_visit = ?, what_to_bring = ?, tour_type = ?, languages = ?, age_restriction = ?, accommodation_type = ?, meal_plan = ?, booking_deadline = ?, tour_tags = ?, meta_title = ?, meta_description = ?, video_url = ?, virtual_tour_url = ?, drone_footage_url = ?, media_gallery = ?, accommodation_gallery = ?, video_gallery = ?, status = ?, featured = ? WHERE id = ?");
                
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
                    $_POST['image_url'], 
                    $_POST['cover_image'] ?? '', 
                    json_encode($gallery),
                    json_encode($highlights),
                    json_encode($itinerary), 
                    json_encode($inclusions), 
                    json_encode($exclusions), 
                    $_POST['requirements'] ?? '',
                    $_POST['difficulty_level'] ?? 'moderate',
                    $_POST['best_time_to_visit'] ?? '',
                    $_POST['what_to_bring'] ?? '',
                    $_POST['tour_type'] ?? 'group',
                    json_encode($languages),
                    $_POST['age_restriction'] ?? '',
                    $_POST['accommodation_type'] ?? '',
                    $_POST['meal_plan'] ?? '',
                    $_POST['booking_deadline'] ?? 7,
                    json_encode($tour_tags),
                    $_POST['meta_title'] ?? '',
                    $_POST['meta_description'] ?? '',
                    $_POST['video_url'] ?? '',
                    $_POST['virtual_tour_url'] ?? '',
                    $_POST['drone_footage_url'] ?? '',
                    json_encode($media_gallery),
                    json_encode($accommodation_gallery),
                    json_encode($video_gallery),
                    $_POST['status'] ?? 'active',
                    isset($_POST['featured']) ? 1 : 0,
                    $_POST['tour_id']
                ]);
                
                // Handle multiple images
                if (!empty($_POST['gallery_images'])) {
                    // Delete existing gallery images
                    $conn->prepare("DELETE FROM tour_images WHERE tour_id = ? AND image_type = 'gallery'")->execute([$_POST['tour_id']]);
                    
                    // Insert new gallery images
                    $gallery_images = array_filter(explode("\n", trim($_POST['gallery_images'])));
                    foreach ($gallery_images as $index => $image_url) {
                        $image_url = trim($image_url);
                        if (!empty($image_url)) {
                            $stmt = $conn->prepare("INSERT INTO tour_images (tour_id, image_url, image_type, sort_order, alt_text) VALUES (?, ?, 'gallery', ?, ?)");
                            $stmt->execute([$_POST['tour_id'], $image_url, $index + 1, $_POST['name'] . ' - Gallery Image ' . ($index + 1)]);
                        }
                    }
                }
                
                header('Location: tours-enhanced.php?updated=1');
                exit;
                break;
                
            case 'add':
                $slug = strtolower(str_replace(' ', '-', $_POST['name']));
                // Get country details
                $country_stmt = $conn->prepare("SELECT name FROM countries WHERE id = ?");
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
                
                // Prepare highlights JSON
                $highlights = [];
                if (!empty($_POST['highlights'])) {
                    $highlights = array_filter(explode("\n", trim($_POST['highlights'])));
                }
                
                // Prepare languages JSON
                $languages = [];
                if (!empty($_POST['languages'])) {
                    $languages = array_filter(array_map('trim', explode(",", trim($_POST['languages']))));
                }
                
                // Prepare tour tags JSON
                $tour_tags = [];
                if (!empty($_POST['tour_tags'])) {
                    $tour_tags = array_filter(array_map('trim', explode(",", trim($_POST['tour_tags']))));
                }
                
                $stmt = $conn->prepare("INSERT INTO tours (name, slug, description, detailed_description, destination, destination_country, country_id, category, price, base_price, duration, duration_days, max_participants, min_participants, image_url, cover_image, gallery, highlights, itinerary, inclusions, exclusions, requirements, difficulty_level, best_time_to_visit, what_to_bring, tour_type, languages, age_restriction, accommodation_type, meal_plan, booking_deadline, tour_tags, meta_title, meta_description, video_url, virtual_tour_url, drone_footage_url, media_gallery, accommodation_gallery, video_gallery, status, featured, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                
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
                    $_POST['image_url'], 
                    $_POST['cover_image'] ?? '', 
                    json_encode($gallery),
                    json_encode($highlights),
                    json_encode($itinerary), 
                    json_encode($inclusions), 
                    json_encode($exclusions), 
                    $_POST['requirements'] ?? '',
                    $_POST['difficulty_level'] ?? 'moderate',
                    $_POST['best_time_to_visit'] ?? '',
                    $_POST['what_to_bring'] ?? '',
                    $_POST['tour_type'] ?? 'group',
                    json_encode($languages),
                    $_POST['age_restriction'] ?? '',
                    $_POST['accommodation_type'] ?? '',
                    $_POST['meal_plan'] ?? '',
                    $_POST['booking_deadline'] ?? 7,
                    json_encode($tour_tags),
                    $_POST['meta_title'] ?? '',
                    $_POST['meta_description'] ?? '',
                    $_POST['video_url'] ?? '',
                    $_POST['virtual_tour_url'] ?? '',
                    $_POST['status'] ?? 'active',
                    isset($_POST['featured']) ? 1 : 0
                ]);
                
                $tour_id = $conn->lastInsertId();
                
                // Handle multiple images
                if (!empty($_POST['gallery_images'])) {
                    $gallery_images = array_filter(explode("\n", trim($_POST['gallery_images'])));
                    foreach ($gallery_images as $index => $image_url) {
                        $image_url = trim($image_url);
                        if (!empty($image_url)) {
                            $stmt = $conn->prepare("INSERT INTO tour_images (tour_id, image_url, image_type, sort_order, alt_text) VALUES (?, ?, 'gallery', ?, ?)");
                            $stmt->execute([$tour_id, $image_url, $index + 1, $_POST['name'] . ' - Gallery Image ' . ($index + 1)]);
                        }
                    }
                }
                
                header('Location: tours-enhanced.php?added=1');
                exit;
                break;
                
            case 'delete':
                $stmt = $conn->prepare("UPDATE tours SET status = 'inactive' WHERE id = ?");
                $stmt->execute([$_POST['tour_id']]);
                header('Location: tours-enhanced.php?deleted=1');
                exit;
                break;
        }
    }
}

// Filter by country if specified
$country_filter = $_GET['country_id'] ?? '';
$where_clause = $country_filter ? "WHERE t.country_id = :country_id" : "WHERE 1=1";

// Get tours with country and region info
$stmt = $conn->prepare("SELECT t.*, c.name as country_name, r.name as region_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id LEFT JOIN regions r ON c.region_id = r.id $where_clause ORDER BY r.name, c.name, t.created_at DESC");
if ($country_filter) {
    $stmt->bindParam(':country_id', $country_filter);
}
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all countries for dropdown
$stmt = $conn->prepare("SELECT c.*, r.name as region_name FROM countries c JOIN regions r ON c.region_id = r.id WHERE c.status = 'active' ORDER BY r.name, c.name");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get tour for editing if edit parameter is present
$edit_tour = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_tour = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get tour images
    if ($edit_tour) {
        $stmt = $conn->prepare("SELECT * FROM tour_images WHERE tour_id = ? ORDER BY sort_order");
        $stmt->execute([$edit_tour['id']]);
        $edit_tour['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Tours Management - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Super Admin</h2>
                <p class="text-sm text-slate-600">Enhanced Tour Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="destinations.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-map-marker-alt mr-3"></i>Destinations
                </a>
                <a href="tours-enhanced.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Enhanced Tours
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="advisor-management.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user-tie mr-3"></i>Advisor Management
                </a>
                <a href="mca-management.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users-cog mr-3"></i>MCA Management
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gradient">Enhanced Tours Management</h1>
                <div class="flex gap-4">
                    <?php if (isset($_GET['edit'])): ?>
                    <a href="tours-enhanced.php" class="btn-secondary px-6 py-3 rounded-lg">Cancel Edit</a>
                    <?php endif; ?>
                    <button onclick="openAddModal()" class="btn-primary px-6 py-3 rounded-lg"><?php echo isset($_GET['edit']) ? 'Edit Tour' : 'Add New Tour'; ?></button>
                </div>
            </div>
            
            <?php if (isset($_GET['added'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                Enhanced tour added successfully!
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['updated'])): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                Enhanced tour updated successfully!
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
                <p class="text-slate-500 text-lg">No tours found. Start by adding enhanced tours to your destinations.</p>
            </div>
            <?php else: ?>
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">All Enhanced Tours & Packages</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">Tour Details</th>
                                <th class="text-left p-4">Destination</th>
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
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <img src="<?php echo $tour['image_url'] ?: '../assets/images/default-tour.jpg'; ?>" alt="<?php echo $tour['name']; ?>" class="w-16 h-16 object-cover rounded-lg mr-4">
                                        <div>
                                            <h4 class="font-bold text-slate-900"><?php echo htmlspecialchars($tour['name']); ?></h4>
                                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars(substr($tour['description'], 0, 80)); ?>...</p>
                                            <?php if ($tour['difficulty_level']): ?>
                                            <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800"><?php echo ucfirst($tour['difficulty_level']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['region_name']); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo htmlspecialchars($tour['destination']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php 
                                        echo match($tour['category']) {
                                            'cultural' => 'bg-blue-100 text-blue-800',
                                            'wildlife' => 'bg-green-100 text-green-800',
                                            'adventure' => 'bg-red-100 text-red-800',
                                            'city' => 'bg-purple-100 text-purple-800',
                                            'sports' => 'bg-orange-100 text-orange-800',
                                            'agro' => 'bg-yellow-100 text-yellow-800',
                                            'conference' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-slate-100 text-slate-800'
                                        };
                                    ?>">
                                        <?php echo ucfirst($tour['category']); ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></p>
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
                                        <button onclick="viewTour(<?php echo $tour['id']; ?>)" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Delete this tour?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
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
    </div>

    <!-- Enhanced Add Tour Modal -->
    <div id="addTourModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient"><?php echo $edit_tour ? 'Edit Enhanced Tour' : 'Add New Enhanced Tour'; ?></h3>
            </div>
            <form method="POST" class="p-6">
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
                                <option value="<?php echo $country['id']; ?>" <?php echo ($edit_tour && $edit_tour['country_id'] == $country['id']) ? 'selected' : ''; ?>>
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
                                <option value="cultural" <?php echo ($edit_tour && $edit_tour['category'] == 'cultural') ? 'selected' : ''; ?>>Cultural Tours</option>
                                <option value="wildlife" <?php echo ($edit_tour && $edit_tour['category'] == 'wildlife') ? 'selected' : ''; ?>>Wildlife Safari</option>
                                <option value="adventure" <?php echo ($edit_tour && $edit_tour['category'] == 'adventure') ? 'selected' : ''; ?>>Adventure</option>
                                <option value="city" <?php echo ($edit_tour && $edit_tour['category'] == 'city') ? 'selected' : ''; ?>>City Breaks</option>
                                <option value="sports" <?php echo ($edit_tour && $edit_tour['category'] == 'sports') ? 'selected' : ''; ?>>Sports & Recreation</option>
                                <option value="agro" <?php echo ($edit_tour && $edit_tour['category'] == 'agro') ? 'selected' : ''; ?>>Agro Tourism</option>
                                <option value="conference" <?php echo ($edit_tour && $edit_tour['category'] == 'conference') ? 'selected' : ''; ?>>Conference & Expos</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                        <textarea name="description" rows="3" placeholder="Brief tour overview for listings" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo $edit_tour ? htmlspecialchars($edit_tour['description']) : ''; ?></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Detailed Description</label>
                        <textarea name="detailed_description" rows="5" placeholder="Comprehensive tour description with highlights and detailed information" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo $edit_tour ? htmlspecialchars($edit_tour['detailed_description']) : ''; ?></textarea>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Difficulty Level</label>
                            <select name="difficulty_level" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="easy" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'easy') ? 'selected' : ''; ?>>Easy</option>
                                <option value="moderate" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'moderate') ? 'selected' : ''; ?>>Moderate</option>
                                <option value="challenging" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'challenging') ? 'selected' : ''; ?>>Challenging</option>
                                <option value="extreme" <?php echo ($edit_tour && $edit_tour['difficulty_level'] == 'extreme') ? 'selected' : ''; ?>>Extreme</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tour Type</label>
                            <select name="tour_type" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                <option value="group" <?php echo ($edit_tour && $edit_tour['tour_type'] == 'group') ? 'selected' : ''; ?>>Group Tour</option>
                                <option value="private" <?php echo ($edit_tour && $edit_tour['tour_type'] == 'private') ? 'selected' : ''; ?>>Private Tour</option>
                                <option value="custom" <?php echo ($edit_tour && $edit_tour['tour_type'] == 'custom') ? 'selected' : ''; ?>>Custom Tour</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Booking Deadline (days)</label>
                            <input type="number" name="booking_deadline" value="<?php echo $edit_tour ? $edit_tour['booking_deadline'] : '7'; ?>" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
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

                <!-- Images & Media -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Images & Media</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Main Image URL</label>
                            <input type="url" name="image_url" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['image_url']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Cover Image URL</label>
                            <input type="url" name="cover_image" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['cover_image']) : ''; ?>">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Gallery Images (one URL per line)</label>
                        <textarea name="gallery_images" rows="6" placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg&#10;https://example.com/image3.jpg" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php 
                        if ($edit_tour && isset($edit_tour['images'])) {
                            echo implode("\n", array_column($edit_tour['images'], 'image_url'));
                        }
                        ?></textarea>
                        <p class="text-xs text-slate-500 mt-1">Add multiple high-quality images to showcase your tour. Each image should be on a new line.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Video URL (YouTube/Vimeo)</label>
                            <input type="url" name="video_url" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['video_url']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Virtual Tour URL</label>
                            <input type="url" name="virtual_tour_url" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['virtual_tour_url']) : ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Tour Highlights -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Tour Highlights</h4>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Key Highlights (one per line)</label>
                        <textarea name="highlights" rows="4" placeholder="Expert local guides&#10;Small group experience&#10;Authentic cultural interactions&#10;Professional photography opportunities" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php 
                        if ($edit_tour && $edit_tour['highlights']) {
                            $highlights = json_decode($edit_tour['highlights'], true);
                            echo is_array($highlights) ? implode("\n", $highlights) : '';
                        }
                        ?></textarea>
                    </div>
                </div>

                <!-- Tour Details -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Tour Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Best Time to Visit</label>
                            <input type="text" name="best_time_to_visit" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['best_time_to_visit']) : ''; ?>" placeholder="e.g., June to October">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Age Restriction</label>
                            <input type="text" name="age_restriction" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['age_restriction']) : ''; ?>" placeholder="e.g., 12+ years">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Accommodation Type</label>
                            <input type="text" name="accommodation_type" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['accommodation_type']) : ''; ?>" placeholder="e.g., Luxury lodges and hotels">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Meal Plan</label>
                            <input type="text" name="meal_plan" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['meal_plan']) : ''; ?>" placeholder="e.g., Full board (breakfast, lunch, dinner)">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Available Languages (comma-separated)</label>
                        <input type="text" name="languages" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php 
                        if ($edit_tour && $edit_tour['languages']) {
                            $languages = json_decode($edit_tour['languages'], true);
                            echo is_array($languages) ? implode(', ', $languages) : '';
                        }
                        ?>" placeholder="English, French, Spanish, Local language">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">What to Bring</label>
                        <textarea name="what_to_bring" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Comfortable walking shoes, sun hat, sunscreen, camera..."><?php echo $edit_tour ? htmlspecialchars($edit_tour['what_to_bring']) : ''; ?></textarea>
                    </div>
                </div>

                <!-- Inclusions & Exclusions -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Inclusions & Exclusions</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Inclusions (one per line)</label>
                            <textarea name="inclusions" rows="4" placeholder="Accommodation&#10;Meals&#10;Transportation&#10;Guide" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php 
                            if ($edit_tour && $edit_tour['inclusions']) {
                                $inclusions = json_decode($edit_tour['inclusions'], true);
                                echo is_array($inclusions) ? implode("\n", $inclusions) : '';
                            }
                            ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Exclusions (one per line)</label>
                            <textarea name="exclusions" rows="4" placeholder="International flights&#10;Visa fees&#10;Personal expenses" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php 
                            if ($edit_tour && $edit_tour['exclusions']) {
                                $exclusions = json_decode($edit_tour['exclusions'], true);
                                echo is_array($exclusions) ? implode("\n", $exclusions) : '';
                            }
                            ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Itinerary -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Itinerary</h4>
                    <div id="itinerary-container">
                        <?php if ($edit_tour && $edit_tour['itinerary']): ?>
                            <?php 
                            $itinerary = json_decode($edit_tour['itinerary'], true);
                            if ($itinerary && is_array($itinerary)):
                                foreach ($itinerary as $day):
                            ?>
                            <div class="itinerary-day mb-4 p-4 border border-slate-200 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Day</label>
                                        <input type="number" name="itinerary_day[]" value="<?php echo $day['day']; ?>" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                                        <input type="text" name="itinerary_title[]" value="<?php echo htmlspecialchars($day['title']); ?>" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Activities</label>
                                    <textarea name="itinerary_activities[]" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo htmlspecialchars($day['activities']); ?></textarea>
                                </div>
                                <button type="button" onclick="removeItineraryDay(this)" class="mt-2 text-red-500 hover:text-red-700 text-sm">Remove Day</button>
                            </div>
                            <?php 
                                endforeach;
                            endif;
                        else: ?>
                        <div class="itinerary-day mb-4 p-4 border border-slate-200 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Day</label>
                                    <input type="number" name="itinerary_day[]" value="1" min="1" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Title</label>
                                    <input type="text" name="itinerary_title[]" placeholder="Day 1: Arrival" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Activities</label>
                                <textarea name="itinerary_activities[]" rows="3" placeholder="Describe the day's activities and highlights" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="addItineraryDay()" class="btn-secondary px-4 py-2 rounded-lg text-sm">Add Another Day</button>
                </div>

                <!-- SEO & Marketing -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">SEO & Marketing</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php echo $edit_tour ? htmlspecialchars($edit_tour['meta_title']) : ''; ?>" placeholder="SEO-friendly title for search engines">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2" placeholder="Brief description for search engine results"><?php echo $edit_tour ? htmlspecialchars($edit_tour['meta_description']) : ''; ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tour Tags (comma-separated)</label>
                            <input type="text" name="tour_tags" class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?php 
                            if ($edit_tour && $edit_tour['tour_tags']) {
                                $tour_tags = json_decode($edit_tour['tour_tags'], true);
                                echo is_array($tour_tags) ? implode(', ', $tour_tags) : '';
                            }
                            ?>" placeholder="safari, wildlife, adventure, cultural">
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4 text-golden-600">Requirements & Information</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Requirements</label>
                            <textarea name="requirements" rows="4" placeholder="Valid passport&#10;Yellow fever certificate&#10;Fitness level required" class="w-full border border-slate-300 rounded-lg px-4 py-2"><?php echo $edit_tour ? htmlspecialchars($edit_tour['requirements']) : ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeAddModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg"><?php echo $edit_tour ? 'Update Enhanced Tour' : 'Add Enhanced Tour'; ?></button>
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
            window.location.href = 'tours-enhanced.php?edit=' + id;
        }
        function viewTour(id) {
            window.open('../pages/tour-detail.php?id=' + id, '_blank');
        }
        function filterByCountry(countryId) {
            if (countryId) {
                window.location.href = 'tours-enhanced.php?country_id=' + countryId;
            } else {
                window.location.href = 'tours-enhanced.php';
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
        
        // Auto-open modal if edit parameter is present
        <?php if (isset($_GET['edit'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            openAddModal();
        });
        <?php endif; ?>
    </script>
</body>
</html>