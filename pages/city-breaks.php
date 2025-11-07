<?php
$page_title = "City Breaks - iForYoungTours";
$page_description = "Explore vibrant African cities with guided tours, cultural experiences, and urban adventures.";
$base_path = "../";
$css_path = "../assets/css/modern-styles.css";
include './header.php';
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-teal-50 via-blue-50 to-indigo-50 py-20">
        <div class="absolute inset-0 bg-[url('../assets/images/africa.png')] bg-cover bg-center opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6">
                City Breaks
            </h1>
            <p class="text-xl text-slate-600 mb-8 max-w-3xl mx-auto">
                Discover the vibrant pulse of Africa's major cities with guided tours, cultural experiences, and urban adventures.
            </p>
            <a href="tour.php?id=9" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book City Break
            </a>
        </div>
    </section>

    <!-- Cities Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Urban Adventures</h2>
                <p class="text-xl text-slate-600">Explore Africa's dynamic cities</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Cape Town</h3>
                        <p class="text-slate-600">Explore the Mother City's vibrant neighborhoods, world-class restaurants, and stunning waterfront.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Nairobi</h3>
                        <p class="text-slate-600">Experience Kenya's bustling capital with its modern skyline, markets, and nearby national park.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Lagos</h3>
                        <p class="text-slate-600">Discover Nigeria's economic hub with its vibrant culture, music scene, and bustling markets.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Marrakech</h3>
                        <p class="text-slate-600">Explore Morocco's imperial city with its ancient medina, souks, and stunning architecture.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Johannesburg</h3>
                        <p class="text-slate-600">Experience South Africa's largest city with its rich history, museums, and vibrant townships.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                    <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Cairo</h3>
                        <p class="text-slate-600">Discover Egypt's capital with its ancient pyramids, bustling bazaars, and Nile River views.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-teal-50 to-blue-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-slate-900 mb-6">Explore Urban Africa</h2>
            <p class="text-xl text-slate-600 mb-8">Discover the energy and culture of Africa's greatest cities</p>
            <a href="tour.php?id=9" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Your City Break
            </a>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>