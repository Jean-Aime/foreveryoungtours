<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$tour_id = $_GET['id'] ?? 0;
$shared_link = $_GET['ref'] ?? null;

// Track shared link click if present
if ($shared_link) {
    $stmt = $conn->prepare("UPDATE shared_links SET clicks = clicks + 1, last_clicked_at = NOW() WHERE link_code = ?");
    $stmt->execute([$shared_link]);
}

// Get tour details with enhanced information
$stmt = $conn->prepare("
    SELECT t.*, c.name as country_name, c.slug as country_slug, r.name as region_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    WHERE t.id = ? AND t.status = 'active'
");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tour) {
    header('Location: packages.php');
    exit;
}

// Get tour images
$stmt = $conn->prepare("SELECT * FROM tour_images WHERE tour_id = ? ORDER BY sort_order");
$stmt->execute([$tour_id]);
$tour_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get tour reviews
$stmt = $conn->prepare("SELECT * FROM tour_reviews WHERE tour_id = ? AND status = 'approved' ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$tour_id]);
$tour_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get tour FAQs
$stmt = $conn->prepare("SELECT * FROM tour_faqs WHERE tour_id = ? AND is_active = 1 ORDER BY sort_order");
$stmt->execute([$tour_id]);
$tour_faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get similar tours
$stmt = $conn->prepare("
    SELECT t.*, c.name as country_name 
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE t.category = ? AND t.id != ? AND t.status = 'active' 
    ORDER BY t.popularity_score DESC 
    LIMIT 4
");
$stmt->execute([$tour['category'], $tour_id]);
$similar_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = htmlspecialchars($tour['meta_title'] ?: $tour['name']) . " - iForYoungTours";
$page_description = htmlspecialchars($tour['meta_description'] ?: substr($tour['description'], 0, 160));
$css_path = '../assets/css/modern-styles.css';

include '../includes/header.php';
?>

<div class="min-h-screen bg-cream pt-20">
    <!-- Hero Section with Image Gallery -->
    <section class="relative">
        <div class="h-96 md:h-[500px] overflow-hidden">
            <?php if (!empty($tour_images)): ?>
            <div id="heroCarousel" class="relative h-full">
                <?php foreach ($tour_images as $index => $image): ?>
                <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?> absolute inset-0">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($image['alt_text'] ?: $tour['name']); ?>" 
                         class="w-full h-full object-cover">
                </div>
                <?php endforeach; ?>
                
                <!-- Carousel Controls -->
                <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Carousel Indicators -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                    <?php foreach ($tour_images as $index => $image): ?>
                    <button onclick="goToSlide(<?php echo $index; ?>)" class="carousel-indicator w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 <?php echo $index === 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <img src="<?php echo htmlspecialchars($tour['cover_image'] ?: $tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>" 
                 alt="<?php echo htmlspecialchars($tour['name']); ?>" 
                 class="w-full h-full object-cover">
            <?php endif; ?>
        </div>
        
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white max-w-4xl px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4"><?php echo htmlspecialchars($tour['name']); ?></h1>
                <p class="text-xl mb-6"><?php echo htmlspecialchars($tour['country_name'] . ', ' . $tour['region_name']); ?></p>
                <div class="flex justify-center items-center space-x-6 flex-wrap">
                    <span class="bg-golden-500 text-black px-4 py-2 rounded-full font-bold text-lg">
                        From $<?php echo number_format($tour['price']); ?>
                    </span>
                    <span class="text-lg"><?php echo htmlspecialchars($tour['duration'] ?: $tour['duration_days'] . ' days'); ?></span>
                    <span class="text-lg"><?php echo ucfirst($tour['category']); ?></span>
                    <?php if ($tour['difficulty_level']): ?>
                    <span class="text-lg"><?php echo ucfirst($tour['difficulty_level']); ?> Level</span>
                    <?php endif; ?>
                </div>
                <?php if ($tour['average_rating'] > 0): ?>
                <div class="mt-4 flex justify-center items-center">
                    <div class="flex text-yellow-400 mr-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?php echo $i <= $tour['average_rating'] ? '' : 'text-gray-400'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-white"><?php echo number_format($tour['average_rating'], 1); ?> (<?php echo $tour['review_count']; ?> reviews)</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Tour Details -->
            <div class="lg:col-span-2">
                <!-- Tour Overview -->
                <div class="nextcloud-card p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Tour Overview</h2>
                    <p class="text-slate-600 leading-relaxed mb-4"><?php echo nl2br(htmlspecialchars($tour['description'])); ?></p>
                    
                    <?php if ($tour['detailed_description']): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3">Detailed Description</h3>
                        <p class="text-slate-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($tour['detailed_description'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['highlights']): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3">Tour Highlights</h3>
                        <?php 
                        $highlights = json_decode($tour['highlights'], true);
                        if ($highlights && is_array($highlights)):
                        ?>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <?php foreach ($highlights as $highlight): ?>
                            <li class="flex items-start">
                                <i class="fas fa-star text-golden-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($highlight); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tour Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <?php if ($tour['best_time_to_visit']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-lg font-bold mb-3 text-blue-600">
                            <i class="fas fa-calendar-alt mr-2"></i>Best Time to Visit
                        </h3>
                        <p class="text-slate-600"><?php echo htmlspecialchars($tour['best_time_to_visit']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['accommodation_type']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-lg font-bold mb-3 text-green-600">
                            <i class="fas fa-bed mr-2"></i>Accommodation
                        </h3>
                        <p class="text-slate-600"><?php echo htmlspecialchars($tour['accommodation_type']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['meal_plan']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-lg font-bold mb-3 text-orange-600">
                            <i class="fas fa-utensils mr-2"></i>Meal Plan
                        </h3>
                        <p class="text-slate-600"><?php echo htmlspecialchars($tour['meal_plan']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['languages']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-lg font-bold mb-3 text-purple-600">
                            <i class="fas fa-language mr-2"></i>Languages
                        </h3>
                        <?php 
                        $languages = json_decode($tour['languages'], true);
                        if ($languages && is_array($languages)):
                        ?>
                        <p class="text-slate-600"><?php echo implode(', ', array_map('htmlspecialchars', $languages)); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Itinerary -->
                <?php if ($tour['itinerary']): ?>
                <div class="nextcloud-card p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-6">Detailed Itinerary</h2>
                    <?php 
                    $itinerary = json_decode($tour['itinerary'], true);
                    if ($itinerary && is_array($itinerary)):
                    ?>
                    <div class="space-y-6">
                        <?php foreach ($itinerary as $day): ?>
                        <div class="border-l-4 border-golden-500 pl-6 pb-6">
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Day <?php echo $day['day']; ?>: <?php echo htmlspecialchars($day['title']); ?></h3>
                            <p class="text-slate-600"><?php echo nl2br(htmlspecialchars($day['activities'])); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Inclusions & Exclusions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <?php if ($tour['inclusions']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-xl font-bold mb-4 text-green-600">What's Included</h3>
                        <?php 
                        $inclusions = json_decode($tour['inclusions'], true);
                        if ($inclusions && is_array($inclusions)):
                        ?>
                        <ul class="space-y-2">
                            <?php foreach ($inclusions as $inclusion): ?>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($inclusion); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($tour['exclusions']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-xl font-bold mb-4 text-red-600">What's Not Included</h3>
                        <?php 
                        $exclusions = json_decode($tour['exclusions'], true);
                        if ($exclusions && is_array($exclusions)):
                        ?>
                        <ul class="space-y-2">
                            <?php foreach ($exclusions as $exclusion): ?>
                            <li class="flex items-start">
                                <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                <span class="text-slate-600"><?php echo htmlspecialchars($exclusion); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- What to Bring & Requirements -->
                <?php if ($tour['what_to_bring'] || $tour['requirements']): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <?php if ($tour['what_to_bring']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-xl font-bold mb-4 text-blue-600">What to Bring</h3>
                        <p class="text-slate-600"><?php echo nl2br(htmlspecialchars($tour['what_to_bring'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($tour['requirements']): ?>
                    <div class="nextcloud-card p-6">
                        <h3 class="text-xl font-bold mb-4 text-orange-600">Requirements</h3>
                        <p class="text-slate-600"><?php echo nl2br(htmlspecialchars($tour['requirements'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Video & Virtual Tour -->
                <?php if ($tour['video_url'] || $tour['virtual_tour_url']): ?>
                <div class="nextcloud-card p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-6">Experience Preview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if ($tour['video_url']): ?>
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Tour Video</h3>
                            <div class="aspect-video">
                                <iframe src="<?php echo htmlspecialchars($tour['video_url']); ?>" 
                                        class="w-full h-full rounded-lg" 
                                        frameborder="0" 
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($tour['virtual_tour_url']): ?>
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Virtual Tour</h3>
                            <a href="<?php echo htmlspecialchars($tour['virtual_tour_url']); ?>" 
                               target="_blank" 
                               class="block bg-gradient-to-r from-golden-400 to-golden-600 text-white p-6 rounded-lg text-center hover:from-golden-500 hover:to-golden-700 transition-all">
                                <i class="fas fa-vr-cardboard text-3xl mb-2"></i>
                                <p class="font-semibold">Take Virtual Tour</p>
                                <p class="text-sm opacity-90">360° Experience</p>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- FAQs -->
                <?php if (!empty($tour_faqs)): ?>
                <div class="nextcloud-card p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-6">Frequently Asked Questions</h2>
                    <div class="space-y-4">
                        <?php foreach ($tour_faqs as $faq): ?>
                        <div class="border border-slate-200 rounded-lg">
                            <button onclick="toggleFAQ(this)" class="w-full text-left p-4 font-semibold hover:bg-slate-50 flex justify-between items-center">
                                <?php echo htmlspecialchars($faq['question']); ?>
                                <i class="fas fa-chevron-down transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 pt-0 text-slate-600">
                                <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews -->
                <?php if (!empty($tour_reviews)): ?>
                <div class="nextcloud-card p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>
                    <div class="space-y-6">
                        <?php foreach ($tour_reviews as $review): ?>
                        <div class="border-b border-slate-200 pb-6 last:border-b-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold"><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                                    <div class="flex text-yellow-400 text-sm">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $review['rating'] ? '' : 'text-gray-300'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <span class="text-sm text-slate-500"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <?php if ($review['review_title']): ?>
                            <h5 class="font-medium mb-2"><?php echo htmlspecialchars($review['review_title']); ?></h5>
                            <?php endif; ?>
                            <p class="text-slate-600"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                            <?php if ($review['verified_booking']): ?>
                            <span class="inline-block mt-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Verified Booking</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Similar Tours -->
                <?php if (!empty($similar_tours)): ?>
                <div class="nextcloud-card p-8">
                    <h2 class="text-2xl font-bold mb-6">Similar Tours You Might Like</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($similar_tours as $similar_tour): ?>
                        <div class="border border-slate-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <img src="<?php echo htmlspecialchars($similar_tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($similar_tour['name']); ?>" 
                                 class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold mb-2"><?php echo htmlspecialchars($similar_tour['name']); ?></h3>
                                <p class="text-sm text-slate-600 mb-2"><?php echo htmlspecialchars($similar_tour['country_name']); ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-golden-600">$<?php echo number_format($similar_tour['price']); ?></span>
                                    <a href="tour-detail-enhanced.php?id=<?php echo $similar_tour['id']; ?>" class="btn-primary px-4 py-2 rounded text-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Booking Sidebar -->
            <div class="lg:col-span-1">
                <div class="nextcloud-card p-8 sticky top-24">
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-golden-600 mb-2">$<?php echo number_format($tour['price']); ?></div>
                        <p class="text-slate-600">per person</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Duration:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($tour['duration'] ?: $tour['duration_days'] . ' days'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Group Size:</span>
                            <span class="font-semibold"><?php echo $tour['min_participants']; ?>-<?php echo $tour['max_participants']; ?> people</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Category:</span>
                            <span class="font-semibold"><?php echo ucfirst($tour['category']); ?></span>
                        </div>
                        <?php if ($tour['difficulty_level']): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Difficulty:</span>
                            <span class="font-semibold"><?php echo ucfirst($tour['difficulty_level']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($tour['tour_type']): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Tour Type:</span>
                            <span class="font-semibold"><?php echo ucfirst($tour['tour_type']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($tour['age_restriction']): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Age Requirement:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($tour['age_restriction']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($tour['booking_deadline']): ?>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Book By:</span>
                            <span class="font-semibold"><?php echo $tour['booking_deadline']; ?> days before</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <button onclick="openBookingModal(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>', <?php echo $tour['price']; ?>, '<?php echo $shared_link; ?>')" 
                            class="btn-primary w-full py-4 rounded-lg font-bold text-lg mb-4">
                        Book This Tour
                    </button>

                    <!-- Share Tour -->
                    <div class="border-t pt-4 mb-4">
                        <h4 class="font-semibold mb-3">Share This Tour</h4>
                        <div class="flex space-x-2">
                            <button onclick="shareOnFacebook()" class="flex-1 bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">
                                <i class="fab fa-facebook-f mr-1"></i>Facebook
                            </button>
                            <button onclick="shareOnTwitter()" class="flex-1 bg-blue-400 text-white py-2 rounded text-sm hover:bg-blue-500">
                                <i class="fab fa-twitter mr-1"></i>Twitter
                            </button>
                            <button onclick="shareOnWhatsApp()" class="flex-1 bg-green-500 text-white py-2 rounded text-sm hover:bg-green-600">
                                <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                            </button>
                        </div>
                        <button onclick="copyTourLink()" class="w-full mt-2 bg-slate-200 text-slate-700 py-2 rounded text-sm hover:bg-slate-300">
                            <i class="fas fa-link mr-1"></i>Copy Link
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-slate-500 mb-2">Need help? Contact our experts</p>
                        <a href="tel:+1234567890" class="text-golden-600 font-semibold">+1 (234) 567-890</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'enhanced-booking-modal.php'; ?>

<script>
// Image Carousel
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const indicators = document.querySelectorAll('.carousel-indicator');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
    currentSlide = index;
}

function nextSlide() {
    showSlide((currentSlide + 1) % slides.length);
}

function prevSlide() {
    showSlide((currentSlide - 1 + slides.length) % slides.length);
}

function goToSlide(index) {
    showSlide(index);
}

// Auto-advance carousel
if (slides.length > 1) {
    setInterval(nextSlide, 5000);
}

// FAQ Toggle
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    answer.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Booking Modal
function openBookingModal(tourId, tourName, tourPrice, sharedLink) {
    document.getElementById('tourId').value = tourId;
    document.getElementById('tourName').textContent = tourName;
    document.getElementById('tourPrice').textContent = '$' + new Intl.NumberFormat().format(tourPrice);
    document.getElementById('sharedLink').value = sharedLink || '';
    document.getElementById('bookingModal').classList.remove('hidden');
    updateTotalPrice();
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function updateTotalPrice() {
    const participants = document.getElementById('participants').value;
    const pricePerPerson = <?php echo $tour['price']; ?>;
    const totalPrice = participants * pricePerPerson;
    document.getElementById('totalPrice').textContent = '$' + new Intl.NumberFormat().format(totalPrice);
}

// Social Sharing
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('<?php echo addslashes($tour['name']); ?> - Amazing African Tour Experience!');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Check out this amazing tour: <?php echo addslashes($tour['name']); ?>');
    window.open(`https://wa.me/?text=${text} ${url}`, '_blank');
}

function copyTourLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Tour link copied to clipboard!');
    });
}

// Form Submission
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'book_tour');
    
    fetch('../booking-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Booking request submitted successfully! We will contact you soon.');
            closeBookingModal();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>

<style>
.carousel-slide {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.carousel-slide.active {
    opacity: 1;
}

.carousel-indicator.active {
    background-color: white !important;
}

.rotate-180 {
    transform: rotate(180deg);
}
</style>

<?php include '../includes/footer.php'; ?>