<?php

require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = "Travel Store - Forever Young Tours | Essential Travel Gear & Equipment";
$page_description = "Shop essential travel gear, equipment, and accessories for your next adventure. Quality camping gear, hiking equipment, and travel essentials.";
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

// Fetch categories
try {
    $stmt = $pdo->query("SELECT * FROM store_categories WHERE status = 'active' ORDER BY display_order ASC");
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    $categories = [];
    error_log("Error fetching categories: " . $e->getMessage());
}

// Fetch products
try {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM store_products p 
        LEFT JOIN store_categories c ON p.category_id = c.id 
        WHERE p.status = 'active' 
        ORDER BY p.is_featured DESC, p.created_at DESC
    ");
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $products = [];
    error_log("Error fetching products: " . $e->getMessage());
}

include '../includes/header.php';
?>

<!-- Hero Section with Search -->
<section class="relative pt-24 pb-20 bg-gradient-to-br from-yellow-600 via-yellow-500 to-green-600 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-green-300 rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-yellow-200 rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-4000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-6 border border-white/30">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                Travel Store
            </div>
            <h1 class="text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                Gear Up for Your<br>Next Adventure
            </h1>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Premium travel essentials and outdoor gear to make your journey unforgettable
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="relative">
                    <input type="text" 
                           placeholder="Search for products..." 
                           class="w-full px-6 py-4 pl-14 rounded-2xl bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-white/50 shadow-2xl">
                    <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-2 rounded-xl font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md">
                        Search
                    </button>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                <div class="text-white">
                    <div class="text-3xl font-bold">50+</div>
                    <div class="text-white/80 text-sm">Products</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-bold">4.8★</div>
                    <div class="text-white/80 text-sm">Rating</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl font-bold">Free</div>
                    <div class="text-white/80 text-sm">Shipping</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter Section -->
<section class="sticky top-16 z-40 bg-white border-b border-yellow-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <!-- Mobile Layout -->
        <div class="block lg:hidden space-y-4">
            <!-- Sort Dropdown - Mobile First -->
            <div class="flex justify-center">
                <select id="sortProductsMobile" class="w-full max-w-xs px-4 py-2 bg-white border-2 border-yellow-200 text-gray-700 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 cursor-pointer text-center">
                    <option value="featured">Sort by: Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
            
            <!-- Category Filters - Mobile Scrollable -->
            <div class="overflow-x-auto scrollbar-hide">
                <div class="flex space-x-3 pb-2 min-w-max px-1">
                    <button class="category-filter active px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-full font-semibold whitespace-nowrap hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md text-sm" data-category="all">
                        All Products
                    </button>
                    <?php foreach($categories as $category): ?>
                    <button class="category-filter px-4 py-2 bg-white text-gray-700 border-2 border-yellow-200 rounded-full font-semibold whitespace-nowrap hover:bg-yellow-50 hover:border-yellow-400 transition-all text-sm" 
                            data-category="<?php echo htmlspecialchars($category['slug']); ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Desktop Layout -->
        <div class="hidden lg:flex items-center justify-between">
            <div class="flex items-center space-x-4 overflow-x-auto scrollbar-hide">
                <button class="category-filter active px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-full font-semibold whitespace-nowrap hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md" data-category="all">
                    All Products
                </button>
                <?php foreach($categories as $category): ?>
                <button class="category-filter px-6 py-2 bg-white text-gray-700 border-2 border-yellow-200 rounded-full font-semibold whitespace-nowrap hover:bg-yellow-50 hover:border-yellow-400 transition-all" 
                        data-category="<?php echo htmlspecialchars($category['slug']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Sort Dropdown - Desktop -->
            <div class="relative ml-4 flex-shrink-0">
                <select id="sortProducts" class="px-4 py-2 bg-white border-2 border-yellow-200 text-gray-700 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 cursor-pointer">
                    <option value="featured">Sort by: Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Banner -->
