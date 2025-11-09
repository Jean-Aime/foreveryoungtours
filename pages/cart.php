<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$page_title = "Shopping Cart - Forever Young Tours";
$page_description = "Review your shopping cart and proceed to checkout";
$css_path = "../assets/css/modern-styles.css";

$user_id = $_SESSION['user_id'];

// Fetch cart items
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.id as cart_id,
            c.quantity,
            p.id as product_id,
            p.name,
            p.price,
            p.image_url,
            p.stock_quantity,
            p.stock_status,
            p.sku,
            cat.name as category_name,
            (c.quantity * p.price) as subtotal
        FROM shopping_cart c
        JOIN store_products p ON c.product_id = p.id
        LEFT JOIN store_categories cat ON p.category_id = cat.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll();
    
    $cart_total = array_sum(array_column($cart_items, 'subtotal'));
    $tax_rate = 0.10; // 10% tax
    $tax_amount = $cart_total * $tax_rate;
    $shipping_fee = $cart_total > 100 ? 0 : 15; // Free shipping over $100
    $grand_total = $cart_total + $tax_amount + $shipping_fee;
    
} catch(PDOException $e) {
    $cart_items = [];
    $cart_total = 0;
    $tax_amount = 0;
    $shipping_fee = 0;
    $grand_total = 0;
}

include '../includes/header.php';
?>

<!-- Page Header -->
<section class="pt-24 pb-12 bg-gradient-to-r from-yellow-500 to-yellow-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Shopping Cart</h1>
            <p class="text-xl">Review your items and proceed to checkout</p>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($cart_items)): ?>
            <!-- Empty Cart -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg class="w-32 h-32 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Add some products to get started!</p>
                <a href="store.php" class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-md">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
                            <h2 class="text-2xl font-bold">Cart Items (<?php echo count($cart_items); ?>)</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($cart_items as $item): ?>
                            <div class="p-6 hover:bg-gray-50 transition-colors" id="cart-item-<?php echo $item['cart_id']; ?>">
                                <div class="flex items-center gap-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             class="w-24 h-24 object-cover rounded-lg shadow-md"
                                             onerror="this.src='https://via.placeholder.com/100'">
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-2">
                                            <?php echo htmlspecialchars($item['category_name']); ?> • SKU: <?php echo htmlspecialchars($item['sku']); ?>
                                        </p>
                                        <div class="flex items-center gap-4">
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                                <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] - 1; ?>)" 
                                                        class="px-3 py-1 hover:bg-gray-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" 
                                                       max="<?php echo $item['stock_quantity']; ?>"
                                                       class="w-16 text-center border-x-2 border-gray-300 py-1 focus:outline-none"
                                                       onchange="updateQuantity(<?php echo $item['cart_id']; ?>, this.value)">
                                                <button onclick="updateQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['quantity'] + 1; ?>)" 
                                                        class="px-3 py-1 hover:bg-gray-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Stock Status -->
                                            <?php if ($item['stock_status'] === 'low_stock'): ?>
                                            <span class="text-xs text-orange-600 font-semibold">
                                                Only <?php echo $item['stock_quantity']; ?> left!
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Price & Remove -->
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-gray-900 mb-2">
                                            $<?php echo number_format($item['subtotal'], 2); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 mb-3">
                                            $<?php echo number_format($item['price'], 2); ?> each
                                        </p>
                                        <button onclick="removeItem(<?php echo $item['cart_id']; ?>)" 
                                                class="text-red-600 hover:text-red-800 font-semibold text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Continue Shopping -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <a href="store.php" class="text-yellow-600 hover:text-yellow-700 font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4">
                            <h2 class="text-2xl font-bold">Order Summary</h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span class="font-semibold">$<?php echo number_format($cart_total, 2); ?></span>
                            </div>
                            
                            <!-- Tax -->
                            <div class="flex justify-between text-gray-700">
                                <span>Tax (10%)</span>
                                <span class="font-semibold">$<?php echo number_format($tax_amount, 2); ?></span>
                            </div>
                            
                            <!-- Shipping -->
                            <div class="flex justify-between text-gray-700">
                                <span>Shipping</span>
                                <span class="font-semibold">
                                    <?php if ($shipping_fee == 0): ?>
                                        <span class="text-green-600">FREE</span>
                                    <?php else: ?>
                                        $<?php echo number_format($shipping_fee, 2); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if ($cart_total < 100 && $cart_total > 0): ?>
                            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Add $<?php echo number_format(100 - $cart_total, 2); ?> more</strong> for free shipping!
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="border-t-2 border-gray-200 pt-4">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-xl font-bold text-gray-900">Total</span>
                                    <span class="text-3xl font-bold text-green-600">$<?php echo number_format($grand_total, 2); ?></span>
                                </div>
                                
                                <!-- Checkout Button -->
                                <a href="checkout.php" class="block w-full bg-gradient-to-r from-green-500 to-green-600 text-white text-center py-4 rounded-lg font-bold text-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg mb-3">
                                    Proceed to Checkout
                                </a>
                                
                                <!-- PayPal Button (Placeholder) -->
                                <button class="block w-full bg-yellow-400 text-gray-900 text-center py-4 rounded-lg font-bold hover:bg-yellow-500 transition-all">
                                    PayPal Checkout
                                </button>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Secure Checkout</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                    </svg>
                                    <span>Free Returns within 30 days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// Update quantity
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(cartId);
        return;
    }
    
    fetch('../includes/cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_cart&cart_id=${cartId}&quantity=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Remove item
function removeItem(cartId) {
    if (!confirm('Remove this item from your cart?')) return;
    
    fetch('../includes/cart-actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove_from_cart&cart_id=${cartId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php include '../includes/footer.php'; ?>
