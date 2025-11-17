<?php

require_once 'config.php';
$page_title = "Travel Club Membership - Forever Young Tours | Join the FYT Travel Club. Live the Legacy.";
$page_description = "Annual membership unlocking member-only experiences, early-bird tour access, partner discounts, and invitation-only FYT Retreats. Receive your FYT Passport for lifetime milestones.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=2072&q=80" alt="Travel Club Membership" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-yellow-500 text-black rounded-full text-sm font-semibold mb-6">
                Travel Club Membership
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Join the FYT Travel Club. Live the Legacy.
            </h1>
            <p class="text-xl text-gray-200 mb-8 max-w-4xl mx-auto">
                Membership opens doors to private journeys, early-access tours, and partner privileges. Receive your FYT Passport—a lifetime record of your adventures and achievements within the network.
            </p>
            <div class="text-lg text-gray-300 mb-8">
                Stay connected with like-minded explorers shaping <strong>Global Africa & the Caribbean</strong>.
            </div>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg">
                → Become a Member
            </button>
        </div>
    </div>
</section>

<!-- Membership Benefits -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Exclusive Member Benefits</h2>
            <p class="text-lg text-gray-600">Unlock premium experiences and privileges</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Member-Only Experiences -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-yellow-500">
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Member-Only Experiences</h3>
                <p class="text-gray-600 mb-4">Access exclusive tours and experiences not available to the general public.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Private cultural ceremonies</li>
                    <li>• Behind-the-scenes access</li>
                    <li>• VIP venue experiences</li>
                    <li>• Exclusive dining events</li>
                </ul>
            </div>

            <!-- Early-Bird Access -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-green-500">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Early-Bird Tour Access</h3>
                <p class="text-gray-600 mb-4">First access to new tour releases and limited-capacity experiences.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• 48-hour booking priority</li>
                    <li>• Limited edition tours</li>
                    <li>• Seasonal specials first</li>
                    <li>• Premium seat selection</li>
                </ul>
            </div>

            <!-- Partner Discounts -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-blue-500">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Partner Discounts</h3>
                <p class="text-gray-600 mb-4">Exclusive discounts with our global network of travel partners.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Hotel partner discounts</li>
                    <li>• Airline upgrade privileges</li>
                    <li>• Restaurant reservations</li>
                    <li>• Activity discounts</li>
                </ul>
            </div>

            <!-- FYT Retreats -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-purple-500">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Invitation-Only FYT Retreats</h3>
                <p class="text-gray-600 mb-4">Exclusive retreats for members to connect and explore together.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Annual member retreat</li>
                    <li>• Networking events</li>
                    <li>• Cultural workshops</li>
                    <li>• Adventure challenges</li>
                </ul>
            </div>

            <!-- FYT Passport -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-red-500">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Digital FYT Passport</h3>
                <p class="text-gray-600 mb-4">Lifetime record of your adventures and achievements within the network.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Trip history tracking</li>
                    <li>• Achievement badges</li>
                    <li>• Milestone rewards</li>
                    <li>• Travel statistics</li>
                </ul>
            </div>

            <!-- Community Access -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 border-l-4 border-teal-500">
                <div class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Like-Minded Community</h3>
                <p class="text-gray-600 mb-4">Connect with fellow travelers shaping Global Africa & the Caribbean.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Member directory access</li>
                    <li>• Travel companion matching</li>
                    <li>• Regional meetups</li>
                    <li>• Online community forum</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Membership Tiers -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Membership Tiers</h2>
            <p class="text-lg text-gray-600">Choose the membership level that fits your travel style</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Explorer Tier -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Explorer</h3>
                    <div class="text-4xl font-bold text-yellow-600 mb-2">$199</div>
                    <div class="text-gray-600">per year</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Early-bird tour access
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        10% partner discounts
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Digital FYT Passport
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Community forum access
                    </li>
                </ul>
                <button class="w-full py-3 border-2 border-yellow-500 text-yellow-600 rounded-lg font-semibold hover:bg-yellow-50 transition-all">
                    Choose Explorer
                </button>
            </div>

            <!-- Adventurer Tier -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-yellow-500 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <div class="bg-yellow-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</div>
                </div>
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Adventurer</h3>
                    <div class="text-4xl font-bold text-yellow-600 mb-2">$399</div>
                    <div class="text-gray-600">per year</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        All Explorer benefits
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Member-only experiences
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        20% partner discounts
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        FYT Retreat invitations
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Priority customer support
                    </li>
                </ul>
                <button class="w-full py-3 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-all">
                    Choose Adventurer
                </button>
            </div>

            <!-- Legacy Tier -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Legacy</h3>
                    <div class="text-4xl font-bold text-yellow-600 mb-2">$799</div>
                    <div class="text-gray-600">per year</div>
                </div>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        All Adventurer benefits
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        VIP concierge services
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        30% partner discounts
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Private group experiences
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Annual complimentary tour
                    </li>
                </ul>
                <button class="w-full py-3 border-2 border-yellow-500 text-yellow-600 rounded-lg font-semibold hover:bg-yellow-50 transition-all">
                    Choose Legacy
                </button>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Join the FYT Travel Club Today</h2>
        <p class="text-xl text-white/90 mb-8">Start your journey with like-minded explorers and unlock exclusive travel experiences.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-4 bg-white text-yellow-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Become a Member
            </button>
            <button class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-yellow-600 transition-all">
                Learn More
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
