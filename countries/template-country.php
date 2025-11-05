<?php
$page_title = "Discover {COUNTRY_NAME} | Premium African Travel | Forever Young Tours";
$meta_description = "Experience {COUNTRY_NAME} with curated luxury tours. Wildlife, culture, adventure. Expert guides, premium lodges, seamless logistics.";
require_once __DIR__ . '/../config/database.php';

// Get country data - replace 'rwanda' with dynamic slug
$country_slug = 'rwanda'; // This will be dynamic per country
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

// Get featured tours
$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC LIMIT 6");
$stmt->execute([$country['id']]);
$tours = $stmt->fetchAll();

$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <meta name="description" content="<?= $meta_description ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-overlay { background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.7) 100%); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-white">

<!-- HERO SECTION -->
<section class="relative h-screen bg-cover bg-center" style="background-image: url('assets/images/rwanda-gorilla-hero.png');">
    <div class="absolute inset-0 gradient-overlay"></div>
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 w-full">
            <div class="max-w-3xl animate-fade-in-up">
                <h1 class="text-6xl md:text-8xl font-bold text-white mb-6 leading-tight">
                    Discover<br><span class="text-yellow-400"><?= htmlspecialchars($country['name']) ?></span>
                </h1>
                <p class="text-2xl text-white/90 mb-8 font-light">Where adventure meets luxury in the heart of Africa</p>
                <div class="flex flex-wrap gap-4">
                    <button onclick="openRequestModal()" class="bg-yellow-500 hover:bg-yellow-600 text-black px-8 py-4 rounded-xl font-bold transition transform hover:scale-105 shadow-2xl">
                        Plan Your Journey
                    </button>
                    <a href="#tours" class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold transition border-2 border-white/30">
                        Explore Tours
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- STATS BAR -->
<section class="bg-gradient-to-r from-yellow-500 via-orange-500 to-yellow-500 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <div class="text-4xl font-bold mb-1"><?= count($tours) ?>+</div>
                <div class="text-sm font-medium opacity-90">Curated Tours</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-1">24/7</div>
                <div class="text-sm font-medium opacity-90">Support</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-1">100%</div>
                <div class="text-sm font-medium opacity-90">Satisfaction</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-1">5★</div>
                <div class="text-sm font-medium opacity-90">Rated Service</div>
            </div>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-600 font-semibold text-sm uppercase tracking-wider">Why Choose Us</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-4">Premium Travel Experience</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">We deliver exceptional journeys with attention to every detail</p>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-500 transition">
                    <i class="fas fa-shield-alt text-3xl text-yellow-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Trusted & Safe</h3>
                <p class="text-gray-600">Licensed operators with comprehensive insurance coverage</p>
            </div>
            <div class="text-center group">
                <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-500 transition">
                    <i class="fas fa-users text-3xl text-yellow-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Expert Guides</h3>
                <p class="text-gray-600">Professional local guides with deep cultural knowledge</p>
            </div>
            <div class="text-center group">
                <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-500 transition">
                    <i class="fas fa-hotel text-3xl text-yellow-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Premium Lodges</h3>
                <p class="text-gray-600">Handpicked luxury accommodations for comfort</p>
            </div>
            <div class="text-center group">
                <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-yellow-500 transition">
                    <i class="fas fa-headset text-3xl text-yellow-600 group-hover:text-white transition"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">24/7 Support</h3>
                <p class="text-gray-600">Round-the-clock assistance throughout your journey</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED TOURS -->
