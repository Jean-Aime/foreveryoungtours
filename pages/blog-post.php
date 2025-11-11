<?php
$page_title = "Travel Story";
$page_description = "Read amazing travel experiences and adventures from fellow African explorers.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: blog.php');
    exit;
}

// Get blog post
$stmt = $conn->prepare("
    SELECT bp.*, bc.name as category_name, bc.slug as category_slug, bc.color as category_color,
           c.name as country_name, t.name as tour_name
    FROM blog_posts bp 
    LEFT JOIN blog_categories bc ON bp.category_id = bc.id
    LEFT JOIN countries c ON bp.country_id = c.id
    LEFT JOIN tours t ON bp.tour_id = t.id
    WHERE bp.slug = ? AND bp.status = 'published'
");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Update view count
$stmt = $conn->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
$stmt->execute([$post['id']]);

// Get post tags
$stmt = $conn->prepare("
    SELECT bt.name, bt.slug 
    FROM blog_tags bt 
    JOIN blog_post_tags bpt ON bt.id = bpt.tag_id 
    WHERE bpt.post_id = ?
");
$stmt->execute([$post['id']]);
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get related posts
$stmt = $conn->prepare("
    SELECT bp.*, bc.name as category_name, bc.color as category_color
    FROM blog_posts bp 
    LEFT JOIN blog_categories bc ON bp.category_id = bc.id
    WHERE bp.status = 'published' AND bp.id != ? AND bp.category_id = ?
    ORDER BY bp.published_at DESC LIMIT 3
");
$stmt->execute([$post['id'], $post['category_id']]);
$related_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get comments
$stmt = $conn->prepare("
    SELECT * FROM blog_comments 
    WHERE post_id = ? AND status = 'approved' AND parent_id IS NULL
    ORDER BY created_at DESC
");
$stmt->execute([$post['id']]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comment submission
$comment_success = '';
$comment_error = '';

if ($_POST && isset($_POST['submit_comment'])) {
    $comment_name = trim($_POST['comment_name'] ?? '');
    $comment_email = trim($_POST['comment_email'] ?? '');
    $comment_content = trim($_POST['comment_content'] ?? '');
    
    if (empty($comment_name) || empty($comment_email) || empty($comment_content)) {
        $comment_error = "All fields are required.";
    } elseif (!filter_var($comment_email, FILTER_VALIDATE_EMAIL)) {
        $comment_error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO blog_comments (post_id, author_name, author_email, content, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        
        if ($stmt->execute([$post['id'], $comment_name, $comment_email, $comment_content])) {
            $comment_success = "Thank you for your comment! It will be published after review.";
        } else {
            $comment_error = "Sorry, there was an error submitting your comment.";
        }
    }
}

// Handle like
if ($_POST && isset($_POST['like_post'])) {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $conn->prepare("INSERT IGNORE INTO blog_likes (post_id, user_ip, created_at) VALUES (?, ?, NOW())");
    if ($stmt->execute([$post['id'], $user_ip])) {
        $stmt = $conn->prepare("UPDATE blog_posts SET likes = (SELECT COUNT(*) FROM blog_likes WHERE post_id = ?) WHERE id = ?");
        $stmt->execute([$post['id'], $post['id']]);
    }
}

// Get current like count
$stmt = $conn->prepare("SELECT likes FROM blog_posts WHERE id = ?");
$stmt->execute([$post['id']]);
$current_likes = $stmt->fetchColumn();

// Check if user already liked
$user_ip = $_SERVER['REMOTE_ADDR'];
$stmt = $conn->prepare("SELECT id FROM blog_likes WHERE post_id = ? AND user_ip = ?");
$stmt->execute([$post['id'], $user_ip]);
$user_liked = $stmt->fetch() ? true : false;

$page_title = htmlspecialchars($post['title']) . " - iForYoungTours Blog";
$page_description = htmlspecialchars($post['excerpt']);

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-8 bg-gradient-to-br from-slate-900 to-blue-900 relative overflow-hidden">
    <?php if ($post['featured_image']): ?>
    <div class="absolute inset-0">
        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-full object-cover opacity-30">
    </div>
    <?php endif; ?>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center text-white">
            <?php if ($post['category_name']): ?>
            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold mb-4" style="background-color: <?php echo $post['category_color']; ?>">
                <?php echo htmlspecialchars($post['category_name']); ?>
            </span>
            <?php endif; ?>
            
            <h1 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">
                <?php echo htmlspecialchars($post['title']); ?>
            </h1>
            
            <p class="text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($post['excerpt']); ?>
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8">
                <div class="flex items-center space-x-3">
                    <img src="<?php echo htmlspecialchars($post['author_avatar'] ?: '../assets/images/default-avatar.jpg'); ?>" alt="<?php echo htmlspecialchars($post['author_name']); ?>" class="w-12 h-12 rounded-full border-2 border-white">
                    <div class="text-left">
                        <div class="font-semibold"><?php echo htmlspecialchars($post['author_name']); ?></div>
                        <div class="text-gray-300 text-sm">Traveler</div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6 text-gray-300">
                    <span><?php echo date('M j, Y', strtotime($post['published_at'])); ?></span>
                    <span>•</span>
                    <span><?php echo number_format($post['views']); ?> views</span>
                    <span>•</span>
                    <span><?php echo number_format($current_likes); ?> likes</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Article Content -->
            <article class="lg:w-3/4">
                <div class="prose prose-lg max-w-none">
                    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                </div>
                
                <!-- Trip Details -->
                <?php if ($post['country_name'] || $post['tour_name'] || $post['trip_date']): ?>
                <div class="mt-12 p-6 bg-slate-50 rounded-2xl">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Trip Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php if ($post['country_name']): ?>
                        <div>
                            <div class="text-sm text-gray-500">Destination</div>
                            <div class="font-semibold"><?php echo htmlspecialchars($post['country_name']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($post['tour_name']): ?>
                        <div>
                            <div class="text-sm text-gray-500">Tour Package</div>
                            <div class="font-semibold"><?php echo htmlspecialchars($post['tour_name']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($post['trip_date']): ?>
                        <div>
                            <div class="text-sm text-gray-500">Trip Date</div>
                            <div class="font-semibold"><?php echo date('M Y', strtotime($post['trip_date'])); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                        <a href="blog.php?tag=<?php echo $tag['slug']; ?>" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">
                            #<?php echo htmlspecialchars($tag['name']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Like & Share -->
                <div class="mt-8 flex items-center justify-between p-6 bg-slate-50 rounded-2xl">
                    <div class="flex items-center space-x-4">
                        <form method="POST" class="inline">
                            <button type="submit" name="like_post" <?php echo $user_liked ? 'disabled' : ''; ?> class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-colors <?php echo $user_liked ? 'bg-red-100 text-red-600' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
                                <svg class="w-5 h-5" fill="<?php echo $user_liked ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span><?php echo number_format($current_likes); ?> <?php echo $user_liked ? 'Liked' : 'Like'; ?></span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Share:</span>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($post['title']); ?>&url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Comments (<?php echo count($comments); ?>)</h3>
                    
                    <!-- Comment Form -->
                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Leave a Comment</h4>
                        
                        <?php if ($comment_success): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <p class="text-green-800"><?php echo $comment_success; ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($comment_error): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <p class="text-red-800"><?php echo $comment_error; ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                                    <input type="text" name="comment_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="comment_email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comment *</label>
                                <textarea name="comment_content" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" name="submit_comment" class="btn-primary px-6 py-2 rounded-lg">Post Comment</button>
                        </form>
                    </div>
                    
                    <!-- Comments List -->
                    <?php if (!empty($comments)): ?>
                    <div class="space-y-6">
                        <?php foreach ($comments as $comment): ?>
                        <div class="p-6 bg-white border border-gray-200 rounded-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold"><?php echo strtoupper(substr($comment['author_name'], 0, 1)); ?></span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($comment['author_name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No comments yet. Be the first to share your thoughts!</p>
                    <?php endif; ?>
                </div>
            </article>
            
            <!-- Sidebar -->
            <aside class="lg:w-1/4">
                <!-- Related Posts -->
                <?php if (!empty($related_posts)): ?>
                <div class="nextcloud-card mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Related Stories</h3>
                    <div class="space-y-4">
                        <?php foreach ($related_posts as $related): ?>
                        <a href="blog-post.php?slug=<?php echo $related['slug']; ?>" class="block group">
                            <div class="flex space-x-3">
                                <img src="<?php echo htmlspecialchars($related['featured_image'] ?: '../assets/images/default-blog.jpg'); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 group-hover:text-golden-600 transition-colors line-clamp-2"><?php echo htmlspecialchars($related['title']); ?></h4>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo date('M j, Y', strtotime($related['published_at'])); ?></p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                

            </aside>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>