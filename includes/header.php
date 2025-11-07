<?php
// Auto-detect base path based on server configuration
if (!isset($base_path)) {
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Check if we're in a subdirectory (like /pages/)
    if (strpos($script_name, '/pages/') !== false) {
        // We're in the pages directory, need to go up one level
        $base_path = '../';
    }
    // If we're in the root directory
    else {
        $base_path = './';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ForeverYoung Tours - Discover Africa\'s Wonders'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Explore Africa\'s most breathtaking destinations with expert guidance. From safaris to cultural experiences, book your dream African adventure with confidence.'; ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Core Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>

    <!-- Splide CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Modern Styles -->
    <link rel="stylesheet" href="<?php echo isset($css_path) ? $css_path : 'assets/css/modern-styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/responsive-utilities.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/client-dashboard.css">
</head>
<body>
    <!-- Nextcloud-Style Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-md z-50 border-b border-slate-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3">
                    <img src="<?php echo $base_path; ?>assets/images/logo.png" alt="iForYoungTours Logo" class="w-10 h-10">

                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="/ForeverYoungTours/index.php" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all">Home</a>

                    <!-- Solution Dropdown -->
                    <div class="relative dropdown-container">
                        <a href="/ForeverYoungTours/pages/solutions.php" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all flex items-center space-x-1" onmouseover="showDropdown('solutionDropdown')" onmouseout="hideDropdown('solutionDropdown')">
                            <span>Solution</span>
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div id="solutionDropdown" class="nextcloud-dropdown-mega hidden" onmouseover="showDropdown('solutionDropdown')" onmouseout="hideDropdown('solutionDropdown')">
                            <div class="dropdown-grid">
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/destinations.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-blue-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Destination</div>
                                            <div class="dropdown-desc-large">Explore African destinations</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/booking-engine.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-green-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Booking Engine</div>
                                            <div class="dropdown-desc-large">Complete booking system</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/packing-guide.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-purple-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Packing Guide</div>
                                            <div class="dropdown-desc-large">Essential packing tips</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/travel-tips.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-yellow-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Travel Tips</div>
                                            <div class="dropdown-desc-large">Expert travel advice</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/emergency.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-red-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Emergency</div>
                                            <div class="dropdown-desc-large">24/7 emergency support</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/vip-support.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-indigo-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">VIP Support</div>
                                            <div class="dropdown-desc-large">Premium concierge service</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/personalized-planning.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-pink-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 11-4 0 2 2 0 014 0m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Personalized Planning</div>
                                            <div class="dropdown-desc-large">Custom itinerary design</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/store.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-orange-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Store</div>
                                            <div class="dropdown-desc-large">Essential travel gear & items</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Community Dropdown -->
                    <div class="relative dropdown-container">
                        <a href="/ForeverYoungTours/pages/community.php" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all flex items-center space-x-1" onmouseover="showDropdown('communityDropdown')" onmouseout="hideDropdown('communityDropdown')">
                            <span>Community</span>
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div id="communityDropdown" class="nextcloud-dropdown-mega hidden" onmouseover="showDropdown('communityDropdown')" onmouseout="hideDropdown('communityDropdown')">
                            <div class="dropdown-grid">
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/travel-club-membership.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-yellow-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Travel Club Membership</div>
                                            <div class="dropdown-desc-large">Join our exclusive travel community</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/mca-advisor-network.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-green-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">MCA & Advisor Network</div>
                                            <div class="dropdown-desc-large">Professional travel advisor ecosystem</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/partners.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-blue-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Partners</div>
                                            <div class="dropdown-desc-large">Strategic travel alliances</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- Resources Dropdown -->
                    <div class="relative dropdown-container">
                        <a href="/ForeverYoungTours/pages/resources.php" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all flex items-center space-x-1" onmouseover="showDropdown('resourcesDropdown')" onmouseout="hideDropdown('resourcesDropdown')">
                            <span>Resources</span>
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div id="resourcesDropdown" class="nextcloud-dropdown-mega hidden" onmouseover="showDropdown('resourcesDropdown')" onmouseout="hideDropdown('resourcesDropdown')">
                            <div class="dropdown-grid">
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/packing-guides-resources.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-blue-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Packing Guides</div>
                                            <div class="dropdown-desc-large">Essential packing tips</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/travel-tips-resources.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-green-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Travel Tips</div>
                                            <div class="dropdown-desc-large">Expert travel advice</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/destination-guides.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-purple-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Destination Guides</div>
                                            <div class="dropdown-desc-large">Comprehensive location info</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-column">
                                    <a href="/ForeverYoungTours/pages/senior-discounts.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-orange-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Senior Discounts</div>
                                            <div class="dropdown-desc-large">Special pricing for seniors</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/cultural-etiquette.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-red-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Cultural & Etiquette</div>
                                            <div class="dropdown-desc-large">Local customs and manners</div>
                                        </div>
                                    </a>
                                    <a href="/ForeverYoungTours/pages/visa-documents.php" class="dropdown-item-large">
                                        <div class="dropdown-icon-large bg-indigo-500">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="dropdown-title-large">Visa & Documents</div>
                                            <div class="dropdown-desc-large">Travel documentation help</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="/ForeverYoungTours/pages/contact.php" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all">Contact Us</a>
                </div>
                
                <!-- Right Section -->
                <div class="flex items-center space-x-3">
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])): ?>
                        <!-- Dashboard Button (for logged in users) -->
                        <a href="<?php 
                            echo match($_SESSION['user_role']) {
                                'super_admin', 'admin' => $base_path . 'admin/index.php',
                                'mca' => $base_path . 'mca/index.php',
                                'advisor' => $base_path . 'advisor/index.php',
                                'client' => $base_path . 'client/index.php',
                                default => $base_path . 'auth/login.php'
                            };
                        ?>" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="hidden sm:inline">My Dashboard</span>
                        </a>
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="/ForeverYoungTours/auth/login.php" target="_blank" class="px-4 py-2 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg font-medium transition-all flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="hidden sm:inline">Login</span>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Book Now Button -->
                    <a href="/ForeverYoungTours/pages/packages.php" class="btn-primary px-6 py-2 rounded-lg font-semibold">Book Now
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden p-2 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors" onclick="toggleMobileMenu()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="lg:hidden bg-white border-t border-slate-200 hidden">
                <div class="px-4 py-6 space-y-2">
                    <a href="/ForeverYoungTours/index.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors">Home</a>
                    <a href="/ForeverYoungTours/pages/solutions.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors">Solution</a>
                    <a href="/ForeverYoungTours/pages/community.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors">Community</a>
                    <a href="/ForeverYoungTours/pages/resources.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors">Resources</a>
                    <a href="/ForeverYoungTours/pages/contact.php" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors">Contact Us</a>
                    <div class="pt-4 mt-4 border-t border-slate-200">
                        <a href="/ForeverYoungTours/auth/login.php" target="_blank" class="block px-4 py-2 text-slate-700 hover:bg-slate-100 rounded-lg font-medium transition-colors mb-2">Login</a>
                        <a href="/ForeverYoungTours/pages/packages.php" class="block btn-primary px-6 py-3 rounded-lg font-semibold text-center">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Nextcloud-Style Navigation JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        

        
        let dropdownTimeout;
        
        function showDropdown(dropdownId) {
            clearTimeout(dropdownTimeout);
            // Hide all other dropdowns
            const allDropdowns = document.querySelectorAll('.nextcloud-dropdown-mega');
            allDropdowns.forEach(dropdown => {
                if (dropdown.id !== dropdownId) {
                    dropdown.classList.add('hidden');
                }
            });
            
            // Show the target dropdown
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.classList.remove('hidden');
            }
        }
        
        function hideDropdown(dropdownId) {
            dropdownTimeout = setTimeout(() => {
                const dropdown = document.getElementById(dropdownId);
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            }, 150);
        }
        
        // Hide all dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdownContainers = document.querySelectorAll('.dropdown-container');
            let clickedInside = false;
            
            dropdownContainers.forEach(container => {
                if (container.contains(event.target)) {
                    clickedInside = true;
                }
            });
            
            if (!clickedInside) {
                const allDropdowns = document.querySelectorAll('.nextcloud-dropdown-mega');
                allDropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });
        
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-sm');
                nav.classList.remove('border-b');
            } else {
                nav.classList.remove('shadow-sm');
                nav.classList.add('border-b');
            }
        });
    </script>