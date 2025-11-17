<?php

require_once 'config.php';
$page_title = "Emergency Support - Forever Young Tours | 24/7 Travel Assistance";
$page_description = "24/7 multilingual response desk. Priority medical, embassy, and travel insurance coordination. Real-time alert integration for destination safety updates.";
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
                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z"/>
                </svg>
                24/7 Emergency Support
            </div>
            <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6">
                Travel with <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-yellow-600">Peace of Mind</span>
            </h1>
            <p class="text-xl text-gray-600 mb-12 max-w-4xl mx-auto leading-relaxed">
                Your safety is our priority. Forever Young Tours provides comprehensive 24/7 emergency support, connecting you to multilingual assistance teams worldwide. We coordinate with embassies, hospitals, and insurers for rapid response.
            </p>
            
            <!-- Emergency Contact Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-3xl mx-auto mb-12 border border-yellow-100">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Emergency Hotline</div>
                    <div class="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent mb-3">+1 (737) 443-9646</div>
                    <p class="text-gray-600 mb-6">Available 24/7 in multiple languages</p>
                    <div class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        All travelers automatically covered
                    </div>
                </div>
            </div>
            
            <a href="tel:+17374439646" class="inline-block px-10 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl font-semibold text-lg hover:from-yellow-500 hover:to-yellow-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                    </svg>
                    Call Emergency Support Now
                </span>
            </a>
        </div>
    </div>
</section>

<!-- Emergency Services -->
<section class="py-20 bg-gradient-to-b from-white to-yellow-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Comprehensive Emergency Services</h2>
            <p class="text-xl text-gray-600">Professional assistance when you need it most</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Medical Emergency -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3V8zM4 8h2v8H4V8zm3 0h2v8H7V8zm3 0h2v8h-2V8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Medical Emergency</h3>
                <p class="text-gray-600 mb-4">Immediate coordination with local hospitals, medical evacuation services, and insurance providers for urgent medical situations.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Hospital admission assistance</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Medical evacuation coordination</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Insurance claim processing</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Family notification services</li>
                </ul>
            </div>

            <!-- Embassy Coordination -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Embassy Coordination</h3>
                <p class="text-gray-600 mb-4">Direct liaison with embassies and consulates for document replacement, legal assistance, and diplomatic support.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Lost passport replacement</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Legal assistance coordination</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Consular services access</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Emergency travel documents</li>
                </ul>
            </div>

            <!-- Travel Insurance -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-green-500 hover:border-green-600">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11.5C15.4,11.5 16,12.1 16,12.7V16.2C16,16.8 15.4,17.3 14.8,17.3H9.2C8.6,17.3 8,16.8 8,16.2V12.7C8,12.1 8.6,11.5 9.2,11.5V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,10V11.5H13.5V10C13.5,8.7 12.8,8.2 12,8.2Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Insurance Coordination</h3>
                <p class="text-gray-600 mb-4">Seamless coordination with travel insurance providers for claims processing and coverage verification.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Claims processing assistance</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Coverage verification</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Direct billing coordination</li>
                    <li class="flex items-start"><span class="text-green-500 mr-2">✓</span> Reimbursement support</li>
                </ul>
            </div>

            <!-- Safety Alerts -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Real-Time Safety Alerts</h3>
                <p class="text-gray-600 mb-4">Continuous monitoring of destination safety conditions with immediate alerts for weather, political, or security concerns.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Weather emergency alerts</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Political situation updates</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Security threat notifications</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Evacuation coordination</li>
                </ul>
            </div>

            <!-- Lost/Stolen Items -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18,8A6,6 0 0,0 12,2A6,6 0 0,0 6,8H4A2,2 0 0,0 2,10V20A2,2 0 0,0 4,22H20A2,2 0 0,0 22,20V10A2,2 0 0,0 20,8H18M12,4A4,4 0 0,1 16,8H8A4,4 0 0,1 12,4Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Lost/Stolen Assistance</h3>
                <p class="text-gray-600 mb-4">Immediate support for lost or stolen documents, luggage, and personal items with replacement coordination.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Document replacement</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Credit card cancellation</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Luggage tracking</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Emergency cash advance</li>
                </ul>
            </div>

            <!-- Communication Support -->
            <div class="group bg-white rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 border-t-4 border-yellow-400 hover:border-yellow-500">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Communication Support</h3>
                <p class="text-gray-600 mb-4">Multilingual support team available 24/7 to assist with language barriers and local communication needs.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Translation services</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Local contact assistance</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Family communication</li>
                    <li class="flex items-start"><span class="text-yellow-500 mr-2">✓</span> Emergency messaging</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">How Our Emergency Response Works</h2>
            <p class="text-xl text-gray-600">Simple steps to get immediate assistance</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-lg group-hover:scale-110 transition-transform">1</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Call Emergency Line</h3>
                <p class="text-gray-600">Contact our 24/7 hotline immediately when emergency occurs</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-lg group-hover:scale-110 transition-transform">2</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Immediate Assessment</h3>
                <p class="text-gray-600">Our team quickly assesses your situation and determines required assistance</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-lg group-hover:scale-110 transition-transform">3</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Coordinate Response</h3>
                <p class="text-gray-600">We coordinate with local authorities, medical facilities, and relevant services</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold shadow-lg group-hover:scale-110 transition-transform">4</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Follow-up Support</h3>
                <p class="text-gray-600">Continuous support until situation is resolved and you're safe</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Protocols -->
