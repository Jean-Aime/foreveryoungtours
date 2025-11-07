<?php
$page_title = "Packing Guides - Forever Young Tours | Interactive Checklists & Mobile Export";
$page_description = "Interactive checklists per region and tour type with mobile app export options. Comprehensive packing resources for every type of African and Caribbean adventure.";
$css_path = "../assets/css/modern-styles.css";

include '../includes/header.php';
?>

<!-- Hero Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-emerald-50 to-teal-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-emerald-500 text-white rounded-full text-sm font-semibold mb-6">
                Packing Guides
            </div>
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                Interactive Checklists & Mobile Export
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-4xl mx-auto">
                Smart packing solutions with interactive checklists tailored to your specific region and tour type. Export to mobile apps, print customized lists, and never forget essential items again.
            </p>
            <div class="text-lg text-gray-700 mb-8">
                <strong>Pack smart. Travel light. Experience more.</strong>
            </div>
            <button class="btn-primary px-8 py-4 rounded-lg font-semibold text-lg bg-emerald-600 hover:bg-emerald-700">
                → Create My Packing List
            </button>
        </div>
    </div>
</section>

<!-- Interactive Checklist Builder -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Build Your Custom Packing List</h2>
            <p class="text-lg text-gray-600">Select your destination and tour type for personalized recommendations</p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Region Selection -->
                    <div>
                        <label class="block text-lg font-bold text-gray-900 mb-4">Select Region</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="region" value="west-africa" class="mr-3">
                                <span class="text-gray-700">West Africa (Ghana, Nigeria, Senegal)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="region" value="east-africa" class="mr-3">
                                <span class="text-gray-700">East Africa (Kenya, Tanzania, Rwanda)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="region" value="southern-africa" class="mr-3">
                                <span class="text-gray-700">Southern Africa (South Africa, Botswana)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="region" value="caribbean" class="mr-3">
                                <span class="text-gray-700">Caribbean (Barbados, Jamaica, Trinidad)</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Tour Type Selection -->
                    <div>
                        <label class="block text-lg font-bold text-gray-900 mb-4">Select Tour Type</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="tour-type" value="safari" class="mr-3">
                                <span class="text-gray-700">Safari & Wildlife</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tour-type" value="cultural" class="mr-3">
                                <span class="text-gray-700">Cultural Heritage</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tour-type" value="beach" class="mr-3">
                                <span class="text-gray-700">Beach & Coastal</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tour-type" value="adventure" class="mr-3">
                                <span class="text-gray-700">Adventure & Hiking</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="tour-type" value="luxury" class="mr-3">
                                <span class="text-gray-700">Luxury & Wellness</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <button class="w-full py-4 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition-all">
                    Generate My Packing List
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Export Options -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Export Your Packing List</h2>
            <p class="text-lg text-gray-600">Take your checklist anywhere with multiple export options</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Mobile App -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 19H7V5h10v14zm-1-6h-8v4h8v-4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Mobile App</h3>
                <p class="text-gray-600 mb-4">Export to FYT mobile app with offline access and check-off functionality</p>
                <button class="px-6 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-all">
                    Export to App
                </button>
            </div>

            <!-- PDF Download -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">PDF Download</h3>
                <p class="text-gray-600 mb-4">Download printable PDF with checkboxes and space for notes</p>
                <button class="px-6 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-all">
                    Download PDF
                </button>
            </div>

            <!-- Email List -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,5.11 21.1,4 20,4Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Email List</h3>
                <p class="text-gray-600 mb-4">Send formatted checklist directly to your email for easy access</p>
                <button class="px-6 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-all">
                    Email List
                </button>
            </div>

            <!-- Share Link -->
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.91 18,21.91C19.61,21.91 20.92,20.61 20.92,19A2.92,2.92 0 0,0 18,16.08Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Share Link</h3>
                <p class="text-gray-600 mb-4">Generate shareable link for travel companions and family</p>
                <button class="px-6 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-all">
                    Create Link
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Sample Packing Categories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Comprehensive Packing Categories</h2>
            <p class="text-lg text-gray-600">Our checklists cover every essential category</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Clothing & Accessories -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Clothing & Accessories</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Climate-appropriate clothing</li>
                    <li>• Layering options</li>
                    <li>• Footwear for different activities</li>
                    <li>• Sun protection accessories</li>
                    <li>• Formal/cultural attire</li>
                </ul>
            </div>

            <!-- Health & Safety -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Health & Safety</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• First aid essentials</li>
                    <li>• Prescription medications</li>
                    <li>• Insect repellent & sunscreen</li>
                    <li>• Water purification tablets</li>
                    <li>• Emergency contact cards</li>
                </ul>
            </div>

            <!-- Electronics & Gadgets -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Electronics & Gadgets</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Camera equipment</li>
                    <li>• Power adapters & converters</li>
                    <li>• Portable chargers</li>
                    <li>• Waterproof cases</li>
                    <li>• Offline maps & apps</li>
                </ul>
            </div>

            <!-- Documents & Money -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Documents & Money</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Passport & visa copies</li>
                    <li>• Travel insurance documents</li>
                    <li>• Credit cards & cash</li>
                    <li>• Emergency contact information</li>
                    <li>• Vaccination certificates</li>
                </ul>
            </div>

            <!-- Personal Care -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Care</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Toiletries & hygiene items</li>
                    <li>• Hair care products</li>
                    <li>• Skincare essentials</li>
                    <li>• Feminine hygiene products</li>
                    <li>• Comfort items</li>
                </ul>
            </div>

            <!-- Activity-Specific -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-teal-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Activity-Specific</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Safari binoculars</li>
                    <li>• Snorkeling gear</li>
                    <li>• Hiking equipment</li>
                    <li>• Beach accessories</li>
                    <li>• Cultural tour items</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-emerald-600 to-teal-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Never Forget Anything Again</h2>
        <p class="text-xl text-white/90 mb-8">Create your personalized packing checklist and export it to your preferred format for stress-free travel preparation.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="px-8 py-4 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                Build My Checklist
            </button>
            <button class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-emerald-600 transition-all">
                Download Sample Lists
            </button>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
