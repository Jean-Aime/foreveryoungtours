<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get all available tours
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.status = 'active' ORDER BY t.featured DESC, t.created_at DESC");
$stmt->execute();
$tours = $stmt->fetchAll();

$page_title = 'Available Tours';
$page_subtitle = 'Browse and share amazing African tours with your clients';

include 'includes/advisor-header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($tours as $tour): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow">
        <?php 
        $image = !empty($tour['image_url']) ? '../' . ltrim($tour['image_url'], '/') : '../assets/images/africa.png';
        ?>
        <div class="h-48 bg-cover bg-center relative" style="background-image: url('<?php echo htmlspecialchars($image); ?>'); background-color: #e5e7eb;">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <?php if ($tour['featured']): ?>
            <span class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold">Featured</span>
            <?php endif; ?>
            <div class="absolute bottom-4 left-4 text-white">
                <h3 class="font-bold text-xl"><?php echo htmlspecialchars($tour['name']); ?></h3>
                <p class="text-sm opacity-90"><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($tour['country_name']); ?></p>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-3xl font-bold text-slate-900">$<?php echo number_format($tour['price']); ?></span>
                <span class="text-sm text-slate-600"><i class="fas fa-clock mr-1"></i><?php echo $tour['duration_days']; ?> days</span>
            </div>
            
            <p class="text-slate-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
            
            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                <div class="bg-slate-50 rounded-lg p-2">
                    <span class="text-slate-500">Group:</span>
                    <span class="font-semibold ml-1"><?php echo $tour['max_participants']; ?> max</span>
                </div>
                <div class="bg-green-50 rounded-lg p-2">
                    <span class="text-green-700">Commission:</span>
                    <span class="font-semibold text-green-600 ml-1">10%</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="../pages/tour-details.php?id=<?php echo $tour['id']; ?>" target="_blank" class="flex-1 text-center px-4 py-2 border border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-colors">
                    <i class="fas fa-eye mr-1"></i>View
                </a>
                <button onclick="shareTour(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>')" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                    <i class="fas fa-share mr-1"></i>Share
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
function shareTour(tourId, tourName) {
    const url = window.location.origin + '/foreveryoungtours/pages/tour-details.php?id=' + tourId + '&ref=ADV<?php echo $advisor_id; ?>';
    const message = encodeURIComponent('Check out this amazing tour: ' + tourName + '\n\n' + url);
    
    if (confirm('Share via WhatsApp?')) {
        window.open('https://wa.me/?text=' + message, '_blank');
    }
}
</script>

<?php include 'includes/advisor-footer.php'; ?>