<section class="py-20 bg-gradient-to-b from-yellow-50/30 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Emergency Contact Protocols</h2>
            <p class="text-xl text-gray-600">Contact protocols embedded in every booking confirmation</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Primary Emergency Contacts</h3>
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-yellow-400 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="font-bold text-gray-900 mb-1">Global Emergency Hotline</div>
                        <div class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent mb-2">+1 (737) 443-9646</div>
                        <div class="text-sm text-gray-600">Available 24/7 in multiple languages</div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-yellow-400 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="font-bold text-gray-900 mb-1">WhatsApp Emergency</div>
                        <div class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent mb-2">+1 (737) 443-9646</div>
                        <div class="text-sm text-gray-600">For areas with limited phone service</div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 border-l-4 border-green-500 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="font-bold text-gray-900 mb-1">Emergency Email</div>
                        <div class="text-2xl font-bold text-green-600 mb-2">emergency@fyt.com</div>
                        <div class="text-sm text-gray-600">For non-urgent emergency documentation</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Regional Emergency Numbers</h3>
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="font-semibold text-gray-900">Africa Regional</span>
                            <span class="text-yellow-600 font-medium">+250-XXX-XXXX</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="font-semibold text-gray-900">Caribbean Regional</span>
                            <span class="text-yellow-600 font-medium">+1-246-XXX-XXXX</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="font-semibold text-gray-900">Europe Regional</span>
                            <span class="text-yellow-600 font-medium">+44-20-XXX-XXXX</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="font-semibold text-gray-900">North America</span>
                            <span class="text-yellow-600 font-medium">+1 (737) 443-9646</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 p-6 bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-2xl border border-yellow-200">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        <div>
                            <div class="font-bold text-gray-900 mb-2">Important Note</div>
                            <p class="text-sm text-gray-700">All emergency contact information is automatically included in your booking confirmation and travel documents.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust & Safety Gallery -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Your Safety, Our Priority</h2>
            <p class="text-xl text-gray-600">Professional support across Africa and beyond</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Gallery Item 1 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1584515933487-779824d29309?w=800&q=80" alt="Medical Support" class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-xl mb-2">Medical Assistance</h3>
                    <p class="text-white/90 text-sm">Immediate coordination with top hospitals</p>
                </div>
            </div>
            
            <!-- Gallery Item 2 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=800&q=80" alt="24/7 Support" class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-xl mb-2">24/7 Hotline</h3>
                    <p class="text-white/90 text-sm">Always available, anywhere you travel</p>
                </div>
            </div>
            
            <!-- Gallery Item 3 -->
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&q=80" alt="Global Coverage" class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-xl mb-2">Global Coverage</h3>
                    <p class="text-white/90 text-sm">Protected across all destinations</p>
                </div>
            </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 text-center border border-yellow-100">
                <div class="text-4xl font-bold text-yellow-600 mb-2">24/7</div>
                <p class="text-sm text-gray-600 font-medium">Emergency Response</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 text-center border border-yellow-100">
                <div class="text-4xl font-bold text-yellow-600 mb-2">50+</div>
                <p class="text-sm text-gray-600 font-medium">Countries Covered</p>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 text-center border border-green-100">
                <div class="text-4xl font-bold text-green-600 mb-2">15min</div>
                <p class="text-sm text-gray-600 font-medium">Average Response</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl p-6 text-center border border-yellow-100">
                <div class="text-4xl font-bold text-yellow-600 mb-2">100%</div>
                <p class="text-sm text-gray-600 font-medium">Traveler Coverage</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-gradient-to-b from-yellow-50/30 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Real Stories, Real Support</h2>
            <p class="text-xl text-gray-600">Hear from travelers we've helped</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-yellow-400">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-700 italic mb-4">"When I lost my passport in Kigali, the emergency team had me sorted within hours. They coordinated with the embassy and I had my documents the next day. Incredible service!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold">JD</div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-900">James Davidson</p>
                        <p class="text-sm text-gray-600">United Kingdom</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-green-500">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-700 italic mb-4">"Medical emergency in Tanzania - the team arranged everything from hospital admission to insurance coordination. They even kept my family updated. True peace of mind!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">MC</div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-900">Maria Chen</p>
                        <p class="text-sm text-gray-600">Canada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative py-20 bg-gradient-to-br from-yellow-400 via-yellow-500 to-yellow-600 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1920&q=80" alt="African Landscape" class="w-full h-full object-cover">
    </div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">Travel with Confidence</h2>
        <p class="text-xl text-white/95 mb-10 leading-relaxed">Know that help is always just a phone call away, no matter where your adventures take you. Your safety and peace of mind are our top priorities.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <a href="tel:+17374439646" class="inline-flex items-center justify-center px-10 py-4 bg-white text-yellow-600 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z"/>
                </svg>
                Call Emergency Line
            </a>
            <a href="<?php echo $base_path; ?>pages/packages.php" class="inline-flex items-center justify-center px-10 py-4 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-yellow-600 transition-all shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z"/>
                </svg>
                View All Tours
            </a>
        </div>
        
        <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full text-white">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z"/>
            </svg>
            <span class="font-medium">All travelers automatically covered • No extra fees</span>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