<section class="py-8 bg-gradient-to-r from-yellow-50 to-green-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between bg-white rounded-2xl p-6 shadow-lg border-2 border-yellow-200">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Limited Time Offer!</h3>
                    <p class="text-gray-600">Get 20% off on all camping gear this week</p>
                </div>
            </div>
            <button class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md">
                Shop Now
            </button>
        </div>
    </div>
</section>

<!-- Products Grid Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <?php if(empty($products)): ?>
                <div class="col-span-full text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600">Please run the database setup script first.</p>
                </div>
            <?php else: ?>
                <?php foreach($products as $product): 
                    $discount = $product['discount_percentage'];
                    $hasDiscount = $discount > 0 && $product['original_price'] > 0;
                ?>
                <!-- Product Card -->
                <div class="product-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group border-2 border-yellow-100 hover:border-yellow-300" 
                     data-category="<?php echo htmlspecialchars($product['category_slug']); ?>"
                     data-price="<?php echo $product['price']; ?>"
                     data-rating="<?php echo $product['rating']; ?>"
                     data-date="<?php echo strtotime($product['created_at']); ?>"
                     data-featured="<?php echo $product['is_featured']; ?>">
                <div class="relative overflow-hidden">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="wishlist-btn w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all shadow-md"
                                data-product-id="<?php echo $product['id']; ?>">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute top-4 right-4 z-10 flex flex-col gap-2">
                        <?php if($hasDiscount): ?>
                        <span class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                            -<?php echo $discount; ?>% OFF
                        </span>
                        <?php endif; ?>
                        <?php if($product['stock_status'] == 'in_stock'): ?>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">In Stock</span>
                        <?php elseif($product['stock_status'] == 'low_stock'): ?>
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">Low Stock</span>
                        <?php else: ?>
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-8 relative">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500"
                             onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-yellow-700 bg-yellow-50 px-2 py-1 rounded-full border border-yellow-200">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </span>
                        <div class="flex items-center text-yellow-600">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs font-semibold text-gray-700 ml-1">
                                <?php echo number_format($product['rating'], 1); ?>
                            </span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-yellow-600 transition-colors line-clamp-1">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h3>
                    <p class="text-gray-600 text-xs mb-3 line-clamp-2">
                        <?php echo htmlspecialchars($product['short_description']); ?>
                    </p>
                    
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-2xl font-bold text-gray-900">
                            $<?php echo number_format($product['price'], 2); ?>
                        </span>
                        <?php if($hasDiscount): ?>
                        <span class="text-sm text-gray-400 line-through">
                            $<?php echo number_format($product['original_price'], 2); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button class="add-to-cart flex-1 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold py-2.5 rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm"
                                data-product-id="<?php echo $product['id']; ?>">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Add
                            </span>
                        </button>
                        <button class="quick-view w-10 h-10 border-2 border-green-500 text-green-600 rounded-lg hover:bg-green-50 transition-all flex items-center justify-center"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-original-price="<?php echo $product['original_price']; ?>"
                                data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                data-image="<?php echo htmlspecialchars($product['image_url']); ?>"
                                data-category="<?php echo htmlspecialchars($product['category_name']); ?>"
                                data-rating="<?php echo $product['rating']; ?>"
                                data-stock="<?php echo $product['stock_status']; ?>"
                                data-sku="<?php echo htmlspecialchars($product['sku']); ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Floating Shopping Cart Widget -->
