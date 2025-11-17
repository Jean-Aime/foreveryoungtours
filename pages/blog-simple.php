<?php

require_once 'config.php';
$page_title = "Travel Stories & Experiences - Forever Young Tours Blog";
$page_description = "Read amazing travel stories and share your own African adventure experiences. Discover insider tips, cultural insights, and inspiring journeys.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-12 bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Travel <span class="text-gradient">Stories</span> & Experiences
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Discover amazing African adventures through the eyes of fellow travelers. Share your own story and inspire others to explore.
            </p>
            <div class="flex justify-center">
                <a href="#stories" class="btn-primary px-8 py-4 rounded-2xl font-semibold">Read Stories</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Story -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Story</h2>
        </div>
        <div class="nextcloud-card overflow-hidden">
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="relative">
                    <img src="<?= getImageUrl('assets/images/Africa Travel.jpg') ?>" alt="Featured Story" class="w-full h-80 lg:h-full object-cover rounded-2xl">
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-sm font-semibold text-white bg-blue-500">
                        Adventure
                    </span>
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">My Journey Through East Africa</h3>
                    <p class="text-gray-600 mb-6 text-lg">An incredible 14-day adventure through Kenya, Tanzania, and Uganda. From the Serengeti to Mount Kilimanjaro, this journey changed my perspective on travel.</p>
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                            <span class="font-medium text-gray-900">Sarah Johnson</span>
                        </div>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-500">Nov 1, 2024</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-500">1,234 views</span>
                    </div>
                    <div class="text-primary-gold font-semibold">Read Full Story →</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section id="stories" class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Stories</h2>
            <p class="text-xl text-gray-600">Coming soon - Share your travel experiences with the community</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <!-- Sample Story Cards -->
            <article class="nextcloud-card overflow-hidden">
                <div class="relative">
                    <img src="<?= getImageUrl('assets/images/Destination.jpg') ?>" alt="Story" class="w-full h-48 object-cover">
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-semibold text-white bg-green-500">
                        Culture
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Discovering Ghana's Rich Heritage</h3>
                    <p class="text-gray-600 mb-4">A cultural immersion into the heart of West Africa, exploring ancient castles and vibrant markets...</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900">Michael Chen</span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Oct 28</span>
                            <span>892 views</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="nextcloud-card overflow-hidden">
                <div class="relative">
                    <img src="<?= getImageUrl('assets/images/Africa Travel.jpg') ?>" alt="Story" class="w-full h-48 object-cover">
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-semibold text-white bg-orange-500">
                        Safari
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Wildlife Photography in Botswana</h3>
                    <p class="text-gray-600 mb-4">Capturing the Big Five in their natural habitat during an unforgettable safari experience...</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900">Emma Wilson</span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Oct 25</span>
                            <span>1,156 views</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="nextcloud-card overflow-hidden">
                <div class="relative">
                    <img src="<?= getImageUrl('assets/images/landscape.jpg') ?>" alt="Story" class="w-full h-48 object-cover">
                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-semibold text-white bg-purple-500">
                        Adventure
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Hiking the Drakensberg Mountains</h3>
                    <p class="text-gray-600 mb-4">A challenging trek through South Africa's most spectacular mountain range...</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900">David Park</span>
                        </div>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Oct 22</span>
                            <span>743 views</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-yellow-50 to-orange-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-gray-900 mb-6">Share Your Story</h2>
        <p class="text-xl text-gray-600 mb-8">Have an amazing African travel experience? We'd love to hear about it and share it with our community.</p>
        <button class="btn-primary px-8 py-4 rounded-2xl font-semibold">Submit Your Story</button>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