<section id="tours" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-600 font-semibold text-sm uppercase tracking-wider">Our Tours</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-4">Featured Experiences</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Carefully crafted itineraries for unforgettable adventures</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach (array_slice($tours, 0, 6) as $tour): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition group">
                <div class="relative h-64 overflow-hidden">
                    <img src="<?= htmlspecialchars($tour['image_url'] ?: '../assets/images/africa.png') ?>" 
                         alt="<?= htmlspecialchars($tour['name']) ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <?php if ($tour['featured']): ?>
                    <span class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">FEATURED</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?= htmlspecialchars(substr($tour['description'] ?: 'Discover amazing experiences', 0, 100)) ?>...</p>
                    <div class="flex items-center justify-between mb-4 pb-4 border-b">
                        <div>
                            <span class="text-3xl font-bold text-yellow-600">$<?= number_format($tour['price'], 0) ?></span>
                            <span class="text-gray-500 text-sm">/person</span>
                        </div>
                        <div class="text-gray-600 text-sm flex items-center">
                            <i class="far fa-clock mr-2"></i>
                            <?= htmlspecialchars($tour['duration']) ?>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="../pages/tour-detail.php?id=<?= $tour['id'] ?>" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg text-center font-semibold transition">
                            View Details
                        </a>
                        <button onclick="openRequestModal('<?= htmlspecialchars($tour['name']) ?>')" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg font-semibold transition">
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- EXPERIENCES GRID -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-600 font-semibold text-sm uppercase tracking-wider">What to Do</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-4">Unique Experiences</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <?php 
            $experiences = [
                ['icon' => 'fa-paw', 'title' => 'Wildlife Safari', 'desc' => 'Encounter majestic animals in their natural habitat'],
                ['icon' => 'fa-mountain', 'title' => 'Mountain Trekking', 'desc' => 'Conquer peaks with breathtaking views'],
                ['icon' => 'fa-landmark', 'title' => 'Cultural Tours', 'desc' => 'Immerse in rich traditions and heritage'],
                ['icon' => 'fa-water', 'title' => 'Water Adventures', 'desc' => 'Explore lakes, rivers, and waterfalls'],
                ['icon' => 'fa-utensils', 'title' => 'Culinary Journey', 'desc' => 'Taste authentic local cuisine'],
                ['icon' => 'fa-camera', 'title' => 'Photography Tours', 'desc' => 'Capture stunning landscapes and wildlife']
            ];
            foreach ($experiences as $exp): ?>
            <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-200 hover:border-yellow-500 transition group">
                <i class="fas <?= $exp['icon'] ?> text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2"><?= $exp['title'] ?></h3>
                <p class="text-gray-600"><?= $exp['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="py-20 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-400 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-4xl md:text-5xl font-bold mt-2 mb-4">What Travelers Say</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php 
            $testimonials = [
                ['name' => 'Sarah Johnson', 'country' => 'USA', 'text' => 'Absolutely incredible experience! The guides were knowledgeable and the accommodations were top-notch.', 'rating' => 5],
                ['name' => 'David Chen', 'country' => 'Singapore', 'text' => 'Best safari of my life. Every detail was perfectly planned and executed. Highly recommend!', 'rating' => 5],
                ['name' => 'Emma Williams', 'country' => 'UK', 'text' => 'The cultural immersion was authentic and moving. Forever Young Tours exceeded all expectations.', 'rating' => 5]
            ];
            foreach ($testimonials as $test): ?>
            <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl">
                <div class="flex mb-4">
                    <?php for($i=0; $i<$test['rating']; $i++): ?>
                    <i class="fas fa-star text-yellow-400"></i>
                    <?php endfor; ?>
                </div>
                <p class="text-white/90 mb-6 italic">"<?= $test['text'] ?>"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-black font-bold mr-4">
                        <?= substr($test['name'], 0, 1) ?>
                    </div>
                    <div>
                        <div class="font-bold"><?= $test['name'] ?></div>
                        <div class="text-sm text-white/70"><?= $test['country'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-600 font-semibold text-sm uppercase tracking-wider">FAQ</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-4">Common Questions</h2>
        </div>
        <div class="space-y-4">
            <?php 
            $faqs = [
                ['q' => 'What is included in the tour packages?', 'a' => 'All tours include accommodation, meals, transportation, professional guides, park fees, and activities as specified in the itinerary.'],
                ['q' => 'How do I book a tour?', 'a' => 'Click any "Book Now" button, fill out the inquiry form, and our team will contact you within 24 hours with availability and payment details.'],
                ['q' => 'What is your cancellation policy?', 'a' => 'Cancellations made 30+ days before departure receive full refund minus 10% admin fee. 15-29 days: 50% refund. Less than 15 days: no refund.'],
                ['q' => 'Do you offer private tours?', 'a' => 'Yes! All our tours can be customized for private groups with flexible dates and personalized itineraries.']
            ];
            foreach ($faqs as $i => $faq): ?>
            <div class="bg-gray-50 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(<?= $i ?>)" class="w-full text-left px-6 py-5 flex justify-between items-center hover:bg-gray-100 transition">
                    <h3 class="font-bold text-lg text-gray-900 pr-4"><?= $faq['q'] ?></h3>
                    <i id="icon-<?= $i ?>" class="fas fa-chevron-down text-yellow-600 transition-transform"></i>
                </button>
                <div id="faq-<?= $i ?>" class="hidden px-6 pb-5">
                    <p class="text-gray-600 leading-relaxed"><?= $faq['a'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready for Your Adventure?</h2>
        <p class="text-xl text-white/90 mb-8">Let's create your perfect <?= htmlspecialchars($country['name']) ?> experience</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <button onclick="openRequestModal()" class="bg-white text-gray-900 px-10 py-4 rounded-xl font-bold hover:bg-gray-100 transition transform hover:scale-105 shadow-2xl">
                Start Planning
            </button>
            <a href="https://wa.me/17374439646" class="bg-green-500 text-white px-10 py-4 rounded-xl font-bold hover:bg-green-600 transition transform hover:scale-105 shadow-2xl">
                <i class="fab fa-whatsapp mr-2"></i>WhatsApp Us
            </a>
        </div>
    </div>
</section>

<!-- REQUEST MODAL -->
<div id="requestModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-8 py-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold">Plan Your Journey</h2>
            <button onclick="closeRequestModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form action="../pages/inquiry-form.php" method="POST" class="p-8">
            <input type="hidden" name="tour_name" id="modal_tour_name" value="<?= htmlspecialchars($country['name']) ?> Inquiry">
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <input type="text" name="client_name" placeholder="Full Name" required class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <input type="email" name="email" placeholder="Email" required class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <input type="tel" name="phone" placeholder="Phone Number" required class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <input type="number" name="adults" placeholder="Number of Travelers" required class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <input type="text" name="travel_dates" placeholder="Preferred Travel Dates" class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-4 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            <textarea name="notes" placeholder="Tell us about your dream trip..." rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-6 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-4 rounded-xl font-bold hover:shadow-xl transition">
                Send Inquiry
            </button>
        </form>
    </div>
</div>

<script>
function openRequestModal(tourName = '<?= htmlspecialchars($country['name']) ?> Inquiry') {
    document.getElementById('modal_tour_name').value = tourName;
    document.getElementById('requestModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeRequestModal() {
    document.getElementById('requestModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function toggleFaq(index) {
    const content = document.getElementById('faq-' + index);
    const icon = document.getElementById('icon-' + index);
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('fa-chevron-up');
        icon.classList.remove('fa-chevron-down');
    } else {
        content.classList.add('hidden');
        icon.classList.add('fa-chevron-down');
        icon.classList.remove('fa-chevron-up');
    }
}
document.getElementById('requestModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRequestModal();
});
</script>

<!-- Floating WhatsApp -->
<a href="https://wa.me/17374439646" class="fixed bottom-6 right-6 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:bg-green-600 transition transform hover:scale-110 z-50">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>

<?php include '../includes/footer.php'; ?>
