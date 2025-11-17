<?php
// Set the base path for includes
$base_path = dirname(dirname(dirname(__DIR__)));

// Database configuration is included by subdomain-handler.php
// Do not include it here to prevent duplicate inclusion

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug: Check if database connection is available
if (!function_exists('getDB')) {
    error_log("Database connection not available. Check if subdomain-handler.php included the database config.");
    die("Configuration error. Please contact the administrator.");
}

// Set default values if not set by subdomain handler
if (!defined('COUNTRY_SUBDOMAIN') || !COUNTRY_SUBDOMAIN) {
    // If not loaded via subdomain, get country data directly
    $stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c 
                          LEFT JOIN regions r ON c.region_id = r.id 
                          WHERE c.slug = 'rwanda' AND c.status = 'active'");
    $stmt->execute();
    $country = $stmt->fetch();
    
    if ($country) {
        $_SESSION['subdomain_country_code'] = $country['country_code'];
        $_SESSION['subdomain_country_name'] = $country['name'];
        $_SESSION['subdomain_country_slug'] = $country['slug'];
    }
}

// Set page metadata
$page_title = "Discover Rwanda | Luxury Group Travel, Primate Safaris, Culture | Forever Young Tours";
$meta_description = "Premium Rwanda travel. Gorillas, chimps, volcanoes, canopy walks, culture. Curated 6–10 day programs, premium lodges, seamless logistics. Request dates via WhatsApp or email.";

// Handle case where country is not found
if (!$country) {
    // Redirect to main site or show error
    header('Location: ../../index.php');
    exit;
}

