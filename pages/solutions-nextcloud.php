<?php
$page_title = "Travel Solutions - iForYoungTours | Comprehensive African Travel Services";
$page_description = "Discover our comprehensive travel solutions including custom tours, travel insurance, emergency support, and VIP services for your African adventure.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";

include './header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">
                Complete Travel <span class="text-gradient">Solutions</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                From custom itineraries to emergency support, we provide comprehensive solutions 
                to make your African journey seamless and worry-free.
            </p>
        </div>
    </div>
</section>

<!-- Solutions Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Custom Tours -->
            <div class="nextcloud-card p-8 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-golden-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-golden-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Custom Tour Planning</h3>
                        <p class="text-gray-600 mb-6">
                            Work with our expert travel consultants to create personalized itineraries 
                            that match your interests, budget, and travel style.
                        </p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                One-on-one consultation
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Flexible itinerary modifications
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Special interest accommodations
                            </li>
                        </ul>
                        <button class="btn-primary px-6 py-3 rounded-lg font-semibold">Start Planning</button>
                    </div>
                </div>
            </div>

            <!-- Travel Insurance -->
            <div class="nextcloud-card p-8 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Comprehensive Travel Insurance</h3>
                        <p class="text-gray-600 mb-6">
                            Protect your investment and travel with confidence with our comprehensive 
                            insurance coverage options.
                        </p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Medical emergency coverage
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Trip cancellation protection
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Baggage and personal effects
                            </li>
                        </ul>
                        <button class="btn-secondary px-6 py-3 rounded-lg font-semibold">Get Quote</button>
                    </div>
                </div>
            </div>

            <!-- Emergency Support -->
            <div class="nextcloud-card p-8 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">24/7 Emergency Support</h3>
                        <p class="text-gray-600 mb-6">
                            Round-the-clock assistance for any emergencies or unexpected situations 
                            during your African adventure.
                        </p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Multilingual support team
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Medical assistance coordination
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Embassy and consulate liaison
                            </li>
                        </ul>
                        <button class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700">Emergency Hotline</button>
                    </div>
                </div>
            </div>

            <!-- VIP Services -->
            <div class="nextcloud-card p-8 hover:shadow-xl transition-all duration-300">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">VIP Concierge Services</h3>
                        <p class="text-gray-600 mb-6">
                            Enjoy premium services and exclusive access with our VIP concierge 
                            program for discerning travelers.
                        </p>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Private airport transfers
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Exclusive lodge access
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Personal travel coordinator
                            </li>
                        </ul>
                        <button class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">Learn More</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Additional Services -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Additional Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Complete your African adventure with our comprehensive range of additional services.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Visa Assistance -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Visa & Documentation</h3>
                <p class="text-gray-600 mb-6">
                    Complete assistance with visa applications and travel documentation for all African countries.
                </p>
                <button class="btn-secondary px-6 py-2 rounded-lg font-semibold">Get Help</button>
            </div>

            <!-- Photography Services -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Professional Photography</h3>
                <p class="text-gray-600 mb-6">
                    Capture your African memories with professional photography services and equipment rental.
                </p>
                <button class="btn-secondary px-6 py-2 rounded-lg font-semibold">Book Session</button>
            </div>

            <!-- Cultural Experiences -->
            <div class="nextcloud-card p-6 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Cultural Immersion</h3>
                <p class="text-gray-600 mb-6">
                    Deep cultural experiences including homestays, traditional ceremonies, and local workshops.
                </p>
                <button class="btn-secondary px-6 py-2 rounded-lg font-semibold">Explore</button>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Need a Custom Solution?</h2>
        <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
            Our travel experts are ready to create a personalized solution that meets your specific needs and preferences.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <button class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Contact Our Experts
            </button>
            <button class="bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/20 transition-all duration-300">
                Schedule Consultation
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>