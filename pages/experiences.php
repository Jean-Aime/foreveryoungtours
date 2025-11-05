<?php
$page_title = "African Experiences - iForYoungTours | Safari, Culture & Adventure";
$page_description = "Discover curated African experiences from luxury safaris to cultural immersions. Choose your perfect adventure style and create unforgettable memories.";
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
                African <span class="text-gradient">Experiences</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Choose your perfect African adventure style. From thrilling safaris to cultural immersions, luxury escapes to adrenaline-pumping adventures.
            </p>
        </div>
    </div>
</section>

<!-- Experience Filters -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
            <button onclick="filterByExperience('all')" class="experience-filter active px-6 py-3 rounded-full font-medium">
                All Experiences
            </button>
            <button onclick="filterByExperience('safari')" class="experience-filter px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Safari & Wildlife
            </button>
            <button onclick="filterByExperience('cultural')" class="experience-filter px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Cultural Heritage
            </button>
            <button onclick="filterByExperience('beach')" class="experience-filter px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Beach & Relaxation
            </button>
            <button onclick="filterByExperience('adventure')" class="experience-filter px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Adventure & Sports
            </button>
            <button onclick="filterByExperience('luxury')" class="experience-filter px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Luxury Experiences
            </button>
        </div>
    </div>
</section>

<!-- Experience Categories -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="experiences-container">
            <!-- Experience sections will be dynamically loaded here -->
        </div>
    </div>
</section>

<!-- Featured Experiences Carousel -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Most Popular Experiences</h2>
            <p class="text-xl text-gray-600">Discover the adventures that travelers love most</p>
        </div>
        
        <div class="splide" id="popular-experiences">
            <div class="splide__track">
                <ul class="splide__list">
                    <li class="splide__slide">
                        <div class="relative rounded-2xl overflow-hidden experience-card cursor-pointer">
                            <img src="https://kimi-web-img.moonshot.cn/img/cdn.pixabay.com/b48c23d2697572a428c82f4586d7d4d00cf1c896.jpg" alt="Great Migration Safari" class="w-full h-80 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Great Migration Safari</h3>
                                <p class="text-gray-200 mb-4">Witness nature's greatest spectacle in Kenya and Tanzania</p>
                                <div class="flex items-center space-x-4">
                                    <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">From $3,299</span>
                                    <span class="text-sm">7-14 days</span>
                                    <span class="text-sm">★ 4.9 (2,847 reviews)</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="splide__slide">
                        <div class="relative rounded-2xl overflow-hidden experience-card cursor-pointer">
                            <img src="https://kimi-web-img.moonshot.cn/img/images.squarespace-cdn.com/e64bfa69368dd0572e5cb9a25c221c42a846b95d.jpeg" alt="Sahara Desert Adventure" class="w-full h-80 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Sahara Desert Adventure</h3>
                                <p class="text-gray-200 mb-4">Camel trekking and luxury desert camping in Morocco</p>
                                <div class="flex items-center space-x-4">
                                    <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">From $2,199</span>
                                    <span class="text-sm">6-10 days</span>
                                    <span class="text-sm">★ 4.8 (1,623 reviews)</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="splide__slide">
                        <div class="relative rounded-2xl overflow-hidden experience-card cursor-pointer">
                            <img src="https://kimi-web-img.moonshot.cn/img/assets.website-files.com/dcda843a2d3e8172aa8604e745dd50321b6288e1.jpg" alt="Gorilla Trekking" class="w-full h-80 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Gorilla Trekking</h3>
                                <p class="text-gray-200 mb-4">Intimate encounters with endangered mountain gorillas</p>
                                <div class="flex items-center space-x-4">
                                    <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">From $4,899</span>
                                    <span class="text-sm">4-7 days</span>
                                    <span class="text-sm">★ 4.9 (1,156 reviews)</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="splide__slide">
                        <div class="relative rounded-2xl overflow-hidden experience-card cursor-pointer">
                            <img src="https://kimi-web-img.moonshot.cn/img/kreolmagazine.com/fd583351bb188ce6deb799ac8fd78f9b62ca251e.jpg" alt="Tropical Paradise" class="w-full h-80 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="text-2xl font-bold mb-2">Tropical Paradise</h3>
                                <p class="text-gray-200 mb-4">Luxury beach resorts and crystal-clear waters</p>
                                <div class="flex items-center space-x-4">
                                    <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-semibold">From $2,899</span>
                                    <span class="text-sm">5-12 days</span>
                                    <span class="text-sm">★ 4.7 (892 reviews)</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Additional JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

<script src="../assets/js/pages.js"></script>

<?php include '../includes/footer.php'; ?>