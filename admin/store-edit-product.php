<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';

// Check if user is logged in and is super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Edit Product";
$page_subtitle = "Update Product Information";
$current_page = 'store-management';

// Get product ID
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    $_SESSION['error_message'] = "Product ID is required";
    header('Location: store-management.php');
    exit();
}

// Fetch product details
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name
        FROM store_products p
        LEFT JOIN store_categories c ON p.category_id = c.id
        WHERE p.id = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        $_SESSION['error_message'] = "Product not found";
        header('Location: store-management.php');
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error_message'] = "Error fetching product: " . $e->getMessage();
    header('Location: store-management.php');
    exit();
}

// Fetch categories
try {
    $stmt = $pdo->query("SELECT * FROM store_categories ORDER BY display_order ASC");
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link href="../assets/css/admin-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <?php include 'includes/admin-header.php'; ?>

    <div class="flex pt-16">
        <?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
            <div class="p-6 md:p-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gradient mb-2">Edit Product</h1>
                            <p class="text-slate-600">Update product information and settings</p>
                        </div>
                        <a href="store-management.php" class="bg-slate-200 text-slate-700 px-6 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Store
                        </a>
                    </div>
                </div>

                <!-- Edit Product Form -->
                <form action="store-actions.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit_product">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Content Column -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Basic Information -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Basic Information</h2>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Product Name *</label>
                                        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required 
                                               class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">SKU *</label>
                                        <input type="text" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" required 
                                               class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                                        <textarea name="short_description" rows="2" 
                                                  class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent"><?php echo htmlspecialchars($product['short_description']); ?></textarea>
                                        <p class="text-xs text-slate-500 mt-1">Brief description for product cards (max 150 characters)</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Description</label>
                                        <textarea name="description" rows="6" 
                                                  class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Pricing</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Current Price *</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500">$</span>
                                            <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required 
                                                   class="w-full pl-8 pr-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Original Price</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500">$</span>
                                            <input type="number" name="original_price" value="<?php echo $product['original_price']; ?>" step="0.01" 
                                                   class="w-full pl-8 pr-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">Leave empty if no discount</p>
                                    </div>
                                </div>

                                <?php if($product['discount_percentage'] > 0): ?>
                                <div class="mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag text-green-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-green-800">
                                            Current Discount: <?php echo $product['discount_percentage']; ?>% OFF
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Inventory -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Inventory</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Stock Quantity *</label>
                                        <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required 
                                               class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Stock Status *</label>
                                        <select name="stock_status" required 
                                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                            <option value="in_stock" <?php echo $product['stock_status'] === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                                            <option value="low_stock" <?php echo $product['stock_status'] === 'low_stock' ? 'selected' : ''; ?>>Low Stock</option>
                                            <option value="out_of_stock" <?php echo $product['stock_status'] === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Image -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Product Image</h2>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Image URL *</label>
                                        <input type="url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required 
                                               class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent"
                                               placeholder="https://example.com/image.jpg">
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="border-2 border-slate-200 rounded-lg p-4">
                                        <p class="text-sm font-medium text-slate-700 mb-3">Current Image:</p>
                                        <div class="aspect-square bg-slate-50 rounded-lg flex items-center justify-center max-w-xs">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                 class="w-full h-full object-contain rounded-lg"
                                                 onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <!-- Publish Settings -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Publish</h2>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Status *</label>
                                        <select name="status" required 
                                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                            <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="draft" <?php echo $product['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                            <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="pt-4 border-t border-slate-200">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>
                                                   class="w-5 h-5 text-primary-gold border-2 border-slate-300 rounded focus:ring-2 focus:ring-primary-gold">
                                            <span class="ml-3 text-sm font-medium text-slate-700">Featured Product</span>
                                        </label>
                                        <p class="text-xs text-slate-500 mt-1 ml-8">Show on homepage and featured sections</p>
                                    </div>

                                    <div>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_on_sale" value="1" <?php echo $product['is_on_sale'] ? 'checked' : ''; ?>
                                                   class="w-5 h-5 text-primary-gold border-2 border-slate-300 rounded focus:ring-2 focus:ring-primary-gold">
                                            <span class="ml-3 text-sm font-medium text-slate-700">On Sale</span>
                                        </label>
                                        <p class="text-xs text-slate-500 mt-1 ml-8">Display sale badge on product</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Category</h2>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Product Category *</label>
                                    <select name="category_id" required 
                                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Product Stats -->
                            <div class="nextcloud-card p-6">
                                <h2 class="text-xl font-bold text-gradient mb-6">Statistics</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                        <span class="text-sm text-slate-600">Rating</span>
                                        <div class="flex items-center">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            <span class="text-sm font-semibold text-slate-900"><?php echo number_format($product['rating'], 1); ?></span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                        <span class="text-sm text-slate-600">Reviews</span>
                                        <span class="text-sm font-semibold text-slate-900"><?php echo $product['review_count']; ?></span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                        <span class="text-sm text-slate-600">Created</span>
                                        <span class="text-sm font-semibold text-slate-900"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></span>
                                    </div>
                                    <div class="flex items-center justify-between py-2">
                                        <span class="text-sm text-slate-600">Last Updated</span>
                                        <span class="text-sm font-semibold text-slate-900"><?php echo date('M d, Y', strtotime($product['updated_at'])); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="nextcloud-card p-6">
                                <div class="space-y-3">
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md">
                                        <i class="fas fa-save mr-2"></i>
                                        Update Product
                                    </button>
                                    
                                    <a href="store-management.php" class="block w-full text-center bg-slate-200 text-slate-700 px-6 py-3 rounded-lg font-semibold hover:bg-slate-300 transition-all">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancel
                                    </a>
                                    
                                    <button type="button" onclick="confirmDelete(<?php echo $product['id']; ?>)" class="w-full bg-red-100 text-red-700 px-6 py-3 rounded-lg font-semibold hover:bg-red-200 transition-all">
                                        <i class="fas fa-trash-alt mr-2"></i>
                                        Delete Product
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    function confirmDelete(productId) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            window.location.href = 'store-actions.php?action=delete_product&id=' + productId;
        }
    }

    // Image preview update
    document.querySelector('input[name="image_url"]').addEventListener('input', function(e) {
        const img = document.querySelector('.aspect-square img');
        img.src = e.target.value || 'https://via.placeholder.com/400x400?text=No+Image';
    });

    // Auto-calculate discount percentage
    const priceInput = document.querySelector('input[name="price"]');
    const originalPriceInput = document.querySelector('input[name="original_price"]');
    
    function updateDiscount() {
        const price = parseFloat(priceInput.value) || 0;
        const originalPrice = parseFloat(originalPriceInput.value) || 0;
        
        if (originalPrice > 0 && price < originalPrice) {
            const discount = Math.round(((originalPrice - price) / originalPrice) * 100);
            console.log('Discount calculated:', discount + '%');
        }
    }
    
    priceInput.addEventListener('input', updateDiscount);
    originalPriceInput.addEventListener('input', updateDiscount);
    </script>

</body>
</html>
