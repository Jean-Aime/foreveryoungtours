<?php

require_once 'config.php';
$page_title = "Travel Calendar - iForYoungTours | Events, Departures & Promotions";
$page_description = "View upcoming departures, special events, and seasonal promotions. Plan your African adventure with our comprehensive travel calendar.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";
$js_path = "../assets/js/main.js";
include './header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-r from-blue-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Travel <span class="text-gradient">Calendar</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Plan your perfect African adventure with our comprehensive calendar. View departures, special events, and seasonal promotions.
            </p>
        </div>
    </div>
</section>

<!-- Calendar Controls -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            <div class="flex items-center space-x-4">
                <button onclick="previousMonth()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2 id="current-month" class="text-2xl font-bold text-gray-900">December 2024</h2>
                <button onclick="nextMonth()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Departures</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Promotions</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Events</span>
                </div>
            </div>
        </div>
        
        <!-- Calendar -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Calendar Header -->
            <div class="calendar-grid bg-gray-50">
                <div class="p-4 text-center font-semibold text-gray-700">Sun</div>
                <div class="p-4 text-center font-semibold text-gray-700">Mon</div>
                <div class="p-4 text-center font-semibold text-gray-700">Tue</div>
                <div class="p-4 text-center font-semibold text-gray-700">Wed</div>
                <div class="p-4 text-center font-semibold text-gray-700">Thu</div>
                <div class="p-4 text-center font-semibold text-gray-700">Fri</div>
                <div class="p-4 text-center font-semibold text-gray-700">Sat</div>
            </div>
            
            <!-- Calendar Days -->
            <div id="calendar-days" class="calendar-grid">
                <!-- Days will be dynamically generated -->
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Upcoming Events & Departures</h2>
            <p class="text-xl text-gray-600">Don't miss these exciting opportunities</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="upcoming-events">
            <!-- Events will be dynamically loaded -->
        </div>
    </div>
</section>

<!-- Seasonal Highlights -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Seasonal Highlights</h2>
            <p class="text-xl text-gray-600">The best times to experience Africa's wonders</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Summer (Dec-Feb)</h3>
                <p class="text-gray-600 mb-4">Perfect for beach destinations and Southern Africa safaris. Warm weather and excellent wildlife viewing.</p>
                <div class="text-blue-600 font-semibold">Peak Season</div>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Autumn (Mar-May)</h3>
                <p class="text-gray-600 mb-4">Ideal for East African safaris with the Great Migration. Mild temperatures and fewer crowds.</p>
                <div class="text-green-600 font-semibold">Great Migration</div>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Winter (Jun-Aug)</h3>
                <p class="text-gray-600 mb-4">Best time for North Africa and dry season safaris. Cool weather perfect for desert adventures.</p>
                <div class="text-yellow-600 font-semibold">Dry Season</div>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Spring (Sep-Nov)</h3>
                <p class="text-gray-600 mb-4">Shoulder season with good prices and pleasant weather. Perfect for cultural experiences.</p>
                <div class="text-purple-600 font-semibold">Shoulder Season</div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-red-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Stay Updated</h2>
        <p class="text-xl text-blue-100 mb-8">Get notified about new departures, special events, and exclusive promotions.</p>
        
        <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            <input type="email" placeholder="Enter your email address" class="flex-1 px-6 py-4 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none">
            <button type="submit" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Subscribe
            </button>
        </form>
    </div>
</section>

<!-- JavaScript -->
<script src="<?= getImageUrl('assets/js/main.js') ?>"></script>
<script src="<?= getImageUrl('assets/js/pages.js') ?>"></script>

<?php include '../includes/footer.php'; ?>