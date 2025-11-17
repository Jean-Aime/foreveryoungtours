<?php

require_once 'config.php';
$page_title = "Partners - Forever Young Tours | Building Global Bridges Through Travel";
$page_description = "Strategic alliances with airlines, resorts, cultural institutions, and ministries of tourism. Partnership page showcases collaborative programs and corporate sponsorship opportunities within FYT's Global Network.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-green-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-green-500 text-white rounded-full text-sm font-semibold mb-6">
                Partners
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Building Global Bridges Through Travel
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto">
                FYT partners with airlines, hotels, ministries of tourism, and innovation hubs to deliver transformative travel experiences. Explore partnership opportunities and join our mission to connect Global Africa through luxury, culture, and commerce.
            </p>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-green-600 hover:bg-green-700">
                → Partner with FYT
            </button>
        </div>
    </div>
</section>

<!-- Partnership Types -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Partnership Opportunities</h2>
            <p class="text-xl text-gray-600">Choose the partnership that best fits your business</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="partner-card rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Travel Advisor</h3>
                <p class="text-gray-600 mb-6 text-center">Join our MCA network and earn commissions by promoting African travel experiences to your clients.</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Up to 15% commission on bookings</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Marketing materials and support</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Training and certification programs</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Personalized booking portal</span>
                    </div>
                </div>
                
                <button onclick="showPartnerForm('advisor')" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                    Become an Advisor
                </button>
            </div>
            
            <div class="partner-card rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Accommodation Partner</h3>
                <p class="text-gray-600 mb-6 text-center">Hotels, lodges, and camps can partner with us to reach international travelers seeking authentic African experiences.</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Increased international visibility</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Steady stream of qualified bookings</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Quality assurance and standards</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Marketing and promotional support</span>
                    </div>
                </div>
                
                <button onclick="showPartnerForm('accommodation')" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                    Partner with Us
                </button>
            </div>
            
            <div class="partner-card rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Local Operator</h3>
                <p class="text-gray-600 mb-6 text-center">Local tour operators and DMCs can expand their reach and collaborate on creating unique African experiences.</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Access to global client base</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Technology and booking platform</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Shared revenue opportunities</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Operational support and training</span>
                    </div>
                </div>
                
                <button onclick="showPartnerForm('operator')" class="w-full btn-primary text-white py-3 rounded-lg font-semibold">
                    Become a Partner
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Partner with iForYoungTours?</h2>
            <p class="text-xl text-gray-600">Join Africa's fastest-growing travel network with proven results</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">50K+</h3>
                <p class="text-gray-600">Happy Travelers Served</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">47</h3>
                <p class="text-gray-600">African Countries Covered</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">15%</h3>
                <p class="text-gray-600">Average Commission Rate</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 benefit-icon rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">4.9</h3>
                <p class="text-gray-600">Customer Satisfaction Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Success Stories</h2>
            <p class="text-xl text-gray-600">Hear from our successful partners</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center mb-6">
                    <img src="https://kimi-web-img.moonshot.cn/img/govolunteerafrica.org/1935ad8b4e285447eb446b26ed39c7572e161b11.png" alt="Sarah M." class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Sarah Johnson</h4>
                        <p class="text-gray-600 text-sm">Travel Advisor, Kenya</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">"Joining iForYoungTours has transformed my business. The support, training, and commission structure have allowed me to focus on what I love - creating amazing experiences for my clients."</p>
                <div class="flex items-center text-yellow-400">
                    ★★★★★
                    <span class="text-gray-600 ml-2 text-sm">5.0 Rating</span>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center mb-6">
                    <img src="https://kimi-web-img.moonshot.cn/img/www.cuisinenoir.com/02ec9c166503b6d6f0c577beac1a87758ed659b7.jpg" alt="Michael R." class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Michael Chen</h4>
                        <p class="text-gray-600 text-sm">Lodge Owner, Tanzania</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">"The partnership with iForYoungTours has brought us consistent, high-quality bookings from around the world. Their marketing support and professional approach are exceptional."</p>
                <div class="flex items-center text-yellow-400">
                    ★★★★★
                    <span class="text-gray-600 ml-2 text-sm">5.0 Rating</span>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <div class="flex items-center mb-6">
                    <img src="https://kimi-web-img.moonshot.cn/img/www.bigwildadventures.com/325488bf48b16a7ae0819256219df6db64a9ad7b.jpg" alt="Emma L." class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-bold text-gray-900">Emma Rodriguez</h4>
                        <p class="text-gray-600 text-sm">Tour Operator, South Africa</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">"Collaborating with iForYoungTours has expanded our reach significantly. Their technology platform and global network have opened new markets for our tours."</p>
                <div class="flex items-center text-yellow-400">
                    ★★★★★
                    <span class="text-gray-600 ml-2 text-sm">5.0 Rating</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partnership Process -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How to Become a Partner</h2>
            <p class="text-xl text-gray-600">Simple steps to join our network</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-blue-600">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Submit Application</h3>
                <p class="text-gray-600">Fill out our partnership application form with your business details and partnership interests.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-green-600">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Review Process</h3>
                <p class="text-gray-600">Our team will review your application and conduct due diligence to ensure quality partnerships.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-yellow-600">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Agreement & Setup</h3>
                <p class="text-gray-600">Sign partnership agreement and complete setup process including training and system integration.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl font-bold text-purple-600">4</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Start Earning</h3>
                <p class="text-gray-600">Begin receiving bookings and earning commissions while we handle marketing and client support.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Ready to Partner with Us?</h2>
        <p class="text-xl text-gray-600 mb-8">Get in touch with our partnership team to discuss opportunities</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Us</h3>
                <p class="text-gray-600">partnerships@iforeveryoungtours.com</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Call Us</h3>
                <p class="text-gray-600">+1 (555) 123-4567</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Chat with Us</h3>
                <p class="text-gray-600">Live Chat Available</p>
            </div>
        </div>
        
        <button onclick="showPartnerForm('general')" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-lg">
            Start Partnership Discussion
        </button>
    </div>
</section>

<!-- JavaScript -->
<script src="<?= getImageUrl('assets/js/main.js') ?>"></script>
<script src="<?= getImageUrl('assets/js/pages.js') ?>"></script>

<?php include '../includes/footer.php'; ?>