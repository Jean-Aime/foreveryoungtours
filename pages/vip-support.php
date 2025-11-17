<?php

require_once 'config.php';
$page_title = "VIP Support - Forever Young Tours | Elite Service. Every Step of the Way.";
$page_description = "Exclusive concierge access for FYT Club Members and Premium Tour Guests. Airport fast-track, luggage handling, private transfers, and on-demand itinerary adjustments.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative pt-24 pb-20 bg-gradient-to-br from-yellow-50 via-white to-yellow-50/30 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-full text-sm font-semibold mb-6 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                VIP Support
            </div>
            <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Elite Service. <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-yellow-600">Every Step of the Way.</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto leading-relaxed">
                Our VIP Concierge Desk ensures seamless experiences—priority check-in, private transfers, personal escorts, and real-time itinerary updates.
            </p>
            <div class="text-lg text-gray-700 mb-10 max-w-3xl mx-auto">
                Whether you're arriving in <strong class="text-yellow-600">Kigali, Accra, or Barbados</strong>, Forever Young Tours' premium service team guarantees a frictionless journey.
            </div>
            <a href="#contact" class="inline-block px-10 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl font-semibold text-lg hover:from-yellow-500 hover:to-yellow-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Request VIP Concierge
                </span>
            </a>
        </div>
    </div>
</section>

<!-- VIP Services -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-block px-6 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold mb-4">Premium Services</div>
            <h2 class="text-5xl font-bold text-gray-900 mb-6">Exclusive VIP Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Experience luxury travel with our comprehensive VIP services designed for discerning travelers</p>
        </div>
        
        <!-- Service 1: Airport Fast-Track -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-3xl transform rotate-3 group-hover:rotate-6 transition-transform"></div>
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200&q=80" alt="Airport VIP Lounge" class="relative rounded-3xl shadow-2xl w-full h-96 object-cover">
            </div>
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-yellow-100 rounded-full mb-4">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                    <span class="text-yellow-700 font-semibold text-sm">01</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Airport Fast-Track</h3>
                <p class="text-lg text-gray-600 mb-6">Skip the lines with priority check-in, security clearance, and boarding privileges at major airports worldwide.</p>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span class="ml-3 text-gray-700">Priority check-in counters</span>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span class="ml-3 text-gray-700">Express security screening</span>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <span class="ml-3 text-gray-700">Lounge access privileges</span>
                    </li>
                </ul>
            </div>
        </div>

            <!-- Luggage Handling -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18,8A6,6 0 0,0 12,2A6,6 0 0,0 6,8H4A2,2 0 0,0 2,10V20A2,2 0 0,0 4,22H20A2,2 0 0,0 22,20V10A2,2 0 0,0 20,8H18M12,4A4,4 0 0,1 16,8H8A4,4 0 0,1 12,4Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Luggage Handling</h3>
                <p class="text-gray-600 mb-4">White-glove luggage service from departure to destination with real-time tracking and priority handling.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Door-to-door luggage service</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Priority baggage handling</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Real-time tracking updates</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Loss/damage protection</li>
                </ul>
            </div>

            <!-- Private Transfers -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-green-500 hover:border-green-600">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.92,6.01C18.72,5.42 18.16,5 17.5,5H15V4A2,2 0 0,0 13,2H11A2,2 0 0,0 9,4V5H6.5C5.84,5 5.28,5.42 5.08,6.01L3,12V20A1,1 0 0,0 4,21H5A1,1 0 0,0 6,20V19H18V20A1,1 0 0,0 19,21H20A1,1 0 0,0 21,20V12L18.92,6.01M6.5,16A1.5,1.5 0 0,1 5,14.5A1.5,1.5 0 0,1 6.5,13A1.5,1.5 0 0,1 8,14.5A1.5,1.5 0 0,1 6.5,16M17.5,16A1.5,1.5 0 0,1 16,14.5A1.5,1.5 0 0,1 17.5,13A1.5,1.5 0 0,1 19,14.5A1.5,1.5 0 0,1 17.5,16M5,11L6.5,6.5H9V4H11V6.5H17.5L19,11H5Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Private Transfers</h3>
                <p class="text-gray-600 mb-4">Luxury vehicle transfers with professional drivers for seamless transportation throughout your journey.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Airport pickup/drop-off</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Hotel transfers</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Excursion transportation</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Multi-city transfers</li>
                </ul>
            </div>

            <!-- Personal Escorts -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Personal Escorts</h3>
                <p class="text-gray-600 mb-4">Dedicated travel companions for navigation assistance, cultural interpretation, and personalized support.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Airport navigation assistance</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Cultural interpretation</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Language translation</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Local expertise guidance</li>
                </ul>
            </div>

            <!-- Itinerary Management -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zM4 19h16v2H4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Real-Time Itinerary Updates</h3>
                <p class="text-gray-600 mb-4">On-demand itinerary adjustments with instant notifications and seamless rebooking services.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Live itinerary modifications</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Instant rebooking services</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Weather-based adjustments</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Preference-based changes</li>
                </ul>
            </div>

            <!-- Concierge Services -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Exclusive Concierge</h3>
                <p class="text-gray-600 mb-4">Personal concierge services for dining reservations, entertainment bookings, and special requests.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Restaurant reservations</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Entertainment bookings</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Shopping assistance</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Special occasion planning</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- VIP Experience Gallery -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">The VIP Experience</h2>
            <p class="text-xl text-gray-600">Luxury and comfort at every touchpoint</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Gallery Item 1 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80" alt="Private Jet" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-white font-bold text-lg mb-1">Private Aviation</h3>
                    <p class="text-white/90 text-sm">Exclusive flight arrangements</p>
                </div>
            </div>
            
            <!-- Gallery Item 2 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80" alt="Luxury Hotel" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-white font-bold text-lg mb-1">5-Star Accommodations</h3>
                    <p class="text-white/90 text-sm">Premium suite upgrades</p>
                </div>
            </div>
            
            <!-- Gallery Item 3 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1464037866556-6812c9d1c72e?w=800&q=80" alt="Luxury Car" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-white font-bold text-lg mb-1">Luxury Transfers</h3>
                    <p class="text-white/90 text-sm">Premium vehicle fleet</p>
                </div>
            </div>
            
            <!-- Gallery Item 4 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80" alt="Fine Dining" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="text-white font-bold text-lg mb-1">Gourmet Dining</h3>
                    <p class="text-white/90 text-sm">Exclusive reservations</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VIP Benefits -->
