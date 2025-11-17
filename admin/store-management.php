<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';

// Check if user is logged in and is super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Store Management";
$page_subtitle = "Manage Products & Categories";
$current_page = 'store-management';

// Fetch all products with category info
try {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM store_products p 
        LEFT JOIN store_categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC
    ");
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $products = [];
    $error = "Error fetching products: " . $e->getMessage();
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
                            <h1 class="text-3xl font-bold text-gradient mb-2">Store Management</h1>
                            <p class="text-slate-600">Manage products, categories, and store settings</p>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="openAddProductModal()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md">
                                <i class="fas fa-plus mr-2"></i>
                                Add Product
                            </button>
                            <button onclick="openCategoryModal()" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-md">
                                <i class="fas fa-tags mr-2"></i>
                                Categories
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <?php
                $total_products = count($products);
                $active_products = count(array_filter($products, fn($p) => $p['status'] === 'active'));
                $total_categories = count($categories);
                $low_stock = count(array_filter($products, fn($p) => $p['stock_quantity'] < 10));
                ?>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="nextcloud-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600">Total Products</p>
                                <p class="text-2xl font-bold text-gradient"><?php echo $total_products; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="nextcloud-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600">Active Products</p>
                                <p class="text-2xl font-bold text-gradient"><?php echo $active_products; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="nextcloud-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600">Categories</p>
                                <p class="text-2xl font-bold text-gradient"><?php echo $total_categories; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tags text-purple-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="nextcloud-card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600">Low Stock</p>
                                <p class="text-2xl font-bold text-gradient"><?php echo $low_stock; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="nextcloud-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <h2 class="text-xl font-bold text-gradient">All Products</h2>
                            <div class="flex gap-3">
                                <input type="text" id="searchProducts" placeholder="Search products..." class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold">
                                <select id="filterStatus" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-gold">
                                    <option value="all">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                <?php foreach($products as $product): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-12 h-12 rounded-lg object-cover shadow-sm" onerror="this.src='https://via.placeholder.com/100'">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                                <div class="text-sm text-slate-500">SKU: <?php echo htmlspecialchars($product['sku']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <?php echo htmlspecialchars($product['category_name']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-900">$<?php echo number_format($product['price'], 2); ?></div>
                                        <?php if($product['original_price'] > 0): ?>
                                        <div class="text-xs text-slate-500 line-through">$<?php echo number_format($product['original_price'], 2); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900 mb-1"><?php echo $product['stock_quantity']; ?> units</div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            <?php 
                                            if($product['stock_status'] === 'in_stock') echo 'bg-green-100 text-green-800';
                                            elseif($product['stock_status'] === 'low_stock') echo 'bg-orange-100 text-orange-800';
                                            else echo 'bg-red-100 text-red-800';
                                            ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $product['stock_status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            <?php 
                                            if($product['status'] === 'active') echo 'bg-green-100 text-green-800';
                                            elseif($product['status'] === 'draft') echo 'bg-slate-100 text-slate-800';
                                            else echo 'bg-red-100 text-red-800';
                                            ?>">
                                            <?php echo ucfirst($product['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="store-edit-product.php?id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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

<!-- Add Product Modal -->
<div id="addProductModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Add New Product</h2>
                <button onclick="closeAddProductModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="store-actions.php" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="add_product">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <input type="text" name="sku" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Original Price</label>
                    <input type="number" name="original_price" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status *</label>
                    <select name="stock_status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <input type="text" name="short_description" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL *</label>
                    <input type="url" name="image_url" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Enter the full URL of the product image</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                    </label>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" name="is_on_sale" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">On Sale</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeAddProductModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddProductModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
}

function closeAddProductModal() {
    document.getElementById('addProductModal').classList.add('hidden');
}

function openCategoryModal() {
    window.location.href = 'store-categories.php';
}

function editProduct(id) {
    window.location.href = 'store-edit-product.php?id=' + id;
}

function deleteProduct(id) {
    if(confirm('Are you sure you want to delete this product?')) {
        window.location.href = 'store-actions.php?action=delete_product&id=' + id;
    }
}

// Search and filter functionality
document.getElementById('searchProducts').addEventListener('input', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchProducts').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matchesSearch = text.includes(searchTerm);
        const statusCell = row.querySelector('td:nth-child(5) span').textContent.toLowerCase();
        const matchesStatus = statusFilter === 'all' || statusCell === statusFilter;
        
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}
</script>

</body>
</html>
