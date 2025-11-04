<?php
session_start();
$page_title = "Share Your Travel Story - iForYoungTours Blog";
$page_description = "Share your amazing African travel experience with fellow adventurers. Submit your story, photos, and tips to inspire others.";
$base_path = "/foreveryoungtours/";
$css_path = "../assets/css/modern-styles.css";

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Check access - allow admin access from admin panel, require login for clients
$is_admin_access = (strpos($_SERVER['HTTP_REFERER'] ?? '', '/admin/') !== false);
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin');
$is_client = (isset($_SESSION['user_id']) && $_SESSION['role'] === 'client');

if (!$is_admin_access && !$is_admin && !$is_client) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Set admin flag for form behavior
$is_admin = $is_admin || $is_admin_access;

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? '';
$user_email = $_SESSION['email'] ?? '';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_POST) {
    $title = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author_name = trim($_POST['author_name'] ?? '');
    $author_email = trim($_POST['author_email'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $country_id = (int)($_POST['country_id'] ?? 0) ?: null;
    $tour_id = (int)($_POST['tour_id'] ?? 0) ?: null;
    $trip_date = $_POST['trip_date'] ?: null;
    $tags = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
    
    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Title is required";
    if (empty($excerpt)) $errors[] = "Excerpt is required";
    if (empty($content)) $errors[] = "Story content is required";
    if (empty($author_name)) $errors[] = "Your name is required";
    if (empty($author_email) || !filter_var($author_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if ($category_id <= 0) $errors[] = "Please select a category";
    
    if (empty($errors)) {
        try {
            $conn->beginTransaction();
            
            // Generate slug
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
            $original_slug = $slug;
            $counter = 1;
            
            // Ensure unique slug
            while (true) {
                $stmt = $conn->prepare("SELECT id FROM blog_posts WHERE slug = ?");
                $stmt->execute([$slug]);
                if (!$stmt->fetch()) break;
                $slug = $original_slug . '-' . $counter++;
            }
            
            // Handle file upload
            $featured_image = null;
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/blog/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($file_extension, $allowed_extensions)) {
                    $filename = $slug . '-' . time() . '.' . $file_extension;
                    $filepath = $upload_dir . $filename;
                    
                    if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $filepath)) {
                        $featured_image = 'assets/images/blog/' . $filename;
                    }
                }
            }
            
            // Insert blog post - auto-publish if admin
            $status = $is_admin ? 'published' : 'pending';
            $published_at = $is_admin ? 'NOW()' : 'NULL';
            
            $stmt = $conn->prepare("
                INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author_name, author_email, 
                                      user_id, category_id, country_id, tour_id, trip_date, status, published_at, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, $published_at, NOW())
            ");
            
            $stmt->execute([
                $title, $slug, $excerpt, $content, $featured_image, $user_name, 
                $user_email, $user_id, $category_id, $country_id, $tour_id, $trip_date, $status
            ]);
            
            $post_id = $conn->lastInsertId();
            
            // Handle tags
            foreach ($tags as $tag_name) {
                if (empty($tag_name)) continue;
                
                $tag_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag_name), '-'));
                
                // Insert or get tag
                $stmt = $conn->prepare("INSERT IGNORE INTO blog_tags (name, slug) VALUES (?, ?)");
                $stmt->execute([$tag_name, $tag_slug]);
                
                $stmt = $conn->prepare("SELECT id FROM blog_tags WHERE slug = ?");
                $stmt->execute([$tag_slug]);
                $tag_id = $stmt->fetchColumn();
                
                // Link tag to post
                $stmt = $conn->prepare("INSERT IGNORE INTO blog_post_tags (post_id, tag_id) VALUES (?, ?)");
                $stmt->execute([$post_id, $tag_id]);
            }
            
            $conn->commit();
            if ($is_admin) {
                $success_message = "Blog post created and published successfully!";
            } else {
                $success_message = "Thank you for sharing your story! Your submission has been received and will be reviewed before publishing.";
            }
            
        } catch (Exception $e) {
            $conn->rollBack();
            $error_message = "Sorry, there was an error submitting your story. Please try again.";
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}

