<?php
session_start();
require_once '../../config.php';
require_once '../../config/database.php';

$country_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT c.*, r.name as continent_name, r.slug as continent_slug FROM countries c LEFT JOIN regions r ON c.region_id = r.id WHERE c.slug = ? AND c.status = 'active'");
$stmt->execute([$country_slug]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: ' . BASE_URL . '/pages/destinations.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tours WHERE country_id = ? AND status = 'active' ORDER BY featured DESC, created_at DESC");
$stmt->execute([$country['id']]);
$all_tours = $stmt->fetchAll();

// Get tour dates for calendar highlighting
$tour_dates = [];
foreach ($all_tours as $tour) {
    if (!empty($tour['start_date'])) {
        $date = new DateTime($tour['start_date']);
        $tour_dates[$date->format('Y-m-d')] = true;
    }
}

$page_title = "Discover " . $country['name'] . " | Forever Young Tours";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', 'Inter', sans-serif !important; }
        h1, h2, h3, h4, h5, h6 { font-weight: 800; letter-spacing: -0.5px; }
        .calendar-day-with-tour {
            background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
            color: white;
            font-weight: 700;
            position: relative;
        }
        .calendar-day-with-tour::after {
            content: '●';
            position: absolute;
            bottom: 2px;
            right: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="relative w-full min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0 w-full h-full">
        <img src="https://images.pexels.com/photos/2398220/pexels-photo-2398220.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="<?= htmlspecialchars($country['name']) ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/1920x1080/333333/ffffff?text=Nigeria';">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white mb-1 sm:mb-2 leading-tight drop-shadow-lg">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-gray-100 mb-3 sm:mb-4 max-w-4xl mx-auto leading-relaxed font-bold drop-shadow-md">
            <?= htmlspecialchars($country['continent_name']) ?>
        </p>
        <p class="text-sm sm:text-base md:text-lg text-gray-50 mb-6 sm:mb-8 max-w-2xl mx-auto leading-relaxed drop-shadow-md px-2">
            <?= htmlspecialchars($country['tourism_description'] ?: $country['description'] ?: 'Experience Nigeria\'s vibrant culture, diverse landscapes, and warm hospitality. Discover the gateway to West Africa.') ?>
        </p>
        <a href="#tours" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-base sm:text-lg font-bold rounded-lg sm:rounded-xl hover:shadow-2xl hover:from-yellow-600 hover:to-orange-600 transition-all transform hover:scale-105">
            <span>Explore Tours</span>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>

<!-- Calendar Section -->
<section class="py-16 bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Tour Availability Calendar</h2>
            <p class="text-gray-600">Highlighted dates show when tours are available</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <button id="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-all">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <div class="text-center flex-1">
                    <h3 class="text-2xl font-bold text-gray-900" id="monthYear"></h3>
                </div>
                <button id="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-all">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="mb-4">
                <!-- Day headers -->
                <div class="grid grid-cols-7 gap-2 mb-2">
                    <div class="text-center font-bold text-gray-700 py-2">Sun</div>
                    <div class="text-center font-bold text-gray-700 py-2">Mon</div>
                    <div class="text-center font-bold text-gray-700 py-2">Tue</div>
                    <div class="text-center font-bold text-gray-700 py-2">Wed</div>
                    <div class="text-center font-bold text-gray-700 py-2">Thu</div>
                    <div class="text-center font-bold text-gray-700 py-2">Fri</div>
                    <div class="text-center font-bold text-gray-700 py-2">Sat</div>
                </div>
                
                <!-- Calendar days -->
                <div id="calendarDays" class="grid grid-cols-7 gap-2"></div>
            </div>

            <!-- Legend -->
            <div class="flex items-center justify-center gap-6 pt-4 border-t border-gray-200">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gradient-to-r from-yellow-500 to-orange-500 rounded"></div>
                    <span class="text-sm text-gray-700">Tour Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-200 rounded"></div>
                    <span class="text-sm text-gray-700">No Tours</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tours Section -->
<section id="tours" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Tours in <?= htmlspecialchars($country['name']) ?></h2>
            <p class="text-xl text-gray-600">Discover unforgettable experiences</p>
        </div>
        
        <?php if (empty($all_tours)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">No tours available yet. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($all_tours as $tour): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                <div class="relative">
                    <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url'], 'assets/images/default-tour.jpg') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-56 object-cover">
                    <?php if ($tour['featured']): ?>
                    <span class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars(substr($tour['description'], 0, 100)) . '...' ?></p>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">$<?= number_format($tour['price'], 0) ?></span>
                        <span class="text-gray-500"><?= htmlspecialchars($tour['duration']) ?></span>
                    </div>
                    <a href="<?= BASE_URL ?>/pages/tour-detail.php?id=<?= $tour['id'] ?>" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold text-center hover:shadow-xl transition-all">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Country Info -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">About <?= htmlspecialchars($country['name']) ?></h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">💰</div>
                <h3 class="font-bold text-gray-900 mb-2">Currency</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['currency'] ?: 'Local Currency') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🗣️</div>
                <h3 class="font-bold text-gray-900 mb-2">Language</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['language'] ?: 'Local Language') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🌤️</div>
                <h3 class="font-bold text-gray-900 mb-2">Best Time</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['best_time_to_visit'] ?: 'Year-round') ?></p>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">📍</div>
                <h3 class="font-bold text-gray-900 mb-2">Region</h3>
                <p class="text-gray-600"><?= htmlspecialchars($country['continent_name']) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?= htmlspecialchars($country['name']) ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Book your adventure today</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>/pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-full hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="<?= BASE_URL ?>/pages/contact.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-full hover:bg-white/20 transition-all">
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<?php require_once '../../includes/footer.php'; ?>

<script>
const tourDates = <?= json_encode(array_keys($tour_dates)) ?>;
let currentDate = new Date();

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    // Update month/year display
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('monthYear').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    let html = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="p-2 text-center text-gray-400"></div>';
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const hasTourn = tourDates.includes(dateStr);
        
        if (hasTourn) {
            html += `<div class="p-2 text-center rounded-lg calendar-day-with-tour">${day}</div>`;
        } else {
            html += `<div class="p-2 text-center rounded-lg bg-gray-100 text-gray-700 font-semibold">${day}</div>`;
        }
    }
    
    document.getElementById('calendarDays').innerHTML = html;
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Initial render
renderCalendar();
</script>

</body>
</html>