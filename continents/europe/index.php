<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';

$continent_slug = basename(dirname(__FILE__));
$stmt = $pdo->prepare("SELECT * FROM regions WHERE slug = ? AND status = 'active'");
$stmt->execute([$continent_slug]);
$continent = $stmt->fetch();

if (!$continent) {
    header('Location: ../../pages/destinations.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM countries WHERE region_id = ? AND status = 'active' ORDER BY name");
$stmt->execute([$continent['id']]);
$countries = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name FROM tours t
    INNER JOIN countries c ON t.country_id = c.id
    WHERE c.region_id = ? AND t.status = 'active'
    ORDER BY t.featured DESC, t.popularity_score DESC
    LIMIT 6
");
$stmt->execute([$continent['id']]);
$featured_tours = $stmt->fetchAll();

$page_title = $continent['name'] . " - Discover Amazing Destinations - iForYoungTours";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($continent['image_url'] ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=2072&q=80'); ?>" alt="<?php echo htmlspecialchars($continent['name']); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block px-4 py-2 bg-yellow-500/20 backdrop-blur-sm border border-yellow-500/30 rounded-full mb-6">
            <span class="text-yellow-400 font-semibold">Explore the Continent</span>
        </div>
        <h1 class="text-6xl md:text-8xl font-extrabold text-white mb-6 leading-tight">
            <?php echo htmlspecialchars($continent['name']); ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-4xl mx-auto leading-relaxed">
            <?php echo htmlspecialchars($continent['description']); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="#countries" class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-lg font-bold rounded-xl hover:shadow-2xl transition-all transform hover:-translate-y-1">
                Explore Countries
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            <a href="#tours" class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm text-white border-2 border-white text-lg font-bold rounded-xl hover:bg-white/20 transition-all">
                View Tours
            </a>
        </div>
    </div>
    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Countries Grid -->
<section id="countries" class="py-20 bg-gradient-to-b from-white to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">DESTINATIONS</span>
            <h2 class="text-5xl font-bold text-gray-900 mb-4">Explore by Country</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover the diverse beauty of <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($countries as $country): ?>
            <?php
            $country_code = strtolower(substr($country['country_code'], 0, 2));
            $current_host = $_SERVER['HTTP_HOST'];
            if (strpos($current_host, 'iforeveryoungtours.com') !== false) {
                $country_url = "https://visit-{$country_code}.iforeveryoungtours.com";
            } else {
                $country_url = "http://visit-{$country_code}.foreveryoungtours.local";
            }
            ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer transform hover:-translate-y-2" onclick="window.open('<?php echo $country_url; ?>', '_blank')">
                <div class="relative h-72 overflow-hidden">
                    <img src="<?= getImageUrl($country['image_url'], 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800') ?>" alt="<?php echo htmlspecialchars($country['name']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg">EXPLORE</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-yellow-400 transition-colors"><?php echo htmlspecialchars($country['name']); ?></h3>
                        <p class="text-sm text-gray-200 mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($country['description'] ?: 'Discover the beauty and culture', 0, 80)); ?>...</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-300"><i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($country['currency'] ?: 'Local Currency'); ?></span>
                            <svg class="w-5 h-5 text-yellow-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Tours -->
<section id="tours" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Top <?php echo htmlspecialchars($continent['name']); ?> Tours</h2>
            <p class="text-xl text-gray-600">Discover our most popular experiences from <?php echo htmlspecialchars($continent['name']); ?></p>
        </div>
        
        <?php if (!empty($featured_tours)): ?>
        <div id="toursCarousel" class="relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $visible_tours = array_slice($featured_tours, 0, 3);
                foreach ($visible_tours as $tour): 
                ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer" onclick="openBookingModal(<?php echo $tour['id']; ?>, '<?php echo htmlspecialchars($tour['name'], ENT_QUOTES); ?>', <?php echo $tour['price']; ?>)">
                    <?php 
                    $tour_image = $tour['image_url'] ?: $tour['cover_image'];
                    if ($tour_image && strpos($tour_image, 'uploads/') === 0) {
                        $tour_image_url = '../../' . $tour_image;
                    } else {
                        $tour_image_url = $tour_image ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=800&q=80';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($tour_image_url); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($tour['name']); ?></h3>
                        <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars(substr($tour['description'] ?: 'Discover amazing experiences', 0, 100)) . '...'; ?></p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-yellow-600">$<?php echo number_format($tour['price'], 0); ?></span>
                            <span class="text-gray-500"><?php echo htmlspecialchars($tour['duration']); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><?php echo htmlspecialchars($tour['country_name'] ?: 'Multiple Countries'); ?></span>
                            <a href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/pages/tour-detail.php?id=<?php echo $tour['id']; ?>" class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-full text-sm font-semibold hover:shadow-xl transition-all">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Featured tours coming soon!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Calendar Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Travel Calendar</h2>
            <p class="text-xl text-gray-600">Plan your perfect trip with our scheduled departures</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Calendar Widget -->
            <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Departure Calendar</h3>
                <div id="calendar" class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800" id="currentMonth"></h4>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-sm">
                        <div class="font-semibold text-gray-600 p-2">Sun</div>
                        <div class="font-semibold text-gray-600 p-2">Mon</div>
                        <div class="font-semibold text-gray-600 p-2">Tue</div>
                        <div class="font-semibold text-gray-600 p-2">Wed</div>
                        <div class="font-semibold text-gray-600 p-2">Thu</div>
                        <div class="font-semibold text-gray-600 p-2">Fri</div>
                        <div class="font-semibold text-gray-600 p-2">Sat</div>
                    </div>
                    <div id="calendarDays" class="grid grid-cols-7 gap-2 mt-2"></div>
                </div>
            </div>
            
            <!-- Upcoming Tours -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Upcoming Departures</h3>
                <div class="space-y-4">
                    <?php 
                    // Get upcoming tours (simplified without schedule table)
                    $upcoming_stmt = $pdo->prepare("
                        SELECT t.*, c.name as country_name
                        FROM tours t
                        JOIN countries c ON t.country_id = c.id
                        WHERE c.region_id = ? AND t.status = 'active'
                        ORDER BY t.created_at DESC
                        LIMIT 5
                    ");
                    $upcoming_stmt->execute([$continent['id']]);
                    $upcoming_tours = $upcoming_stmt->fetchAll();
                    
                    if (!empty($upcoming_tours)):
                        foreach ($upcoming_tours as $tour):
                    ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex">
                            <div class="w-24 h-20 flex-shrink-0">
                                <?php 
                                $upcoming_tour_image = $tour['image_url'] ?: $tour['cover_image'];
                                if ($upcoming_tour_image && strpos($upcoming_tour_image, 'uploads/') === 0) {
                                    $upcoming_tour_image_url = '../../' . $upcoming_tour_image;
                                } else {
                                    $upcoming_tour_image_url = $upcoming_tour_image ?: 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=200&q=80';
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars_decode($upcoming_tour_image_url); ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>" class="w-full h-full object-cover"
                            </div>
                            <div class="flex-1 p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-bold text-gray-900 text-sm"><?php echo htmlspecialchars($tour['name']); ?></h4>
                                    <span class="text-xs text-gray-500"><?php echo htmlspecialchars($tour['country_name']); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-600">Duration: <?php echo $tour['duration_days']; ?> days</p>
                                        <p class="text-xs text-green-600">Available now</p>
                                    </div>
                                    <a href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/pages/tour-detail.php?id=<?php echo $tour['id']; ?>" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-yellow-600">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <div class="text-center py-8">
                        <p class="text-gray-500">No scheduled departures yet. Check back soon!</p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-6">
                    <a href="pages/calendar.php" class="inline-block bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        View Full Calendar
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Ready to Explore <?php echo htmlspecialchars($continent['name']); ?>?</h2>
        <p class="text-xl text-white/90 mb-8">Join thousands of travelers discovering the magic of <?php echo htmlspecialchars($continent['name']); ?></p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="pages/packages.php" class="bg-white text-yellow-600 px-8 py-4 text-lg font-semibold rounded-xl hover:shadow-2xl transition-all">
                Browse All Tours
            </a>
            <a href="register.php" class="bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 text-lg font-semibold rounded-xl hover:bg-white/20 transition-all">
                Join FYT Club
            </a>
        </div>
    </div>
</section>



<script>

// Calendar functionality
function generateCalendar() {
    const now = new Date();
    const currentMonth = now.getMonth();
    const currentYear = now.getFullYear();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    
    document.getElementById('currentMonth').textContent = monthNames[currentMonth] + ' ' + currentYear;
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'p-2';
        calendarDays.appendChild(emptyDay);
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className = 'p-2 text-center cursor-pointer rounded hover:bg-yellow-100';
        dayElement.textContent = day;
        
        if (day === now.getDate()) {
            dayElement.className += ' bg-yellow-500 text-white';
        }
        
        calendarDays.appendChild(dayElement);
    }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', generateCalendar);
</script>

<?php include '../../includes/footer.php'; ?>

