<?php
$page_title = "Travel Stories & Experiences - Forever Young Tours Blog";
$page_description = "Read amazing travel stories and share your own African adventure experiences. Discover insider tips, cultural insights, and inspiring journeys.";
$css_path = "../assets/css/modern-styles.css";


include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Travel <span class="text-gradient">Stories</span> & Experiences
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Discover amazing African adventures through the eyes of fellow travelers. Share your own story and inspire others to explore.
            </p>
            <div class="flex justify-center">
                <a href="#stories" class="btn-primary px-8 py-4 rounded-2xl font-semibold">Read Stories</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Story -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Story</h2>
        </div>
        <div class="nextcloud-card overflow-hidden">
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="relative">
                    <img src="../assets/images/Africa Travel.jpg" alt="Featured Story" class="w-full h-80 lg:h-full object-cover rounded-2xl">
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-sm font-semibold text-white bg-blue-500">
                        Adventure
                    </span>
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">My Journey Through East Africa</h3>
                    <p class="text-gray-600 mb-6 text-lg">An incredible 14-day adventure through Kenya, Tanzania, and Uganda. From the Serengeti to Mount Kilimanjaro, this journey changed my perspective on travel.</p>
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                            <span class="font-medium text-gray-900">Sarah Johnson</span>
                        </div>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-500">Nov 1, 2024</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-500">1,234 views</span>
                    </div>
                    <div class="text-primary-gold font-semibold">Read Full Story →</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Filters & Search -->
<section class="py-8 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <form method="GET" class="relative">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search stories..." class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <input type="hidden" name="tag" value="<?php echo htmlspecialchars($tag); ?>">
                </form>
            </div>
            
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="blog.php" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo !$category ? 'bg-golden-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                <a href="blog.php?category=<?php echo $cat['slug']; ?>" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?php echo $category === $cat['slug'] ? 'text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>" <?php echo $category === $cat['slug'] ? 'style="background-color: ' . $cat['color'] . '"' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?> (<?php echo $cat['post_count']; ?>)
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section id="stories" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <?php if (empty($posts)): ?>
                <div class="text-center py-12">
                    <div class="text-slate-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-600 mb-2">No stories found</h3>
                    <p class="text-slate-500 mb-4">Try adjusting your search or browse all stories</p>
                    <a href="blog.php" class="btn-primary px-6 py-3 rounded-lg">View All Stories</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    <?php foreach ($posts as $post): ?>
                    <article class="nextcloud-card overflow-hidden cursor-pointer" onclick="window.location.href='blog-post.php?slug=<?php echo $post['slug']; ?>'">
                        <div class="relative">
                            <img src="<?php echo htmlspecialchars($post['featured_image'] ?: '../assets/images/default-blog.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-48 object-cover">
                            <?php if ($post['category_name']): ?>
                            <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-semibold text-white" style="background-color: <?php echo $post['category_color']; ?>">
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 120)) . '...'; ?></p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <img src="<?php echo htmlspecialchars($post['author_avatar'] ?: '../assets/images/default-avatar.jpg'); ?>" alt="<?php echo htmlspecialchars($post['author_name']); ?>" class="w-8 h-8 rounded-full">
                                    <span class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($post['author_name']); ?></span>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><?php echo date('M j', strtotime($post['published_at'])); ?></span>
                                    <span><?php echo number_format($post['views']); ?> views</span>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="flex justify-center mt-12">
                    <nav class="flex space-x-2">
                        <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page-1; ?>&category=<?php echo urlencode($category); ?>&tag=<?php echo urlencode($tag); ?>&search=<?php echo urlencode($search); ?>" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                        <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>&tag=<?php echo urlencode($tag); ?>&search=<?php echo urlencode($search); ?>" class="px-4 py-2 border rounded-lg <?php echo $i === $page ? 'bg-golden-500 text-white border-golden-500' : 'border-gray-300 hover:bg-gray-50'; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>&category=<?php echo urlencode($category); ?>&tag=<?php echo urlencode($tag); ?>&search=<?php echo urlencode($search); ?>" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Next</a>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4">

                
                <!-- Popular Tags -->
                <?php if (!empty($tags)): ?>
                <div class="nextcloud-card mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag_item): ?>
                        <a href="blog.php?tag=<?php echo $tag_item['slug']; ?>" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">
                            #<?php echo htmlspecialchars($tag_item['name']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>