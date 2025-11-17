<?php

require_once 'config.php';
$page_title = "Cultural Heritage Tours - iForYoungTours";
$page_description = "Immerse yourself in rich African cultures, traditions, and historical sites across multiple countries.";
$base_path = "../";
$css_path = "../assets/css/modern-styles.css";
include './header.php';
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 py-20">
        <div class="absolute inset-0 bg-[url('../assets/images/africa.png')] bg-cover bg-center opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6">
                Cultural Heritage Tours
            </h1>
            <p class="text-xl text-slate-600 mb-8 max-w-3xl mx-auto">
                Immerse yourself in the rich tapestry of African cultures, traditions, and historical sites that have shaped civilizations for millennia.
            </p>
            <a href="tour.php?id=5" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Cultural Tour
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Cultural Experiences</h2>
                <p class="text-xl text-slate-600">Discover the authentic heart of Africa</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-rose-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Traditional Villages</h3>
                    <p class="text-slate-600">Visit authentic villages and experience traditional ways of life that have been preserved for generations.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Historical Sites</h3>
                    <p class="text-slate-600">Explore ancient ruins, archaeological sites, and monuments that tell the story of Africa's rich history.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Arts & Crafts</h3>
                    <p class="text-slate-600">Learn traditional crafts, participate in art workshops, and meet local artisans creating beautiful works.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-amber-50 to-orange-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-slate-900 mb-6">Ready to Explore African Culture?</h2>
            <p class="text-xl text-slate-600 mb-8">Join us on an unforgettable journey through Africa's cultural heritage</p>
            <a href="tour.php?id=5" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Your Cultural Tour
            </a>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>