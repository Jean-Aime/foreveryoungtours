<?php
$page_title = "Travel Community - iForYoungTours | Connect with Fellow African Travelers";
$page_description = "Join our exclusive travel community and connect with fellow African adventure enthusiasts. Share experiences, get tips, and discover new destinations together.";
// $base_path will be auto-detected in header.php based on server port
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-purple-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">
                Join Our Travel <span class="text-gradient">Community</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Connect with passionate African travelers, share your adventures, and discover 
                hidden gems through our vibrant community of explorers.
            </p>
        </div>
    </div>
</section>

<!-- Community Features -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Connect & Share</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Our community platform brings together travelers who share a passion for African adventures. 
                    Share your experiences, get insider tips, and plan your next journey with fellow explorers.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Travel Forums</h3>
                            <p class="text-gray-600">Engage in discussions about destinations, share travel tips, and get advice from experienced African travelers.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Photo Sharing</h3>
                            <p class="text-gray-600">Share your stunning African photography and get inspired by incredible images from fellow travelers.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Group Travel Events</h3>
                            <p class="text-gray-600">Join organized group trips and meet like-minded travelers for shared African adventures.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg">Join Community</button>
                </div>
            </div>
            
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80" 
                     alt="Travel Community" 
                     class="rounded-2xl shadow-2xl">
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-golden-600">5,000+</div>
                            <div class="text-sm text-gray-600">Active Members</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-emerald-600">1,200+</div>
                            <div class="text-sm text-gray-600">Shared Stories</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Membership Benefits -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Membership Benefits</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Unlock exclusive benefits and enhance your African travel experience with our community membership.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Exclusive Discounts -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-golden-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-golden-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Exclusive Discounts</h3>
                <p class="text-gray-600 mb-6">
                    Get up to 15% off on all tours and packages, plus early access to special promotions and deals.
                </p>
                <div class="text-2xl font-bold text-golden-600 mb-2">Up to 15% OFF</div>
                <div class="text-sm text-gray-500">All Tours & Packages</div>
            </div>
            
            <!-- Priority Booking -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Priority Booking</h3>
                <p class="text-gray-600 mb-6">
                    Get first access to new tours, limited-capacity experiences, and popular seasonal packages.
                </p>
                <div class="text-2xl font-bold text-emerald-600 mb-2">24 Hours</div>
                <div class="text-sm text-gray-500">Early Access</div>
            </div>
            
            <!-- Expert Consultations -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Expert Consultations</h3>
                <p class="text-gray-600 mb-6">
                    Free one-on-one consultations with our travel experts to plan your perfect African adventure.
                </p>
                <div class="text-2xl font-bold text-blue-600 mb-2">Free</div>
                <div class="text-sm text-gray-500">Travel Consultations</div>
            </div>
            
            <!-- Monthly Webinars -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Monthly Webinars</h3>
                <p class="text-gray-600 mb-6">
                    Attend exclusive webinars featuring destination experts, wildlife specialists, and cultural guides.
                </p>
                <div class="text-2xl font-bold text-purple-600 mb-2">Monthly</div>
                <div class="text-sm text-gray-500">Expert Sessions</div>
            </div>
            
            <!-- Travel Insurance -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Complimentary Insurance</h3>
                <p class="text-gray-600 mb-6">
                    Basic travel insurance coverage included with all bookings for community members.
                </p>
                <div class="text-2xl font-bold text-red-600 mb-2">Included</div>
                <div class="text-sm text-gray-500">Basic Coverage</div>
            </div>
            
            <!-- Loyalty Rewards -->
            <div class="nextcloud-card p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Loyalty Rewards</h3>
                <p class="text-gray-600 mb-6">
                    Earn points for every booking and redeem them for future trips, upgrades, and exclusive experiences.
                </p>
                <div class="text-2xl font-bold text-indigo-600 mb-2">Earn Points</div>
                <div class="text-sm text-gray-500">Every Booking</div>
            </div>
        </div>
    </div>
</section>

<!-- Community Stories -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Community Stories</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Read inspiring stories and experiences shared by our community members from their African adventures.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Story 1 -->
            <div class="nextcloud-card overflow-hidden hover:shadow-xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=400&q=80" 
                     alt="Safari Adventure" 
                     class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?auto=format&fit=crop&w=50&q=80" 
                             alt="Sarah Johnson" 
                             class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-semibold text-gray-900">Sarah Johnson</div>
                            <div class="text-sm text-gray-500">Kenya Safari, 2024</div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">My First African Safari</h3>
                    <p class="text-gray-600 text-sm">
                        "The Maasai Mara exceeded all my expectations. Witnessing the Great Migration was a life-changing experience that I'll treasure forever..."
                    </p>
                </div>
            </div>
            
            <!-- Story 2 -->
            <div class="nextcloud-card overflow-hidden hover:shadow-xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?auto=format&fit=crop&w=400&q=80" 
                     alt="Cultural Experience" 
                     class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=50&q=80" 
                             alt="Michael Chen" 
                             class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-semibold text-gray-900">Michael Chen</div>
                            <div class="text-sm text-gray-500">Morocco Cultural Tour, 2024</div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Discovering Berber Culture</h3>
                    <p class="text-gray-600 text-sm">
                        "Living with a Berber family in the Atlas Mountains opened my eyes to a completely different way of life. The hospitality was incredible..."
                    </p>
                </div>
            </div>
            
            <!-- Story 3 -->
            <div class="nextcloud-card overflow-hidden hover:shadow-xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1547036967-23d11aacaee0?auto=format&fit=crop&w=400&q=80" 
                     alt="Adventure Trek" 
                     class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=50&q=80" 
                             alt="Emma Rodriguez" 
                             class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-semibold text-gray-900">Emma Rodriguez</div>
                            <div class="text-sm text-gray-500">Kilimanjaro Trek, 2024</div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Conquering Kilimanjaro</h3>
                    <p class="text-gray-600 text-sm">
                        "Reaching Uhuru Peak was the most challenging yet rewarding experience of my life. The support from our guides was exceptional..."
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <button class="btn-secondary px-8 py-4 rounded-lg font-semibold text-lg">Read More Stories</button>
        </div>
    </div>
</section>

<!-- Join CTA -->
<section class="py-20 bg-gradient-to-br from-purple-900 to-indigo-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Join Our Community?</h2>
        <p class="text-xl text-purple-200 mb-8 max-w-3xl mx-auto">
            Become part of Africa's most passionate travel community. Share your adventures, 
            discover new destinations, and connect with fellow explorers.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <button class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
                Join Free Today
            </button>
            <button class="bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/20 transition-all duration-300">
                Learn More
            </button>
        </div>
        
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-purple-300">5,000+</div>
                <div class="text-sm text-purple-200">Active Members</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-purple-300">47</div>
                <div class="text-sm text-purple-200">Countries Covered</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-purple-300">1,200+</div>
                <div class="text-sm text-purple-200">Shared Stories</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-purple-300">24/7</div>
                <div class="text-sm text-purple-200">Community Support</div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>