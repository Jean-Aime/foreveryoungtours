<?php
$page_title = "Senior Discounts - Forever Young Tours | Exclusive Packages for Travelers 50+";
$page_description = "Exclusive packages and loyalty tiers for travelers aged 50+ via FYT Partner Network. Special rates, group discounts, and premium services designed for mature travelers.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-purple-500 text-white rounded-full text-sm font-semibold mb-6">
                Senior Discounts
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Exclusive Packages for Travelers 50+
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto">
                Discover exclusive packages, loyalty tiers, and special rates designed specifically for mature travelers. Enjoy premium services, group discounts, and tailored experiences through our FYT Partner Network.
            </p>
            <div class="text-lg text-gray-700 mb-8">
                <strong>Age is just a number. Adventure is timeless.</strong>
            </div>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-purple-600 hover:bg-purple-700">
                → View Senior Packages
            </button>
        </div>
    </div>
</section>

<!-- Discount Tiers -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Senior Loyalty Tiers</h2>
            <p class="text-lg text-gray-600">Increasing benefits with every adventure</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Silver Tier -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Silver Explorer</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">10% OFF</div>
                    <div class="text-gray-600">Ages 50-59</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        10% discount on all tours
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Complimentary travel insurance
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Priority booking support
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Flexible cancellation policy
                    </li>
                </ul>
                <button class="w-full py-3 border-2 border-purple-500 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition-all">
                    Learn More
                </button>
            </div>

            <!-- Gold Tier -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-yellow-500 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <div class="bg-yellow-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</div>
                </div>
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Gold Adventurer</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">15% OFF</div>
                    <div class="text-gray-600">Ages 60-69</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        All Silver benefits
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        15% discount on all tours
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Complimentary airport transfers
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Room upgrade (subject to availability)
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Dedicated senior travel advisor
                    </li>
                </ul>
                <button class="w-full py-3 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                    Choose Gold
                </button>
            </div>

            <!-- Platinum Tier -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Platinum Legend</h3>
                    <div class="text-3xl font-bold text-purple-600 mb-2">20% OFF</div>
                    <div class="text-gray-600">Ages 70+</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        All Gold benefits
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        20% discount on all tours
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        VIP concierge services
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Medical assistance on tours
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Complimentary companion discount
                    </li>
                </ul>
                <button class="w-full py-3 border-2 border-purple-500 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition-all">
                    Choose Platinum
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Special Features -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Senior-Friendly Features</h2>
            <p class="text-lg text-gray-600">Designed with mature travelers in mind</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Comfortable Pace</h3>
                <p class="text-gray-600 text-sm">Relaxed itineraries with ample rest time and flexible scheduling</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Accessible Accommodations</h3>
                <p class="text-gray-600 text-sm">Senior-friendly hotels with accessibility features and medical facilities nearby</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Group Discounts</h3>
                <p class="text-gray-600 text-sm">Special rates for senior groups and travel companions</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Medical Support</h3>
                <p class="text-gray-600 text-sm">On-call medical assistance and travel insurance coverage</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-purple-600 to-pink-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready for Your Next Adventure?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of senior travelers who have discovered the world with Forever Young Tours.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-4 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                View Senior Tours
            </button>
            <button class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-all">
                Contact Senior Advisor
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
