<?php
$page_title = "Destination Guides - Forever Young Tours | Comprehensive Travel Insights";
$page_description = "Comprehensive overviews of climate, history, must-see sites, festivals, and cuisine for destinations across Global Africa and the Caribbean.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-orange-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-semibold mb-6">
                Destination Guides
            </div>
            <h1 class="text-4xl lg:text-4xl font-bold text-gray-900 mb-6">
                Comprehensive Travel Insights
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto">
                Detailed destination guides covering climate, history, must-see sites, festivals, and cuisine. Explore the rich tapestry of Global Africa and the Caribbean with insider knowledge from our travel experts.
            </p>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-orange-600 hover:bg-orange-700">
                → Explore Destinations
            </button>
        </div>
    </div>
</section>

<!-- Featured Destinations -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Destination Guides</h2>
            <p class="text-lg text-gray-600">Discover the best of Africa and the Caribbean</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Kenya Guide -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-green-400 to-blue-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kenya</h3>
                    <p class="text-gray-600 mb-4">Safari capital with diverse wildlife, Maasai culture, and stunning landscapes from Maasai Mara to coastal Mombasa.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">East Africa</span>
                        <button class="text-orange-600 font-semibold hover:text-orange-700">Read Guide →</button>
                    </div>
                </div>
            </div>

            <!-- Ghana Guide -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-yellow-400 to-red-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Ghana</h3>
                    <p class="text-gray-600 mb-4">Gateway to West Africa with rich history, vibrant markets, and the historic Cape Coast castles.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">West Africa</span>
                        <button class="text-orange-600 font-semibold hover:text-orange-700">Read Guide →</button>
                    </div>
                </div>
            </div>

            <!-- Barbados Guide -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-teal-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Barbados</h3>
                    <p class="text-gray-600 mb-4">Caribbean paradise with pristine beaches, rum heritage, and vibrant Bajan culture.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Caribbean</span>
                        <button class="text-orange-600 font-semibold hover:text-orange-700">Read Guide →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Guide Categories -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">What's Covered in Our Guides</h2>
            <p class="text-lg text-gray-600">Comprehensive information for every aspect of your journey</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Climate & Weather</h3>
                <p class="text-gray-600 text-sm">Best times to visit, seasonal patterns, and weather expectations</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">History & Culture</h3>
                <p class="text-gray-600 text-sm">Rich heritage, cultural traditions, and historical significance</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Must-See Sites</h3>
                <p class="text-gray-600 text-sm">Top attractions, hidden gems, and iconic landmarks</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Festivals & Events</h3>
                <p class="text-gray-600 text-sm">Cultural celebrations, seasonal festivals, and special events</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Cuisine & Dining</h3>
                <p class="text-gray-600 text-sm">Local dishes, dining customs, and culinary experiences</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-orange-600 to-red-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Explore?</h2>
        <p class="text-xl text-white/90 mb-8">Browse our comprehensive destination guides and start planning your next adventure.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-4 bg-white text-orange-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Browse All Guides
            </button>
            <button class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-orange-600 transition-all">
                Plan Your Trip
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
