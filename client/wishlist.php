<?php

require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

$page_title = 'My Wishlist';
$page_subtitle = 'Your Saved Tours';

$client_id = $_SESSION['user_id'] ?? null;

// Get wishlist tours
$wishlist_tours = [];
if ($client_id) {
    try {
        $stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.id IN (SELECT tour_id FROM wishlist WHERE user_id = ?) AND t.status = 'active'");
        $stmt->execute([$client_id]);
        $wishlist_tours = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Wishlist table doesn't exist yet
        $wishlist_tours = [];
    }
}

include 'includes/client-header.php';
?>

<?php if (empty($wishlist_tours)): ?>
<div class="text-center py-12">
    <i class="fas fa-heart text-6xl text-slate-300 mb-4"></i>
    <h3 class="text-2xl font-bold text-slate-900 mb-2">Your wishlist is empty</h3>
    <p class="text-slate-600 mb-6">Start adding tours you love!</p>
    <a href="tours.php" class="btn-primary px-6 py-3 rounded-lg">Browse Tours</a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($wishlist_tours as $tour): ?>
    <div class="nextcloud-card overflow-hidden">
        <img src="<?php echo htmlspecialchars($tour['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5'); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-48 object-cover">
        <div class="p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
            <p class="text-slate-600 text-sm mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 80)); ?>...</p>
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm text-slate-500"><?php echo $tour['duration_days']; ?> days</span>
                <span class="text-lg font-bold text-primary-gold">$<?php echo number_format($tour['price']); ?></span>
            </div>
            <div class="flex gap-2">
                <a href="../pages/tour-detail.php?id=<?php echo $tour['id']; ?>" class="btn-secondary flex-1 text-center py-2 rounded-lg text-sm">View</a>
                <a href="../pages/booking-form.php?tour_id=<?php echo $tour['id']; ?>&tour_name=<?php echo urlencode($tour['name']); ?>&price=<?php echo $tour['price']; ?>" class="btn-primary flex-1 text-center py-2 rounded-lg text-sm">Book</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include 'includes/client-footer.php'; ?>
