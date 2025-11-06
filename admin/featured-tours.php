<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$success = '';
$error = '';

// Handle tour feature/unfeature
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'toggle_featured':
                $tour_id = $_POST['tour_id'];
                $featured = $_POST['featured'] == '1' ? 0 : 1;
                
                $stmt = $pdo->prepare("UPDATE tours SET featured = ? WHERE id = ?");
                $stmt->execute([$featured, $tour_id]);
                
                $success = $featured ? 'Tour added to featured list!' : 'Tour removed from featured list!';
                break;
        }
    }
}

// Get all tours with their featured status
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, r.name as region_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    WHERE t.status = 'active'
    ORDER BY t.featured DESC, t.created_at DESC
");
$stmt->execute();
$tours = $stmt->fetchAll();

$page_title = "Featured Tours Management";
require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Featured Tours Management</h1>
                <p class="text-slate-600">Manage which tours appear in the featured section on the homepage</p>
            </div>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <!-- Tours List -->
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">All Tours</h2>
                    <p class="text-sm text-slate-600 mt-1">Click the star to feature/unfeature tours on the homepage</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">Featured</th>
                                <th class="text-left p-4">Tour Details</th>
                                <th class="text-left p-4">Destination</th>
                                <th class="text-left p-4">Price</th>
                                <th class="text-left p-4">Duration</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-4">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="action" value="toggle_featured">
                                        <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                        <input type="hidden" name="featured" value="<?php echo $tour['featured']; ?>">
                                        <button type="submit" class="text-2xl hover:scale-110 transition-transform">
                                            <?php if ($tour['featured']): ?>
                                                <span class="text-yellow-500">★</span>
                                            <?php else: ?>
                                                <span class="text-gray-300">☆</span>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <?php 
                                        $display_image = $tour['image_url'] ?: $tour['cover_image'] ?: '../assets/images/default-tour.jpg';
                                        if (strpos($display_image, 'uploads/') === 0) {
                                            $display_image = '../' . $display_image;
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($display_image); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-16 h-16 object-cover rounded-lg mr-4" onerror="this.src='../assets/images/default-tour.jpg'; this.onerror=null;">
                                        <div>
                                            <h4 class="font-bold text-slate-900"><?php echo htmlspecialchars($tour['name']); ?></h4>
                                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars(substr($tour['description'], 0, 60)); ?>...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($tour['region_name']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></div>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold"><?php echo $tour['duration_days']; ?> days</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <a href="tours.php?edit=<?php echo $tour['id']; ?>" class="btn-secondary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="../pages/tour-detail.php?id=<?php echo $tour['id']; ?>" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/admin-footer.php'; ?>