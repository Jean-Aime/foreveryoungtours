<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Get all active tours
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.status = 'active' ORDER BY t.featured DESC, t.created_at DESC");
$stmt->execute();
$tours = $stmt->fetchAll();

$page_title = 'Marketing Tools';
$page_subtitle = 'Share tours on your Facebook groups and earn commissions';

include 'includes/advisor-header.php';
?>

<div class="mb-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fab fa-facebook-f text-2xl"></i>
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold mb-2">Facebook Group Marketing</h2>
            <p class="text-blue-100 text-sm">Share FYT tour packages in your Facebook groups. Each tour includes your unique referral link to track commissions.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    <?php foreach ($tours as $tour): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php 
        $image = !empty($tour['image_url']) ? '../' . ltrim($tour['image_url'], '/') : '../assets/images/africa.png';
        $referral_url = 'http://' . $_SERVER['HTTP_HOST'] . '/foreveryoungtours/pages/tour-details.php?id=' . $tour['id'] . '&ref=ADV' . $advisor_id;
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
            
            <div class="bg-green-50 rounded-lg p-3 mb-4 text-center">
                <span class="text-green-700 text-sm">Your Commission:</span>
                <span class="font-bold text-green-600 text-lg ml-2">$<?php echo number_format($tour['price'] * 0.10); ?></span>
            </div>
            
            <button onclick="shareToFacebook(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>', '<?php echo addslashes($tour['country_name']); ?>', <?php echo $tour['price']; ?>, <?php echo $tour['duration_days']; ?>, '<?php echo addslashes($referral_url); ?>')" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:shadow-lg transition-all mb-2">
                <i class="fab fa-facebook-f mr-2"></i>Share on Facebook
            </button>
            
            <button onclick="copyLink('<?php echo $referral_url; ?>')" class="w-full px-4 py-2 border border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-colors">
                <i class="fas fa-link mr-2"></i>Copy Link
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="mt-8 bg-white rounded-xl p-6 border border-slate-200">
    <h3 class="text-lg font-bold text-slate-900 mb-4"><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Marketing Tips</h3>
    <div class="grid md:grid-cols-2 gap-4 text-sm text-slate-600">
        <div class="flex gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <p>Post in groups where members are interested in travel and African destinations</p>
        </div>
        <div class="flex gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <p>Share personal experiences or testimonials about the tours</p>
        </div>
        <div class="flex gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <p>Post during peak engagement times (evenings and weekends)</p>
        </div>
        <div class="flex gap-3">
            <i class="fas fa-check-circle text-green-500 mt-1"></i>
            <p>Use engaging images and compelling descriptions</p>
        </div>
    </div>
</div>

<script>
function shareToFacebook(tourId, tourName, country, price, days, url) {
    const text = `🌍 Discover ${tourName}!\n\n📍 ${country}\n💰 $${price.toLocaleString()}\n⏱️ ${days} days\n\n✨ Book your African adventure today!\n\n${url}`;
    
    const fbUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
    window.open(fbUrl, '_blank', 'width=600,height=400');
}

function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('✓ Referral link copied! Paste it in your Facebook group.');
    }).catch(() => {
        prompt('Copy this link:', url);
    });
}
</script>

<?php include 'includes/advisor-footer.php'; ?>
