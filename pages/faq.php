<?php
$page_title = "Frequently Asked Questions - iForYoungTours";
$page_description = "Find answers to common questions about African travel, bookings, and our services.";
include '../includes/header.php';
?>

<div class="pt-20 pb-16 bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600">Find answers to common questions about African travel</p>
        </div>

        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" onclick="toggleFAQ(1)">
                    <h3 class="text-lg font-semibold text-gray-900">What is included in tour packages?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4" id="content-1">
                    <p class="text-gray-600">Our tour packages typically include accommodation, transportation, guided tours, some meals, and entrance fees to attractions. Specific inclusions vary by package and are clearly listed in each tour description.</p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" onclick="toggleFAQ(2)">
                    <h3 class="text-lg font-semibold text-gray-900">Do I need a visa for African countries?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4" id="content-2">
                    <p class="text-gray-600">Visa requirements vary by country and your nationality. We provide detailed visa information for each destination and can assist with the application process. Check our resources section for country-specific requirements.</p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" onclick="toggleFAQ(3)">
                    <h3 class="text-lg font-semibold text-gray-900">What vaccinations do I need?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4" id="content-3">
                    <p class="text-gray-600">Yellow fever vaccination is required for many African countries. We recommend consulting with a travel medicine specialist 4-6 weeks before departure for personalized advice on vaccinations and health precautions.</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" onclick="toggleFAQ(4)">
                    <h3 class="text-lg font-semibold text-gray-900">How do I book a tour?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4" id="content-4">
                    <p class="text-gray-600">You can book directly through our website, contact our booking team via WhatsApp, or work with one of our certified travel advisors. We accept various payment methods and offer flexible payment plans.</p>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none" onclick="toggleFAQ(5)">
                    <h3 class="text-lg font-semibold text-gray-900">What is your cancellation policy?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4" id="content-5">
                    <p class="text-gray-600">Cancellation policies vary by tour and timing. Generally, cancellations made 60+ days before departure receive full refund minus processing fees. We recommend travel insurance for added protection.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(id) {
    const content = document.getElementById(`content-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>

<?php include '../includes/footer.php'; ?>