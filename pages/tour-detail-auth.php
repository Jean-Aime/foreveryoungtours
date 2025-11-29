<?php
session_start();
require_once 'config.php';
require_once '../config/database.php';

$tour_id = $_GET['id'] ?? null;
$tour_slug = $_GET['slug'] ?? null;

if ($tour_slug) {
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name, t.category as category_name 
        FROM tours t 
        LEFT JOIN countries c ON t.country_id = c.id 
        LEFT JOIN regions r ON c.region_id = r.id 
        WHERE t.slug = ? AND t.status = 'active'
    ");
    $stmt->execute([$tour_slug]);
} else {
    $stmt = $pdo->prepare("
        SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name, t.category as category_name 
        FROM tours t 
        LEFT JOIN countries c ON t.country_id = c.id 
        LEFT JOIN regions r ON c.region_id = r.id 
        WHERE t.id = ? AND t.status = 'active'
    ");
    $stmt->execute([$tour_id]);
}
$tour = $stmt->fetch();

if (!$tour) {
    header('Location: packages.php');
    exit;
}

$related_stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE t.status = 'active' AND t.id != ? 
    AND (t.country_id = ? OR t.category = ?) 
    ORDER BY t.featured DESC, RAND() 
    LIMIT 3
");
$related_stmt->execute([$tour['id'], $tour['country_id'], $tour['category']]);
$related_tours = $related_stmt->fetchAll();

$page_title = htmlspecialchars($tour['name']) . " - iForYoungTours";
$page_description = htmlspecialchars(substr($tour['description'], 0, 160));
$css_path = '../assets/css/modern-styles.css';

include '../includes/header.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.book-tour-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const tourId = this.getAttribute('data-tour-id');
            const tourName = this.getAttribute('data-tour-name');
            const tourImage = this.getAttribute('data-tour-image');
            const tourDesc = this.getAttribute('data-tour-desc');
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            
            if (isLoggedIn) {
                if (typeof openBookingModal === 'function') {
                    openBookingModal(tourId, tourName, 0, '');
                }
            } else {
                showAuthModal(tourName, tourImage, tourDesc);
            }
        });
    });
});

function showAuthModal(tourName, tourImage, tourDesc) {
    const modal = document.createElement('div');
    modal.id = 'authModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    const currentUrl = window.location.href;
    modal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <button onclick="document.getElementById('authModal').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            <img src="${tourImage}" class="w-full h-40 object-cover rounded-lg mb-4" onerror="this.src='https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=500&q=60'">
            <h3 class="text-xl font-bold text-gray-900 mb-2">${tourName}</h3>
            <p class="text-sm text-gray-600 mb-6">${tourDesc}</p>
            <p class="text-gray-700 mb-6 font-semibold">Please login or create an account to book this tour.</p>
            <div class="space-y-3">
                <a href="auth/login.php?redirect=${encodeURIComponent(currentUrl)}" class="block w-full bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg font-semibold text-center transition-colors">Login</a>
                <a href="auth/register.php?redirect=${encodeURIComponent(currentUrl)}" class="block w-full border-2 border-yellow-500 text-yellow-600 hover:bg-yellow-50 py-3 rounded-lg font-semibold text-center transition-colors">Create Account</a>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}
</script>

<?php include '../includes/footer.php'; ?>