<div id="floatingCart" class="fixed bottom-6 left-6 z-40">
    <!-- Cart Button -->
    <button onclick="toggleCartPreview()" class="relative bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-full p-4 shadow-2xl hover:from-yellow-600 hover:to-yellow-700 transition-all transform hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center hidden">0</span>
    </button>
    
    <!-- Cart Preview Dropdown -->
    <div id="cartPreview" class="hidden absolute bottom-20 left-0 w-96 bg-white rounded-2xl shadow-2xl border-2 border-yellow-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">Shopping Cart</h3>
                <button onclick="toggleCartPreview()" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Cart Items -->
        <div id="cartItemsList" class="max-h-96 overflow-y-auto p-4">
            <!-- Items will be loaded here via JavaScript -->
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>Your cart is empty</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="border-t-2 border-gray-200 p-4 bg-gray-50">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-700 font-semibold">Subtotal:</span>
                <span id="cartSubtotal" class="text-2xl font-bold text-gray-900">$0.00</span>
            </div>
            <div class="space-y-2">
                <a href="cart.php" class="block w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-center py-3 rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md">
                    View Cart
                </a>
                <a href="checkout.php" class="block w-full bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-3 rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-md">
                    Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-gray-900">Product Details</h2>
            <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center p-8">
                    <img id="modalImage" src="" alt="" class="w-full h-full object-contain">
                </div>
                
                <!-- Product Info -->
                <div class="flex flex-col">
                    <div class="mb-4">
                        <span id="modalCategory" class="inline-block px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-50 rounded-full border border-yellow-200 mb-3"></span>
                        <h3 id="modalName" class="text-3xl font-bold text-gray-900 mb-2"></h3>
                        <div class="flex items-center mb-4">
                            <div class="flex items-center text-yellow-600 mr-3">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span id="modalRating" class="text-sm font-semibold text-gray-700 ml-1"></span>
                            </div>
                            <span id="modalStock" class="px-3 py-1 text-xs font-semibold rounded-full"></span>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-baseline gap-3 mb-4">
                            <span id="modalPrice" class="text-4xl font-bold text-gray-900"></span>
                            <span id="modalOriginalPrice" class="text-xl text-gray-400 line-through"></span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">SKU: <span id="modalSku" class="font-semibold"></span></p>
                    </div>
                    
                    <div class="mb-6 flex-1">
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Description</h4>
                        <p id="modalDescription" class="text-gray-600 leading-relaxed"></p>
                    </div>
                    
                    <div class="flex gap-3 mt-auto">
                        <button class="flex-1 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold py-4 rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md hover:shadow-lg">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Add to Cart
                            </span>
                        </button>
                        <button class="w-14 h-14 border-2 border-red-300 text-red-500 rounded-lg hover:bg-red-50 transition-all flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Stay Updated</h2>
        <p class="text-gray-600 mb-8">Get notified about new products and special offers</p>
        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
            <button class="bg-yellow-500 text-black font-semibold px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                Subscribe
            </button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const sortSelect = document.getElementById('sortProducts');
    let currentCategory = 'all';
    
    // Category Filtering
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            // Update active state
            categoryFilters.forEach(f => {
                f.classList.remove('active', 'bg-gradient-to-r', 'from-yellow-500', 'to-yellow-600', 'text-white', 'shadow-md');
                f.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-yellow-200');
            });
            this.classList.add('active', 'bg-gradient-to-r', 'from-yellow-500', 'to-yellow-600', 'text-white', 'shadow-md');
            this.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-yellow-200');
            
            // Get selected category
            currentCategory = this.getAttribute('data-category');
            
            // Filter products
            filterProducts();
        });
    });
    
    // Sorting - Handle both mobile and desktop dropdowns
    const sortSelectMobile = document.getElementById('sortProductsMobile');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
            // Sync mobile dropdown
            if (sortSelectMobile) {
                sortSelectMobile.value = this.value;
            }
        });
    }
    
    if (sortSelectMobile) {
        sortSelectMobile.addEventListener('change', function() {
            sortProducts(this.value);
            // Sync desktop dropdown
            if (sortSelect) {
                sortSelect.value = this.value;
            }
        });
    }
    
    function filterProducts() {
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            
            if (currentCategory === 'all' || cardCategory === currentCategory) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.5s ease-in';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show message if no products found
        const grid = document.getElementById('productsGrid');
        let noResultsMsg = document.getElementById('noResultsMessage');
        
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMessage';
                noResultsMsg.className = 'col-span-full text-center py-16';
                noResultsMsg.innerHTML = `
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600">Try selecting a different category</p>
                `;
                grid.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    function sortProducts(sortType) {
        const grid = document.getElementById('productsGrid');
        const cardsArray = Array.from(productCards);
        
        cardsArray.sort((a, b) => {
            switch(sortType) {
                case 'price-asc':
                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                case 'price-desc':
                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                case 'rating':
                    return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute('data-rating'));
                case 'newest':
                    return parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date'));
                case 'featured':
                default:
                    return parseInt(b.getAttribute('data-featured')) - parseInt(a.getAttribute('data-featured'));
            }
        });
        
        // Re-append sorted cards
        cardsArray.forEach(card => grid.appendChild(card));
    }
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const originalHTML = this.innerHTML;
            
            <?php if (!$is_logged_in): ?>
            // Redirect to login if not logged in
            window.location.href = '../auth/login.php?redirect=' + encodeURIComponent(window.location.href);
            return;
            <?php endif; ?>
            
            this.innerHTML = '<span class="flex items-center justify-center"><svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>';
            this.disabled = true;
            
            // AJAX request to add to cart
            fetch('../includes/cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add_to_cart&product_id=' + productId + '&quantity=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<span class="flex items-center justify-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Added!</span>';
                    this.classList.remove('from-yellow-500', 'to-yellow-600');
                    this.classList.add('from-green-500', 'to-green-600');
                    
                    // Update cart count if element exists
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                        cartCount.classList.remove('hidden');
                    }
                    
                    // Update floating cart
                    loadCartPreview();
                    
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('from-green-500', 'to-green-600');
                        this.classList.add('from-yellow-500', 'to-yellow-600');
                        this.disabled = false;
                    }, 1500);
                } else {
                    alert(data.message || 'Failed to add to cart');
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                this.innerHTML = originalHTML;
                this.disabled = false;
            });
        });
    });
    
    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const svg = this.querySelector('svg');
            const isInWishlist = svg.getAttribute('fill') === 'currentColor';
            
            <?php if (!$is_logged_in): ?>
            // Redirect to login if not logged in
            window.location.href = '../auth/login.php?redirect=' + encodeURIComponent(window.location.href);
            return;
            <?php endif; ?>
            
            const action = isInWishlist ? 'remove_from_wishlist' : 'add_to_wishlist';
            
            fetch('../includes/cart-actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=' + action + '&product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isInWishlist) {
                        svg.setAttribute('fill', 'none');
                        this.classList.remove('text-red-500');
                    } else {
                        svg.setAttribute('fill', 'currentColor');
                        this.classList.add('text-red-500');
                    }
                } else {
                    alert(data.message || 'Failed to update wishlist');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
    
    // Quick View functionality
    document.querySelectorAll('.quick-view').forEach(button => {
        button.addEventListener('click', function() {
            const modal = document.getElementById('quickViewModal');
            
            // Get product data from button attributes
            const productData = {
                name: this.getAttribute('data-name'),
                price: parseFloat(this.getAttribute('data-price')),
                originalPrice: parseFloat(this.getAttribute('data-original-price')),
                description: this.getAttribute('data-description'),
                image: this.getAttribute('data-image'),
                category: this.getAttribute('data-category'),
                rating: parseFloat(this.getAttribute('data-rating')),
                stock: this.getAttribute('data-stock'),
                sku: this.getAttribute('data-sku')
            };
            
            // Populate modal with product data
            document.getElementById('modalName').textContent = productData.name;
            document.getElementById('modalPrice').textContent = '$' + productData.price.toFixed(2);
            document.getElementById('modalDescription').textContent = productData.description || 'No description available.';
            document.getElementById('modalImage').src = productData.image;
            document.getElementById('modalImage').alt = productData.name;
            document.getElementById('modalCategory').textContent = productData.category;
            document.getElementById('modalRating').textContent = productData.rating.toFixed(1);
            document.getElementById('modalSku').textContent = productData.sku;
            
            // Handle original price (show only if exists and is greater than current price)
            const originalPriceEl = document.getElementById('modalOriginalPrice');
            if (productData.originalPrice && productData.originalPrice > productData.price) {
                originalPriceEl.textContent = '$' + productData.originalPrice.toFixed(2);
                originalPriceEl.style.display = 'inline';
            } else {
                originalPriceEl.style.display = 'none';
            }
            
            // Handle stock status badge
            const stockEl = document.getElementById('modalStock');
            stockEl.className = 'px-3 py-1 text-xs font-semibold rounded-full';
            
            if (productData.stock === 'in_stock') {
                stockEl.textContent = 'In Stock';
                stockEl.classList.add('bg-green-100', 'text-green-800');
            } else if (productData.stock === 'low_stock') {
                stockEl.textContent = 'Low Stock';
                stockEl.classList.add('bg-orange-100', 'text-orange-800');
            } else {
                stockEl.textContent = 'Out of Stock';
                stockEl.classList.add('bg-red-100', 'text-red-800');
            }
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });
});

// Close quick view modal
function closeQuickView() {
    const modal = document.getElementById('quickViewModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('quickViewModal');
    if (e.target === modal) {
        closeQuickView();
    }
});

// Add fadeIn animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);

// ============================================
// Floating Cart Functions
// ============================================

// Toggle cart preview
function toggleCartPreview() {
    const preview = document.getElementById('cartPreview');
    preview.classList.toggle('hidden');
    
    // Load cart when opening
    if (!preview.classList.contains('hidden')) {
        loadCartPreview();
    }
}

// Load cart preview
function loadCartPreview() {
    <?php if (!$is_logged_in): ?>
    document.getElementById('cartItemsList').innerHTML = `
        <div class="text-center py-8">
            <p class="text-gray-600 mb-4">Please login to view your cart</p>
            <a href="../auth/login.php" class="inline-block bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">
                Login
            </a>
        </div>
    `;
    return;
    <?php endif; ?>
    
    fetch('../includes/cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_cart'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data);
        }
    })
    .catch(error => console.error('Error loading cart:', error));
}

