<?php
$page_title = "Agro Tourism Experience - iForYoungTours";
$page_description = "Explore sustainable farming practices and rural communities while enjoying farm-to-table experiences.";
$base_path = "../";
$css_path = "../assets/css/modern-styles.css";
include './header.php';
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-20">
        <div class="absolute inset-0 bg-[url('../assets/images/africa.png')] bg-cover bg-center opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6">
                Agro Tourism Experience
            </h1>
            <p class="text-xl text-slate-600 mb-8 max-w-3xl mx-auto">
                Discover sustainable farming practices, connect with rural communities, and enjoy authentic farm-to-table experiences across Africa.
            </p>
            <a href="tour.php?id=7" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Agro Tour
            </a>
        </div>
    </section>

    <!-- Experiences Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Farm Experiences</h2>
                <p class="text-xl text-slate-600">Connect with nature and sustainable agriculture</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l-3-3m3 3l3-3"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Organic Farming</h3>
                    <p class="text-slate-600">Learn sustainable farming techniques and participate in organic crop cultivation.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Farm-to-Table</h3>
                    <p class="text-slate-600">Enjoy fresh, locally sourced meals prepared with ingredients straight from the farm.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Rural Communities</h3>
                    <p class="text-slate-600">Connect with local farming communities and learn about traditional agricultural practices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-slate-900 mb-6">Experience Sustainable Agriculture</h2>
            <p class="text-xl text-slate-600 mb-8">Join us for an authentic agro-tourism experience in rural Africa</p>
            <a href="tour.php?id=7" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Your Agro Tour
            </a>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>