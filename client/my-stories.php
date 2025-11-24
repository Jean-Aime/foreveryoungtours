<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('client');

$client_email = $_SESSION['client_email'] ?? $_SESSION['user_email'] ?? '';
$client_id = $_SESSION['user_id'] ?? null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'submit_story') {
        $title = trim($_POST['title']);
        $excerpt = trim($_POST['excerpt']);
        $content = trim($_POST['content']);
        $category_id = $_POST['category_id'];
        $tour_id = $_POST['tour_id'] ?? null;
        $trip_date = $_POST['trip_date'] ?? null;
        
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, excerpt, content, author_email, user_id, category_id, tour_id, trip_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$title, $excerpt, $content, $client_email, $client_id, $category_id, $tour_id, $trip_date]);
        
        header('Location: my-stories.php?success=1');
        exit;
    }
}

// Get user's stories
$stmt = $pdo->prepare("SELECT bp.*, bc.name as category_name, t.name as tour_name FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id LEFT JOIN tours t ON bp.tour_id = t.id WHERE bp.author_email = ? ORDER BY bp.created_at DESC");
$stmt->execute([$client_email]);
$stories = $stmt->fetchAll();

// Get categories
$categories = $pdo->query("SELECT * FROM blog_categories WHERE status = 'active' ORDER BY name")->fetchAll();

// Get user's tours
$tours = [];
if ($client_id) {
    $stmt = $pdo->prepare("SELECT DISTINCT t.id, t.name FROM tours t INNER JOIN bookings b ON t.id = b.tour_id WHERE b.user_id = ? OR b.customer_email = ?");
    $stmt->execute([$client_id, $client_email]);
    $tours = $stmt->fetchAll();
}

$page_title = 'My Stories';
$page_subtitle = 'Share your travel experiences';

include 'includes/client-header.php';
?>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-100 border border-green-400 rounded-lg p-4 mb-6" style="color: #065f46;">
    <i class="fas fa-check-circle mr-2"></i>Story submitted successfully! It will be published after admin approval.
</div>
<?php endif; ?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #FDF6E3;">
                <i class="fas fa-pen-fancy" style="color: #DAA520;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Total Stories</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo count($stories); ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #fef3c7;">
                <i class="fas fa-clock" style="color: #92400e;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Pending</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo count(array_filter($stories, fn($s) => $s['status'] === 'pending')); ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #d1fae5;">
                <i class="fas fa-check-circle" style="color: #228B22;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Published</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo count(array_filter($stories, fn($s) => $s['status'] === 'published')); ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #FDF6E3;">
                <i class="fas fa-eye" style="color: #DAA520;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Total Views</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo array_sum(array_column($stories, 'views')); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- New Story Button -->
<div class="mb-6">
    <button onclick="document.getElementById('newStoryForm').classList.toggle('hidden')" class="btn-primary px-6 py-3 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Share New Story
    </button>
</div>

<!-- New Story Form -->
<div id="newStoryForm" class="hidden bg-white rounded-lg shadow-sm p-6 mb-6">
    <h3 class="text-xl font-bold mb-4" style="color: #333;">Share Your Travel Experience</h3>
    <form method="POST">
        <input type="hidden" name="action" value="submit_story">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #333;">Story Title *</label>
                <input type="text" name="title" required class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;" placeholder="My Amazing Safari Adventure">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #333;">Category *</label>
                <select name="category_id" required class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #333;">Related Tour (Optional)</label>
                <select name="tour_id" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
                    <option value="">Select Tour</option>
                    <?php foreach ($tours as $tour): ?>
                    <option value="<?php echo $tour['id']; ?>"><?php echo htmlspecialchars($tour['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #333;">Trip Date (Optional)</label>
                <input type="date" name="trip_date" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2" style="color: #333;">Short Summary *</label>
            <textarea name="excerpt" required rows="2" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;" placeholder="Brief description of your experience (150-200 characters)"></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2" style="color: #333;">Your Story *</label>
            <textarea name="content" required rows="8" class="w-full border rounded-lg px-4 py-2" style="border-color: #ddd; color: #333;" placeholder="Share your detailed travel experience..."></textarea>
        </div>
        
        <div class="p-4 rounded-lg mb-4" style="background: #FDF6E3; border: 1px solid #DAA520;">
            <p class="text-sm" style="color: #666;">
                <i class="fas fa-info-circle mr-2" style="color: #DAA520;"></i>
                Your story will be reviewed by our team before publishing. We'll notify you once it's approved!
            </p>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg">
                <i class="fas fa-paper-plane mr-2"></i>Submit Story
            </button>
            <button type="button" onclick="document.getElementById('newStoryForm').classList.add('hidden')" class="btn-secondary px-6 py-2 rounded-lg">
                Cancel
            </button>
        </div>
    </form>
</div>

<!-- Stories List -->
<?php if (empty($stories)): ?>
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <i class="fas fa-book-open text-6xl mb-4" style="color: #ccc;"></i>
    <h3 class="text-xl font-medium mb-2" style="color: #333;">No stories yet</h3>
    <p class="mb-6" style="color: #666;">Share your travel experiences with the community!</p>
    <button onclick="document.getElementById('newStoryForm').classList.remove('hidden')" class="btn-primary px-6 py-3 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Write Your First Story
    </button>
</div>
<?php else: ?>
<div class="space-y-6">
    <?php foreach ($stories as $story): ?>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-2" style="color: #333;"><?php echo htmlspecialchars($story['title']); ?></h3>
                <div class="flex items-center gap-4 text-sm" style="color: #666;">
                    <span><i class="fas fa-folder mr-1"></i><?php echo htmlspecialchars($story['category_name']); ?></span>
                    <?php if ($story['tour_name']): ?>
                    <span><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($story['tour_name']); ?></span>
                    <?php endif; ?>
                    <span><i class="fas fa-calendar mr-1"></i><?php echo date('M j, Y', strtotime($story['created_at'])); ?></span>
                </div>
            </div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full status-badge-<?php echo $story['status']; ?>">
                <?php echo ucfirst($story['status']); ?>
            </span>
        </div>
        
        <p class="mb-4" style="color: #666;"><?php echo htmlspecialchars($story['excerpt']); ?></p>
        
        <div class="flex justify-between items-center pt-4 border-t">
            <div class="flex gap-4 text-sm" style="color: #666;">
                <span><i class="fas fa-eye mr-1"></i><?php echo $story['views']; ?> views</span>
                <span><i class="fas fa-heart mr-1"></i><?php echo $story['likes']; ?> likes</span>
            </div>
            <?php if ($story['status'] === 'published'): ?>
            <a href="../pages/blog-detail.php?id=<?php echo $story['id']; ?>" target="_blank" class="text-sm font-medium" style="color: #DAA520;">
                <i class="fas fa-external-link-alt mr-1"></i>View Published
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
.status-badge-pending { background: #fef3c7; color: #92400e; }
.status-badge-published { background: #d1fae5; color: #065f46; }
.status-badge-rejected { background: #fee2e2; color: #991b1b; }
.status-badge-draft { background: #e5e7eb; color: #374151; }
</style>

<?php include 'includes/client-footer.php'; ?>
