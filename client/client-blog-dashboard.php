<?php

require_once 'config.php';
session_start();
$page_title = "My Stories - Client Dashboard";
$page_description = "Manage your travel stories and submissions";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Check if user is logged in as client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's blog posts
$stmt = $conn->prepare("
    SELECT bp.*, bc.name as category_name, bc.color as category_color
    FROM blog_posts bp 
    LEFT JOIN blog_categories bc ON bp.category_id = bc.id
    WHERE bp.user_id = ?
    ORDER BY bp.created_at DESC
");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM blog_posts WHERE user_id = ? GROUP BY status");
$stmt->execute([$user_id]);
$stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-8 bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                My <span class="text-gradient">Travel Stories</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Manage your submitted stories and share new adventures
            </p>
            <a href="blog-submit.php" class="btn-primary px-8 py-4 rounded-2xl font-semibold">Share New Story</a>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="nextcloud-card p-6 text-center">
                <div class="text-3xl font-bold text-gradient mb-2"><?php echo array_sum($stats); ?></div>
                <div class="text-gray-600">Total Stories</div>
            </div>
            <div class="nextcloud-card p-6 text-center">
                <div class="text-3xl font-bold text-yellow-600 mb-2"><?php echo $stats['pending'] ?? 0; ?></div>
                <div class="text-gray-600">Pending Review</div>
            </div>
            <div class="nextcloud-card p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2"><?php echo $stats['published'] ?? 0; ?></div>
                <div class="text-gray-600">Published</div>
            </div>
            <div class="nextcloud-card p-6 text-center">
                <div class="text-3xl font-bold text-gray-600 mb-2"><?php echo $stats['draft'] ?? 0; ?></div>
                <div class="text-gray-600">Drafts</div>
            </div>
        </div>

        <!-- Stories List -->
        <div class="nextcloud-card">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-900">Your Stories</h2>
            </div>
            
            <?php if (empty($posts)): ?>
            <div class="p-12 text-center">
                <div class="text-slate-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-600 mb-2">No stories yet</h3>
                <p class="text-slate-500 mb-6">Share your first travel adventure with the community!</p>
                <a href="blog-submit.php" class="btn-primary px-6 py-3 rounded-lg">Share Your Story</a>
            </div>
            <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($posts as $post): ?>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <img src="<?php echo htmlspecialchars($post['featured_image'] ?: '../assets/images/default-blog.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-20 h-20 object-cover rounded-lg">
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($post['title']); ?></h3>
                                    <p class="text-gray-600 mb-3"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 120)) . '...'; ?></p>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                        <?php if ($post['category_name']): ?>
                                        <span class="px-2 py-1 rounded text-xs text-white" style="background-color: <?php echo $post['category_color']; ?>">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($post['status'] === 'published'): ?>
                                        <span><?php echo number_format($post['views']); ?> views</span>
                                        <span><?php echo number_format($post['likes']); ?> likes</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium <?php 
                                        echo match($post['status']) {
                                            'published' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                    
                                    <?php if ($post['status'] === 'published'): ?>
                                    <a href="blog-post.php?slug=<?php echo $post['slug']; ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Story →
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($post['featured']): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Featured</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($post['status'] === 'rejected'): ?>
                            <div class="mt-3 p-3 bg-red-50 rounded-lg">
                                <p class="text-red-800 text-sm">
                                    <strong>Story not approved:</strong> Please review our guidelines and consider resubmitting with improvements.
                                </p>
                            </div>
                            <?php elseif ($post['status'] === 'pending'): ?>
                            <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                                <p class="text-yellow-800 text-sm">
                                    <strong>Under Review:</strong> Your story is being reviewed by our team. This usually takes 2-3 business days.
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Guidelines -->
        <div class="mt-8 nextcloud-card">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Story Guidelines</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">What makes a great story:</h4>
                        <ul class="space-y-1">
                            <li>• Personal experiences and insights</li>
                            <li>• Helpful tips for other travelers</li>
                            <li>• High-quality photos</li>
                            <li>• Detailed descriptions of places visited</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Review process:</h4>
                        <ul class="space-y-1">
                            <li>• Stories are reviewed within 2-3 business days</li>
                            <li>• We check for quality and authenticity</li>
                            <li>• Minor edits may be made for clarity</li>
                            <li>• You'll be notified of the decision</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>