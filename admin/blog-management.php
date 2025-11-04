<?php
session_start();
require_once '../config/database.php';
$conn = $pdo;

// Handle actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    $post_id = (int)($_POST['post_id'] ?? 0);
    
    if ($action === 'approve' && $post_id) {
        $stmt = $conn->prepare("UPDATE blog_posts SET status = 'published', published_at = NOW() WHERE id = ?");
        $stmt->execute([$post_id]);
    } elseif ($action === 'reject' && $post_id) {
        $stmt = $conn->prepare("UPDATE blog_posts SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$post_id]);
    } elseif ($action === 'delete' && $post_id) {
        $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$post_id]);
    } elseif ($action === 'feature' && $post_id) {
        // Remove featured from all posts first
        $stmt = $conn->prepare("UPDATE blog_posts SET featured = 0");
        $stmt->execute();
        // Set this post as featured
        $stmt = $conn->prepare("UPDATE blog_posts SET featured = 1 WHERE id = ?");
        $stmt->execute([$post_id]);
    }
}

// Get filter
$status_filter = $_GET['status'] ?? 'all';
$where_clause = $status_filter !== 'all' ? "WHERE bp.status = ?" : "";
$params = $status_filter !== 'all' ? [$status_filter] : [];

// Get blog posts
$stmt = $conn->prepare("
    SELECT bp.*, bc.name as category_name, c.name as country_name, u.first_name, u.last_name, u.email as user_email
    FROM blog_posts bp 
    LEFT JOIN blog_categories bc ON bp.category_id = bc.id
    LEFT JOIN countries c ON bp.country_id = c.id
    LEFT JOIN users u ON bp.user_id = u.id
    $where_clause
    ORDER BY bp.created_at DESC
");
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM blog_posts GROUP BY status");
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<?php $current_page = 'blog-management'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-cream">
    <?php include 'includes/admin-header.php'; ?>
    
    <div class="flex pt-16">
        <?php include 'includes/admin-sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
            <div class="p-6 md:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">Blog Management</h1>
                <p class="text-slate-600">Manage user-submitted travel stories and blog posts</p>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Posts</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo array_sum($stats); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-blog text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Pending Review</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $stats['pending'] ?? 0; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Published</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $stats['published'] ?? 0; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Drafts</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $stats['draft'] ?? 0; ?></p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex space-x-2">
                    <a href="../admin/blog-submit.php" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">Create New Post</a>
                </div>
                <div class="flex space-x-2">
                    <a href="?status=all" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'all' ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">All</a>
                    <a href="?status=pending" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'pending' ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Pending</a>
                    <a href="?status=published" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'published' ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Published</a>
                    <a href="?status=draft" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'draft' ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Drafts</a>
                    <a href="?status=rejected" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'rejected' ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Rejected</a>
                </div>
            </div>

            <!-- Blog Posts Table -->
            <div class="nextcloud-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left py-3 px-6 font-semibold">Post</th>
                                <th class="text-left py-3 px-6 font-semibold">Author</th>
                                <th class="text-left py-3 px-6 font-semibold">Category</th>
                                <th class="text-left py-3 px-6 font-semibold">Status</th>
                                <th class="text-left py-3 px-6 font-semibold">Stats</th>
                                <th class="text-left py-3 px-6 font-semibold">Date</th>
                                <th class="text-left py-3 px-6 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr class="border-b hover:bg-slate-50">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img src="<?php echo htmlspecialchars($post['featured_image'] ?: '../assets/images/default-blog.jpg'); ?>" alt="" class="w-12 h-12 object-cover rounded-lg">
                                        <div>
                                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars(substr($post['title'], 0, 50)) . (strlen($post['title']) > 50 ? '...' : ''); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 80)) . '...'; ?></div>
                                            <?php if ($post['featured']): ?>
                                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full mt-1">Featured</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium"><?php echo htmlspecialchars(($post['first_name'] . ' ' . $post['last_name']) ?: $post['author_name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($post['user_email'] ?: $post['author_email']); ?></div>
                                    <?php if ($post['user_id']): ?>
                                    <div class="text-xs text-blue-600">Registered User</div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                        <?php echo htmlspecialchars($post['category_name'] ?: 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-1 rounded text-xs <?php 
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
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500">
                                    <div><?php echo number_format($post['views']); ?> views</div>
                                    <div><?php echo number_format($post['likes']); ?> likes</div>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500">
                                    <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        <?php if ($post['status'] === 'pending'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="text-green-600 hover:text-green-800" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($post['status'] === 'published' && !$post['featured']): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="feature">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Feature">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($post['status'] === 'published'): ?>
                                        <a href="../pages/blog-post.php?slug=<?php echo $post['slug']; ?>" target="_blank" class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </main>
    </div>
</body>
</html>