// Get categories
$stmt = $conn->prepare("SELECT * FROM blog_categories WHERE status = 'active' ORDER BY name");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get countries
$stmt = $conn->prepare("SELECT * FROM countries WHERE status = 'active' ORDER BY name");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get tours
$stmt = $conn->prepare("SELECT id, name FROM tours WHERE status = 'active' ORDER BY name");
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-br from-golden-50 via-emerald-50 to-blue-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Share Your <span class="text-gradient">Travel Story</span>
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Inspire fellow travelers by sharing your amazing African adventure. Your story could be the inspiration someone needs for their next journey!
        </p>
    </div>
</section>

<!-- Submission Form -->
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($success_message): ?>
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800"><?php echo $success_message; ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800"><?php echo $error_message; ?></p>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="nextcloud-card">
            <div class="space-y-8">
                <!-- Story Details -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Story Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Story Title *</label>
                            <input type="text" name="title" required maxlength="255" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent" placeholder="My Amazing Safari Adventure in Kenya">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (($_POST['category_id'] ?? '') == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country Visited</label>
                            <select name="country_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                                <option value="">Select a country</option>
                                <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country['id']; ?>" <?php echo (($_POST['country_id'] ?? '') == $country['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tour Package (if applicable)</label>
                            <select name="tour_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                                <option value="">Select a tour</option>
                                <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['id']; ?>" <?php echo (($_POST['tour_id'] ?? '') == $tour['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tour['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trip Date</label>
                            <input type="date" name="trip_date" value="<?php echo htmlspecialchars($_POST['trip_date'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short Description *</label>
                            <textarea name="excerpt" required maxlength="500" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent" placeholder="A brief summary of your experience (max 500 characters)"><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Story *</label>
                            <textarea name="content" required rows="12" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent" placeholder="Tell us about your amazing African adventure! Share the highlights, challenges, memorable moments, and tips for future travelers."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" value="<?php echo htmlspecialchars($_POST['tags'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent" placeholder="safari, wildlife, adventure, photography (separate with commas)">
                            <p class="text-sm text-gray-500 mt-1">Add relevant tags to help others find your story</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                            <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Upload a beautiful photo that represents your story (JPG, PNG, WebP)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Author Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">About You</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name <?php echo $is_admin ? '*' : ''; ?></label>
                            <input type="text" name="author_name" <?php echo $is_client ? 'readonly' : 'required'; ?> value="<?php echo htmlspecialchars($user_name); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg <?php echo $is_client ? 'bg-gray-50' : ''; ?>" placeholder="<?php echo $is_admin ? 'Enter author name' : ''; ?>">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <?php echo $is_admin ? '*' : ''; ?></label>
                            <input type="email" name="author_email" <?php echo $is_client ? 'readonly' : 'required'; ?> value="<?php echo htmlspecialchars($user_email); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg <?php echo $is_client ? 'bg-gray-50' : ''; ?>" placeholder="<?php echo $is_admin ? 'Enter email address' : ''; ?>">
                            <p class="text-sm text-gray-500 mt-1"><?php echo $is_client ? 'Using your account information' : 'Author contact information'; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Submission Guidelines -->
                <div class="bg-blue-50 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4">Submission Guidelines</h3>
                    <ul class="text-blue-800 space-y-2 text-sm">
                        <li>• Your story should be original and based on your personal experience</li>
                        <li>• Include specific details, tips, and insights that would help other travelers</li>
                        <li>• Photos should be your own and of good quality</li>
                        <li>• Stories will be reviewed before publication (usually within 3-5 business days)</li>
                        <li>• We reserve the right to edit for clarity and length</li>
                        <li>• By submitting, you grant us permission to publish your story on our website</li>
                    </ul>
                </div>
                
                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn-primary px-12 py-4 text-lg font-semibold rounded-2xl">
                        Submit Your Story
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include '../includes/footer.php'; ?>