// Get featured tours
if (isset($country['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC LIMIT 4");
    $stmt->execute([$country['id']]);
    $tours = $stmt->fetchAll();
} else {
    $tours = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $page_title ?></title>
    <meta name="description" content="<?= $meta_description ?>">
    <link rel="canonical" href="https://visit-rw.iforeveryoungtours.com/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://visit-rw.iforeveryoungtours.com/">
    <meta property="og:title" content="<?= $page_title ?>">
    <meta property="og:description" content="<?= $meta_description ?>">
    <meta property="og:image" content="<?= getImageUrl('assets/images/Rwanda.jpg') ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://visit-rw.iforeveryoungtours.com/">
    <meta property="twitter:title" content="<?= $page_title ?>">
    <meta property="twitter:description" content="<?= $meta_description ?>">
    <meta property="twitter:image" content="<?= getImageUrl('assets/images/Rwanda.jpg') ?>">
    
    <!-- Mobile Optimization -->
    <meta name="theme-color" content="#F59E0B">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=yes">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-amber': '#F59E0B',
                        'brand-orange': '#EA580C',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TouristDestination",
      "name": "Rwanda",
      "description": "Premium Rwanda travel with gorilla, chimp, and golden monkey encounters. Curated itineraries, premium lodges, and on-ground FYT operations.",
      "url": "https://visit-rw.iforeveryoungtours.com/",
      "touristType": ["Luxury Group", "Adventure", "Cultural", "MICE"],
      "provider": {
        "@type": "TravelAgency",
        "name": "Forever Young Tours",
        "telephone": "+1-737-443-9646",
        "email": "info@iforeveryoungtours.com",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Norrsken House Kigali",
          "addressLocality": "Kigali",
          "addressCountry": "RW"
        }
      },
      "hasPart": [
        {
          "@type": "TouristTrip",
          "name": "6 Days Rwanda Premium Primate Safari",
          "itinerary": "Kigali • Nyungwe • Volcanoes • Kigali",
          "offers": {
            "@type": "Offer",
            "price": "4600",
            "priceCurrency": "USD",
            "availability": "https://schema.org/LimitedAvailability"
          }
        }
      ]
    }
    </script>
</head>
<body class="font-sans">

<!-- HERO - Professional Parallax -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden" id="hero">
    <!-- Parallax Background -->
    <div class="absolute inset-0 z-0" data-parallax>
        <img src="<?= getImageUrl('countries/rwanda/assets/images/hero-rwanda.jpg') ?>" alt="Rwanda Gorillas" class="w-full h-full object-cover scale-110" onerror="this.src='<?= getImageUrl('countries/rwanda/assets/images/rwanda-gorilla-hero.png') ?>'; this.onerror=function(){this.src='<?= getImageUrl('assets/images/africa.png') ?>';}">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-amber-900/70"></div>
        
        <!-- Animated Overlay Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
        </div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-6xl mx-auto text-center">
            <!-- Region Badge -->
            <div class="mb-8 animate-fade-in-down">
                <span class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500/20 to-orange-500/20 backdrop-blur-md text-amber-300 px-8 py-3 rounded-full text-sm font-bold border-2 border-amber-400/30 shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    East Africa
                </span>
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-tight animate-fade-in">
                <span class="block mb-2 text-2xl sm:text-3xl md:text-4xl font-light text-amber-300">Discover</span>
                <span class="block bg-gradient-to-r from-amber-200 via-amber-400 to-orange-400 bg-clip-text text-transparent drop-shadow-2xl">
                    Rwanda
                </span>
            </h1>
            
            <!-- Description -->
            <p class="text-lg sm:text-xl md:text-2xl text-gray-200 mb-12 leading-relaxed max-w-4xl mx-auto animate-fade-in-up font-light">
                Gorillas. Volcanoes. Culture. Premium by design.
            </p>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-12 max-w-5xl mx-auto animate-fade-in-up px-4 sm:px-0">
                <div class="bg-gradient-to-br from-amber-500/20 to-orange-500/20 backdrop-blur-md p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-amber-400/30 hover:scale-105 transition-transform duration-300 shadow-xl shadow-amber-500/10">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white mb-1">Kigali</div>
                    <div class="text-xs sm:text-sm text-amber-300 font-semibold">Capital City</div>
                </div>
                
                <div class="bg-gradient-to-br from-emerald-500/20 to-green-500/20 backdrop-blur-md p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-emerald-400/30 hover:scale-105 transition-transform duration-300 shadow-xl shadow-emerald-500/10">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white mb-1">13.5M</div>
                    <div class="text-xs sm:text-sm text-emerald-300 font-semibold">Population</div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 backdrop-blur-md p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-blue-400/30 hover:scale-105 transition-transform duration-300 shadow-xl shadow-blue-500/10">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white mb-1">15+</div>
                    <div class="text-xs sm:text-sm text-blue-300 font-semibold">Tours Available</div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 backdrop-blur-md p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-purple-400/30 hover:scale-105 transition-transform duration-300 shadow-xl shadow-purple-500/10">
                    <div class="flex justify-center mb-2 sm:mb-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white mb-1">RWF</div>
                    <div class="text-xs sm:text-sm text-purple-300 font-semibold">Currency</div>
                </div>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 justify-center mb-8 animate-fade-in-up">
                <button onclick="openRequestModal()" class="group relative bg-gradient-to-r from-amber-500 to-orange-500 text-white px-10 py-4 rounded-full font-bold overflow-hidden shadow-2xl shadow-amber-500/50 hover:shadow-amber-500/70 transition-all duration-300 hover:scale-105">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Request Availability
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
                
                <a href="https://wa.me/17374439646?text=Rwanda%20Inquiry" class="group relative bg-white text-gray-900 px-10 py-4 rounded-full font-bold overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-300 hover:scale-105">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                        </svg>
                        Talk to Advisor
                    </span>
                </a>
                
                <a href="#" class="group relative border-2 border-white text-white px-10 py-4 rounded-full font-bold overflow-hidden hover:bg-white/10 backdrop-blur-sm transition-all duration-300 hover:scale-105">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path>
                        </svg>
                        Download PDF
                    </span>
                </a>
            </div>
            
            <!-- Contact Info -->
            <div class="text-white/80 text-sm animate-fade-in-up flex flex-wrap justify-center gap-4">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Norrsken House Kigali
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                    info@iforeveryoungtours.com
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    +1 737 443 9646
                </span>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- VALUE PROPOSITIONS - Enhanced -->
<section class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold mb-4">Why Choose Us</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Premium Rwanda Experience</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Curated adventures with unmatched expertise and on-ground support</p>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-amber-500/50">
                    <i class="fas fa-paw text-white text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">Primate Access</h3>
                <p class="text-gray-600 leading-relaxed">Gorilla, Chimp, Golden Monkey permits handled end-to-end with guaranteed availability.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-emerald-500/50">
                    <i class="fas fa-hotel text-white text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">Premium Lodges</h3>
                <p class="text-gray-600 leading-relaxed">Boutique and luxury partners vetted for consistency and exceptional service.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/50">
                    <i class="fas fa-check-circle text-white text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">Seamless Ops</h3>
                <p class="text-gray-600 leading-relaxed">FYT on-ground team in Kigali. Zero friction transfers and 24/7 support.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-purple-500/50">
                    <i class="fas fa-leaf text-white text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">Impact Travel</h3>
                <p class="text-gray-600 leading-relaxed">Conservation-aligned itineraries and meaningful local engagement.</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED ITINERARIES - Enhanced -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold mb-4">Popular Tours</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Featured Itineraries</h2>
            <p class="text-gray-600 text-lg">Curated Rwanda experiences designed for unforgettable moments</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach (array_slice($tours, 0, 3) as $tour): ?>
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="relative overflow-hidden">
                    <img src="<?= getImageUrl($tour['image_url'] ?: $tour['cover_image'], 'countries/rwanda/assets/images/hero-rwanda.jpg') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='<?= getImageUrl('countries/rwanda/assets/images/rwanda-gorilla-hero.png') ?>'; this.onerror=function(){this.src='<?= getImageUrl('assets/images/africa.png') ?>';}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">Featured</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex gap-2 mb-4">
                        <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs rounded-full font-semibold">Wildlife</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs rounded-full font-semibold">Premium</span>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900 group-hover:text-amber-600 transition-colors"><?= htmlspecialchars($tour['name']) ?></h3>
                    <div class="flex items-baseline gap-2 mb-6">
                        <p class="text-3xl font-black text-amber-600">$<?= number_format($tour['price'], 0) ?></p>
                        <span class="text-gray-500 text-sm">per person</span>
                    </div>
                    <div class="flex gap-3">
                        <a href="../../pages/tour-detail.php?id=<?= $tour['id'] ?>" class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white py-3 rounded-xl text-center font-bold hover:shadow-lg hover:shadow-amber-500/50 transition-all">View Details</a>
                        <button onclick="openBookingModal(<?= $tour['id'] ?>, '<?= htmlspecialchars($tour['name']) ?>', <?= $tour['price'] ?>)" class="flex-1 border-2 border-amber-500 text-amber-600 py-3 rounded-xl text-center font-bold hover:bg-amber-50 transition-all">Book Now</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCES MATRIX -->
<section class="py-20 bg-gray-50">
    <div class="w-full">
        <h2 class="text-4xl font-bold text-center mb-12">Rwanda Experiences</h2>
        
        <!-- First Row - Left to Right -->
        <div class="relative overflow-hidden mb-0">
            <div class="flex gap-0 animate-scroll-right">
                <?php 
                $experiences_row1 = [
                    ['category' => 'Responsible Tourism', 'title' => 'Gorilla Tracking', 'image' => 'https://images.unsplash.com/photo-1551918120-9739cb430c6d?w=800'],
                    ['category' => 'Culture & Heritage', 'title' => "King's Palace", 'image' => 'https://images.unsplash.com/photo-1609137144813-7d9921338f24?w=800'],
                    ['category' => 'Accommodation', 'title' => 'Where to Stay', 'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800'],
                    ['category' => 'Sport & Adventure', 'title' => 'Canopy Walkway', 'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800'],
                    ['category' => 'Wildlife', 'title' => 'Golden Monkey Trekking', 'image' => 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=800']
                ];
                $all_row1 = array_merge($experiences_row1, $experiences_row1, $experiences_row1);
                foreach ($all_row1 as $exp): ?>
                <div class="relative h-64 flex-shrink-0 w-96 overflow-hidden group">
                    <img src="<?= $exp['image'] ?>" alt="<?= $exp['title'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <p class="text-xs font-semibold mb-1 uppercase tracking-wider"><?= $exp['category'] ?></p>
                        <h3 class="text-2xl font-bold"><?= $exp['title'] ?></h3>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Second Row - Right to Left -->
        <div class="relative overflow-hidden">
            <div class="flex gap-0 animate-scroll-left">
                <?php 
                $experiences_row2 = [
                    ['category' => 'Nature', 'title' => 'Mukungwa River Canoeing', 'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800'],
                    ['category' => 'Agro-Tourism', 'title' => 'Tea Plantation Immersion', 'image' => 'https://images.unsplash.com/photo-1563789031959-4c02bcb41319?w=800'],
                    ['category' => 'Culture', 'title' => 'Kigali Craft & Culinary', 'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800'],
                    ['category' => 'Conservation', 'title' => 'Ellen DeGeneres Campus', 'image' => 'https://images.unsplash.com/photo-1497206365907-f5e630693df0?w=800']
                ];
                $all_row2 = array_merge($experiences_row2, $experiences_row2, $experiences_row2);
                foreach ($all_row2 as $exp): ?>
                <div class="relative h-64 flex-shrink-0 w-96 overflow-hidden group">
                    <img src="<?= $exp['image'] ?>" alt="<?= $exp['title'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <p class="text-xs font-semibold mb-1 uppercase tracking-wider"><?= $exp['category'] ?></p>
                        <h3 class="text-2xl font-bold"><?= $exp['title'] ?></h3>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes scrollRight {
    0% { transform: translateX(0); }
    100% { transform: translateX(-33.33%); }
}
@keyframes scrollLeft {
    0% { transform: translateX(-33.33%); }
    100% { transform: translateX(0); }
}
.animate-scroll-right {
    animation: scrollRight 30s linear infinite;
}
.animate-scroll-left {
    animation: scrollLeft 30s linear infinite;
}
.animate-scroll-right:hover,
.animate-scroll-left:hover {
    animation-play-state: paused;
}
</style>

<!-- PRICING STRIP - Enhanced -->
<section class="relative py-20 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.3) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 text-center text-white">
        <div class="mb-8">
            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-semibold mb-4">Special Pricing</span>
            <h3 class="text-4xl md:text-5xl font-black mb-4">From $4,600 per person</h3>
            <p class="text-xl text-white/90 mb-2">2 Pax Basis • Premium Lodges • All Permits Included</p>
            <p class="text-white/80">Ask for 4/6/8 pax tier matrix and group discounts</p>
        </div>
        <div class="flex flex-wrap gap-4 justify-center">
            <button onclick="openRequestModal()" class="group bg-white text-amber-600 px-10 py-4 rounded-full font-bold hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                    </svg>
                    Get Custom Quote
                </span>
            </button>
            <a href="https://wa.me/17374439646?text=Rwanda%20Pricing%20Request" class="group border-2 border-white text-white px-10 py-4 rounded-full font-bold hover:bg-white/10 backdrop-blur-sm transition-all duration-300 hover:scale-105">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    WhatsApp Us
                </span>
            </a>
        </div>
        <div class="mt-8 flex flex-wrap justify-center gap-6 text-sm text-white/80">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Flexible Payment Plans
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Free Cancellation
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Best Price Guarantee
            </span>
        </div>
    </div>
</section>

<!-- REQUEST MODAL -->
<div id="requestModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-8 py-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold">Request Rwanda Dates</h2>
            <button onclick="closeRequestModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-8">
            <form action="../../pages/inquiry-form.php" method="POST">
                <input type="hidden" name="tour_name" id="modal_tour_name" value="Rwanda Dates Request">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <input type="text" name="client_name" placeholder="Full Name" required class="border rounded-lg px-4 py-3">
                    <input type="email" name="email" placeholder="Email" required class="border rounded-lg px-4 py-3">
                </div>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <input type="tel" name="phone" placeholder="WhatsApp Number" required class="border rounded-lg px-4 py-3">
                    <input type="number" name="adults" placeholder="Group Size" required class="border rounded-lg px-4 py-3">
                </div>
                <input type="text" name="travel_dates" placeholder="Month Window (e.g., June 2025)" class="w-full border rounded-lg px-4 py-3 mb-4">
                <select name="categories" class="w-full border rounded-lg px-4 py-3 mb-4">
                    <option value="">Select Interest</option>
                    <option value="Wildlife">Wildlife</option>
                    <option value="Culture">Culture</option>
                    <option value="Agro">Agro-Tourism</option>
                    <option value="MICE">MICE/Conferences</option>
                </select>
                <textarea name="notes" placeholder="Additional Notes" rows="4" class="w-full border rounded-lg px-4 py-3 mb-4"></textarea>
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-4 rounded-lg font-bold hover:shadow-xl">Check Availability Now</button>
            </form>
            <div class="text-center mt-6">
                <p class="text-gray-600 mb-3">Prefer WhatsApp? Get answers in minutes.</p>
                <a href="https://wa.me/17374439646?text=Rwanda%20Dates%20Request" class="inline-flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-600">
                    <i class="fab fa-whatsapp text-xl"></i> Open WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openRequestModal(tourName = 'Rwanda Dates Request') {
    document.getElementById('modal_tour_name').value = tourName;
    document.getElementById('requestModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRequestModal() {
    document.getElementById('requestModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close on outside click
document.getElementById('requestModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRequestModal();
});
</script>

<!-- FAQS -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">FAQ</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-600 text-lg">Everything you need to know about Rwanda travel</p>
        </div>
        <div class="space-y-4">
            <?php 
            $faqs = [
                ['q' => 'How far in advance should I book gorilla permits?', 'a' => 'Early booking is optimal. Gorilla permits are limited and in high demand. We recommend booking 3-6 months in advance, especially for peak seasons (June-September, December-February). We allocate permits immediately upon inquiry confirmation.'],
                ['q' => 'What fitness level is required?', 'a' => 'Moderate fitness is recommended. Gorilla trekking involves hiking through mountainous terrain at altitude. Treks can last 1-6 hours depending on gorilla location. We work with park authorities to match groups to your fitness level where possible.'],
                ['q' => "What's included in FYT primate programs?", 'a' => 'Our programs include premium lodge accommodation, all gorilla/chimp permits, full board meals, private 4×4 safari vehicle, professional English-speaking guide, park fees, bottled water, and all government taxes.'],
                ['q' => 'Can I combine Akagera Big Five with gorillas?', 'a' => 'Absolutely! We recommend 8-10 day circuits that combine Volcanoes National Park (gorillas), Nyungwe Forest (chimps & canopy walk), and Akagera National Park (Big Five safari). This gives you the complete Rwanda wildlife experience.'],
                ['q' => 'Do you handle dietary needs?', 'a' => 'Yes, we accommodate all dietary requirements including vegetarian, vegan, gluten-free, halal, and kosher meals. Please advise us of any dietary restrictions or allergies at the time of booking.'],
                ['q' => 'Do you support private departures?', 'a' => 'Yes. All our Rwanda programs can be customized for private departures with flexible dates. We tailor itineraries to your preferences, pace, and interests. Contact us for a personalized quote.']
            ];
            foreach ($faqs as $index => $faq): ?>
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <button onclick="toggleFaq(<?= $index ?>)" class="w-full text-left px-6 py-5 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-900 pr-4"><?= $faq['q'] ?></h3>
                    <svg id="icon-<?= $index ?>" class="w-6 h-6 text-yellow-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="faq-<?= $index ?>" class="hidden px-6 pb-5">
                    <p class="text-gray-600 leading-relaxed"><?= $faq['a'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
function toggleFaq(index) {
    const content = document.getElementById('faq-' + index);
    const icon = document.getElementById('icon-' + index);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

<!-- Floating WhatsApp Button -->
<div class="fixed bottom-6 right-6 z-50">
    <!-- Dropdown Card -->
    <div id="whatsappDropup" class="hidden mb-4 bg-white rounded-2xl shadow-2xl w-80 animate-slide-up">
        <div class="bg-green-500 text-white px-6 py-4 rounded-t-2xl">
            <h3 class="font-bold text-lg">WhatsApp Support</h3>
            <p class="text-sm text-white/90">We're here to help!</p>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4 text-sm">Get instant answers about Rwanda tours, pricing, and availability.</p>
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>24/7 Support</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>Expert Advisors</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                    <span>Quick Response</span>
                </div>
            </div>
            <a href="https://wa.me/17374439646?text=Hi!%20I%20need%20help%20with%20Rwanda%20travel" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 rounded-xl font-bold transition text-sm">
                <i class="fab fa-whatsapp mr-2"></i>Start Chat
            </a>
            <p class="text-center text-gray-500 text-xs mt-3">+1 (737) 443-9646</p>
        </div>
    </div>
    
    <!-- WhatsApp Button -->
    <button onclick="toggleWhatsAppDropup()" class="bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 transition transform hover:scale-110">
        <i class="fab fa-whatsapp text-3xl"></i>
    </button>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slide-up {
    animation: slideUp 0.3s ease-out;
}
</style>

<script>
function toggleWhatsAppDropup() {
    const dropup = document.getElementById('whatsappDropup');
    dropup.classList.toggle('hidden');
}
// Close when clicking outside
document.addEventListener('click', function(e) {
    const dropup = document.getElementById('whatsappDropup');
    const button = e.target.closest('button[onclick="toggleWhatsAppDropup()"]');
    if (!button && !dropup.contains(e.target)) {
        dropup.classList.add('hidden');
    }
});
</script>

<style>
/* Professional Tourism Theme Animations */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

.animate-fade-in-down {
    animation: fade-in-down 1s ease-out forwards;
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: #1e293b;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #f59e0b, #ea580c);
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #d97706, #c2410c);
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Touch-Friendly Improvements */
@media (hover: none) and (pointer: coarse) {
    /* Mobile touch optimizations */
    .hover\:scale-105:hover {
        transform: scale(1.02);
    }
    
    button, a {
        -webkit-tap-highlight-color: rgba(245, 158, 11, 0.3);
    }
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden;
}

/* Ensure images don't overflow */
img {
    max-width: 100%;
    height: auto;
}

/* Fix for iOS Safari viewport height */
@supports (-webkit-touch-callout: none) {
    .min-h-screen {
        min-height: -webkit-fill-available;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gradient-to-br {
        border: 2px solid currentColor;
    }
}
</style>

<script>
// Parallax Scrolling Effect
document.addEventListener('DOMContentLoaded', function() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(function(element) {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = 'translateY(' + yPos + 'px)';
        });
    });
    
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.observe-fade').forEach(function(el) {
        observer.observe(el);
    });
});
</script>

<!-- Include Enhanced Booking Modal -->
<?php include '../../pages/enhanced-booking-modal.php'; ?>

</body>
</html>

<?php include 'includes/footer.php'; ?>
