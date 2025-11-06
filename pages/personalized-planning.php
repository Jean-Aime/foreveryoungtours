<?php
$page_title = "Personalized Planning - Forever Young Tours | Your Journey. Your Way.";
$page_description = "One-to-one planning with FYT Advisors. Customize experiences by interest—agro-tourism, cultural immersion, sports tourism, or wellness. Powered by EspoCRM and FYT Planner Portal.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-pink-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-semibold mb-6">
                Personalized Planning
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Your Journey. Your Way.
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto">
                Every adventure begins with a conversation. Partner with our FYT Advisors to tailor your trip—select your interests, pace, and comfort level.
            </p>
            <div class="text-lg text-gray-700 mb-8">
                Our digital platform synchronizes planning with live updates through <strong>EspoCRM and ODIECloud</strong>, keeping every detail within reach.
            </div>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-pink-600 hover:bg-pink-700">
                → Start Your Personalized Plan
            </button>
        </div>
    </div>
</section>

<!-- Planning Categories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Customize by Your Interests</h2>
            <p class="text-lg text-gray-600">Tailor your experience based on what excites you most</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Agro-Tourism -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A3,3 0 0,1 15,5V11A3,3 0 0,1 12,14A3,3 0 0,1 9,11V5A3,3 0 0,1 12,2M12,4A1,1 0 0,0 11,5V11A1,1 0 0,0 12,12A1,1 0 0,0 13,11V5A1,1 0 0,0 12,4M12,15C13.11,15 14.11,15.45 14.83,16.17L16.24,14.76C15.22,13.74 13.86,13.15 12.4,13.05L12,13C10.54,13.05 9.18,13.74 8.16,14.76L9.58,16.17C10.29,15.45 11.29,15 12.4,15H12Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Agro-Tourism</h3>
                <p class="text-gray-600 mb-6">Experience authentic farm life, sustainable agriculture practices, and farm-to-table dining experiences.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold bg-green-500 hover:bg-green-600">Explore Agro Tours</button>
            </div>

            <!-- Cultural Immersion -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Cultural Immersion</h3>
                <p class="text-gray-600 mb-6">Deep dive into local traditions, arts, crafts, and community experiences with authentic cultural exchanges.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold bg-orange-500 hover:bg-orange-600">Explore Cultural Tours</button>
            </div>

            <!-- Sports Tourism -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Sports Tourism</h3>
                <p class="text-gray-600 mb-6">Adventure sports, golf tournaments, marathon events, and active recreational experiences worldwide.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold bg-blue-500 hover:bg-blue-600">Explore Sports Tours</button>
            </div>

            <!-- Wellness -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,18.5C15.5,18.5 18.5,15.5 18.5,12C18.5,8.5 15.5,5.5 12,5.5C8.5,5.5 5.5,8.5 5.5,12C5.5,15.5 8.5,18.5 12,18.5M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Wellness</h3>
                <p class="text-gray-600 mb-6">Spa retreats, meditation experiences, yoga journeys, and holistic wellness programs for mind and body.</p>
                <button class="w-full btn-primary py-3 rounded-lg font-semibold bg-purple-500 hover:bg-purple-600">Explore Wellness Tours</button>
            </div>
        </div>
    </div>
</section>

<!-- Planning Process -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How Personalized Planning Works</h2>
            <p class="text-lg text-gray-600">Your journey from consultation to departure</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">1</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Initial Consultation</h3>
                <p class="text-gray-600">One-on-one discussion with your dedicated FYT Advisor to understand your preferences and interests</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">2</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Custom Itinerary Design</h3>
                <p class="text-gray-600">Tailored itinerary creation based on your interests, pace, and comfort level preferences</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">3</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Digital Synchronization</h3>
                <p class="text-gray-600">Your plan is synchronized through EspoCRM and FYT Planner Portal for real-time updates</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">4</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Ongoing Support</h3>
                <p class="text-gray-600">Continuous support and adjustments throughout your journey with live updates</p>
            </div>
        </div>
    </div>
</section>

<!-- Technology Platform -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Powered by Advanced Technology</h2>
            <p class="text-lg text-gray-600">Seamless itinerary management with live updates</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">EspoCRM Integration</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Centralized Communication</h4>
                            <p class="text-gray-600">All communications with your advisor tracked in one place</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Preference Tracking</h4>
                            <p class="text-gray-600">Your travel preferences saved for future trip planning</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Document Management</h4>
                            <p class="text-gray-600">All travel documents organized and accessible</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">FYT Planner Portal</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Real-Time Updates</h4>
                            <p class="text-gray-600">Live itinerary changes and notifications</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Mobile Access</h4>
                            <p class="text-gray-600">Access your itinerary anywhere, anytime</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Collaborative Planning</h4>
                            <p class="text-gray-600">Share and collaborate with travel companions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-pink-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Plan Your Perfect Journey?</h2>
        <p class="text-xl text-white/90 mb-8">Connect with a FYT Advisor today and start creating your personalized travel experience.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-4 bg-white text-pink-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Schedule Consultation
            </button>
            <button class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-pink-600 transition-all">
                View Sample Itineraries
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
