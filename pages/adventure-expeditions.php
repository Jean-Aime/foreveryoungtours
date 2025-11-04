<?php
$page_title = "Adventure Expeditions - iForYoungTours";
$page_description = "Ultimate adventure package including mountain climbing, desert expeditions, and wilderness survival.";
$base_path = "../";
$css_path = "../assets/css/modern-styles.css";
include '../includes/header.php';
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-orange-50 via-red-50 to-pink-50 py-20">
        <div class="absolute inset-0 bg-[url('../assets/images/africa.png')] bg-cover bg-center opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6">
                Adventure Expeditions
            </h1>
            <p class="text-xl text-slate-600 mb-8 max-w-3xl mx-auto">
                Embark on the ultimate adventure with extreme expeditions including mountain climbing, desert survival, and wilderness exploration.
            </p>
            <a href="tour.php?id=8" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Expedition
            </a>
        </div>
    </section>

    <!-- Expeditions Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Extreme Adventures</h2>
                <p class="text-xl text-slate-600">Push beyond your limits</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Mountain Expeditions</h3>
                    <p class="text-slate-600 mb-4">Conquer Africa's highest peaks including Kilimanjaro, Mount Kenya, and the Drakensberg mountains.</p>
                    <ul class="text-slate-600 space-y-2">
                        <li>• Technical climbing routes</li>
                        <li>• Professional guides</li>
                        <li>• Safety equipment provided</li>
                        <li>• Multi-day expeditions</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Desert Expeditions</h3>
                    <p class="text-slate-600 mb-4">Navigate the vast Sahara and Kalahari deserts with expert survival training and guidance.</p>
                    <ul class="text-slate-600 space-y-2">
                        <li>• Survival skills training</li>
                        <li>• Camel trekking</li>
                        <li>• Desert camping</li>
                        <li>• Navigation techniques</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Wilderness Survival</h3>
                    <p class="text-slate-600 mb-4">Learn essential survival skills in Africa's most challenging wilderness environments.</p>
                    <ul class="text-slate-600 space-y-2">
                        <li>• Bushcraft techniques</li>
                        <li>• Water purification</li>
                        <li>• Shelter building</li>
                        <li>• Wildlife awareness</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Extreme Sports</h3>
                    <p class="text-slate-600 mb-4">Experience heart-pounding extreme sports in spectacular African locations.</p>
                    <ul class="text-slate-600 space-y-2">
                        <li>• Base jumping</li>
                        <li>• Rock climbing</li>
                        <li>• Paragliding</li>
                        <li>• Cave exploration</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-orange-50 to-red-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-slate-900 mb-6">Ready for the Ultimate Challenge?</h2>
            <p class="text-xl text-slate-600 mb-8">Join us for the most extreme adventures Africa has to offer</p>
            <a href="tour.php?id=8" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Book Your Expedition
            </a>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>