<?php
$page_title = "Discover Rwanda | Luxury Group Travel, Primate Safaris, Culture | Forever Young Tours";
$meta_description = "Premium Rwanda travel. Gorillas, chimps, volcanoes, canopy walks, culture. Curated 6–10 day programs, premium lodges, seamless logistics. Request dates via WhatsApp or email.";
require_once __DIR__ . '/../../config/database.php';

// Get Rwanda data
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = 'visit-rw' AND c.status = 'active'");
$stmt->execute();
$country = $stmt->fetch();

// Get featured tours
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC LIMIT 4");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

$base_path = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <meta name="description" content="<?= $meta_description ?>">
    <link rel="canonical" href="https://visit-rw.iforeveryoungtours.com/">
    <meta property="og:title" content="<?= $page_title ?>">
    <meta property="og:description" content="<?= $meta_description ?>">
    <meta property="og:image" content="https://visit-rw.iforeveryoungtours.com/assets/images/rwanda-og.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
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

<!-- HERO -->
<section class="relative h-screen bg-cover bg-center" style="background-image: url('assets/images/rwanda-gorilla-hero.png');">
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>
    <div class="relative z-10 h-full flex flex-col justify-center items-center text-center px-4">
        <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 tracking-tight">Discover Rwanda</h1>
        <p class="text-2xl md:text-3xl text-white/95 mb-8 font-light">Gorillas. Volcanoes. Culture. Premium by design.</p>
        <div class="flex flex-wrap gap-4 justify-center mb-8">
            <button onclick="openRequestModal()" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-10 py-4 rounded-xl font-bold hover:shadow-2xl transition transform hover:scale-105">Request Availability</button>
            <a href="https://wa.me/17374439646?text=Rwanda%20Inquiry" class="bg-white text-gray-900 px-10 py-4 rounded-xl font-bold hover:shadow-2xl transition transform hover:scale-105">Talk to Advisor</a>
            <a href="#" class="border-2 border-white text-white px-10 py-4 rounded-xl font-bold hover:bg-white/10 transition">Download Country PDF</a>
        </div>
        <div class="text-white/80 text-sm">
            Norrsken House Kigali • info@iforeveryoungtours.com • WhatsApp +1 737 443 9646
        </div>
    </div>
</section>

<!-- VALUE PROPOSITIONS -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-paw text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Primate Access</h3>
                <p class="text-gray-600 text-sm">Gorilla, Chimp, Golden Monkey permits handled end-to-end.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hotel text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Premium Lodges</h3>
                <p class="text-gray-600 text-sm">Boutique and luxury partners vetted for consistency.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Seamless Ops</h3>
                <p class="text-gray-600 text-sm">FYT on-ground team in Kigali. Zero friction transfers.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Impact Travel</h3>
                <p class="text-gray-600 text-sm">Conservation-aligned itineraries and local engagement.</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED ITINERARIES -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Featured Itineraries</h2>
            <p class="text-gray-600">Curated Rwanda experiences</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach (array_slice($tours, 0, 3) as $tour): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <img src="<?= htmlspecialchars($tour['image_url'] ?: '../../assets/images/africa.png') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex gap-2 mb-3">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Wildlife</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Premium</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-2xl font-bold text-yellow-600 mb-4">From $<?= number_format($tour['price'], 0) ?> pp</p>
                    <div class="flex gap-2">
                        <a href="../../pages/tour-detail.php?id=<?= $tour['id'] ?>" class="flex-1 bg-yellow-500 text-white py-2 rounded text-center font-semibold hover:bg-yellow-600">View Itinerary</a>
                        <button onclick="openRequestModal('<?= htmlspecialchars($tour['name']) ?>')" class="flex-1 border-2 border-yellow-500 text-yellow-600 py-2 rounded text-center font-semibold hover:bg-yellow-50">Ask Dates</button>
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

<!-- PRICING STRIP -->
<section class="py-12 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 text-center text-white">
        <h3 class="text-3xl font-bold mb-2">$4,600 per person (2 Pax Basis)</h3>
        <p class="mb-6">Ask for 4/6/8 pax tier matrix. Premium Lodge / Boutique. Permits included.</p>
        <div class="flex gap-4 justify-center">
            <button onclick="openRequestModal()" class="bg-white text-yellow-600 px-8 py-3 rounded-lg font-bold hover:shadow-xl">Get Tier Prices</button>
            <a href="https://wa.me/17374439646?text=Rwanda%20Pricing%20Request" class="border-2 border-white px-8 py-3 rounded-lg font-bold hover:bg-white/10">WhatsApp Us</a>
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

</body>
</html>

<?php include 'includes/footer.php'; ?>