<section class="py-20 bg-gradient-to-br from-yellow-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Why Choose VIP Support?</h2>
                <p class="text-lg text-gray-600 mb-8">Experience travel the way it should be—effortless, luxurious, and tailored to your every need.</p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">24/7 Dedicated Support</h3>
                            <p class="text-gray-600">Your personal concierge team is always available, ensuring seamless assistance at any hour.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Skip All Lines</h3>
                            <p class="text-gray-600">Fast-track through airports, attractions, and venues with priority access everywhere.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Personalized Experiences</h3>
                            <p class="text-gray-600">Every detail customized to your preferences, from dining to activities and beyond.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80" alt="Luxury Beach Resort" class="w-full h-[500px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6">
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </div>
                            </div>
                            <p class="text-gray-800 italic mb-3">"The VIP service transformed our African adventure. Every detail was perfect!"</p>
                            <p class="text-sm font-semibold text-gray-900">— Sarah & Michael, USA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative py-20 bg-gradient-to-br from-yellow-400 via-yellow-500 to-yellow-600 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1920&q=80" alt="Travel Background" class="w-full h-full object-cover">
    </div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">Ready for the VIP Treatment?</h2>
        <p class="text-xl text-white/95 mb-10 leading-relaxed">Elevate your travel experience with our exclusive VIP services. Contact our concierge team today.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo $base_path; ?>pages/contact.php" class="inline-flex items-center justify-center px-10 py-4 bg-white text-yellow-600 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Contact VIP Concierge
            </a>
            <a href="tel:+17374439646" class="inline-flex items-center justify-center px-10 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-yellow-600 transition-all shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                </svg>
                Call Now: +1 (737) 443-9646
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
