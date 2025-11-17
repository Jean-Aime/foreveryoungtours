<?php

require_once 'config.php';
$page_title = "Travel Tips - Forever Young Tours | Insider Knowledge. Global Advantage.";
$page_description = "Leverage our network's experience to travel with confidence. From flight timing and insurance coverage to local currency insights and cultural etiquette.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative pt-24 pb-16 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1920&q=80" alt="Travel Tips" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/30"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-semibold mb-6">
                Travel Tips
            </div>
            <h1 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                Insider Knowledge. Global Advantage.
            </h1>
            <p class="text-xl text-white/95 mb-8 max-w-4xl mx-auto">
                Leverage our network's experience to travel with confidence. From flight timing and insurance coverage to local currency insights and cultural etiquette, our tips keep you ahead of every challenge.
            </p>
            <div class="text-lg text-white/90 mb-8">
                <strong>Refreshed quarterly by our MCA & Advisor Network</strong> — these insights evolve as fast as the world does.
            </div>
            <button onclick="openInquiryModal('', 'Travel Tips Consultation')" class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-yellow-500 hover:bg-yellow-600 hover:shadow-xl transition-all">
                → Get Personalized Travel Tips
            </button>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Expert Travel Advice Categories</h2>
            <p class="text-lg text-gray-600">Curated insights from FYT Advisors covering every aspect of travel</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Pre-Departure Planning -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zM4 19h16v2H4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Pre-Departure Planning</h3>
                <p class="text-gray-600 mb-6">Essential preparation steps, documentation requirements, and booking strategies for seamless travel.</p>
                <button class="text-yellow-600 font-semibold hover:text-yellow-700">Read Tips →</button>
            </div>

            <!-- Airport Navigation -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Airport Navigation</h3>
                <p class="text-gray-600 mb-6">Insider tips for efficient check-in, security procedures, and making the most of layovers.</p>
                <button class="text-yellow-600 font-semibold hover:text-yellow-700">Read Tips →</button>
            </div>

            <!-- Currency Management -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Currency Management</h3>
                <p class="text-gray-600 mb-6">Smart money strategies, exchange rates, and payment methods for international travel.</p>
                <button class="text-yellow-600 font-semibold hover:text-yellow-700">Read Tips →</button>
            </div>

            <!-- Sustainable Travel -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.66c.03-.08.06-.17.09-.25.05-.14.11-.28.19-.41.02-.04.04-.07.05-.11.13-.22.28-.42.45-.59.04-.04.09-.07.13-.11.3-.25.64-.42 1.01-.51.12-.03.25-.05.37-.06.06 0 .12-.01.18-.01.06 0 .12.01.18.01.12.01.25.03.37.06.37.09.7.26 1.01.51.04.04.09.07.13.11.17.17.32.37.45.59.01.04.03.07.05.11.08.13.14.27.19.41.03.08.06.17.09.25l.95 2.66 1.89-.66C18.1 16.17 16 10 17 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Sustainable Travel</h3>
                <p class="text-gray-600 mb-6">Eco-friendly practices, responsible tourism, and ways to minimize your environmental impact.</p>
                <button class="text-yellow-600 font-semibold hover:text-yellow-700">Read Tips →</button>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tips Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Travel Tips</h2>
            <p class="text-lg text-gray-600">Updated quarterly with contributions from regional MCAs</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tip 1 -->
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80" alt="Travel Documents" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-yellow-600 font-semibold mb-2">PRE-DEPARTURE</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Essential Documents Checklist</h3>
                    <p class="text-gray-600 mb-4">Never forget important documents again with our comprehensive pre-travel checklist covering passports, visas, insurance, and more.</p>
                    
                </div>
            </article>

            <!-- Tip 2 -->
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1580519542036-c47de6196ba5?w=800&q=80" alt="Currency Exchange" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-yellow-600 font-semibold mb-2">MONEY MATTERS</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Currency Exchange Tips</h3>
                    <p class="text-gray-600 mb-4">Maximize your travel budget with insider knowledge on when and where to exchange money for the best rates.</p>
                   
                </div>
            </article>

            <!-- Tip 3 -->
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="relative h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&q=80" alt="Sustainable Travel" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-yellow-600 font-semibold mb-2">SUSTAINABLE TRAVEL</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Eco-Friendly Travel Practices</h3>
                    <p class="text-gray-600 mb-4">Travel responsibly with practical tips for reducing your environmental footprint while exploring the world.</p>
                  
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Expert Contributors -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Expert Contributors</h2>
            <p class="text-lg text-gray-600">Tips curated by our network of Master Certified Agents and Travel Advisors</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Master Certified Agents</h3>
                <p class="text-gray-600">Licensed professionals with extensive travel industry experience across Global Africa and the Caribbean.</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-4h3v7H5v-5H2v-2l15-2c1.3-.17 2.24-1.3 2.24-2.6 0-1.66-1.35-3-3.01-3-1.6 0-2.9 1.25-2.99 2.84l-1.74.19c.15-2.3 2.18-4.04 4.73-4.04 2.73 0 5.01 2.28 5.01 5.01 0 2.17-1.4 4.08-3.5 4.7L4 18z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Regional Advisors</h3>
                <p class="text-gray-600">Local experts providing destination-specific insights and cultural knowledge for authentic travel experiences.</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">FYT Ambassadors</h3>
                <p class="text-gray-600">Seasoned travelers and industry leaders sharing advanced strategies and insider knowledge.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Stay Updated with Latest Travel Tips</h2>
        <p class="text-xl text-white/90 mb-8">Get quarterly updates with the latest insights from our global network of travel experts.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white/50">
            <button class="px-6 py-3 bg-white text-yellow-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Subscribe
            </button>
        </div>
    </div>
</section>

<?php 
include 'inquiry-modal.php';
include '../includes/footer.php'; 
?>
