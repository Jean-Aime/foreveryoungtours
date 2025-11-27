<?php
session_start();
require_once 'config.php';
require_once '../../../config/database.php';

$tour_id = $_GET['id'] ?? 0;
$tour_slug = $_GET['slug'] ?? '';

// Get tour details with all information
if ($tour_slug) {
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name 
        FROM tours t 
        LEFT JOIN countries c ON t.country_id = c.id 
        LEFT JOIN regions r ON c.region_id = r.id 
        WHERE t.slug = ? AND t.status = 'active'
    ");
    $stmt->execute([$tour_slug]);
} else {
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name 
        FROM tours t 
        LEFT JOIN countries c ON t.country_id = c.id 
        LEFT JOIN regions r ON c.region_id = r.id 
        WHERE t.id = ? AND t.status = 'active'
    ");
    $stmt->execute([$tour_id]);
}
$tour = $stmt->fetch();

// Get related tours from same country only
$related_stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE t.status = 'active' AND t.id != ? 
    AND t.country_id = ? 
    ORDER BY t.featured DESC, RAND() 
    LIMIT 3
");
$related_stmt->execute([$tour_id, $tour['country_id']]);
$related_tours = $related_stmt->fetchAll();

if (!$tour) {
    header('Location: /');
    exit;
}

$page_title = htmlspecialchars($tour['name']) . " - iForYoungTours";
$page_description = htmlspecialchars(substr($tour['description'], 0, 160));
$css_path = '../../../assets/css/modern-styles.css';

