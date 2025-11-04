<?php
session_start();
require_once '../config/database.php';

$page_title = 'Destinations';
$page_subtitle = 'Explore African Destinations';

$stmt = $pdo->query("SELECT c.*, r.name as region_name, COUNT(t.id) as tour_count FROM countries c JOIN regions r ON c.region_id = r.id LEFT JOIN tours t ON c.id = t.country_id WHERE c.status = 'active' GROUP BY c.id ORDER BY c.name");
$destinations = $stmt->fetchAll();

include 'includes/client-header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($destinations as $dest): ?>
    <div class="nextcloud-card overflow-hidden cursor-pointer" onclick="window.location.href='../pages/country.php?slug=<?php echo $dest['slug']; ?>'">
        <img src="<?php echo htmlspecialchars($dest['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5'); ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" class="w-full h-48 object-cover">
        <div class="p-6">
            <h3 class="text-xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($dest['name']); ?></h3>
            <p class="text-slate-600 text-sm mb-4"><?php echo htmlspecialchars(substr($dest['tourism_description'], 0, 100)); ?>...</p>
            <div class="flex justify-between items-center">
                <span class="text-sm text-slate-500"><?php echo htmlspecialchars($dest['region_name']); ?></span>
                <span class="bg-golden-100 text-golden-800 px-3 py-1 rounded-full text-xs font-semibold"><?php echo $dest['tour_count']; ?> Tours</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/client-footer.php'; ?>
