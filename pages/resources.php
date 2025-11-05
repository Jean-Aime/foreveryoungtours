<?php
$page_title = "Travel Resources - iForYoungTours | Guides, Tips & Essential Information";
$page_description = "Comprehensive travel resources for African adventures. Visa information, safety guides, packing lists, and expert tips for your journey.";
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
                Travel <span class="text-gradient">Resources</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Everything you need to know for your African adventure. Expert guides, visa information, safety tips, and essential travel resources.
            </p>
        </div>
    </div>
</section>

<!-- Resource Categories -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button onclick="showResourceCategory('planning')" class="tab-button active px-6 py-3 rounded-full font-medium">
                Trip Planning
            </button>
            <button onclick="showResourceCategory('visas')" class="tab-button px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Visas & Documents
            </button>
            <button onclick="showResourceCategory('safety')" class="tab-button px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Health & Safety
            </button>
            <button onclick="showResourceCategory('packing')" class="tab-button px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Packing Guides
            </button>
            <button onclick="showResourceCategory('culture')" class="tab-button px-6 py-3 rounded-full font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                Culture & Etiquette
            </button>
        </div>
        
        <!-- Tab Content -->
        <div id="resource-content">
            <!-- Content will be dynamically loaded here -->
        </div>
    </div>
</section>

<!-- Quick Links -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Essential Quick Links</h2>
            <p class="text-xl text-gray-600">Quick access to the most important travel information</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('visas')">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Visa Requirements</h3>
                <p class="text-gray-600 mb-6">Check visa requirements for all 47 African countries</p>
                <div class="text-blue-600 font-semibold">Check Requirements →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('safety')">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Travel Safety</h3>
                <p class="text-gray-600 mb-6">Essential safety tips and health precautions</p>
                <div class="text-green-600 font-semibold">Safety Guide →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('packing')">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Packing Lists</h3>
                <p class="text-gray-600 mb-6">Complete packing guides for different African destinations</p>
                <div class="text-yellow-600 font-semibold">View Lists →</div>
            </div>
            
            <div class="resource-card rounded-2xl p-8 text-center cursor-pointer" onclick="showResourceCategory('culture')">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Cultural Guide</h3>
                <p class="text-gray-600 mb-6">Local customs, etiquette, and cultural insights</p>
                <div class="text-purple-600 font-semibold">Cultural Tips →</div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-600">Get answers to the most common questions about traveling in Africa</p>
        </div>
        
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button onclick="toggleAccordion(0)" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="text-lg font-semibold text-gray-900">What vaccinations do I need for Africa?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="accordion-content accordion-content-0 px-8 pb-6">
                    <p class="text-gray-600">Vaccination requirements vary by country and region. Common recommendations include Yellow Fever, Hepatitis A and B, Typhoid, and Meningitis. Some countries require proof of Yellow Fever vaccination for entry. We recommend consulting with a travel medicine specialist 4-6 weeks before your trip. Malaria prophylaxis is often recommended for many African destinations.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button onclick="toggleAccordion(1)" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="text-lg font-semibold text-gray-900">Is it safe to travel in Africa?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="accordion-content accordion-content-1 px-8 pb-6">
                    <p class="text-gray-600">Africa is a vast continent with varying safety conditions. Many tourist destinations are very safe with proper precautions. We continuously monitor safety conditions and only operate in stable regions. Our local guides are experts in navigating their areas safely. We provide comprehensive safety briefings and 24/7 support during your trip. Travel insurance is strongly recommended.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button onclick="toggleAccordion(2)" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="text-lg font-semibold text-gray-900">What's the best time to visit Africa?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="accordion-content accordion-content-2 px-8 pb-6">
                    <p class="text-gray-600">The best time varies by region and activity. For East African safaris, June-October offers excellent wildlife viewing. Southern Africa is ideal May-October. North Africa is best October-April when temperatures are cooler. Beach destinations like Mauritius and Seychelles are great year-round. We provide specific timing recommendations for each destination and experience.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button onclick="toggleAccordion(3)" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="text-lg font-semibold text-gray-900">What should I pack for an African safari?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="accordion-content accordion-content-3 px-8 pb-6">
                    <p class="text-gray-600">Pack neutral-colored clothing (khaki, beige, olive), comfortable walking shoes, hat, sunglasses, and sunscreen. Include layers for temperature changes, a light rain jacket, and insect repellent. Don't forget your camera, binoculars, and any necessary medications. We provide detailed packing lists specific to your destination and season.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button onclick="toggleAccordion(4)" class="w-full px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="text-lg font-semibold text-gray-900">Do I need travel insurance?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform accordion-icon-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="accordion-content accordion-content-4 px-8 pb-6">
                    <p class="text-gray-600">Yes, comprehensive travel insurance is strongly recommended and often required. It should cover medical emergencies, trip cancellation, lost luggage, and evacuation if needed. We can recommend trusted insurance providers who specialize in African travel. Make sure your policy covers all activities you plan to participate in.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Support -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-red-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Need More Information?</h2>
        <p class="text-xl text-blue-100 mb-8">Our travel experts are here to help with any questions or concerns you may have about your African adventure.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Phone Support</h3>
                <p class="text-blue-100">+1 (555) 123-4567</p>
                <p class="text-blue-100 text-sm">24/7 Support Available</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Email Support</h3>
                <p class="text-blue-100">info@iforeveryoungtours.com</p>
                <p class="text-blue-100 text-sm">Response within 2 hours</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Live Chat</h3>
                <p class="text-blue-100">Instant Support</p>
                <p class="text-blue-100 text-sm">Available 24/7</p>
            </div>
        </div>
        
        <button onclick="window.location.href='contact.html'" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors">
            Contact Our Experts
        </button>
    </div>
</section>

<!-- JavaScript -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/pages.js"></script>

<?php include '../includes/footer.php'; ?>