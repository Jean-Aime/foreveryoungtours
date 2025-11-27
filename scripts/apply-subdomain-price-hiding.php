<?php
// Apply price hiding and login modal to all subdomain tour-detail.php files

$base_dir = dirname(__DIR__);

// Get all country directories
$countries_dir = $base_dir . '/countries';
$continents_dir = $base_dir . '/continents';

$all_dirs = [];

// Collect all country subdomain directories
if (is_dir($countries_dir)) {
    $country_folders = array_diff(scandir($countries_dir), ['.', '..']);
    foreach ($country_folders as $folder) {
        $tour_detail_path = $countries_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($tour_detail_path)) {
            $all_dirs[] = $tour_detail_path;
        }
    }
}

// Collect all continent subdomain directories
if (is_dir($continents_dir)) {
    $continent_folders = array_diff(scandir($continents_dir), ['.', '..']);
    foreach ($continent_folders as $folder) {
        $tour_detail_path = $continents_dir . '/' . $folder . '/pages/tour-detail.php';
        if (file_exists($tour_detail_path)) {
            $all_dirs[] = $tour_detail_path;
        }
    }
}

echo "Found " . count($all_dirs) . " tour-detail.php files to update\n\n";

foreach ($all_dirs as $file_path) {
    echo "Processing: $file_path\n";
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    
    // Replace 1: Hero section button - remove price, add login modal
    $content = preg_replace(
        '/(<button[^>]*onclick=")[^"]*("[\s\S]*?>)\s*Book from \$<\?php echo number_format\(\$tour\[\'price\'\]\); \?>/i',
        '$1openLoginModal(<?php echo $tour[\'id\']; ?>, \'<?php echo addslashes($tour[\'name\']); ?>\', \'<?php echo addslashes($tour[\'description\']); ?>\', \'<?php echo htmlspecialchars($bg_image); ?>\')$2Book This Tour',
        $content
    );
    
    // Replace 2: Remove price display from sidebar
    $content = preg_replace(
        '/(<div class="bg-white rounded-xl p-8 shadow-sm border sticky top-24">)\s*<div class="text-center mb-6">\s*<div class="text-3xl font-bold text-golden-600 mb-2">\$<\?php echo number_format\(\$tour\[\'price\'\]\); \?><\/div>\s*<p class="text-slate-600">per person<\/p>\s*<\/div>\s*(<div class="space-y-4 mb-6">)/s',
        '$1$2',
        $content
    );
    
    // Replace 3: Sidebar booking button - add login modal
    $content = preg_replace(
        '/(<button[^>]*onclick=")[^"]*("[\s\S]*?>)\s*Book This Tour\s*<\/button>/i',
        '$1openLoginModal(<?php echo $tour[\'id\']; ?>, \'<?php echo addslashes($tour[\'name\']); ?>\', \'<?php echo addslashes($tour[\'description\']); ?>\', \'<?php echo htmlspecialchars($bg_image); ?>\')$2Book This Tour</button>',
        $content
    );
    
    // Replace 4: Remove price from related tours
    $content = preg_replace(
        '/<span class="text-golden-600 font-bold text-sm">\$<\?php echo number_format\(\$related\[\'price\'\]\); \?><\/span>\s*(<a href="tour-detail\.php)/s',
        '$1',
        $content
    );
    
    // Replace 5: Add login modal HTML and script if not exists
    if (strpos($content, 'id="loginModal"') === false) {
        $modal_code = <<<'MODAL'

<!-- Login Modal -->
<div id="loginModal" class="login-modal">
    <div class="login-modal-content">
        <span class="login-modal-close" onclick="closeLoginModal()">&times;</span>
        <div class="login-modal-body">
            <div class="login-modal-tour-info">
                <img id="modalTourImage" src="" alt="Tour" class="login-modal-tour-image">
                <h3 id="modalTourName" class="login-modal-tour-name"></h3>
                <p id="modalTourDesc" class="login-modal-tour-desc"></p>
            </div>
            <div class="login-modal-message">
                <h2>Login Required</h2>
                <p>Please login or register to book this tour</p>
                <div class="login-modal-buttons">
                    <a href="../../../auth/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="login-modal-btn login-modal-btn-primary">Login</a>
                    <a href="../../../auth/register.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="login-modal-btn login-modal-btn-secondary">Register</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openLoginModal(tourId, tourName, tourDesc, tourImage) {
    document.getElementById('modalTourImage').src = tourImage;
    document.getElementById('modalTourName').textContent = tourName;
    document.getElementById('modalTourDesc').textContent = tourDesc.substring(0, 150) + '...';
    document.getElementById('loginModal').style.display = 'flex';
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

MODAL;
        
        // Remove old booking modal includes
        $content = preg_replace('/<\?php include [\'"]enhanced-booking-modal\.php[\'"]; \?>\s*/s', '', $content);
        
        // Add modal before inquiry modal or footer
        $content = preg_replace(
            '/(<\?php include [\'"]inquiry-modal\.php[\'"]; \?>)/s',
            $modal_code . '$1',
            $content
        );
    }
    
    if ($content !== $original_content) {
        file_put_contents($file_path, $content);
        echo "  ✓ Updated successfully\n";
    } else {
        echo "  - No changes needed\n";
    }
}

echo "\n✓ All tour-detail.php files processed!\n";
