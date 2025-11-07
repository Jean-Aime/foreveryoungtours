<?php
$page_title = "Travel Store - Forever Young Tours | Essential Travel Gear & Equipment";
$page_description = "Shop essential travel gear, equipment, and accessories for your next adventure. Quality camping gear, hiking equipment, and travel essentials.";
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";

include '../includes/header.php';
?>

<!-- Store Header Section -->
<section class="pt-24 pb-12 bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-yellow-500 text-black rounded-full text-sm font-semibold mb-6">
                Travel Store
            </div>
            <h1 class="text-4xl lg:text-4xl font-bold text-gray-900 mb-6">
                Before you book, Take a look
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                These might come in handy during your trip.
            </p>
            <div class="flex justify-center">
                <button class="inline-flex items-center px-6 py-3 bg-yellow-500 text-black rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                    View All →
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Product 1: Camping Tent -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Sale</span>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=400&fit=crop&crop=center" 
                             alt="Camping Tent" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-2">
                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">Warm</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Camping Tent</h3>
                    <p class="text-gray-600 text-sm mb-4">Hiking water for fresh water</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$15</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product 2: Hiking Shoes -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Sale</span>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="../assets/images/shoes.jpg" 
                             alt="Hiking Shoes" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Shoes</h3>
                    <p class="text-gray-600 text-sm mb-4">Hiking water for fresh water</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$15</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product 3: Travel Bottle -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Sale</span>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop&crop=center" 
                             alt="Travel Bottle" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Travel Bottle</h3>
                    <p class="text-gray-600 text-sm mb-4">Compact travel water bottle</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$20</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product 4: Travel Backpack -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop&crop=center" 
                             alt="Travel Backpack" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Travel Backpack</h3>
                    <p class="text-gray-600 text-sm mb-4">Durable hiking and travel backpack</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$45</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product 5: Travel Pillow -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="../assets/images/travel pillow.jpg" 
                             alt="Travel Pillow" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Travel Pillow</h3>
                    <p class="text-gray-600 text-sm mb-4">Comfortable neck support pillow</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$25</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product 6: First Aid Kit -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <button class="w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-8">
                        <img src="../assets/images/first aid kit.jpg" 
                             alt="First Aid Kit" 
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">First Aid Kit</h3>
                    <p class="text-gray-600 text-sm mb-4">Essential medical supplies for travel</p>
                    
                    <!-- Quantity Selector -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="font-semibold">1</span>
                            <button class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">$30</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button class="flex-1 bg-yellow-500 text-black font-semibold py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Add to cart
                        </button>
                        <button class="flex-1 border border-yellow-500 text-yellow-600 font-semibold py-3 rounded-lg hover:bg-yellow-50 transition-colors">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

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
// Add to cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('button:contains("Add to cart")');
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    
    // Handle quantity changes
    document.querySelectorAll('button').forEach(button => {
        if (button.querySelector('svg path[d*="M20 12H4"]')) {
            // Decrease button
            button.addEventListener('click', function() {
                const quantitySpan = this.parentElement.querySelector('span');
                let quantity = parseInt(quantitySpan.textContent);
                if (quantity > 1) {
                    quantitySpan.textContent = quantity - 1;
                }
            });
        } else if (button.querySelector('svg path[d*="M12 4v16m8-8H4"]')) {
            // Increase button
            button.addEventListener('click', function() {
                const quantitySpan = this.parentElement.querySelector('span');
                let quantity = parseInt(quantitySpan.textContent);
                quantitySpan.textContent = quantity + 1;
            });
        }
    });
    
    // Handle add to cart
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Add to cart')) {
            button.addEventListener('click', function() {
                // Simple animation feedback
                this.style.transform = 'scale(0.95)';
                this.textContent = 'Added!';
                this.style.backgroundColor = '#10b981';
                
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                    this.textContent = 'Add to cart';
                    this.style.backgroundColor = '';
                }, 1000);
            });
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>