// Update cart UI
function updateCartUI(data) {
    const cartItemsList = document.getElementById('cartItemsList');
    const cartBadge = document.getElementById('cart-badge');
    const cartSubtotal = document.getElementById('cartSubtotal');
    
    // Update badge
    if (data.count > 0) {
        cartBadge.textContent = data.count;
        cartBadge.classList.remove('hidden');
    } else {
        cartBadge.classList.add('hidden');
    }
    
    // Update subtotal
    cartSubtotal.textContent = '$' + parseFloat(data.total).toFixed(2);
    
    // Update items list
    if (data.items && data.items.length > 0) {
        cartItemsList.innerHTML = data.items.map(item => `
            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200">
                <img src="${item.image_url}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg" onerror="this.src='https://via.placeholder.com/100'">
                <div class="flex-1">
                    <h4 class="font-semibold text-sm text-gray-900 line-clamp-1">${item.name}</h4>
                    <p class="text-xs text-gray-500">Qty: ${item.quantity}</p>
                    <p class="text-sm font-bold text-yellow-600">$${parseFloat(item.subtotal).toFixed(2)}</p>
                </div>
                <button onclick="removeFromCart(${item.cart_id})" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `).join('');
    } else {
        cartItemsList.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p>Your cart is empty</p>
            </div>
        `;
    }
}

// Remove from cart
function removeFromCart(cartId) {
    if (!confirm('Remove this item from cart?')) return;
    
    fetch('../includes/cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=remove_from_cart&cart_id=' + cartId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartPreview();
        } else {
            alert(data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Load cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($is_logged_in): ?>
    loadCartPreview();
    <?php endif; ?>
});

// Close cart preview when clicking outside
document.addEventListener('click', function(e) {
    const floatingCart = document.getElementById('floatingCart');
    const cartPreview = document.getElementById('cartPreview');
    
    if (!floatingCart.contains(e.target) && !cartPreview.classList.contains('hidden')) {
        cartPreview.classList.add('hidden');
    }
});
</script>

<?php include '../includes/footer.php'; ?>