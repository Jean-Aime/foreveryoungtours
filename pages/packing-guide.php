<?php
$page_title = "Packing Guide - Forever Young Tours | Travel Smart. Pack Bold.";
$page_description = "Our curated packing guides take the guesswork out of preparation. Download tailored guides for your specific tour type—Safari, Cruise, Heritage, or Adventure.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=2072&q=80" alt="Packing Guide" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-purple-500 text-white rounded-full text-sm font-semibold mb-6">
                Packing Guide
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Travel Smart. Pack Bold.
            </h1>
            <p class="text-xl text-gray-200 mb-8 max-w-4xl mx-auto">
                Our curated packing guides take the guesswork out of preparation. Each destination has its own checklist—crafted by our travel experts and Advisors—covering climate, comfort, and class.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="btn-primary px-8 py-4 rounded-lg font-semibold">
                    → Download Your Guide
                </button>
                <button class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:border-yellow-500 hover:text-yellow-600 transition-all">
                    View Sample Guide
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Tour Type Selection -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Choose Your Tour Type</h2>
            <p class="text-lg text-gray-600">Download tailored guides for your specific tour type and travel prepared, every time.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Safari Guide -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Safari Packing</h3>
                <p class="text-gray-600 mb-6">Essential gear for wildlife adventures, including clothing, equipment, and safety items for African safaris.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold">Download Safari Guide</button>
            </div>

            <!-- Cruise Guide -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 6c0-1.1-.9-2-2-2h-2l-1.5-1.5c-.4-.4-.9-.5-1.4-.5H9.9c-.5 0-1 .2-1.4.5L7 4H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Cruise Packing</h3>
                <p class="text-gray-600 mb-6">Complete checklist for ocean cruises, including formal wear, casual attire, and onboard essentials.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold">Download Cruise Guide</button>
            </div>

            <!-- Heritage Guide -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Heritage Tours</h3>
                <p class="text-gray-600 mb-6">Cultural immersion essentials, respectful attire, and items for historical site visits.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold">Download Heritage Guide</button>
            </div>

            <!-- Adventure Guide -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 6l-3.75 5 2.85 3.8-1.6 1.2C9.81 13.75 7 10 7 10l-6 8h22L14 6z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Adventure Tours</h3>
                <p class="text-gray-600 mb-6">High-activity gear, outdoor equipment, and specialized clothing for adventure expeditions.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold">Download Adventure Guide</button>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">What's Included in Every Guide</h2>
            <p class="text-lg text-gray-600">Comprehensive checklists designed by travel experts</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zM4 19h16v2H4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Climate-Based Recommendations</h3>
                <p class="text-gray-600">Weather-appropriate clothing and gear suggestions for your specific destination and travel dates.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 4H4c-1.11 0-2 .89-2 2v3h2V6h4V4zm6 0v2h4v3h2V6c0-1.11-.89-2-2-2h-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Carry-On Essentials</h3>
                <p class="text-gray-600">Must-have items for your carry-on bag, including documents, medications, and comfort items.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Travel Gear Optimization</h3>
                <p class="text-gray-600">Space-saving tips and multi-purpose item recommendations to maximize your luggage efficiency.</p>
            </div>
        </div>
    </div>
</section>

<!-- Download Options -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Available Formats</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Printable PDF</h3>
                <p class="text-gray-600 text-sm">Download and print for offline use</p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 19H7V5h7v7h3v7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Digital Checklist</h3>
                <p class="text-gray-600 text-sm">Interactive online version with checkboxes</p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 18c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1zM7 18c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Mobile App</h3>
                <p class="text-gray-600 text-sm">Sync to FYT mobile app for on-the-go access</p>
            </div>
        </div>
        
        <div class="mt-12">
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg">
                Get Started with Your Packing Guide
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