// Header removed for subdomain
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/modal.css">
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
    <style>
        .login-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); align-items: center; justify-content: center; }
        .login-modal-content { background: white; padding: 2rem; border-radius: 1rem; max-width: 500px; width: 90%; position: relative; }
        .login-modal-close { position: absolute; right: 1rem; top: 1rem; font-size: 2rem; cursor: pointer; color: #666; }
        .login-modal-close:hover { color: #000; }
        .login-modal-tour-image { width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1rem; }
        .login-modal-tour-name { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
        .login-modal-tour-desc { color: #666; margin-bottom: 1.5rem; }
        .login-modal-message h2 { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
        .login-modal-message p { color: #666; margin-bottom: 1.5rem; }
        .login-modal-buttons { display: flex; gap: 1rem; }
        .login-modal-btn { flex: 1; padding: 0.75rem; border-radius: 0.5rem; text-align: center; font-weight: 600; text-decoration: none; }
        .login-modal-btn-primary { background: #f59e0b; color: white; }
        .login-modal-btn-primary:hover { background: #d97706; }
        .login-modal-btn-secondary { background: #e5e7eb; color: #374151; }
        .login-modal-btn-secondary:hover { background: #d1d5db; }
    </style>
    <style>
        body { background-color: #f8fafc; color: #1e293b; }
        .text-golden-600 { color: #d97706; }
        .bg-golden-500 { background-color: #f59e0b; }
        .bg-golden-600 { background-color: #d97706; }
        .border-golden-500 { border-color: #f59e0b; }
        .text-slate-900 { color: #0f172a !important; }
        .text-slate-600 { color: #475569 !important; }
        .text-slate-700 { color: #334155 !important; }
    </style>
</head>
<body>

<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <section>
        <!-- Breadcrumb -->
        
        
        <!-- Title and Meta -->
        <?php
        
        // Get the appropriate fallback image
        $fallback_image = getImageUrl('assets/images/default-tour.jpg');

        $bg_image = getImageUrl($tour['cover_image'] ?: $tour['image_url'], $fallback_image);
        ?>
        <div class="relative w-full mb-12" style="background-image: url('<?php echo htmlspecialchars($bg_image); ?>'); background-size: cover; background-position: center; min-height: 400px;">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/80"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-600/40 to-orange-600/40"></div>
            <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    <?php echo htmlspecialchars($tour['name']); ?>
                </h1>
                
                <div class="flex flex-wrap items-center gap-6 text-sm text-white mb-8">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo htmlspecialchars($tour['country_name'] . ', ' . $tour['region_name']); ?>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php echo htmlspecialchars($tour['duration'] ?: $tour['duration_days'] . ' days'); ?>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <?php echo ucfirst($tour['category']); ?>
                    </div>
                    <div class="flex items-center text-yellow-400">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-white">4.9 (127 reviews)</span>
                    </div>
                </div>
                
                <p class="text-xl text-white leading-relaxed mb-8">
                    <?php echo htmlspecialchars(substr($tour['description'], 0, 200)) . '...'; ?>
                </p>
                
                <div class="flex flex-wrap gap-4">
                    <button onclick="<?php echo isset($_SESSION['user_id']) ? 'openInquiryModal(' . $tour['id'] . ', \'' . addslashes($tour['name']) . '\')' : 'openLoginModal()'; ?>" 
                            class="bg-golden-500 hover:bg-golden-600 text-black px-8 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        Book This Tour
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </button>
                    <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Gallery -->
    <?php 
    $gallery_images = [];
    
    if ($tour['image_url']) $gallery_images[] = $tour['image_url'];
    if ($tour['cover_image']) $gallery_images[] = $tour['cover_image'];
    
    if ($tour['gallery']) {
        $gallery_data = json_decode($tour['gallery'], true);
        if ($gallery_data) {
            $gallery_images = array_merge($gallery_images, $gallery_data);
        }
    }
    
    if ($tour['images']) {
        $images_data = json_decode($tour['images'], true);
        if ($images_data) {
            $gallery_images = array_merge($gallery_images, $images_data);
        }
    }
    
    $gallery_images = array_unique(array_filter($gallery_images));
    
    if (!empty($gallery_images)): 
    ?>
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border">
            <h2 class="text-2xl font-bold mb-6">Tour Gallery</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($gallery_images as $index => $image): ?>
                <?php $image_src = getImageUrl($image); ?>
                <div class="relative overflow-hidden rounded-lg cursor-pointer aspect-square">
                    <img src="<?php echo htmlspecialchars($image_src); ?>"
                         alt="<?php echo htmlspecialchars($tour['name']); ?> - Image <?php echo $index + 1; ?>"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                         onerror="this.src='<?= getImageUrl('assets/images/default-tour.jpg') ?>'; this.onerror=null;">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Tour Details -->
            <div class="lg:col-span-2">
                <!-- Trip Overview -->
                <div class="bg-white rounded-xl p-8 mb-8 shadow-sm border">
                    <h2 class="text-2xl font-bold mb-6">Trip Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-golden-600 mr-3"></i>
                                <span><strong>Duration:</strong> <?php echo htmlspecialchars($tour['duration'] ?: $tour['duration_days'] . ' days'); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-golden-600 mr-3"></i>
                                <span><strong>Group Size:</strong> <?php echo $tour['min_participants']; ?>-<?php echo $tour['max_participants']; ?> people</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-golden-600 mr-3"></i>
                                <span><strong>Category:</strong> <?php echo ucfirst($tour['category']); ?></span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-golden-600 mr-3"></i>
                                <span><strong>Destination:</strong> <?php echo htmlspecialchars($tour['destination'] . ', ' . $tour['country_name']); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-star text-golden-600 mr-3"></i>
                                <span><strong>Difficulty:</strong> <?php echo ucfirst($tour['difficulty_level'] ?? 'Moderate'); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-golden-600 mr-3"></i>
                                <span><strong>Best Time:</strong> <?php echo htmlspecialchars($tour['best_time_to_visit'] ?? 'Year-round'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold mb-3">Description</h3>
                        <p class="text-slate-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($tour['detailed_description'] ?: $tour['description'])); ?></p>
                    </div>
                    <?php if ($tour['highlights']): ?>
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-bold mb-3">Tour Highlights</h3>
                        <?php 
                        $highlights = json_decode($tour['highlights'], true);
                        if ($highlights && is_array($highlights)):
                        ?>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <?php foreach ($highlights as $highlight): ?>
                            <li class="flex items-start">
                                <i class="fas fa-star text-golden-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($highlight); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Itinerary -->
                <?php if ($tour['itinerary']): ?>
                <div class="bg-white rounded-xl p-8 mb-8 shadow-sm border">
                    <h2 class="text-2xl font-bold mb-6">Itinerary</h2>
                    <?php 
                    $itinerary = json_decode($tour['itinerary'], true);
                    if ($itinerary && is_array($itinerary)):
                    ?>
                    <div class="space-y-6">
                        <?php foreach ($itinerary as $day): ?>
                        <div class="border-l-4 border-golden-500 pl-6">
                            <h3 class="text-lg font-bold text-slate-900">Day <?php echo $day['day']; ?>: <?php echo htmlspecialchars($day['title']); ?></h3>
                            <p class="text-slate-600 mt-2"><?php echo htmlspecialchars($day['activities']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Inclusions & Exclusions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <?php if ($tour['inclusions']): ?>
                    <div class="bg-white rounded-xl p-6 shadow-sm border">
                        <h3 class="text-xl font-bold mb-4 text-green-600">What's Included</h3>
                        <?php 
                        $inclusions = json_decode($tour['inclusions'], true);
                        if ($inclusions && is_array($inclusions)):
                        ?>
                        <ul class="space-y-2">
                            <?php foreach ($inclusions as $inclusion): ?>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($inclusion); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($tour['exclusions']): ?>
                    <div class="bg-white rounded-xl p-6 shadow-sm border">
                        <h3 class="text-xl font-bold mb-4 text-red-600">What's Not Included</h3>
                        <?php 
                        $exclusions = json_decode($tour['exclusions'], true);
                        if ($exclusions && is_array($exclusions)):
                        ?>
                        <ul class="space-y-2">
                            <?php foreach ($exclusions as $exclusion): ?>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($exclusion); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>



                <!-- Requirements -->
                <?php if ($tour['requirements']): ?>
                <div class="bg-white rounded-xl p-8 shadow-sm border">
                    <h2 class="text-2xl font-bold mb-4">Requirements & Recommendations</h2>
                    <p class="text-slate-600"><?php echo nl2br(htmlspecialchars($tour['requirements'])); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Booking Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl p-8 shadow-sm border sticky top-24">
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Duration:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($tour['duration'] ?: $tour['duration_days'] . ' days'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Max Group:</span>
                            <span class="font-semibold"><?php echo $tour['max_participants']; ?> people</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Category:</span>
                            <span class="font-semibold"><?php echo ucfirst($tour['category']); ?></span>
                        </div>
                    </div>

                    <button onclick="<?php echo isset($_SESSION['user_id']) ? 'openInquiryModal(' . $tour['id'] . ', \'' . addslashes($tour['name']) . '\')' : 'openLoginModal()'; ?>" 
                            class="w-full py-4 bg-yellow-500 text-black rounded-lg font-bold text-lg mb-3 hover:bg-yellow-600 transition-colors">Book This Tour</button>
                    
                    <button onclick="openInquiryModal(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>')" 
                            class="block w-full py-4 rounded-lg font-bold text-lg mb-3 text-center border-2 border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors">
                        Custom Inquiry
                    </button>
                    
                    <?php 
                    // Generate country subdomain URL based on country slug
                    $country_subdomain = str_replace('visit-', '', $tour['country_slug']);
                    $packages_url = BASE_URL . "/pages/packages.php";
                    ?>
                    <a href="<?php echo $packages_url; ?>" 
                       class="block w-full py-3 rounded-lg font-semibold text-center border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors mb-3">
                        View All Tours
                    </a>

                    <div class="text-center">
                        <p class="text-sm text-slate-500 mb-2">Need help? Contact our experts</p>
                        <a href="tel:+1234567890" class="text-golden-600 font-semibold">+1 (234) 567-890</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Tours -->
    <?php if (!empty($related_tours)): ?>
    <section class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl p-8 shadow-sm border">
            <h2 class="text-2xl font-bold mb-6">Related Tours</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($related_tours as $related): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-sm border hover:shadow-md transition-shadow">
                    <?php $related_image = getImageUrl($related['cover_image'] ?: $related['image_url']); ?>
                    <img src="<?php echo htmlspecialchars($related_image); ?>"
                         alt="<?php echo htmlspecialchars($related['name']); ?>"
                         class="w-full h-32 object-cover"
                         onerror="this.src='<?= getImageUrl('assets/images/default-tour.jpg') ?>'; this.onerror=null;">
                    <div class="p-4">
                        <h3 class="font-bold text-sm mb-2"><?php echo htmlspecialchars($related['name']); ?></h3>
                        <p class="text-xs text-slate-600 mb-2"><?php echo htmlspecialchars($related['country_name']); ?></p>
                        <div class="flex justify-between items-center">
                            <a href="<?php echo BASE_URL; ?>/tour/<?php echo $related['slug']; ?>" class="text-xs bg-slate-200 hover:bg-slate-300 px-3 py-1 rounded transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Login Modal -->
<div id="loginModal" class="login-modal">
    <div class="login-modal-content">
        <span class="login-modal-close" onclick="closeLoginModal()">&times;</span>
        <div class="bg-slate-50 rounded-lg overflow-hidden mb-6">
            <img src="<?php echo htmlspecialchars($bg_image); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-40 object-cover">
            <div class="p-4">
                <h4 class="font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h4>
                <p class="text-sm text-slate-600"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)) . '...'; ?></p>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2">Login Required</h2>
        <p class="text-slate-600 mb-6">Please login or create an account to book this tour.</p>
        <div class="space-y-3">
            <a href="../../../auth/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="login-modal-btn login-modal-btn-primary block text-center">Login</a>
            <a href="../../../auth/register.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="login-modal-btn login-modal-btn-secondary block text-center">Create Account</a>
        </div>
    </div>
</div>

<script>
function openLoginModal() {
    const modal = document.getElementById('loginModal');
    if (!modal) {
        console.error('Modal not found!');
        return;
    }
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target == modal) {
        closeLoginModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLoginModal();
    }
});
</script>

<?php include 'inquiry-modal.php'; ?>
<?php include '../../../includes/footer.php'; ?>

</body>
</html>